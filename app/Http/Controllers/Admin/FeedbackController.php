<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_admin');
    }

    public function index(Request $request)
    {
        $events = Event::where('status', 'published')->get();
        $selectedEvent = $request->query('event_id') ? Event::find($request->query('event_id')) : null;
        
        $feedback = [];
        $stats = [];
        
        if ($selectedEvent) {
            $feedback = Feedback::where('event_id', $selectedEvent->id)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();

            $stats = [
                'total_responses' => $feedback->count(),
                'average_rating' => $feedback->count() > 0 ? round($feedback->avg('rating'), 2) : 0,
                'would_attend_again' => $feedback->where('would_attend_again', true)->count(),
                'rating_distribution' => [
                    '5' => $feedback->where('rating', 5)->count(),
                    '4' => $feedback->where('rating', 4)->count(),
                    '3' => $feedback->where('rating', 3)->count(),
                    '2' => $feedback->where('rating', 2)->count(),
                    '1' => $feedback->where('rating', 1)->count(),
                ],
            ];
        }

        return view('admin.feedback.index', compact('events', 'selectedEvent', 'feedback', 'stats'));
    }

    public function show(Feedback $feedback)
    {
        return view('admin.feedback.show', compact('feedback'));
    }
}
