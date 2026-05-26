<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventsController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\EventController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\AuthController as FrontendAuthController;

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

        // Registrations
        Route::get('/registrations', [DashboardController::class, 'registrations'])->name('registrations');

        // Users Management
        Route::get('/users', [DashboardController::class, 'users'])->name('users');

        // Analytics
        Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');

        // Settings
        Route::get('/settings', [SiteSettingController::class, 'index'])->name('settings');
        Route::put('/settings', [SiteSettingController::class, 'update'])->name('settings.update');
    });
});
