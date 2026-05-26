@extends('frontend.layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>My Profile</h1>
        <p>Manage your account and view your event registrations</p>
        <ul class="breadcrumb-custom">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="separator">/</li>
            <li class="current">Profile</li>
        </ul>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="row g-4">
            <!-- Profile Card -->
            <div class="col-lg-4">
                <div class="card" style="border-radius:16px;border:1px solid var(--border);">
                    <div class="card-body text-center" style="padding:32px;">
                        <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:2rem;color:#fff;font-weight:700;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h4 style="font-weight:700;color:var(--dark);">{{ $user->name }}</h4>
                        <p style="color:var(--gray);font-size:0.9rem;">{{ $user->email }}</p>
                        @if($user->phone)
                            <p style="color:var(--gray);font-size:0.9rem;"><i class="fas fa-phone me-1"></i>{{ $user->phone }}</p>
                        @endif
                        <p style="color:var(--gray);font-size:0.8rem;">Member since {{ $user->created_at->format('M Y') }}</p>
                    </div>
                </div>

                <!-- Update Profile -->
                <div class="card mt-4" style="border-radius:16px;border:1px solid var(--border);">
                    <div class="card-body" style="padding:24px;">
                        <h5 style="font-weight:700;color:var(--dark);margin-bottom:16px;">Update Profile</h5>

                        @if($errors->any())
                            <div class="alert alert-danger alert-custom" style="font-size:0.85rem;">
                                <i class="fas fa-exclamation-circle"></i>
                                <div>
                                    @foreach($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required style="border-radius:8px;">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:0.85rem;">Phone</label>
                                <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" style="border-radius:8px;">
                            </div>
                            <button type="submit" class="btn btn-primary-custom w-100">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Registrations -->
            <div class="col-lg-8">
                <div class="card" style="border-radius:16px;border:1px solid var(--border);">
                    <div class="card-body" style="padding:24px;">
                        <h5 style="font-weight:700;color:var(--dark);margin-bottom:20px;">
                            <i class="fas fa-ticket-alt me-2" style="color:var(--primary);"></i>My Event Registrations
                        </h5>

                        @forelse($registrations as $reg)
                            <div style="border:1px solid var(--border);border-radius:12px;padding:20px;margin-bottom:12px;transition:all 0.2s;">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                    <div>
                                        <h6 style="font-weight:700;color:var(--dark);margin-bottom:6px;">
                                            <a href="{{ route('events.show', $reg->event) }}" style="color:var(--dark);text-decoration:none;">{{ $reg->event->title }}</a>
                                        </h6>
                                        <div style="display:flex;gap:16px;flex-wrap:wrap;font-size:0.85rem;color:var(--gray);">
                                            @if($reg->event->start_date)
                                                <span><i class="fas fa-calendar me-1"></i>{{ $reg->event->start_date->format('M d, Y') }}</span>
                                                <span><i class="fas fa-clock me-1"></i>{{ $reg->event->start_date->format('g:i A') }}</span>
                                            @endif
                                            @if($reg->event->location)
                                                <span><i class="fas fa-map-marker-alt me-1"></i>{{ $reg->event->location }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <span style="padding:4px 12px;border-radius:20px;font-size:0.8rem;font-weight:600;
                                        {{ match($reg->status) {
                                            'registered' => 'background:#dcfce7;color:#166534;',
                                            'waitlisted' => 'background:#fef3c7;color:#92400e;',
                                            'attended' => 'background:#dbeafe;color:#1e40af;',
                                            'cancelled' => 'background:#fee2e2;color:#991b1b;',
                                            default => 'background:#f1f5f9;color:#64748b;'
                                        } }}">
                                        {{ ucfirst($reg->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">No event registrations yet</h6>
                                <p class="text-muted" style="font-size:0.9rem;">Browse our events and register to get started!</p>
                                <a href="{{ route('events.index') }}" class="btn btn-primary-custom mt-2">Browse Events</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
