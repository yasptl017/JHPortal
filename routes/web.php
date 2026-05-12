<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventsController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Authentication Routes
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

        // Registrations
        Route::get('/registrations', [DashboardController::class, 'registrations'])->name('registrations');

        // Users Management
        Route::get('/users', [DashboardController::class, 'users'])->name('users');

        // Analytics
        Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');

        // Settings
        Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
    });
});
