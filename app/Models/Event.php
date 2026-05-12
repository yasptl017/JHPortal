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
}
