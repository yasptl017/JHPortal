<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_events' => Event::count(),
            'total_registrations' => EventRegistration::count(),
            'total_users' => User::where('is_admin', false)->count(),
            'unread_messages' => ContactMessage::unread()->count(),
        ];

        $upcomingEvents = Event::query()
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'upcomingEvents'));
    }

    public function registrations()
    {
        $registrations = EventRegistration::with(['user', 'event'])
            ->latest()
            ->paginate(15);

        return view('admin.registrations.index', compact('registrations'));
    }

    public function analytics()
    {
        return view('admin.analytics.index');
    }

    public function users()
    {
        $users = User::where('is_admin', false)
            ->withCount('eventRegistrations')
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }
}
