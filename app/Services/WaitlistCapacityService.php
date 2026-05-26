<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Waitlist;

class WaitlistCapacityService
{
    /**
     * Check if waitlist is at capacity
     */
    public function isWaitlistFull(Event $event): bool
    {
        if (!$event->waitlist_capacity) {
            return false;
        }

        $currentCount = Waitlist::where('event_id', $event->id)
            ->whereIn('status', ['pending', 'notified'])
            ->count();

        return $currentCount >= $event->waitlist_capacity;
    }

    /**
     * Get remaining waitlist capacity
     */
    public function getRemainingCapacity(Event $event): ?int
    {
        if (!$event->waitlist_capacity) {
            return null;
        }

        $currentCount = Waitlist::where('event_id', $event->id)
            ->whereIn('status', ['pending', 'notified'])
            ->count();

        return max(0, $event->waitlist_capacity - $currentCount);
    }

    /**
     * Get waitlist utilization percentage
     */
    public function getUtilizationPercentage(Event $event): float
    {
        if (!$event->waitlist_capacity) {
            return 0;
        }

        $currentCount = Waitlist::where('event_id', $event->id)
            ->whereIn('status', ['pending', 'notified'])
            ->count();

        return ($currentCount / $event->waitlist_capacity) * 100;
    }

    /**
     * Check if user can be added to waitlist
     */
    public function canAddToWaitlist(Event $event, int $userId): bool
    {
        // Check if already on waitlist
        $existing = Waitlist::where('event_id', $event->id)
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'notified'])
            ->exists();

        if ($existing) {
            return false;
        }

        // Check if waitlist is full
        return !$this->isWaitlistFull($event);
    }

    /**
     * Set waitlist capacity for event
     */
    public function setCapacity(Event $event, ?int $capacity): void
    {
        $event->update(['waitlist_capacity' => $capacity]);
    }

    /**
     * Get waitlist statistics
     */
    public function getWaitlistStats(Event $event): array
    {
        $pending = Waitlist::where('event_id', $event->id)
            ->where('status', 'pending')
            ->count();

        $notified = Waitlist::where('event_id', $event->id)
            ->where('status', 'notified')
            ->count();

        $active = $pending + $notified;

        return [
            'capacity' => $event->waitlist_capacity,
            'active_count' => $active,
            'pending' => $pending,
            'notified' => $notified,
            'remaining' => $this->getRemainingCapacity($event),
            'utilization_percentage' => round($this->getUtilizationPercentage($event), 2),
            'is_full' => $this->isWaitlistFull($event),
        ];
    }
}
