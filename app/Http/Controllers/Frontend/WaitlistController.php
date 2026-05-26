<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Waitlist;
use App\Services\WaitlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaitlistController extends Controller
{
    protected WaitlistService $waitlistService;

    public function __construct(WaitlistService $waitlistService)
    {
        $this->waitlistService = $waitlistService;
    }

    /**
     * Display user's waitlist entries
     */
    public function index(Request $request)
    {
        $waitlistEntries = Waitlist::where('user_id', Auth::id())
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('frontend.waitlist.index', compact('waitlistEntries'));
    }

    /**
     * Join event waitlist
     */
    public function join(Event $event)
    {
        $user = Auth::user();

        // Check if user is already registered
        $existingRegistration = $event->registrations()
            ->where('user_id', $user->id)
            ->whereIn('status', ['registered', 'attended'])
            ->first();

        if ($existingRegistration) {
            return back()->with('error', 'You are already registered for this event.');
        }

        // Check if user is already on waitlist
        $existingWaitlist = Waitlist::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->active()
            ->first();

        if ($existingWaitlist) {
            return back()->with('error', 'You are already on the waitlist for this event.');
        }

        // Check if event is full
        if (!$event->isFull()) {
            return back()->with('error', 'This event is not full. Please register directly instead.');
        }

        // Check if waitlist is enabled
        if (!$event->waitlist_enabled) {
            return back()->with('error', 'Waitlist is not available for this event.');
        }

        // Add to waitlist
        $waitlist = $this->waitlistService->addToWaitlist($user, $event);

        if (!$waitlist) {
            return back()->with('error', 'Unable to add you to the waitlist.');
        }

        return back()->with('success', 'You have been added to the waitlist. We will notify you if a spot becomes available.');
    }

    /**
     * Leave event waitlist
     */
    public function leave(Event $event)
    {
        $user = Auth::user();

        if (!$this->waitlistService->removeFromWaitlist($user, $event)) {
            return back()->with('error', 'You are not on the waitlist for this event.');
        }

        return back()->with('success', 'You have been removed from the waitlist.');
    }

    /**
     * Get waitlist position for event
     */
    public function position(Event $event)
    {
        $user = Auth::user();
        $position = $this->waitlistService->getUserWaitlistPosition($user, $event);

        if ($position === null) {
            return response()->json(['position' => null, 'message' => 'Not on waitlist']);
        }

        return response()->json(['position' => $position]);
    }

    /**
     * Get waitlist details for event
     */
    public function details(Event $event)
    {
        $user = Auth::user();
        $waitlist = Waitlist::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if (!$waitlist) {
            return response()->json(['error' => 'Not on waitlist'], 404);
        }

        return response()->json([
            'id' => $waitlist->id,
            'status' => $waitlist->status,
            'position' => $waitlist->getPosition(),
            'joined_at' => $waitlist->created_at,
            'notified_at' => $waitlist->notified_at,
            'confirmed_at' => $waitlist->confirmed_at,
            'days_until_expiration' => $waitlist->getDaysUntilExpiration(),
            'is_expired' => $waitlist->isExpired(),
        ]);
    }

    /**
     * Confirm waitlist spot (when notified)
     */
    public function confirm(Event $event)
    {
        $user = Auth::user();
        $waitlist = Waitlist::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if (!$waitlist) {
            return back()->with('error', 'You are not on the waitlist for this event.');
        }

        if (!$waitlist->isNotified()) {
            return back()->with('error', 'You have not been notified about a spot.');
        }

        if ($this->waitlistService->confirmWaitlistMember($waitlist)) {
            return back()->with('success', 'You have been registered for the event!');
        }

        return back()->with('error', 'Unable to confirm your spot. The event may be full.');
    }
}
