@extends('admin.layouts.app')

@section('title', 'Email Logs')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="fas fa-envelope me-2"></i>Email Logs</h1>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Emails</h6>
                    <h3 class="mb-0">{{ $stats['total_emails'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success bg-opacity-10">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Sent</h6>
                    <h3 class="mb-0 text-success">{{ $stats['total_sent'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning bg-opacity-10">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Pending</h6>
                    <h3 class="mb-0 text-warning">{{ $stats['total_pending'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger bg-opacity-10">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Failed</h6>
                    <h3 class="mb-0 text-danger">{{ $stats['total_failed'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search Email</label>
                    <input type="text" name="search" class="form-control" placeholder="Search by email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="registration_confirmation" @if(request('type') === 'registration_confirmation') selected @endif>Registration Confirmation</option>
                        <option value="event_reminder" @if(request('type') === 'event_reminder') selected @endif>Event Reminder</option>
                        <option value="feedback_request" @if(request('type') === 'feedback_request') selected @endif>Feedback Request</option>
                        <option value="waitlist_notification" @if(request('type') === 'waitlist_notification') selected @endif>Waitlist Notification</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="sent" @if(request('status') === 'sent') selected @endif>Sent</option>
                        <option value="pending" @if(request('status') === 'pending') selected @endif>Pending</option>
                        <option value="failed" @if(request('status') === 'failed') selected @endif>Failed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Email History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Recipient</th>
                            <th>Subject</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Sent At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emailLogs as $log)
                            <tr>
                                <td>{{ $log->recipient_email }}</td>
                                <td>{{ Str::limit($log->subject, 40) }}</td>
                                <td>
                                    <span class="badge bg-info">{{ str_replace('_', ' ', ucfirst($log->type)) }}</span>
                                </td>
                                <td>
                                    @if($log->status === 'sent')
                                        <span class="badge bg-success">Sent</span>
                                    @elseif($log->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Failed</span>
                                    @endif
                                </td>
                                <td>{{ $log->sent_at?->format('M d, Y H:i') ?? '--' }}</td>
                                <td>
                                    <a href="{{ route('admin.email-logs.show', $log) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($log->status !== 'sent')
                                        <form method="POST" action="{{ route('admin.email-logs.resend', $log) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Resend this email?')">
                                                <i class="fas fa-redo"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No email logs found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $emailLogs->links() }}
    </div>
</div>
@endsection
