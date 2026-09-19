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

        $events = Event::where('status', 'published')->where('email_status', 'enabled')
            ->whereNotNull('start_date')->get()->filter(function (Event $event) {
                $hours = ['24h' => 24, '48h' => 48, '7d' => 168][$event->reminder_offset] ?? 24;
                return $event->start_date->between(now()->addHours($hours)->subMinutes(15), now()->addHours($hours)->addMinutes(15));
            });

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
                    // Check if email was actually sent before creating reminder
                    $emailSent = $emailService->sendEventReminder($user, $event);
                    
                    if ($emailSent) {
                        EmailReminder::create([
                            'event_id' => $event->id,
                            'user_id' => $user->id,
                            'type' => 'event_reminder',
                            'scheduled_at' => now(),
                            'sent_at' => now(),
                            'status' => 'sent',
                        ]);
                        $sent++;
                    } else {
                        EmailReminder::create([
                            'event_id' => $event->id,
                            'user_id' => $user->id,
                            'type' => 'event_reminder',
                            'scheduled_at' => now(),
                            'status' => 'failed',
                            'error_message' => 'Email service returned false',
                        ]);
                        $failed++;
                    }
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
