@extends('admin.layouts.app')

@section('title', 'Events Management')
@section('breadcrumb', 'Events')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div>
        <div class="page-title">
            <i class="fas fa-calendar-alt" style="margin-right: 10px; color: var(--primary-color);"></i>
            Events Management
        </div>
        <p class="page-subtitle">Create and manage all your events</p>
    </div>
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Create Event
    </a>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('admin.events') }}" class="row mb-4">
    <div class="col-md-3 mb-2">
        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search events...">
    </div>
    <div class="col-md-3 mb-2">
        <select name="category" class="form-control">
            <option value="">All Categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-2">
        <select name="status" class="form-control">
            <option value="">All Status</option>
            <option value="draft" @selected(request('status') === 'draft')>Draft</option>
            <option value="published" @selected(request('status') === 'published')>Published</option>
        </select>
    </div>
    <div class="col-md-3 mb-2">
        <button type="submit" class="btn btn-secondary w-100">
            <i class="fas fa-filter"></i> Filter
        </button>
    </div>
</form>

<!-- Events Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Registrations</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $event)
                        <tr>
                            <td>
                                <strong>{{ $event->title }}</strong>
                                @if ($event->visibility === 'private')
                                    <span class="badge badge-warning" style="margin-left: 6px;">Private</span>
                                @endif
                            </td>
                            <td>{{ $event->category ?? 'Uncategorized' }}</td>
                            <td>{{ $event->start_date?->format('d M Y') }}</td>
                            <td>
                                {{ $event->start_date?->format('h:i A') }}
                                @if ($event->end_date)
                                    - {{ $event->end_date->format('h:i A') }}
                                @endif
                            </td>
                            <td>{{ $event->location ?? 'TBA' }}</td>
                            <td>
                                0{{ $event->capacity ? ' / ' . $event->capacity : '' }}
                                @if ($event->waitlist_enabled)
                                    <span class="badge badge-info" style="margin-left: 6px;">Waitlist</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $event->status === 'published' ? 'badge-success' : 'badge-warning' }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                    <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Delete this event? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px;">
                                <i class="fas fa-inbox" style="font-size: 40px; color: #cbd5e1; margin-bottom: 15px; display: block;"></i>
                                <p style="color: #94a3b8; margin: 0;">No events found. <a href="{{ route('admin.events.create') }}" style="color: var(--primary-color);">Create your first event</a></p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($events->hasPages())
            <div style="margin-top: 20px;">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
