<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Waitlist extends Model
{
    protected $table = 'waitlist';

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'position',
        'notified_at',
        'confirmed_at',
        'expired_at',
    ];

    protected $casts = [
        'notified_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'expired_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Get pending waitlist entries
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get notified waitlist entries
     */
    public function scopeNotified(Builder $query): Builder
    {
        return $query->where('status', 'notified');
    }

    /**
     * Scope: Get confirmed waitlist entries
     */
    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope: Get active waitlist entries (pending or notified)
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['pending', 'notified']);
    }

    /**
     * Scope: Get waitlist entries for a specific event
     */
    public function scopeForEvent(Builder $query, Event $event): Builder
    {
        return $query->where('event_id', $event->id);
    }

    /**
     * Scope: Get waitlist entries ordered by position
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position', 'asc')->orderBy('created_at', 'asc');
    }

    /**
     * Mark as notified
     */
    public function markAsNotified(): void
    {
        $this->update([
            'status' => 'notified',
            'notified_at' => now(),
        ]);
    }

    /**
     * Mark as confirmed
     */
    public function markAsConfirmed(): void
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Mark as cancelled
     */
    public function markAsCancelled(): void
    {
        $this->update([
            'status' => 'cancelled',
        ]);
    }

    /**
     * Mark as expired
     */
    public function markAsExpired(): void
    {
        $this->update([
            'status' => 'expired',
            'expired_at' => now(),
        ]);
    }

    /**
     * Check if waitlist entry is active
     */
    public function isActive(): bool
    {
        return in_array($this->status, ['pending', 'notified']);
    }

    /**
     * Check if waitlist entry is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if waitlist entry is notified
     */
    public function isNotified(): bool
    {
        return $this->status === 'notified';
    }

    /**
     * Check if waitlist entry is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Get position in waitlist
     */
    public function getPosition(): int
    {
        return $this->position ?? self::forEvent($this->event)
            ->active()
            ->where('created_at', '<=', $this->created_at)
            ->count();
    }

    /**
     * Update position in waitlist
     */
    public function updatePosition(): void
    {
        $position = self::forEvent($this->event)
            ->active()
            ->where('created_at', '<=', $this->created_at)
            ->count();

        $this->update(['position' => $position]);
    }

    /**
     * Get days until expiration
     */
    public function getDaysUntilExpiration(): ?int
    {
        if (!$this->notified_at) {
            return null;
        }

        $expirationDate = $this->notified_at->addDays(7);
        $daysLeft = now()->diffInDays($expirationDate, false);

        return max(0, $daysLeft);
    }

    /**
     * Check if expired
     */
    public function isExpired(): bool
    {
        if ($this->status === 'expired') {
            return true;
        }

        if (!$this->notified_at) {
            return false;
        }

        return now()->greaterThan($this->notified_at->addDays(7));
    }
}
