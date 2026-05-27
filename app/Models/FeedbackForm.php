<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedbackForm extends Model
{
    protected $fillable = [
        'event_id',
        'title',
        'description',
        'include_rating',
        'include_comments',
        'include_attendance',
        'include_would_attend_again',
        'custom_fields',
        'is_active',
    ];

    protected $casts = [
        'include_rating' => 'boolean',
        'include_comments' => 'boolean',
        'include_attendance' => 'boolean',
        'include_would_attend_again' => 'boolean',
        'is_active' => 'boolean',
        'custom_fields' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function getCustomFieldsAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function setCustomFieldsAttribute($value)
    {
        $this->attributes['custom_fields'] = $value ? json_encode($value) : null;
    }
}
