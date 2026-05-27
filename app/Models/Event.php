<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'category',
    'description',
    'audience',
    'visibility',
    'start_date',
    'end_date',
    'location',
    'meeting_link',
    'venue_notes',
    'capacity',
    'registration_limit',
    'registration_close_date',
    'approval_mode',
    'external_id',
    'waitlist_enabled',
    'required_fields',
    'confirmation_message',
    'reminder_message',
    'waitlist_message',
    'feedback_message',
    'reminder_offset',
    'feedback_offset',
    'email_status',
    'status',
])]
class Event extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'registration_close_date' => 'datetime',
            'waitlist_enabled' => 'boolean',
            'capacity' => 'integer',
            'registration_limit' => 'integer',
        ];
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function registeredUsers()
    {
        return $this->belongsToMany(User::class, 'event_registrations')->withPivot('status')->withTimestamps();
    }

    public function waitlist()
    {
        return $this->hasMany(Waitlist::class);
    }

    public function waitlistUsers()
    {
        return $this->belongsToMany(User::class, 'waitlist')->withTimestamps();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now());
    }

    public function getRegisteredCount(): int
    {
        return $this->registrations()->where('status', 'registered')->count();
    }

    public function isFull(): bool
    {
        if (!$this->capacity) return false;
        return $this->getRegisteredCount() >= $this->capacity;
    }

    public function spotsLeft(): ?int
    {
        if (!$this->capacity) return null;
        return max(0, $this->capacity - $this->getRegisteredCount());
    }
}
