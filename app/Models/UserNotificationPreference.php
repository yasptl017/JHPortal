<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotificationPreference extends Model
{
    protected $table = 'user_notification_preferences';

    protected $fillable = [
        'user_id',
        'waitlist_notifications',
        'feedback_notifications',
        'event_reminders',
        'registration_confirmations',
        'email_notifications',
    ];

    protected $casts = [
        'waitlist_notifications' => 'boolean',
        'feedback_notifications' => 'boolean',
        'event_reminders' => 'boolean',
        'registration_confirmations' => 'boolean',
        'email_notifications' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wantsWaitlistNotifications(): bool
    {
        return $this->waitlist_notifications && $this->email_notifications;
    }

    public function wantsFeedbackNotifications(): bool
    {
        return $this->feedback_notifications && $this->email_notifications;
    }

    public function wantsEventReminders(): bool
    {
        return $this->event_reminders && $this->email_notifications;
    }

    public function wantsRegistrationConfirmations(): bool
    {
        return $this->registration_confirmations && $this->email_notifications;
    }

    public static function forUser(User $user): self
    {
        return self::firstOrCreate(
            ['user_id' => $user->id],
            [
                'waitlist_notifications' => true,
                'feedback_notifications' => true,
                'event_reminders' => true,
                'registration_confirmations' => true,
                'email_notifications' => true,
            ]
        );
    }

    public function disableAll(): void
    {
        $this->update([
            'waitlist_notifications' => false,
            'feedback_notifications' => false,
            'event_reminders' => false,
            'registration_confirmations' => false,
            'email_notifications' => false,
        ]);
    }

    public function enableAll(): void
    {
        $this->update([
            'waitlist_notifications' => true,
            'feedback_notifications' => true,
            'event_reminders' => true,
            'registration_confirmations' => true,
            'email_notifications' => true,
        ]);
    }
}
