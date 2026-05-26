<?php

namespace App\Services;

use App\Models\Waitlist;
use Illuminate\Support\Collection;

class WaitlistReminderService
{
    protected EmailNotificationService $emailService;

    public function __construct(EmailNotificationService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Send reminder emails for waitlist members notified 3 days ago
     */
    public function sendThreeDayReminders(): int
    {
        $reminders = $this->getRemindersForDaysAgo(3);
        return $this->sendReminders($reminders, '3 days');
    }

    /**
     * Send reminder emails for waitlist members notified 1 day ago
     */
    public function sendOneDayReminders(): int
    {
        $reminders = $this->getRemindersForDaysAgo(1);
        return $this->sendReminders($reminders, '1 day');
    }

    /**
     * Send reminder emails for waitlist members notified 24 hours ago
     */
    public function sendTwentyFourHourReminders(): int
    {
        $reminders = $this->getRemindersForHoursAgo(24);
        return $this->sendReminders($reminders, '24 hours');
    }

    /**
     * Get waitlist members notified X days ago
     */
    private function getRemindersForDaysAgo(int $days): Collection
    {
        $startDate = now()->subDays($days)->startOfDay();
        $endDate = now()->subDays($days)->endOfDay();

        return Waitlist::where('status', 'notified')
            ->whereBetween('notified_at', [$startDate, $endDate])
            ->with('user', 'event')
            ->get();
    }

    /**
     * Get waitlist members notified X hours ago
     */
    private function getRemindersForHoursAgo(int $hours): Collection
    {
        $startTime = now()->subHours($hours)->subMinutes(5);
        $endTime = now()->subHours($hours)->addMinutes(5);

        return Waitlist::where('status', 'notified')
            ->whereBetween('notified_at', [$startTime, $endTime])
            ->with('user', 'event')
            ->get();
    }

    /**
     * Send reminders to collection of waitlist members
     */
    private function sendReminders(Collection $reminders, string $timeframe): int
    {
        $sent = 0;

        foreach ($reminders as $reminder) {
            if ($this->sendWaitlistReminder($reminder, $timeframe)) {
                $sent++;
            }
        }

        return $sent;
    }

    /**
     * Send reminder email to single waitlist member
     */
    private function sendWaitlistReminder(Waitlist $waitlist, string $timeframe): bool
    {
        try {
            $this->emailService->sendWaitlistReminder(
                $waitlist->user,
                $waitlist->event,
                $timeframe
            );
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send waitlist reminder: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Get expiring waitlist entries (notified 7 days ago)
     */
    public function getExpiringEntries(): Collection
    {
        $expirationDate = now()->subDays(7);

        return Waitlist::where('status', 'notified')
            ->where('notified_at', '<=', $expirationDate)
            ->with('user', 'event')
            ->get();
    }

    /**
     * Expire old waitlist entries
     */
    public function expireOldEntries(): int
    {
        $expiring = $this->getExpiringEntries();
        $expired = 0;

        foreach ($expiring as $entry) {
            $entry->markAsExpired();
            WaitlistAuditService::logExpired($entry);
            $expired++;
        }

        return $expired;
    }

    /**
     * Get days remaining until expiration for waitlist entry
     */
    public function getDaysUntilExpiration(Waitlist $waitlist): ?int
    {
        if ($waitlist->status !== 'notified' || !$waitlist->notified_at) {
            return null;
        }

        $expirationDate = $waitlist->notified_at->addDays(7);
        $daysLeft = now()->diffInDays($expirationDate, false);

        return max(0, $daysLeft);
    }

    /**
     * Check if waitlist entry is expiring soon (within 24 hours)
     */
    public function isExpiringWithin24Hours(Waitlist $waitlist): bool
    {
        if ($waitlist->status !== 'notified' || !$waitlist->notified_at) {
            return false;
        }

        $expirationDate = $waitlist->notified_at->addDays(7);
        $hoursLeft = now()->diffInHours($expirationDate, false);

        return $hoursLeft <= 24 && $hoursLeft > 0;
    }
}
