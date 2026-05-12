<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_events' => Event::count(),
            'total_registrations' => 0,
            'total_attendees' => 0,
            'pending_approvals' => 0,
        ];

        $upcomingEvents = Event::query()
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'upcomingEvents'));
    }

    /**
     * Show events management page.
     */
    public function events()
    {
        return view('admin.events.index');
    }

    /**
     * Show registrations page.
     */
    public function registrations()
    {
        return view('admin.registrations.index');
    }

    /**
     * Show analytics page.
     */
    public function analytics()
    {
        return view('admin.analytics.index');
    }

    /**
     * Show user management page.
     */
    public function users()
    {
        return view('admin.users.index');
    }

    /**
     * Show settings page.
     */
    public function settings()
    {
        return view('admin.settings.index');
    }
}
