<?php

namespace App\Services;

use App\Models\EmailLog;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailNotificationService
{
    /**
     * Send registration confirmation email
     */
    public function sendRegistrationConfirmation(User $user, Event $event): bool
    {
        try {
            $subject = "Registration Confirmation - {$event->title}";
            $body = $this->buildRegistrationConfirmationBody($user, $event);

            $emailLog = EmailLog::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'recipient_email' => $user->email,
                'subject' => $subject,
                'type' => 'registration_confirmation',
                'status' => 'pending',
                'body' => $body,
            ]);

            // Send email (using Mail facade or queue)
            Mail::raw($body, function ($message) use ($user, $subject) {
                $message->to($user->email)
                    ->subject($subject)
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            $emailLog->markAsSent();
            return true;
        } catch (\Exception $e) {
            $emailLog->markAsFailed($e->getMessage());
            return false;
        }
    }

    /**
     * Send event reminder email
     */
    public function sendEventReminder(User $user, Event $event): bool
    {
        try {
            $subject = "Reminder: {$event->title} is coming up!";
            $body = $this->buildEventReminderBody($user, $event);

            $emailLog = EmailLog::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'recipient_email' => $user->email,
                'subject' => $subject,
                'type' => 'event_reminder',
                'status' => 'pending',
                'body' => $body,
            ]);

            Mail::raw($body, function ($message) use ($user, $subject) {
                $message->to($user->email)
                    ->subject($subject)
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            $emailLog->markAsSent();
            return true;
        } catch (\Exception $e) {
            $emailLog->markAsFailed($e->getMessage());
            return false;
        }
    }

    /**
     * Send feedback request email
     */
    public function sendFeedbackRequest(User $user, Event $event): bool
    {
        try {
            $subject = "Share Your Feedback - {$event->title}";
            $body = $this->buildFeedbackRequestBody($user, $event);

            $emailLog = EmailLog::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'recipient_email' => $user->email,
                'subject' => $subject,
                'type' => 'feedback_request',
                'status' => 'pending',
                'body' => $body,
            ]);

            Mail::raw($body, function ($message) use ($user, $subject) {
                $message->to($user->email)
                    ->subject($subject)
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            $emailLog->markAsSent();
            return true;
        } catch (\Exception $e) {
            $emailLog->markAsFailed($e->getMessage());
            return false;
        }
    }

    /**
     * Send waitlist notification email
     */
    public function sendWaitlistNotification(User $user, Event $event): bool
    {
        try {
            $subject = "Spot Available - {$event->title}";
            $body = $this->buildWaitlistNotificationBody($user, $event);

            $emailLog = EmailLog::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'recipient_email' => $user->email,
                'subject' => $subject,
                'type' => 'waitlist_notification',
                'status' => 'pending',
                'body' => $body,
            ]);

            Mail::raw($body, function ($message) use ($user, $subject) {
                $message->to($user->email)
                    ->subject($subject)
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            $emailLog->markAsSent();
            return true;
        } catch (\Exception $e) {
            $emailLog->markAsFailed($e->getMessage());
            return false;
        }
    }

    /**
     * Build registration confirmation email body
     */
    private function buildRegistrationConfirmationBody(User $user, Event $event): string
    {
        return <<<HTML
        <h2>Registration Confirmation</h2>
        <p>Dear {$user->name},</p>
        <p>Thank you for registering for <strong>{$event->title}</strong>!</p>
        <p><strong>Event Details:</strong></p>
        <ul>
            <li>Date & Time: {$event->event_date->format('M d, Y H:i')}</li>
            <li>Location: {$event->location}</li>
            <li>Category: {$event->category}</li>
        </ul>
        <p>We look forward to seeing you there!</p>
        <p>Best regards,<br>Jewish House Team</p>
        HTML;
    }

    /**
     * Build event reminder email body
     */
    private function buildEventReminderBody(User $user, Event $event): string
    {
        $daysUntil = now()->diffInDays($event->event_date);
        return <<<HTML
        <h2>Event Reminder</h2>
        <p>Dear {$user->name},</p>
        <p>This is a friendly reminder that <strong>{$event->title}</strong> is coming up in {$daysUntil} days!</p>
        <p><strong>Event Details:</strong></p>
        <ul>
            <li>Date & Time: {$event->event_date->format('M d, Y H:i')}</li>
            <li>Location: {$event->location}</li>
        </ul>
        <p>We hope to see you soon!</p>
        <p>Best regards,<br>Jewish House Team</p>
        HTML;
    }

    /**
     * Build feedback request email body
     */
    private function buildFeedbackRequestBody(User $user, Event $event): string
    {
        $feedbackUrl = route('feedback.create', $event);
        return <<<HTML
        <h2>Share Your Feedback</h2>
        <p>Dear {$user->name},</p>
        <p>Thank you for attending <strong>{$event->title}</strong>!</p>
        <p>We would love to hear your feedback about the event. Your input helps us improve future events.</p>
        <p><a href="{$feedbackUrl}" style="background-color: #2563eb; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Share Your Feedback</a></p>
        <p>Best regards,<br>Jewish House Team</p>
        HTML;
    }

    /**
     * Build waitlist notification email body
     */
    private function buildWaitlistNotificationBody(User $user, Event $event): string
    {
        $registerUrl = route('events.register', $event);
        return <<<HTML
        <h2>Spot Available!</h2>
        <p>Dear {$user->name},</p>
        <p>Great news! A spot has become available for <strong>{$event->title}</strong>.</p>
        <p><strong>Event Details:</strong></p>
        <ul>
            <li>Date & Time: {$event->event_date->format('M d, Y H:i')}</li>
            <li>Location: {$event->location}</li>
        </ul>
        <p><a href="{$registerUrl}" style="background-color: #10b981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Register Now</a></p>
        <p>Best regards,<br>Jewish House Team</p>
        HTML;
    }
}
