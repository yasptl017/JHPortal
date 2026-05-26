<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Feedback;
use App\Models\Waitlist;
use App\Models\EmailLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard with all statistics.
     */
    public function index()
    {
        // ── Overview Stats ──
        $totalEvents = Event::count();
        $totalRegistrations = EventRegistration::count();
        $totalUsers = User::where('is_admin', false)->count();
        $totalAttendance = Attendance::where('status', 'present')->count();

        // Upcoming vs Past events
        $upcomingEvents = Event::where('start_date', '>=', now())->count();
        $pastEvents = Event::where('start_date', '<', now())->count();

        // Average attendance rate
        $totalAttendanceRecords = Attendance::count();
        $presentRecords = Attendance::where('status', 'present')->count();
        $attendanceRate = $totalAttendanceRecords > 0
            ? round(($presentRecords / $totalAttendanceRecords) * 100, 1)
            : 0;

        // Average feedback rating
        $avgRating = Feedback::avg('rating');
        $avgRating = $avgRating ? round($avgRating, 1) : 0;
        $totalFeedbacks = Feedback::count();

        // Waitlist stats
        $totalWaitlisted = Waitlist::where('status', 'pending')->count();
        $waitlistConverted = Waitlist::where('status', 'confirmed')->count();

        // Email stats
        $totalEmailsSent = EmailLog::where('status', 'sent')->count();
        $emailFailRate = EmailLog::count() > 0
            ? round((EmailLog::where('status', 'failed')->count() / EmailLog::count()) * 100, 1)
            : 0;

        // ── Monthly Registration Trends (last 6 months) ──
        $registrationTrends = $this->getMonthlyRegistrationTrends();

        // ── Event Categories Distribution ──
        $categoryDistribution = Event::select('category', DB::raw('count(*) as count'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderByDesc('count')
            ->get();

        // ── Top 10 Popular Events (by registration count) ──
        $popularEvents = Event::withCount('registrations')
            ->orderByDesc('registrations_count')
            ->limit(10)
            ->get();

        // ── Attendance by Status ──
        $attendanceByStatus = Attendance::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // ── Feedback Rating Distribution ──
        $ratingDistribution = Feedback::select('rating', DB::raw('count(*) as count'))
            ->groupBy('rating')
            ->orderBy('rating')
            ->get();

        // ── Repeat Attendees (users who registered for 2+ events) ──
        $repeatAttendees = User::where('is_admin', false)
            ->withCount('eventRegistrations')
            ->having('event_registrations_count', '>=', 2)
            ->orderByDesc('event_registrations_count')
            ->limit(10)
            ->get();

        // ── Monthly Attendance Trends (last 6 months) ──
        $attendanceTrends = $this->getMonthlyAttendanceTrends();

        // ── Registration vs Capacity per Event (top 8 events) ──
        $capacityComparison = Event::withCount('registrations')
            ->whereNotNull('capacity')
            ->where('capacity', '>', 0)
            ->orderByDesc('registrations_count')
            ->limit(8)
            ->get();

        // ── Recent Activity (last 10 registrations) ──
        $recentRegistrations = EventRegistration::with(['user', 'event'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.analytics.index', compact(
            'totalEvents',
            'totalRegistrations',
            'totalUsers',
            'totalAttendance',
            'upcomingEvents',
            'pastEvents',
            'attendanceRate',
            'avgRating',
            'totalFeedbacks',
            'totalWaitlisted',
            'waitlistConverted',
            'totalEmailsSent',
            'emailFailRate',
            'registrationTrends',
            'categoryDistribution',
            'popularEvents',
            'attendanceByStatus',
            'ratingDistribution',
            'repeatAttendees',
            'attendanceTrends',
            'capacityComparison',
            'recentRegistrations'
        ));
    }

    /**
     * Return analytics data as JSON for AJAX chart updates.
     */
    public function chartData(Request $request)
    {
        $type = $request->get('type', 'registrations');

        switch ($type) {
            case 'registrations':
                return response()->json($this->getMonthlyRegistrationTrends());

            case 'attendance':
                return response()->json($this->getMonthlyAttendanceTrends());

            case 'categories':
                $data = Event::select('category', DB::raw('count(*) as count'))
                    ->whereNotNull('category')
                    ->groupBy('category')
                    ->orderByDesc('count')
                    ->get();
                return response()->json($data);

            case 'ratings':
                $data = Feedback::select('rating', DB::raw('count(*) as count'))
                    ->groupBy('rating')
                    ->orderBy('rating')
                    ->get();
                return response()->json($data);

            case 'popular':
                $data = Event::withCount('registrations')
                    ->orderByDesc('registrations_count')
                    ->limit(10)
                    ->get(['id', 'title']);
                return response()->json($data);

            default:
                return response()->json(['error' => 'Invalid chart type'], 400);
        }
    }

    /**
     * Export analytics report as CSV.
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'registrations');
        $filename = "analytics_{$type}_" . date('Y-m-d') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        switch ($type) {
            case 'registrations':
                $data = EventRegistration::with(['user', 'event'])->get();
                $callback = function () use ($data) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, ['ID', 'User Name', 'User Email', 'Event Title', 'Registered At']);
                    foreach ($data as $row) {
                        fputcsv($file, [
                            $row->id,
                            $row->user->name ?? 'N/A',
                            $row->user->email ?? 'N/A',
                            $row->event->title ?? 'N/A',
                            $row->created_at->format('Y-m-d H:i:s'),
                        ]);
                    }
                    fclose($file);
                };
                break;

            case 'attendance':
                $data = Attendance::with(['user', 'event'])->get();
                $callback = function () use ($data) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, ['ID', 'User Name', 'Event Title', 'Status', 'Checked In At', 'Notes']);
                    foreach ($data as $row) {
                        fputcsv($file, [
                            $row->id,
                            $row->user->name ?? 'N/A',
                            $row->event->title ?? 'N/A',
                            $row->status,
                            $row->checked_in_at,
                            $row->notes,
                        ]);
                    }
                    fclose($file);
                };
                break;

            case 'feedback':
                $data = Feedback::with(['user', 'event'])->get();
                $callback = function () use ($data) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, ['ID', 'User Name', 'Event Title', 'Rating', 'Comments', 'Would Attend Again', 'Submitted At']);
                    foreach ($data as $row) {
                        fputcsv($file, [
                            $row->id,
                            $row->user->name ?? 'N/A',
                            $row->event->title ?? 'N/A',
                            $row->rating,
                            $row->comments,
                            $row->would_attend_again ? 'Yes' : 'No',
                            $row->created_at->format('Y-m-d H:i:s'),
                        ]);
                    }
                    fclose($file);
                };
                break;

            default:
                return back()->with('error', 'Invalid export type.');
        }

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get monthly registration trends for the last 6 months.
     */
    private function getMonthlyRegistrationTrends(): array
    {
        $months = [];
        $counts = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            $counts[] = EventRegistration::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return ['labels' => $months, 'data' => $counts];
    }

    /**
     * Get monthly attendance trends for the last 6 months.
     */
    private function getMonthlyAttendanceTrends(): array
    {
        $months = [];
        $present = [];
        $absent = [];
        $late = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');

            $present[] = Attendance::where('status', 'present')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $absent[] = Attendance::where('status', 'absent')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $late[] = Attendance::where('status', 'late')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return [
            'labels' => $months,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
        ];
    }
}
