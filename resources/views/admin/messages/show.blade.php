@extends('admin.layouts.app')
@section('title', 'View Message')
@section('breadcrumb', 'View Message')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left me-2"></i>Back to Messages</a>
    <h1 class="page-title">Message from {{ $message->name }}</h1>
    <p class="page-subtitle">Received {{ $message->created_at->format('M d, Y \a\t g:i A') }}</p>
</div>

<div class="card" style="max-width:800px;">
    <div class="card-body" style="padding:28px;">
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label" style="font-weight:600;color:#64748b;font-size:0.85rem;">From</label>
                <div style="font-weight:600;color:#1e293b;">{{ $message->name }}</div>
            </div>
            <div class="col-md-6">
                <label class="form-label" style="font-weight:600;color:#64748b;font-size:0.85rem;">Email</label>
                <div><a href="mailto:{{ $message->email }}" style="color:#2563eb;">{{ $message->email }}</a></div>
            </div>
            @if($message->phone)
                <div class="col-md-6">
                    <label class="form-label" style="font-weight:600;color:#64748b;font-size:0.85rem;">Phone</label>
                    <div>{{ $message->phone }}</div>
                </div>
            @endif
            <div class="col-md-6">
                <label class="form-label" style="font-weight:600;color:#64748b;font-size:0.85rem;">Subject</label>
                <div style="font-weight:600;">{{ $message->subject }}</div>
            </div>
        </div>

        <hr>

        <div class="mt-3">
            <label class="form-label" style="font-weight:600;color:#64748b;font-size:0.85rem;">Message</label>
            <div style="background:#f8fafc;border-radius:8px;padding:20px;line-height:1.8;color:#334155;">
                {!! nl2br(e($message->message)) !!}
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}" class="btn btn-primary">
                <i class="fas fa-reply me-2"></i>Reply via Email
            </a>
            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Delete this message?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-2"></i>Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection
