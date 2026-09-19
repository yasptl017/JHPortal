@extends('admin.layouts.app')

@section('title', 'Email Log Details')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-envelope-open-text me-2"></i>Email Log Details</h1>
            <p class="text-muted mb-0">Review the delivery status and message content.</p>
        </div>
        <a href="{{ route('admin.email-logs.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Email Logs
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0">Delivery Details</h5></div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Recipient</dt><dd class="col-sm-8">{{ $emailLog->recipient_email }}</dd>
                        <dt class="col-sm-4">Subject</dt><dd class="col-sm-8">{{ $emailLog->subject }}</dd>
                        <dt class="col-sm-4">Type</dt><dd class="col-sm-8"><span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $emailLog->type)) }}</span></dd>
                        <dt class="col-sm-4">Status</dt><dd class="col-sm-8"><span class="badge bg-{{ $emailLog->status === 'sent' ? 'success' : ($emailLog->status === 'failed' ? 'danger' : 'warning') }}">{{ ucfirst($emailLog->status) }}</span></dd>
                        <dt class="col-sm-4">Created</dt><dd class="col-sm-8">{{ $emailLog->created_at?->format('M d, Y H:i') }}</dd>
                        <dt class="col-sm-4">Sent</dt><dd class="col-sm-8">{{ $emailLog->sent_at?->format('M d, Y H:i') ?? 'Not sent' }}</dd>
                        @if ($emailLog->user)
                            <dt class="col-sm-4">User</dt><dd class="col-sm-8">{{ $emailLog->user->name }}</dd>
                        @endif
                        @if ($emailLog->event)
                            <dt class="col-sm-4">Event</dt><dd class="col-sm-8">{{ $emailLog->event->title }}</dd>
                        @endif
                    </dl>

                    @if ($emailLog->error_message)
                        <div class="alert alert-danger mt-3 mb-0">
                            <strong>Delivery error</strong><br>
                            {{ $emailLog->error_message }}
                        </div>
                    @endif

                    @if ($emailLog->status !== 'sent')
                        <form method="POST" action="{{ route('admin.email-logs.resend', $emailLog) }}" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Resend this email?')">
                                <i class="fas fa-redo me-1"></i>Resend Email
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0">Email Preview</h5></div>
                <div class="card-body">
                    <div class="border rounded p-4 bg-white">{!! $emailLog->body !!}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
