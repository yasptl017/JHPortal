<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\FeedbackReminder;
use App\Models\User;
use Illuminate\Support\Collection;

class FeedbackService
{
    protected EmailNotificationService $emailService;

    public function __construct(EmailNotificationService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Create feedback reminders for event participants
     */
    public function createFeedbackReminders(Event $event): int
    {
        $registrations = EventRegistration::where('event_id', $event->id)
            ->where('status', 'registered')
            ->get();

        $created = 0;

        foreach ($registrations as $registration) {
            $existing = FeedbackReminder::where('event_id', $event->id)
                ->where('user_id', $registration->user_id)
                ->first();

            if (!$existing) {
                FeedbackReminder::create([
                    'event_id' => $event->id,
                    'user_id' => $registration->user_id,
                    'status' => 'pending',
                ]);
                $created++;
            }
        }

        return $created;
    }

    /**
     * Send feedback request emails to participants
     */
    public function sendFeedbackRequests(Event $event): int
    {
        $reminders = FeedbackReminder::where('event_id', $event->id)
            ->where('status', 'pending')
            ->with('user')
            ->get();

        $sent = 0;

        foreach ($reminders as $reminder) {
            if ($this->emailService->sendFeedbackRequest($reminder->user, $event)) {
                $reminder->markAsSent();
                $sent++;
            }
        }

        return $sent;
    }

    /**
     * Send feedback request to single user
     */
    public function sendFeedbackRequest(User $user, Event $event): bool
    {
        $reminder = FeedbackReminder::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$reminder) {
            return false;
        }

        if ($this->emailService->sendFeedbackRequest($user, $event)) {
            $reminder->markAsSent();
            return true;
        }

        return false;
    }

    /**
     * Mark feedback as completed
     */
    public function markFeedbackCompleted(Event $event, User $user): bool
    {
        $reminder = FeedbackReminder::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if ($reminder) {
            $reminder->markAsCompleted();
            return true;
        }

        return false;
    }

    /**
     * Get feedback completion rate for event
     */
    public function getFeedbackCompletionRate(Event $event): float
    {
        $total = FeedbackReminder::where('event_id', $event->id)->count();

        if ($total === 0) {
            return 0;
        }

        $completed = FeedbackReminder::where('event_id', $event->id)
            ->where('status', 'completed')
            ->count();

        return ($completed / $total) * 100;
    }

    /**
     * Get pending feedback reminders
     */
    public function getPendingReminders(): Collection
    {
        return FeedbackReminder::where('status', 'pending')
            ->with('event', 'user')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get sent but not completed reminders
     */
    public function getSentReminders(): Collection
    {
        return FeedbackReminder::where('status', 'sent')
            ->with('event', 'user')
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
