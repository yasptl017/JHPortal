@extends('admin.layouts.app')
@section('title', 'Contact Messages')
@section('breadcrumb', 'Messages')

@section('content')
<div class="mb-4">
    <h1 class="page-title">Contact Messages</h1>
    <p class="page-subtitle">View messages submitted through the website contact form</p>
</div>

<div class="card">
    <div class="card-body">
        @if($messages->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No messages yet</h5>
                <p class="text-muted">Messages from the contact form will appear here.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($messages as $msg)
                            <tr style="{{ !$msg->is_read ? 'background:#f0f7ff;' : '' }}">
                                <td>
                                    @if(!$msg->is_read)
                                        <span class="badge badge-info"><i class="fas fa-circle me-1" style="font-size:8px;"></i>New</span>
                                    @else
                                        <span class="badge badge-success">Read</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight:{{ !$msg->is_read ? '700' : '500' }};">{{ $msg->name }}</div>
                                    <div style="font-size:0.8rem;color:#64748b;">{{ $msg->email }}</div>
                                </td>
                                <td style="font-weight:{{ !$msg->is_read ? '600' : '400' }};">{{ Str::limit($msg->subject, 40) }}</td>
                                <td style="font-size:0.85rem;color:#64748b;">{{ $msg->created_at->format('M d, Y g:i A') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.messages.show', $msg) }}" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.messages.destroy', $msg) }}" onsubmit="return confirm('Delete this message?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
