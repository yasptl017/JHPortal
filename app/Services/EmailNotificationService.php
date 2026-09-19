<?php

namespace App\Services;

use App\Models\EmailConfiguration;
use App\Models\EmailLog;
use App\Models\Event;
use App\Models\User;
use App\Models\UserNotificationPreference;
use Illuminate\Support\Facades\Mail;

class EmailNotificationService
{
    public function sendWelcomeEmail(User $user): bool
    {
        return $this->send(
            $user,
            null,
            'general',
            'Welcome to Jewish House',
            $this->welcomeBody($user),
        );
    }

    public function sendRegistrationConfirmation(User $user, Event $event): bool
    {
        return $this->send($user, $event, 'registration_confirmation', "Registration Confirmation - {$event->title}", $this->registrationConfirmationBody($user, $event));
    }

    public function sendRegistrationCancellation(User $user, Event $event): bool
    {
        return $this->send($user, $event, 'registration_cancellation', "Registration Cancelled - {$event->title}", $this->standardBody('Registration Cancelled', $user, $event, 'Your registration has been cancelled. If this was not intended, please contact us.'));
    }

    public function sendEventReminder(User $user, Event $event): bool
    {
        return $this->send($user, $event, 'event_reminder', "Reminder: {$event->title} is coming up!", $this->standardBody('Event Reminder', $user, $event, $event->reminder_message ?: 'This is a friendly reminder that your event is coming up soon.'));
    }

    public function sendFeedbackRequest(User $user, Event $event): bool
    {
        $url = route('feedback.create', $event);
        $message = $event->feedback_message ?: 'Thank you for attending. We would love to hear your feedback.';
        $body = $this->standardBody('Share Your Feedback', $user, $event, $message) . $this->button($url, 'Share Your Feedback');

        return $this->send($user, $event, 'feedback_request', "Share Your Feedback - {$event->title}", $body);
    }

    public function sendWaitlistNotification(User $user, Event $event): bool
    {
        $url = route('events.register', $event);
        $message = $event->waitlist_message ?: 'Great news! A spot has become available.';
        $body = $this->standardBody('Spot Available', $user, $event, $message) . $this->button($url, 'Register Now');

        return $this->send($user, $event, 'waitlist_notification', "Spot Available - {$event->title}", $body);
    }

    public function sendWaitlistReminder(User $user, Event $event, string $timeframe): bool
    {
        $url = route('waitlist.confirm', $event);
        $body = $this->standardBody('Confirm Your Spot', $user, $event, "You have {$timeframe} remaining to confirm your available spot before it expires.") . $this->button($url, 'Confirm Your Spot');

        return $this->send($user, $event, 'waitlist_reminder', "Reminder: Confirm Your Spot - {$event->title}", $body);
    }

    public function sendEventUpdate(User $user, Event $event): bool
    {
        return $this->send($user, $event, 'event_updated', "Event Updated - {$event->title}", $this->standardBody('Event Updated', $user, $event, 'Details for this event have changed. Please review the updated date, time, and location below.'));
    }

    public function sendEventCancellation(User $user, Event $event): bool
    {
        return $this->send($user, $event, 'event_cancelled', "Event Cancelled - {$event->title}", $this->standardBody('Event Cancelled', $user, $event, 'We are sorry to let you know that this event has been cancelled.'));
    }

    private function send(User $user, ?Event $event, string $type, string $subject, string $body): bool
    {
        if (!$this->shouldSend($user, $event, $type)) {
            return false;
        }

        $log = EmailLog::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'recipient_email' => $user->email,
            'subject' => $subject,
            'type' => $type,
            'status' => 'pending',
            'body' => $body,
        ]);

        try {
            EmailConfiguration::getActive()?->applyToConfig();
            Mail::html($body, function ($message) use ($user, $subject) {
                $message->to($user->email)->subject($subject)->from(config('mail.from.address'), config('mail.from.name'));
            });
            $log->markAsSent();
            return true;
        } catch (\Throwable $exception) {
            $log->markAsFailed($exception->getMessage());
            report($exception);
            return false;
        }
    }

    private function shouldSend(User $user, ?Event $event, string $type): bool
    {
        if ($event?->email_status === 'disabled') {
            return false;
        }

        $preferences = UserNotificationPreference::forUser($user);

        return match ($type) {
            'registration_confirmation', 'registration_cancellation' => $preferences->wantsRegistrationConfirmations(),
            'event_reminder', 'event_updated', 'event_cancelled' => $preferences->wantsEventReminders(),
            'feedback_request' => $preferences->wantsFeedbackNotifications(),
            'waitlist_notification', 'waitlist_reminder' => $preferences->wantsWaitlistNotifications(),
            default => $preferences->email_notifications,
        };
    }

    private function registrationConfirmationBody(User $user, Event $event): string
    {
        return $this->standardBody('Registration Confirmation', $user, $event, $event->confirmation_message ?: 'Thank you for registering. We look forward to seeing you!');
    }

    private function welcomeBody(User $user): string
    {
        return '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head><body style="margin:0;background:#f4f6f8;font-family:Arial,sans-serif;color:#1f2937"><table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:32px 16px"><tr><td align="center"><table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width:600px;background:#ffffff;border-radius:8px;overflow:hidden"><tr><td style="padding:28px 32px;background:#1d4ed8;color:#ffffff"><h1 style="margin:0;font-size:24px">Welcome to Jewish House</h1></td></tr><tr><td style="padding:32px"><p style="margin-top:0">Dear ' . e($user->name) . ',</p><p>Your account has been created successfully. You can now register for upcoming events and manage your bookings from your profile.</p><p style="margin-bottom:0">We look forward to welcoming you at Jewish House.<br><br>Best regards,<br>Jewish House Team</p></td></tr></table></td></tr></table></body></html>';
    }

    private function standardBody(string $heading, User $user, Event $event, string $message): string
    {
        $date = $event->start_date?->format('M d, Y H:i') ?? 'To be confirmed';
        $location = e($event->location ?: 'To be confirmed');

        return '<h2>' . e($heading) . '</h2><p>Dear ' . e($user->name) . ',</p><p>' . nl2br(e($message)) . '</p><p><strong>' . e($event->title) . '</strong><br>Date &amp; time: ' . e($date) . '<br>Location: ' . $location . '</p><p>Best regards,<br>Jewish House Team</p>';
    }

    private function button(string $url, string $label): string
    {
        return '<p><a href="' . e($url) . '" style="background:#2563eb;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block">' . e($label) . '</a></p>';
    }
}
