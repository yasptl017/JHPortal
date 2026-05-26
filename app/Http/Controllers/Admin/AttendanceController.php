<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::where('status', 'published')->get();
        $selectedEvent = $request->query('event_id') ? Event::find($request->query('event_id')) : null;
        
        $attendance = [];
        if ($selectedEvent) {
            $attendance = Attendance::where('event_id', $selectedEvent->id)
                ->with('user')
                ->get();
        }

        return view('admin.attendance.index', compact('events', 'selectedEvent', 'attendance'));
    }

    public function markAttendance(Request $request, Event $event)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:present,absent,late',
            'notes' => 'nullable|string',
        ]);

        $attendance = Attendance::updateOrCreate(
            ['event_id' => $event->id, 'user_id' => $validated['user_id']],
            [
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
                'checked_in_at' => now(),
            ]
        );

        return back()->with('success', 'Attendance recorded successfully.');
    }

    public function bulkMarkAttendance(Request $request, Event $event)
    {
        $validated = $request->validate([
            'attendees' => 'required|array',
            'attendees.*.user_id' => 'required|exists:users,id',
            'attendees.*.status' => 'required|in:present,absent,late',
        ]);

        foreach ($validated['attendees'] as $attendee) {
            Attendance::updateOrCreate(
                ['event_id' => $event->id, 'user_id' => $attendee['user_id']],
                [
                    'status' => $attendee['status'],
                    'checked_in_at' => now(),
                ]
            );
        }

        return back()->with('success', 'Attendance updated for all attendees.');
    }

    public function report(Event $event)
    {
        $attendance = Attendance::where('event_id', $event->id)
            ->with('user')
            ->get();

        $stats = [
            'total_registered' => EventRegistration::where('event_id', $event->id)->count(),
            'present' => $attendance->where('status', 'present')->count(),
            'absent' => $attendance->where('status', 'absent')->count(),
            'late' => $attendance->where('status', 'late')->count(),
            'attendance_rate' => $attendance->count() > 0 
                ? round(($attendance->where('status', 'present')->count() / $attendance->count()) * 100, 2)
                : 0,
        ];

        return view('admin.attendance.report', compact('event', 'attendance', 'stats'));
    }
}
