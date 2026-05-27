<?php

namespace App\Console\Commands;

use App\Models\EmailReminder;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EmailConfiguration;
use App\Services\EmailNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEventReminders extends Command
{
    protected $signature = 'email:send-reminders';
    protected $description = 'Send event reminder emails 24 hours before event start';

    public function handle()
    {
        // Apply email configuration
        $config = EmailConfiguration::getActive();
        if ($config) {
            $config->applyToConfig();
        }

        $emailService = new EmailNotificationService();
        $sent = 0;
        $failed = 0;

        // Find events starting in approximately 24 hours
        $reminderTime = now()->addHours(24);
        $events = Event::where('status', 'published')
            ->whereBetween('start_date', [
                $reminderTime->copy()->subMinutes(30),
                $reminderTime->copy()->addMinutes(30)
            ])
            ->get();

        foreach ($events as $event) {
            // Get registered users
            $registrations = EventRegistration::where('event_id', $event->id)
                ->where('status', 'registered')
                ->with('user')
                ->get();

            foreach ($registrations as $registration) {
                $user = $registration->user;

                // Check if reminder already sent
                $existing = EmailReminder::where('event_id', $event->id)
                    ->where('user_id', $user->id)
                    ->where('type', 'event_reminder')
                    ->first();

                if ($existing) {
                    continue;
                }

                try {
                    $emailService->sendEventReminder($user, $event);
                    
                    EmailReminder::create([
                        'event_id' => $event->id,
                        'user_id' => $user->id,
                        'type' => 'event_reminder',
                        'scheduled_at' => now(),
                        'sent_at' => now(),
                        'status' => 'sent',
                    ]);

                    $sent++;
                } catch (\Exception $e) {
                    EmailReminder::create([
                        'event_id' => $event->id,
                        'user_id' => $user->id,
                        'type' => 'event_reminder',
                        'scheduled_at' => now(),
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);

                    $failed++;
                }
            }
        }

        $this->info("Event reminders sent: {$sent}, Failed: {$failed}");
    }
}
