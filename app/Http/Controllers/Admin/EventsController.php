<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventsController extends Controller
{
    /**
     * Show events management page.
     */
    public function index(Request $request)
    {
        $query = Event::query()->latest('start_date');

        if ($request->filled('search')) {
            $search = $request->string('search');

            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $events = $query->paginate(10)->withQueryString();
        $categories = Event::query()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.events.index', compact('events', 'categories'));
    }

    /**
     * Show the Create Event form with tabs.
     */
    public function create()
    {
        return view('admin.events.create', [
            'event' => null,
        ]);
    }

    /**
     * Handle Create Event form submission.
     */
    public function store(Request $request)
    {
        $event = Event::create($this->eventData($request));

        $message = $event->status === 'published' ? 'published' : 'saved as draft';
        $request->session()->flash('success', 'Event "' . $event->title . '" has been ' . $message . '.');

        return redirect()->route('admin.events');
    }

    /**
     * Show the Edit Event form.
     */
    public function edit(Event $event)
    {
        return view('admin.events.create', compact('event'));
    }

    /**
     * Update an event.
     */
    public function update(Request $request, Event $event)
    {
        $event->update($this->eventData($request));

        $message = $event->status === 'published' ? 'updated and published' : 'updated as draft';
        $request->session()->flash('success', 'Event "' . $event->title . '" has been ' . $message . '.');

        return redirect()->route('admin.events');
    }

    /**
     * Delete an event.
     */
    public function destroy(Request $request, Event $event)
    {
        $title = $event->title;
        $event->delete();

        $request->session()->flash('success', 'Event "' . $title . '" has been deleted.');

        return redirect()->route('admin.events');
    }

    /**
     * Validate and normalize event form data.
     */
    private function eventData(Request $request): array
    {
        $status = $request->input('status', 'draft');
        $requiredWhenPublishing = $status === 'published' ? ['required'] : ['nullable'];

        $validated = $request->validate([
            'title' => [...$requiredWhenPublishing, 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'audience' => ['nullable', 'string', 'max:255'],
            'visibility' => ['required', Rule::in(['public', 'private'])],
            'start_date' => [...$requiredWhenPublishing, 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'location' => ['nullable', 'string', 'max:255'],
            'meeting_link' => ['nullable', 'url', 'max:255'],
            'venue_notes' => ['nullable', 'string'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'registration_limit' => ['nullable', 'integer', 'min:1'],
            'registration_close_date' => ['nullable', 'date', 'before_or_equal:start_date'],
            'approval_mode' => ['required', Rule::in(['automatic', 'manual'])],
            'external_id' => ['nullable', 'string', 'max:255'],
            'required_fields' => ['nullable', 'string'],
            'confirmation_message' => ['nullable', 'string'],
            'reminder_message' => ['nullable', 'string'],
            'waitlist_message' => ['nullable', 'string'],
            'feedback_message' => ['nullable', 'string'],
            'reminder_offset' => ['required', Rule::in(['24h', '48h', '7d'])],
            'feedback_offset' => ['required', Rule::in(['2h', '24h', '48h'])],
            'email_status' => ['required', Rule::in(['enabled', 'disabled'])],
            'status' => ['nullable', Rule::in(['draft', 'published'])],
        ]);

        $validated['title'] = $validated['title'] ?: 'Untitled draft event';
        $validated['waitlist_enabled'] = $request->boolean('waitlist');
        $validated['registration_limit'] = $validated['registration_limit'] ?? 1;
        $validated['status'] = $status;

        return $validated;
    }

    /**
     * Get events as JSON for calendar display.
     */
    public function calendarEvents(Request $request)
    {
        $events = Event::query()
            ->where('status', 'published')
            ->whereNotNull('start_date')
            ->orderBy('start_date')
            ->get();

        $calendarEvents = $events->map(function ($event) {
            $color = match ($event->category) {
                'JH Kids' => '#2563eb',
                'SMART Recovery' => '#059669',
                'Movement Program' => '#d97706',
                'Parent Program' => '#7c3aed',
                'Community Workshop' => '#db2777',
                default => '#2563eb',
            };

            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_date?->format('Y-m-d\TH:i:s'),
                'end' => $event->end_date?->format('Y-m-d\TH:i:s') ?? $event->start_date?->format('Y-m-d\TH:i:s'),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'description' => $event->description,
                    'location' => $event->location,
                    'capacity' => $event->capacity,
                    'category' => $event->category,
                    'status' => $event->status,
                    'visibility' => $event->visibility,
                ],
            ];
        });

        return response()->json($calendarEvents);
    }
}
