<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Feedback;
use App\Models\FeedbackForm;
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

        $feedbackForm = FeedbackForm::where('event_id', $event->id)->first();

        return view('frontend.feedback.create', compact('event', 'feedbackForm'));
    }

    public function store(Request $request, Event $event)
    {
        $feedbackForm = FeedbackForm::where('event_id', $event->id)->first();

        $rules = [];
        
        if ($feedbackForm?->include_rating ?? true) {
            $rules['rating'] = 'required|integer|min:1|max:5';
        }
        
        if ($feedbackForm?->include_comments ?? true) {
            $rules['comments'] = 'nullable|string|max:1000';
        }
        
        if ($feedbackForm?->include_would_attend_again ?? true) {
            $rules['would_attend_again'] = 'nullable|boolean';
        }

        $validated = $request->validate($rules);

        $feedbackData = [
            'event_id' => $event->id,
            'user_id' => Auth::id(),
        ];

        if (isset($validated['rating'])) {
            $feedbackData['rating'] = $validated['rating'];
        }
        
        if (isset($validated['comments'])) {
            $feedbackData['comments'] = $validated['comments'];
        }
        
        if (isset($validated['would_attend_again'])) {
            $feedbackData['would_attend_again'] = $validated['would_attend_again'];
        }

        Feedback::create($feedbackData);

        return redirect()->route('events.show', $event)->with('success', 'Thank you for your feedback!');
    }
}
