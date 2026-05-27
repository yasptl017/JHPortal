<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventsController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\FeedbackFormController;
use App\Http\Controllers\Admin\EmailLogController;
use App\Http\Controllers\Admin\EmailSettingsController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\EventController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\AuthController as FrontendAuthController;
use App\Http\Controllers\Frontend\FeedbackController;
use App\Http\Controllers\Frontend\WaitlistController;
use App\Http\Controllers\Admin\WaitlistController as AdminWaitlistController;

// ============================================
// PUBLIC FRONTEND ROUTES
// ============================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Events (public)
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// ============================================
// USER AUTH ROUTES
// ============================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [FrontendAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [FrontendAuthController::class, 'login']);
    Route::get('/register', [FrontendAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [FrontendAuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [FrontendAuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [FrontendAuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [FrontendAuthController::class, 'updateProfile'])->name('profile.update');

    // Event registration (authenticated users)
    Route::post('/events/{event}/register', [EventController::class, 'register'])->name('events.register');
    Route::post('/events/{event}/cancel', [EventController::class, 'cancelRegistration'])->name('events.cancel');

    // Event feedback (authenticated users)
    Route::get('/events/{event}/feedback', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/events/{event}/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    // Waitlist (authenticated users)
    Route::post('/events/{event}/waitlist', [WaitlistController::class, 'join'])->name('waitlist.join');
    Route::post('/events/{event}/waitlist/leave', [WaitlistController::class, 'leave'])->name('waitlist.leave');
    Route::post('/events/{event}/waitlist/confirm', [WaitlistController::class, 'confirm'])->name('waitlist.confirm');
    Route::get('/waitlist', [WaitlistController::class, 'index'])->name('waitlist.index');
    Route::get('/events/{event}/waitlist/position', [WaitlistController::class, 'position'])->name('waitlist.position');
    Route::get('/events/{event}/waitlist/details', [WaitlistController::class, 'details'])->name('waitlist.details');
});

// ============================================
// ADMIN ROUTES
// ============================================
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Authentication
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Protected Admin Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Events Management
        Route::get('/events', [EventsController::class, 'index'])->name('events');
        Route::get('/events/create', [EventsController::class, 'create'])->name('events.create');
        Route::post('/events', [EventsController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [EventsController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [EventsController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [EventsController::class, 'destroy'])->name('events.destroy');
        Route::get('/events/calendar/data', [EventsController::class, 'calendarEvents'])->name('events.calendar');

        // Slider Management
        Route::get('/sliders', [SliderController::class, 'index'])->name('sliders.index');
        Route::get('/sliders/create', [SliderController::class, 'create'])->name('sliders.create');
        Route::post('/sliders', [SliderController::class, 'store'])->name('sliders.store');
        Route::get('/sliders/{slider}/edit', [SliderController::class, 'edit'])->name('sliders.edit');
        Route::put('/sliders/{slider}', [SliderController::class, 'update'])->name('sliders.update');
        Route::delete('/sliders/{slider}', [SliderController::class, 'destroy'])->name('sliders.destroy');

        // Contact Messages
        Route::get('/messages', [ContactMessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{message}', [ContactMessageController::class, 'show'])->name('messages.show');
        Route::delete('/messages/{message}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');

        // Attendance Tracking
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/events/{event}/attendance', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');
        Route::post('/events/{event}/attendance/bulk', [AttendanceController::class, 'bulkMarkAttendance'])->name('attendance.bulk');
        Route::get('/events/{event}/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');

        // Feedback Management
        Route::get('/feedback', [AdminFeedbackController::class, 'index'])->name('feedback.index');
        Route::get('/feedback/{feedback}', [AdminFeedbackController::class, 'show'])->name('feedback.show');
        Route::get('/events/{event}/feedback-form', [FeedbackFormController::class, 'create'])->name('feedback-form.create');
        Route::post('/events/{event}/feedback-form', [FeedbackFormController::class, 'store'])->name('feedback-form.store');
        Route::get('/events/{event}/feedback-form/edit', [FeedbackFormController::class, 'edit'])->name('feedback-form.edit');
        Route::put('/events/{event}/feedback-form', [FeedbackFormController::class, 'update'])->name('feedback-form.update');

        // Email Logs
        Route::get('/email-logs', [EmailLogController::class, 'index'])->name('email-logs.index');
        Route::get('/email-logs/{emailLog}', [EmailLogController::class, 'show'])->name('email-logs.show');
        Route::post('/email-logs/{emailLog}/resend', [EmailLogController::class, 'resend'])->name('email-logs.resend');
        Route::delete('/email-logs/{emailLog}', [EmailLogController::class, 'destroy'])->name('email-logs.destroy');

        // Waitlist Management
        Route::get('/waitlist', [AdminWaitlistController::class, 'index'])->name('waitlist.index');
        Route::post('/waitlist/{waitlist}/notify', [AdminWaitlistController::class, 'notify'])->name('waitlist.notify');
        Route::post('/waitlist/{waitlist}/confirm', [AdminWaitlistController::class, 'confirm'])->name('waitlist.confirm');
        Route::post('/waitlist/{waitlist}/cancel', [AdminWaitlistController::class, 'cancel'])->name('waitlist.cancel');
        Route::delete('/waitlist/{waitlist}', [AdminWaitlistController::class, 'destroy'])->name('waitlist.destroy');
        Route::post('/waitlist/bulk-notify', [AdminWaitlistController::class, 'bulkNotify'])->name('waitlist.bulkNotify');
        Route::post('/waitlist/bulk-confirm', [AdminWaitlistController::class, 'bulkConfirm'])->name('waitlist.bulkConfirm');
        Route::post('/waitlist/promote', [AdminWaitlistController::class, 'promote'])->name('waitlist.promote');
        Route::get('/waitlist/export', [AdminWaitlistController::class, 'export'])->name('waitlist.export');
        Route::get('/waitlist/stats', [AdminWaitlistController::class, 'stats'])->name('waitlist.stats');

        // Registrations
        Route::get('/registrations', [DashboardController::class, 'registrations'])->name('registrations');

        // Users Management
        Route::get('/users', [DashboardController::class, 'users'])->name('users');

        // Analytics & Reporting Dashboard (FR-09)
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
        Route::get('/analytics/chart-data', [AnalyticsController::class, 'chartData'])->name('analytics.chartData');
        Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');

        // Settings
        Route::get('/settings', [SiteSettingController::class, 'index'])->name('settings');
        Route::put('/settings', [SiteSettingController::class, 'update'])->name('settings.update');

        // Email Settings
        Route::get('/email-settings', [EmailSettingsController::class, 'index'])->name('email-settings.index');
        Route::post('/email-settings', [EmailSettingsController::class, 'store'])->name('email-settings.store');
        Route::post('/email-settings/test', [EmailSettingsController::class, 'testEmail'])->name('email-settings.test');
    });
});
