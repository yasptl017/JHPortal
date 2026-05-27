<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\FeedbackForm;
use Illuminate\Http\Request;

class FeedbackFormController extends Controller
{
    public function create(Event $event)
    {
        $feedbackForm = FeedbackForm::where('event_id', $event->id)->first();
        
        return view('admin.feedback.form-builder', compact('event', 'feedbackForm'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'include_rating' => 'boolean',
            'include_comments' => 'boolean',
            'include_attendance' => 'boolean',
            'include_would_attend_again' => 'boolean',
            'custom_fields' => 'nullable|array',
        ]);

        $feedbackForm = FeedbackForm::updateOrCreate(
            ['event_id' => $event->id],
            $validated
        );

        return redirect()->route('admin.events.edit', $event)
            ->with('success', 'Feedback form saved successfully!');
    }

    public function edit(Event $event)
    {
        $feedbackForm = FeedbackForm::where('event_id', $event->id)->first();
        
        return view('admin.feedback.form-builder', compact('event', 'feedbackForm'));
    }

    public function update(Request $request, Event $event)
    {
        return $this->store($request, $event);
    }
}
