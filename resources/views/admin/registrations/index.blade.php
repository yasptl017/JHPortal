@extends('admin.layouts.app')
@section('title', 'Registrations')
@section('breadcrumb', 'Registrations')

@section('content')
<div class="mb-4">
    <h1 class="page-title">Event Registrations</h1>
    <p class="page-subtitle">View and manage event registrations from community members</p>
</div>

<div class="card">
    <div class="card-body">
        @if($registrations->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-user-check fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No registrations yet</h5>
                <p class="text-muted">Registrations will appear here when users sign up for events.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Event</th>
                            <th>Status</th>
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $reg)
                            <tr>
                                <td>
                                    <div style="font-weight:600;">{{ $reg->user->name ?? 'Deleted User' }}</div>
                                    <div style="font-size:0.8rem;color:#64748b;">{{ $reg->user->email ?? '' }}</div>
                                </td>
                                <td>
                                    <div style="font-weight:600;">{{ $reg->event->title ?? 'Deleted Event' }}</div>
                                    @if($reg->event?->start_date)
                                        <div style="font-size:0.8rem;color:#64748b;">{{ $reg->event->start_date->format('M d, Y') }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ match($reg->status) {
                                        'registered' => 'badge-success',
                                        'waitlisted' => 'badge-warning',
                                        'attended' => 'badge-info',
                                        'cancelled' => 'badge-danger',
                                        default => 'bg-secondary'
                                    } }}">{{ ucfirst($reg->status) }}</span>
                                </td>
                                <td style="font-size:0.85rem;color:#64748b;">{{ $reg->created_at->format('M d, Y g:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
