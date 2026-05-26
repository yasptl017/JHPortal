<?php

namespace App\Services;

use App\Models\Waitlist;
use App\Models\WaitlistAuditLog;

class WaitlistAuditService
{
    /**
     * Log waitlist creation
     */
    public static function logCreated(Waitlist $waitlist): void
    {
        WaitlistAuditLog::logAction(
            $waitlist->id,
            'created',
            null,
            [
                'event_id' => $waitlist->event_id,
                'user_id' => $waitlist->user_id,
                'status' => $waitlist->status,
                'position' => $waitlist->position,
            ]
        );
    }

    /**
     * Log waitlist status change
     */
    public static function logStatusChange(Waitlist $waitlist, string $oldStatus, string $newStatus): void
    {
        WaitlistAuditLog::logAction(
            $waitlist->id,
            'status_changed',
            ['status' => $oldStatus],
            ['status' => $newStatus]
        );
    }

    /**
     * Log waitlist notification
     */
    public static function logNotified(Waitlist $waitlist): void
    {
        WaitlistAuditLog::logAction(
            $waitlist->id,
            'notified',
            ['status' => 'pending'],
            ['status' => 'notified', 'notified_at' => now()]
        );
    }

    /**
     * Log waitlist confirmation
     */
    public static function logConfirmed(Waitlist $waitlist): void
    {
        WaitlistAuditLog::logAction(
            $waitlist->id,
            'confirmed',
            ['status' => 'notified'],
            ['status' => 'confirmed', 'confirmed_at' => now()]
        );
    }

    /**
     * Log waitlist cancellation
     */
    public static function logCancelled(Waitlist $waitlist): void
    {
        WaitlistAuditLog::logAction(
            $waitlist->id,
            'cancelled',
            ['status' => $waitlist->status],
            ['status' => 'cancelled']
        );
    }

    /**
     * Log waitlist expiration
     */
    public static function logExpired(Waitlist $waitlist): void
    {
        WaitlistAuditLog::logAction(
            $waitlist->id,
            'expired',
            ['status' => $waitlist->status],
            ['status' => 'expired', 'expired_at' => now()]
        );
    }

    /**
     * Log position update
     */
    public static function logPositionUpdate(Waitlist $waitlist, int $oldPosition, int $newPosition): void
    {
        WaitlistAuditLog::logAction(
            $waitlist->id,
            'position_updated',
            ['position' => $oldPosition],
            ['position' => $newPosition]
        );
    }

    /**
     * Get audit logs for waitlist
     */
    public static function getAuditLogs(Waitlist $waitlist)
    {
        return WaitlistAuditLog::where('waitlist_id', $waitlist->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get audit logs for event
     */
    public static function getEventAuditLogs($eventId)
    {
        return WaitlistAuditLog::whereHas('waitlist', function ($query) use ($eventId) {
            $query->where('event_id', $eventId);
        })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get audit logs by action
     */
    public static function getLogsByAction(string $action)
    {
        return WaitlistAuditLog::where('action', $action)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
