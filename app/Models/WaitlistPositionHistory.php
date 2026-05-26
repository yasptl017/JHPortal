<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaitlistPositionHistory extends Model
{
    protected $table = 'waitlist_position_history';

    protected $fillable = [
        'waitlist_id',
        'old_position',
        'new_position',
        'reason',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function waitlist(): BelongsTo
    {
        return $this->belongsTo(Waitlist::class);
    }

    public static function recordPositionChange(int $waitlistId, ?int $oldPosition, int $newPosition, ?string $reason = null): self
    {
        return self::create([
            'waitlist_id' => $waitlistId,
            'old_position' => $oldPosition,
            'new_position' => $newPosition,
            'reason' => $reason,
        ]);
    }

    public function getPositionChangeAttribute(): int
    {
        if ($this->old_position === null) {
            return $this->new_position;
        }
        return $this->old_position - $this->new_position;
    }

    public function isMoved(): bool
    {
        return $this->old_position !== null && $this->old_position !== $this->new_position;
    }
}
