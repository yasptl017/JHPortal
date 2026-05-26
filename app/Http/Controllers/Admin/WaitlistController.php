<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Waitlist;
use App\Services\WaitlistService;
use Illuminate\Http\Request;

class WaitlistController extends Controller
{
    protected WaitlistService $waitlistService;

    public function __construct(WaitlistService $waitlistService)
    {
        $this->waitlistService = $waitlistService;
    }

    /**
     * Display waitlist management page
     */
    public function index(Request $request)
    {
        $events = Event::where('status', 'published')
            ->where('waitlist_enabled', true)
            ->orderBy('start_date', 'desc')
            ->get();

        $selectedEvent = $request->query('event_id') ? Event::find($request->query('event_id')) : null;

        $waitlist = [];
        $stats = [];

        if ($selectedEvent) {
            $waitlist = Waitlist::where('event_id', $selectedEvent->id)
                ->with('user')
                ->ordered()
                ->paginate(20);

            $stats = $this->waitlistService->getWaitlistStats($selectedEvent);
        }

        return view('admin.waitlist.index', compact('events', 'selectedEvent', 'waitlist', 'stats'));
    }

    /**
     * Notify a single waitlist member
     */
    public function notify(Waitlist $waitlist)
    {
        if (!$waitlist->isPending()) {
            return back()->with('error', 'Only pending waitlist members can be notified.');
        }

        $this->waitlistService->notifyWaitlistMember($waitlist);

        return back()->with('success', 'Waitlist member notified via email.');
    }

    /**
     * Confirm a waitlist member and register for event
     */
    public function confirm(Waitlist $waitlist)
    {
        if (!$this->waitlistService->confirmWaitlistMember($waitlist)) {
            return back()->with('error', 'Unable to confirm waitlist member. Event may be full.');
        }

        return back()->with('success', 'Waitlist member confirmed and registered for the event.');
    }

    /**
     * Cancel a waitlist entry
     */
    public function cancel(Waitlist $waitlist)
    {
        $waitlist->markAsCancelled();
        $this->waitlistService->reorderWaitlist($waitlist->event);

        return back()->with('success', 'Waitlist entry cancelled.');
    }

    /**
     * Delete a waitlist entry
     */
    public function destroy(Waitlist $waitlist)
    {
        $event = $waitlist->event;
        $waitlist->delete();
        $this->waitlistService->reorderWaitlist($event);

        return back()->with('success', 'Waitlist entry removed.');
    }

    /**
     * Bulk notify waitlist members
     */
    public function bulkNotify(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'integer|exists:users,id',
        ]);

        $event = Event::findOrFail($validated['event_id']);
        $userIds = $validated['user_ids'] ?? [];

        $notified = $this->waitlistService->bulkNotifyWaitlist($event, $userIds);

        return back()->with('success', "Notified {$notified} waitlist member(s).");
    }

    /**
     * Bulk confirm waitlist members
     */
    public function bulkConfirm(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'integer|exists:users,id',
        ]);

        $event = Event::findOrFail($validated['event_id']);
        $userIds = $validated['user_ids'] ?? [];

        $confirmed = $this->waitlistService->bulkConfirmWaitlist($event, $userIds);

        return back()->with('success', "Confirmed {$confirmed} waitlist member(s).");
    }

    /**
     * Promote next waitlist member
     */
    public function promote(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'count' => 'nullable|integer|min:1|max:50',
        ]);

        $event = Event::findOrFail($validated['event_id']);
        $count = $validated['count'] ?? 1;

        $promoted = $this->waitlistService->promoteMultipleWaitlistMembers($event, $count);

        if ($promoted->isEmpty()) {
            return back()->with('warning', 'No pending waitlist members to promote.');
        }

        return back()->with('success', "Promoted {$promoted->count()} waitlist member(s).");
    }

    /**
     * Export waitlist to CSV
     */
    public function export(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $event = Event::findOrFail($validated['event_id']);
        $waitlist = Waitlist::where('event_id', $event->id)
            ->with('user')
            ->ordered()
            ->get();

        $filename = 'waitlist_' . $event->id . '_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($waitlist) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Position', 'Name', 'Email', 'Status', 'Joined Date', 'Notified Date', 'Confirmed Date']);

            foreach ($waitlist as $entry) {
                fputcsv($file, [
                    $entry->position,
                    $entry->user->name,
                    $entry->user->email,
                    ucfirst($entry->status),
                    $entry->created_at->format('Y-m-d H:i'),
                    $entry->notified_at?->format('Y-m-d H:i') ?? '-',
                    $entry->confirmed_at?->format('Y-m-d H:i') ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get waitlist statistics
     */
    public function stats(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $event = Event::findOrFail($validated['event_id']);
        $stats = $this->waitlistService->getWaitlistStats($event);

        return response()->json($stats);
    }
}
