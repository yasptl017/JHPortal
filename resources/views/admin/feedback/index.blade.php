@extends('admin.layouts.app')

@section('title', 'Event Feedback')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="fas fa-star me-2"></i>Event Feedback</h1>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Select Event</label>
                    <select name="event_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Choose an event --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" @if($selectedEvent?->id == $event->id) selected @endif>
                                {{ $event->title }} ({{ $event->event_date->format('M d, Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if($selectedEvent)
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Total Responses</h6>
                        <h3 class="mb-0">{{ $stats['total_responses'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary bg-opacity-10">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Average Rating</h6>
                        <h3 class="mb-0 text-primary">{{ $stats['average_rating'] }}/5</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success bg-opacity-10">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Would Attend Again</h6>
                        <h3 class="mb-0 text-success">{{ $stats['would_attend_again'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info bg-opacity-10">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Response Rate</h6>
                        <h3 class="mb-0 text-info">
                            @php
                                $rate = $selectedEvent->registrations()->count() > 0 
                                    ? round(($stats['total_responses'] / $selectedEvent->registrations()->count()) * 100, 1)
                                    : 0;
                            @endphp
                            {{ $rate }}%
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Rating Distribution</h5>
                    </div>
                    <div class="card-body">
                        @foreach([5, 4, 3, 2, 1] as $rating)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>{{ $rating }} <i class="fas fa-star text-warning"></i></span>
                                    <span class="badge bg-primary">{{ $stats['rating_distribution'][$rating] }}</span>
                                </div>
                                <div class="progress" style="height: 20px;">
                                    @php
                                        $percentage = $stats['total_responses'] > 0 
                                            ? ($stats['rating_distribution'][$rating] / $stats['total_responses']) * 100
                                            : 0;
                                    @endphp
                                    <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Event Details</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Event:</strong> {{ $selectedEvent->title }}</p>
                        <p><strong>Date:</strong> {{ $selectedEvent->event_date->format('M d, Y H:i') }}</p>
                        <p><strong>Location:</strong> {{ $selectedEvent->location }}</p>
                        <p><strong>Category:</strong> <span class="badge bg-secondary">{{ $selectedEvent->category }}</span></p>
                        <p><strong>Total Registered:</strong> {{ $selectedEvent->registrations()->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Feedback Comments</h5>
            </div>
            <div class="card-body">
                @if($feedback->count() > 0)
                    <div class="space-y-3">
                        @foreach($feedback as $item)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1">{{ $item->user->name }}</h6>
                                        <small class="text-muted">{{ $item->created_at->format('M d, Y H:i') }}</small>
                                    </div>
                                    <div>
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $item->rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-muted"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                @if($item->comments)
                                    <p class="mb-2">{{ $item->comments }}</p>
                                @endif
                                <small class="text-muted">
                                    @if($item->would_attend_again)
                                        <span class="badge bg-success">Would attend again</span>
                                    @else
                                        <span class="badge bg-danger">Won't attend again</span>
                                    @endif
                                </small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-4">No feedback received yet</p>
                @endif
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>Select an event to view feedback.
        </div>
    @endif
</div>
@endsection
