 @extends('admin.layouts.app')

@section('title', 'Waitlist Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="fas fa-users me-2"></i>Waitlist Management</h1>
        </div>
        <div class="col-md-4 text-end">
            @if($selectedEvent)
                <a href="{{ route('admin.waitlist.export', ['event_id' => $selectedEvent->id]) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-download me-1"></i>Export CSV
                </a>
            @endif
        </div>
    </div>

    <!-- Event Selection Card -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Select Event</label>
                    <select name="event_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Choose an event --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" @if($selectedEvent?->id == $event->id) selected @endif>
                                {{ $event->title }} ({{ $event->start_date->format('M d, Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if($selectedEvent)
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Total</h6>
                        <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Pending</h6>
                        <h3 class="mb-0 text-warning">{{ $stats['pending'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Notified</h6>
                        <h3 class="mb-0 text-info">{{ $stats['notified'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Confirmed</h6>
                        <h3 class="mb-0 text-success">{{ $stats['confirmed'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Active</h6>
                        <h3 class="mb-0 text-primary">{{ $stats['active'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Expired</h6>
                        <h3 class="mb-0 text-secondary">{{ $stats['expired'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Bulk Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-auto">
                        <form method="POST" action="{{ route('admin.waitlist.promote', ['event_id' => $selectedEvent->id]) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $selectedEvent->id }}">
                            <input type="number" name="count" value="1" min="1" max="50" class="form-control form-control-sm" style="width: 80px;" placeholder="Count">
                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Promote selected members?')">
                                <i class="fas fa-arrow-up me-1"></i>Promote
                            </button>
                        </form>
                    </div>
                    <div class="col-auto">
                        <form method="POST" action="{{ route('admin.waitlist.bulkNotify') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $selectedEvent->id }}">
                            <button type="submit" class="btn btn-sm btn-info" onclick="return confirm('Notify all pending members?')">
                                <i class="fas fa-envelope me-1"></i>Notify All
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Waitlist Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Waitlist for {{ $selectedEvent->title }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 60px;">Position</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined</th>
                                <th>Status</th>
                                <th>Notified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($waitlist as $entry)
                                <tr>
                                    <td><strong>{{ $entry->position ?? $loop->iteration }}</strong></td>
                                    <td><strong>{{ $entry->user->name }}</strong></td>
                                    <td>{{ $entry->user->email }}</td>
                                    <td>{{ $entry->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if($entry->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($entry->status === 'notified')
                                            <span class="badge bg-info">Notified</span>
                                        @elseif($entry->status === 'confirmed')
                                            <span class="badge bg-success">Confirmed</span>
                                        @elseif($entry->status === 'expired')
                                            <span class="badge bg-secondary">Expired</span>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($entry->notified_at)
                                            {{ $entry->notified_at->format('M d, Y H:i') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($entry->status === 'pending')
                                            <form method="POST" action="{{ route('admin.waitlist.notify', $entry) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-info" onclick="return confirm('Send notification email?')">
                                                    <i class="fas fa-envelope"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.waitlist.confirm', $entry) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Confirm this user?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @elseif($entry->status === 'notified')
                                            <form method="POST" action="{{ route('admin.waitlist.confirm', $entry) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Confirm this user?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.waitlist.destroy', $entry) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove from waitlist?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        No users on waitlist for this event
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($waitlist->hasPages())
                    <div class="mt-3">
                        {{ $waitlist->links() }}
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>Select an event to view waitlist members.
        </div>
    @endif
</div>
@endsection
