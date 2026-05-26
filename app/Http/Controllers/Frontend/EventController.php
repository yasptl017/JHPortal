<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Services\WaitlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::published()
            ->where('visibility', 'public')
            ->upcoming()
            ->orderBy('start_date');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $events = $query->paginate(9)->withQueryString();
        $categories = Event::published()->whereNotNull('category')->distinct()->pluck('category');

        return view('frontend.events.index', compact('events', 'categories'));
    }

    public function show(Event $event)
    {
        if ($event->status !== 'published' || $event->visibility !== 'public') {
            abort(404);
        }

        $isRegistered = false;
        $registration = null;
        if (Auth::check()) {
            $registration = EventRegistration::where('user_id', Auth::id())
                ->where('event_id', $event->id)
                ->first();
            $isRegistered = (bool) $registration;
        }

        return view('frontend.events.show', compact('event', 'isRegistered', 'registration'));
    }

    public function register(Event $event)
    {
        $user = Auth::user();

        $existing = EventRegistration::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'You are already registered for this event.');
        }

        $status = 'registered';
        if ($event->isFull()) {
            if ($event->waitlist_enabled) {
                $status = 'waitlisted';
            } else {
                return back()->with('error', 'This event is full and waitlist is not available.');
            }
        }

        EventRegistration::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => $status,
        ]);

        $message = $status === 'waitlisted'
            ? 'You have been added to the waitlist.'
            : 'You have successfully registered for this event!';

        return back()->with('success', $message);
    }

    public function cancelRegistration(Event $event, WaitlistService $waitlistService)
    {
        $registration = EventRegistration::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        if ($registration) {
            $registration->update(['status' => 'cancelled']);
            
            // Promote next waitlist member if event has waitlist enabled
            if ($event->waitlist_enabled) {
                $waitlistService->promoteNextWaitlistMember($event);
            }
        }

        return back()->with('success', 'Your registration has been cancelled.');
    }
}
