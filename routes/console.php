<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\WaitlistReminderService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('email:send-reminders')->everyFifteenMinutes()->withoutOverlapping();
Schedule::command('email:send-feedback-requests')->everyFifteenMinutes()->withoutOverlapping();
Schedule::call(fn () => app(WaitlistReminderService::class)->expireOldEntries())
    ->name('expire-waitlist-entries')
    ->daily()
    ->withoutOverlapping();
