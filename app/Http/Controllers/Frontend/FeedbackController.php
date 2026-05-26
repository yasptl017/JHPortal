<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Event $event)
    {
        $feedback = Feedback::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($feedback) {
            return redirect()->route('events.show', $event)->with('info', 'You have already submitted feedback for this event.');
        }

        return view('frontend.feedback.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string|max:1000',
            'would_attend_again' => 'required|boolean',
        ]);

        Feedback::create([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comments' => $validated['comments'],
            'would_attend_again' => $validated['would_attend_again'],
        ]);

        return redirect()->route('events.show', $event)->with('success', 'Thank you for your feedback!');
    }
}
