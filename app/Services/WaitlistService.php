<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Waitlist;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Support\Collection;

class WaitlistService
{
    protected EmailNotificationService $emailService;

    public function __construct(EmailNotificationService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Add user to waitlist
     */
    public function addToWaitlist(User $user, Event $event): ?Waitlist
    {
        // Check if already registered
        $existing = EventRegistration::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existing) {
            return null;
        }

        // Check if already on waitlist
        $waitlistEntry = Waitlist::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($waitlistEntry) {
            return $waitlistEntry;
        }

        // Get next position
        $position = Waitlist::forEvent($event)
            ->active()
            ->max('position') ?? 0;
        $position++;

        return Waitlist::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'pending',
            'position' => $position,
        ]);
    }

    /**
     * Remove user from waitlist
     */
    public function removeFromWaitlist(User $user, Event $event): bool
    {
        $waitlist = Waitlist::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if (!$waitlist) {
            return false;
        }

        $waitlist->markAsCancelled();
        $this->reorderWaitlist($event);

        return true;
    }

    /**
     * Notify waitlist member
     */
    public function notifyWaitlistMember(Waitlist $waitlist): bool
    {
        if (!$waitlist->isPending()) {
            return false;
        }

        $waitlist->markAsNotified();
        $this->emailService->sendWaitlistNotification($waitlist->user, $waitlist->event);

        return true;
    }

    /**
     * Confirm waitlist member and register for event
     */
    public function confirmWaitlistMember(Waitlist $waitlist): bool
    {
        if (!$waitlist->isActive()) {
            return false;
        }

        // Check if event still has capacity
        if ($waitlist->event->isFull()) {
            return false;
        }

        // Register user for event
        EventRegistration::create([
            'event_id' => $waitlist->event_id,
            'user_id' => $waitlist->user_id,
            'status' => 'registered',
        ]);

        $waitlist->markAsConfirmed();
        $this->reorderWaitlist($waitlist->event);

        return true;
    }

    /**
     * Promote next waitlist member when spot becomes available
     */
    public function promoteNextWaitlistMember(Event $event): ?Waitlist
    {
        // Check if event has available spots
        if ($event->isFull()) {
            return null;
        }

        // Get first pending member
        $nextMember = Waitlist::forEvent($event)
            ->pending()
            ->ordered()
            ->first();

        if (!$nextMember) {
            return null;
        }

        // Notify and confirm
        $this->notifyWaitlistMember($nextMember);

        return $nextMember;
    }

    /**
     * Promote multiple waitlist members
     */
    public function promoteMultipleWaitlistMembers(Event $event, int $count = 1): Collection
    {
        $promoted = collect();

        for ($i = 0; $i < $count; $i++) {
            $member = $this->promoteNextWaitlistMember($event);
            if ($member) {
                $promoted->push($member);
            } else {
                break;
            }
        }

        return $promoted;
    }

    /**
     * Reorder waitlist positions
     */
    public function reorderWaitlist(Event $event): void
    {
        $entries = Waitlist::forEvent($event)
            ->active()
            ->ordered()
            ->get();

        foreach ($entries as $index => $entry) {
            $entry->update(['position' => $index + 1]);
        }
    }

    /**
     * Expire old notifications
     */
    public function expireOldNotifications(int $daysOld = 7): int
    {
        $expiredDate = now()->subDays($daysOld);

        $count = Waitlist::where('status', 'notified')
            ->where('notified_at', '<', $expiredDate)
            ->update(['status' => 'expired', 'expired_at' => now()]);

        return $count;
    }

    /**
     * Get waitlist statistics for event
     */
    public function getWaitlistStats(Event $event): array
    {
        return [
            'total' => Waitlist::forEvent($event)->count(),
            'pending' => Waitlist::forEvent($event)->pending()->count(),
            'notified' => Waitlist::forEvent($event)->notified()->count(),
            'confirmed' => Waitlist::forEvent($event)->confirmed()->count(),
            'cancelled' => Waitlist::forEvent($event)->where('status', 'cancelled')->count(),
            'expired' => Waitlist::forEvent($event)->where('status', 'expired')->count(),
            'active' => Waitlist::forEvent($event)->active()->count(),
        ];
    }

    /**
     * Get user's waitlist position
     */
    public function getUserWaitlistPosition(User $user, Event $event): ?int
    {
        $waitlist = Waitlist::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if (!$waitlist || !$waitlist->isActive()) {
            return null;
        }

        return $waitlist->getPosition();
    }

    /**
     * Check if user is on waitlist
     */
    public function isUserOnWaitlist(User $user, Event $event): bool
    {
        return Waitlist::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->active()
            ->exists();
    }

    /**
     * Bulk notify waitlist members
     */
    public function bulkNotifyWaitlist(Event $event, array $userIds = []): int
    {
        $query = Waitlist::forEvent($event)->pending();

        if (!empty($userIds)) {
            $query->whereIn('user_id', $userIds);
        }

        $members = $query->get();
        $notified = 0;

        foreach ($members as $member) {
            if ($this->notifyWaitlistMember($member)) {
                $notified++;
            }
        }

        return $notified;
    }

    /**
     * Bulk confirm waitlist members
     */
    public function bulkConfirmWaitlist(Event $event, array $userIds = []): int
    {
        $query = Waitlist::forEvent($event)->active();

        if (!empty($userIds)) {
            $query->whereIn('user_id', $userIds);
        }

        $members = $query->get();
        $confirmed = 0;

        foreach ($members as $member) {
            if ($this->confirmWaitlistMember($member)) {
                $confirmed++;
            }
        }

        return $confirmed;
    }

    /**
     * Clear expired waitlist entries
     */
    public function clearExpiredEntries(): int
    {
        return Waitlist::where('status', 'expired')
            ->where('expired_at', '<', now()->subDays(30))
            ->delete();
    }
}
