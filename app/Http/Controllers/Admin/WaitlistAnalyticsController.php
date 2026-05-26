<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Waitlist;
use Illuminate\Http\Request;

class WaitlistAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_admin');
    }

    /**
     * Display waitlist analytics dashboard
     */
    public function index(Request $request)
    {
        $events = Event::where('status', 'published')
            ->where('waitlist_enabled', true)
            ->orderBy('start_date', 'desc')
            ->get();

        $selectedEvent = $request->query('event_id') ? Event::find($request->query('event_id')) : null;

        $analytics = [];
        $chartData = [];

        if ($selectedEvent) {
            $analytics = $this->getEventAnalytics($selectedEvent);
            $chartData = $this->buildChartData($selectedEvent);
        }

        return view('admin.waitlist.analytics', compact('events', 'selectedEvent', 'analytics', 'chartData'));
    }

    /**
     * Get analytics for specific event
     */
    private function getEventAnalytics(Event $event): array
    {
        $totalWaitlist = Waitlist::where('event_id', $event->id)->count();
        $pending = Waitlist::where('event_id', $event->id)->where('status', 'pending')->count();
        $notified = Waitlist::where('event_id', $event->id)->where('status', 'notified')->count();
        $confirmed = Waitlist::where('event_id', $event->id)->where('status', 'confirmed')->count();
        $cancelled = Waitlist::where('event_id', $event->id)->where('status', 'cancelled')->count();
        $expired = Waitlist::where('event_id', $event->id)->where('status', 'expired')->count();

        $conversionRate = $totalWaitlist > 0 ? ($confirmed / $totalWaitlist) * 100 : 0;
        $abandonmentRate = $totalWaitlist > 0 ? (($cancelled + $expired) / $totalWaitlist) * 100 : 0;

        $avgWaitTime = $this->calculateAverageWaitTime($event);

        return [
            'total_waitlist' => $totalWaitlist,
            'pending' => $pending,
            'notified' => $notified,
            'confirmed' => $confirmed,
            'cancelled' => $cancelled,
            'expired' => $expired,
            'conversion_rate' => round($conversionRate, 2),
            'abandonment_rate' => round($abandonmentRate, 2),
            'avg_wait_time' => $avgWaitTime,
        ];
    }

    /**
     * Calculate average wait time in hours
     */
    private function calculateAverageWaitTime(Event $event): float
    {
        $confirmed = Waitlist::where('event_id', $event->id)
            ->where('status', 'confirmed')
            ->whereNotNull('notified_at')
            ->whereNotNull('confirmed_at')
            ->get();

        if ($confirmed->isEmpty()) {
            return 0;
        }

        $totalHours = 0;
        foreach ($confirmed as $entry) {
            $hours = $entry->notified_at->diffInHours($entry->confirmed_at);
            $totalHours += $hours;
        }

        return round($totalHours / $confirmed->count(), 2);
    }

    /**
     * Build chart data for waitlist status distribution
     */
    private function buildChartData(Event $event): array
    {
        $pending = Waitlist::where('event_id', $event->id)->where('status', 'pending')->count();
        $notified = Waitlist::where('event_id', $event->id)->where('status', 'notified')->count();
        $confirmed = Waitlist::where('event_id', $event->id)->where('status', 'confirmed')->count();
        $cancelled = Waitlist::where('event_id', $event->id)->where('status', 'cancelled')->count();
        $expired = Waitlist::where('event_id', $event->id)->where('status', 'expired')->count();

        return [
            'labels' => ['Pending', 'Notified', 'Confirmed', 'Cancelled', 'Expired'],
            'data' => [$pending, $notified, $confirmed, $cancelled, $expired],
            'colors' => ['#FFC107', '#17A2B8', '#28A745', '#DC3545', '#6C757D'],
        ];
    }

    /**
     * Get analytics API endpoint
     */
    public function getAnalytics(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $event = Event::findOrFail($validated['event_id']);
        $analytics = $this->getEventAnalytics($event);

        return response()->json($analytics);
    }

    /**
     * Get chart data API endpoint
     */
    public function getChartDataApi(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $event = Event::findOrFail($validated['event_id']);
        $chartData = $this->buildChartData($event);

        return response()->json($chartData);
    }

    /**
     * Get trend data over time
     */
    public function getTrendData(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'days' => 'nullable|integer|min:7|max:90',
        ]);

        $event = Event::findOrFail($validated['event_id']);
        $days = $validated['days'] ?? 30;

        $trendData = [];
        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = Waitlist::where('event_id', $event->id)
                ->whereDate('created_at', $date)
                ->count();

            $trendData[] = [
                'date' => $date,
                'count' => $count,
            ];
        }

        return response()->json($trendData);
    }
}
