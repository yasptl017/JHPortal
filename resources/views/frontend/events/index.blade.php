@extends('frontend.layouts.app')
@section('title', 'Events')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Community Events</h1>
        <p>Discover and join upcoming events, workshops, and programs</p>
        <ul class="breadcrumb-custom">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="separator">/</li>
            <li class="current">Events</li>
        </ul>
    </div>
</div>

<section class="section">
    <div class="container">
        <!-- Filters -->
        <div class="card mb-4" style="border-radius:12px;border:1px solid var(--border);">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('events.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold" style="font-size:0.85rem;">Search Events</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search by title, location..." value="{{ request('search') }}" style="border-radius:0 8px 8px 0;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="font-size:0.85rem;">Category</label>
                        <select name="category" class="form-select" style="border-radius:8px;">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom flex-fill"><i class="fas fa-filter me-1"></i> Filter</button>
                        <a href="{{ route('events.index') }}" class="btn btn-outline-custom">Clear</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="row g-4">
            @forelse($events as $event)
                <div class="col-lg-4 col-md-6">
                    <div class="event-card">
                        <div class="event-card-img" style="background: linear-gradient(135deg,
                            {{ match($event->category) {
                                'JH Kids' => '#2563eb, #3b82f6',
                                'SMART Recovery' => '#059669, #10b981',
                                'Movement Program' => '#d97706, #f59e0b',
                                'Parent Program' => '#7c3aed, #8b5cf6',
                                'Community Workshop' => '#db2777, #ec4899',
                                default => '#2563eb, #7c3aed'
                            } }});">
                            <i class="fas {{ match($event->category) {
                                'JH Kids' => 'fa-child',
                                'SMART Recovery' => 'fa-hand-holding-heart',
                                'Movement Program' => 'fa-running',
                                'Parent Program' => 'fa-people-arrows',
                                'Community Workshop' => 'fa-chalkboard-teacher',
                                default => 'fa-calendar-alt'
                            } }}"></i>
                            @if($event->category)
                                <span class="event-card-category">{{ $event->category }}</span>
                            @endif
                        </div>
                        <div class="event-card-body">
                            <h5>{{ $event->title }}</h5>
                            @if($event->description)
                                <p style="font-size:0.9rem;color:var(--gray);margin-bottom:12px;">{{ Str::limit(strip_tags($event->description), 100) }}</p>
                            @endif
                            <div class="event-card-meta">
                                <div><i class="fas fa-calendar"></i> {{ $event->start_date->format('D, M d, Y') }}</div>
                                <div><i class="fas fa-clock"></i> {{ $event->start_date->format('g:i A') }}@if($event->end_date) - {{ $event->end_date->format('g:i A') }}@endif</div>
                                @if($event->location)
                                    <div><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</div>
                                @endif
                            </div>
                            <div class="event-card-footer">
                                @php $spots = $event->spotsLeft(); @endphp
                                @if($spots === null)
                                    <span class="spots-badge spots-available">Open</span>
                                @elseif($spots > 10)
                                    <span class="spots-badge spots-available">{{ $spots }} spots</span>
                                @elseif($spots > 0)
                                    <span class="spots-badge spots-limited">{{ $spots }} spots left</span>
                                @else
                                    <span class="spots-badge spots-full">Full</span>
                                @endif
                                <a href="{{ route('events.show', $event) }}" class="btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No events found</h5>
                    <p class="text-muted">Try adjusting your filters or check back later for new events.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($events->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
