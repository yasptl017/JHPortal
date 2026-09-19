<?php

namespace App\Console\Commands;

use App\Models\EmailReminder;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Feedback;
use App\Models\EmailConfiguration;
use App\Services\EmailNotificationService;
use Illuminate\Console\Command;

class SendFeedbackEmails extends Command
{
    protected $signature = 'email:send-feedback-requests';
    protected $description = 'Send feedback request emails after event completion';

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
            ->whereNotNull('end_date')->get()->filter(function (Event $event) {
                $hours = ['2h' => 2, '24h' => 24, '48h' => 48][$event->feedback_offset] ?? 24;
                return $event->end_date->between(now()->subHours($hours)->subMinutes(15), now()->subHours($hours)->addMinutes(15));
            });

        foreach ($events as $event) {
            // Get registered users who attended
            $registrations = EventRegistration::where('event_id', $event->id)
                ->where('status', 'registered')
                ->with('user')
                ->get();

            foreach ($registrations as $registration) {
                $user = $registration->user;

                // Check if feedback already submitted
                $existingFeedback = Feedback::where('event_id', $event->id)
                    ->where('user_id', $user->id)
                    ->first();

                if ($existingFeedback) {
                    continue;
                }

                // Check if feedback email already sent
                $existing = EmailReminder::where('event_id', $event->id)
                    ->where('user_id', $user->id)
                    ->where('type', 'feedback_request')
                    ->first();

                if ($existing) {
                    continue;
                }

                try {
                    // Check if email was actually sent before creating reminder
                    $emailSent = $emailService->sendFeedbackRequest($user, $event);
                    
                    if ($emailSent) {
                        EmailReminder::create([
                            'event_id' => $event->id,
                            'user_id' => $user->id,
                            'type' => 'feedback_request',
                            'scheduled_at' => now(),
                            'sent_at' => now(),
                            'status' => 'sent',
                        ]);
                        $sent++;
                    } else {
                        EmailReminder::create([
                            'event_id' => $event->id,
                            'user_id' => $user->id,
                            'type' => 'feedback_request',
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
                        'type' => 'feedback_request',
                        'scheduled_at' => now(),
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);

                    $failed++;
                }
            }
        }

        $this->info("Feedback emails sent: {$sent}, Failed: {$failed}");
    }
}
