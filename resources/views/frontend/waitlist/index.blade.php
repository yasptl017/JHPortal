@extends('frontend.layouts.app')

@section('title', 'My Waitlist')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>My Waitlist</h1>
        <p>Events you're waiting to join</p>
    </div>
</div>

<section class="section">
    <div class="container">
        @if($waitlistEntries->count() > 0)
            <div class="row g-4">
                @foreach($waitlistEntries as $entry)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <!-- Card Header with Status Badge -->
                            <div class="card-header bg-light border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $entry->event->category }}</h6>
                                        <small class="text-muted">Position #{{ $entry->position ?? $loop->iteration }}</small>
                                    </div>
                                    <span class="badge bg-{{ $entry->status === 'pending' ? 'warning' : ($entry->status === 'notified' ? 'info' : ($entry->status === 'confirmed' ? 'success' : 'secondary')) }}">
                                        {{ ucfirst($entry->status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="card-body">
                                <h5 class="card-title">{{ $entry->event->title }}</h5>
                                
                                <!-- Event Details -->
                                <div class="event-details mb-3">
                                    <div class="detail-item mb-2">
                                        <i class="fas fa-calendar text-primary"></i>
                                        <span>{{ $entry->event->start_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="detail-item mb-2">
                                        <i class="fas fa-clock text-primary"></i>
                                        <span>{{ $entry->event->start_date->format('H:i') }}</span>
                                    </div>
                                    <div class="detail-item mb-2">
                                        <i class="fas fa-map-marker-alt text-primary"></i>
                                        <span>{{ $entry->event->location }}</span>
                                    </div>
                                </div>

                                <!-- Status Information -->
                                <div class="status-info mb-3 p-2 bg-light rounded">
                                    @if($entry->status === 'pending')
                                        <small class="text-muted">
                                            <i class="fas fa-hourglass-half"></i>
                                            Waiting for a spot to become available
                                        </small>
                                    @elseif($entry->status === 'notified')
                                        <small class="text-info">
                                            <i class="fas fa-bell"></i>
                                            A spot is available! You have 7 days to confirm.
                                        </small>
                                        @if($entry->getDaysUntilExpiration() !== null)
                                            <div class="mt-2">
                                                <strong>{{ $entry->getDaysUntilExpiration() }} days remaining</strong>
                                            </div>
                                        @endif
                                    @elseif($entry->status === 'confirmed')
                                        <small class="text-success">
                                            <i class="fas fa-check-circle"></i>
                                            You're registered for this event!
                                        </small>
                                    @elseif($entry->status === 'expired')
                                        <small class="text-danger">
                                            <i class="fas fa-times-circle"></i>
                                            Your notification has expired
                                        </small>
                                    @else
                                        <small class="text-muted">
                                            <i class="fas fa-ban"></i>
                                            You've cancelled this waitlist entry
                                        </small>
                                    @endif
                                </div>

                                <!-- Timestamps -->
                                <div class="timestamps text-muted small mb-3">
                                    <div>Joined: {{ $entry->created_at->format('M d, Y H:i') }}</div>
                                    @if($entry->notified_at)
                                        <div>Notified: {{ $entry->notified_at->format('M d, Y H:i') }}</div>
                                    @endif
                                    @if($entry->confirmed_at)
                                        <div>Confirmed: {{ $entry->confirmed_at->format('M d, Y H:i') }}</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Footer with Actions -->
                            <div class="card-footer bg-light border-top">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('events.show', $entry->event) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>View Event
                                    </a>
                                    
                                    @if($entry->status === 'notified')
                                        <form method="POST" action="{{ route('waitlist.confirm', $entry->event) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success w-100">
                                                <i class="fas fa-check me-1"></i>Confirm Spot
                                            </button>
                                        </form>
                                    @endif

                                    @if($entry->status === 'pending' || $entry->status === 'notified')
                                        <form method="POST" action="{{ route('waitlist.leave', $entry->event) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('Remove from waitlist?')">
                                                <i class="fas fa-times me-1"></i>Leave Waitlist
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($waitlistEntries->hasPages())
                <div class="mt-4">
                    {{ $waitlistEntries->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <i class="fas fa-hourglass-half fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">You're not on any waitlists</h5>
                <p class="text-muted mb-4">When events are full, you can join the waitlist to be notified if a spot becomes available.</p>
                <a href="{{ route('events.index') }}" class="btn btn-primary">
                    <i class="fas fa-calendar me-2"></i>Browse Events
                </a>
            </div>
        @endif
    </div>
</section>

<style>
    .event-details .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .event-details .detail-item i {
        min-width: 20px;
    }

    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    }

    .status-info {
        border-left: 3px solid #0d6efd;
    }

    .status-info.warning {
        border-left-color: #ffc107;
    }

    .status-info.info {
        border-left-color: #0dcaf0;
    }

    .status-info.success {
        border-left-color: #198754;
    }
</style>
@endsection
