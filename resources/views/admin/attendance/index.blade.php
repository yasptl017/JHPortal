@extends('admin.layouts.app')

@section('title', 'Attendance Tracking')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="fas fa-clipboard-check me-2"></i>Attendance Tracking</h1>
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
                @if($selectedEvent)
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <a href="{{ route('admin.attendance.report', $selectedEvent) }}" class="btn btn-info w-100">
                            <i class="fas fa-chart-bar me-2"></i>View Report
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    @if($selectedEvent)
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Total Registered</h6>
                        <h3 class="mb-0">{{ $selectedEvent->registrations()->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success bg-opacity-10">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Present</h6>
                        <h3 class="mb-0 text-success">{{ $attendance->where('status', 'present')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning bg-opacity-10">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Late</h6>
                        <h3 class="mb-0 text-warning">{{ $attendance->where('status', 'late')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger bg-opacity-10">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">Absent</h6>
                        <h3 class="mb-0 text-danger">{{ $attendance->where('status', 'absent')->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Mark Attendance</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Attendee Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Checked In</th>
                                <th>Notes</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($selectedEvent->registrations()->with('user')->get() as $registration)
                                @php
                                    $att = $attendance->firstWhere('user_id', $registration->user_id);
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $registration->user->name }}</strong>
                                    </td>
                                    <td>{{ $registration->user->email }}</td>
                                    <td>
                                        @if($att)
                                            @if($att->status === 'present')
                                                <span class="badge bg-success">Present</span>
                                            @elseif($att->status === 'late')
                                                <span class="badge bg-warning">Late</span>
                                            @else
                                                <span class="badge bg-danger">Absent</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Not Marked</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($att?->checked_in_at)
                                            {{ $att->checked_in_at->format('H:i') }}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>{{ $att?->notes ?? '--' }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.attendance.mark', $selectedEvent) }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $registration->user_id }}">
                                            <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                                <option value="">Mark as...</option>
                                                <option value="present">Present</option>
                                                <option value="late">Late</option>
                                                <option value="absent">Absent</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No registrations for this event
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>Select an event to view and mark attendance.
        </div>
    @endif
</div>
@endsection
