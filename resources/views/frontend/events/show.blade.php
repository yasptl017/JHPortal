@extends('frontend.layouts.app')
@section('title', $event->title)

@section('content')
<div class="page-header">
    <div class="container">
        <h1>{{ $event->title }}</h1>
        @if($event->category)
            <span style="display:inline-block;background:rgba(255,255,255,0.2);padding:4px 16px;border-radius:20px;font-size:0.9rem;margin-top:8px;">{{ $event->category }}</span>
        @endif
        <ul class="breadcrumb-custom">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="separator">/</li>
            <li><a href="{{ route('events.index') }}">Events</a></li>
            <li class="separator">/</li>
            <li class="current">{{ Str::limit($event->title, 30) }}</li>
        </ul>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="row g-4">
            <!-- Event Details -->
            <div class="col-lg-8">
                <div class="card" style="border-radius:16px;border:1px solid var(--border);">
                    <!-- Event Banner -->
                    <div style="height:250px;background:linear-gradient(135deg,
                        {{ match($event->category) {
                            'JH Kids' => '#2563eb, #3b82f6',
                            'SMART Recovery' => '#059669, #10b981',
                            'Movement Program' => '#d97706, #f59e0b',
                            'Parent Program' => '#7c3aed, #8b5cf6',
                            'Community Workshop' => '#db2777, #ec4899',
                            default => '#2563eb, #7c3aed'
                        } }});border-radius:16px 16px 0 0;display:flex;align-items:center;justify-content:center;">
                        <i class="fas {{ match($event->category) {
                            'JH Kids' => 'fa-child',
                            'SMART Recovery' => 'fa-hand-holding-heart',
                            'Movement Program' => 'fa-running',
                            'Parent Program' => 'fa-people-arrows',
                            'Community Workshop' => 'fa-chalkboard-teacher',
                            default => 'fa-calendar-alt'
                        } }}" style="font-size:5rem;color:rgba(255,255,255,0.3);"></i>
                    </div>

                    <div class="card-body" style="padding:32px;">
                        <h2 style="font-weight:800;color:var(--dark);margin-bottom:24px;">About This Event</h2>

                        @if($event->description)
                            <div style="color:var(--gray);line-height:1.8;font-size:1rem;margin-bottom:32px;">
                                {!! nl2br(e($event->description)) !!}
                            </div>
                        @else
                            <p style="color:var(--gray);">No description provided for this event.</p>
                        @endif

                        @if($event->venue_notes)
                            <div style="background:var(--light);border-radius:12px;padding:20px;margin-top:24px;">
                                <h5 style="font-weight:700;margin-bottom:8px;"><i class="fas fa-info-circle me-2" style="color:var(--primary);"></i>Venue Notes</h5>
                                <p style="margin:0;color:var(--gray);">{{ $event->venue_notes }}</p>
                            </div>
                        @endif

                        @if($event->meeting_link)
                            <div style="background:#dbeafe;border-radius:12px;padding:20px;margin-top:16px;">
                                <h5 style="font-weight:700;margin-bottom:8px;"><i class="fas fa-video me-2" style="color:var(--primary);"></i>Online Meeting</h5>
                                <a href="{{ $event->meeting_link }}" target="_blank" style="color:var(--primary);word-break:break-all;">{{ $event->meeting_link }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Event Info Card -->
                <div class="card mb-4" style="border-radius:16px;border:1px solid var(--border);">
                    <div class="card-body" style="padding:24px;">
                        <h5 style="font-weight:700;margin-bottom:20px;">Event Details</h5>

                        <div style="display:flex;flex-direction:column;gap:16px;">
                            <div style="display:flex;align-items:flex-start;gap:12px;">
                                <div style="width:40px;height:40px;border-radius:10px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-calendar" style="color:var(--primary);"></i>
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:0.85rem;color:var(--gray);">Date</div>
                                    <div style="font-weight:600;color:var(--dark);">{{ $event->start_date->format('l, F d, Y') }}</div>
                                </div>
                            </div>

                            <div style="display:flex;align-items:flex-start;gap:12px;">
                                <div style="width:40px;height:40px;border-radius:10px;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-clock" style="color:var(--success);"></i>
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:0.85rem;color:var(--gray);">Time</div>
                                    <div style="font-weight:600;color:var(--dark);">
                                        {{ $event->start_date->format('g:i A') }}
                                        @if($event->end_date) - {{ $event->end_date->format('g:i A') }} @endif
                                    </div>
                                </div>
                            </div>

                            @if($event->location)
                                <div style="display:flex;align-items:flex-start;gap:12px;">
                                    <div style="width:40px;height:40px;border-radius:10px;background:#ede9fe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="fas fa-map-marker-alt" style="color:var(--secondary);"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight:600;font-size:0.85rem;color:var(--gray);">Location</div>
                                        <div style="font-weight:600;color:var(--dark);">{{ $event->location }}</div>
                                    </div>
                                </div>
                            @endif

                            @if($event->capacity)
                                <div style="display:flex;align-items:flex-start;gap:12px;">
                                    <div style="width:40px;height:40px;border-radius:10px;background:#fed7aa;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="fas fa-users" style="color:#ea580c;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight:600;font-size:0.85rem;color:var(--gray);">Capacity</div>
                                        <div style="font-weight:600;color:var(--dark);">
                                            @php $spots = $event->spotsLeft(); @endphp
                                            @if($spots > 0)
                                                {{ $spots }} of {{ $event->capacity }} spots available
                                            @else
                                                Event is full
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Registration Card -->
                <div class="card" style="border-radius:16px;border:1px solid var(--border);">
                    <div class="card-body" style="padding:24px;">
                        <h5 style="font-weight:700;margin-bottom:16px;">Registration</h5>

                        @auth
                            @if($isRegistered)
                                @if($registration->status === 'cancelled')
                                    <div style="background:#fee2e2;border-radius:10px;padding:16px;margin-bottom:16px;">
                                        <p style="margin:0;color:#991b1b;font-weight:600;"><i class="fas fa-times-circle me-2"></i>Registration Cancelled</p>
                                    </div>
                                    <form method="POST" action="{{ route('events.register', $event) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-primary-custom w-100">Register Again</button>
                                    </form>
                                @elseif($registration->status === 'waitlisted')
                                    <div style="background:#fef3c7;border-radius:10px;padding:16px;margin-bottom:16px;">
                                        <p style="margin:0;color:#92400e;font-weight:600;"><i class="fas fa-clock me-2"></i>You're on the waitlist</p>
                                        <p style="margin:8px 0 0;font-size:0.85rem;color:#92400e;">We'll notify you when a spot opens up.</p>
                                    </div>
                                    <form method="POST" action="{{ route('events.cancel', $event) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-custom w-100" onclick="return confirm('Are you sure you want to cancel?')">Cancel Waitlist</button>
                                    </form>
                                @else
                                    <div style="background:#dcfce7;border-radius:10px;padding:16px;margin-bottom:16px;">
                                        <p style="margin:0;color:#166534;font-weight:600;"><i class="fas fa-check-circle me-2"></i>You're registered!</p>
                                        <p style="margin:8px 0 0;font-size:0.85rem;color:#166534;">We look forward to seeing you there.</p>
                                    </div>
                                    <form method="POST" action="{{ route('events.cancel', $event) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-custom w-100" style="color:var(--danger);border-color:var(--danger);" onclick="return confirm('Are you sure you want to cancel your registration?')">Cancel Registration</button>
                                    </form>
                                @endif
                            @else
                                @php $spots = $event->spotsLeft(); @endphp
                                @if($spots === null || $spots > 0)
                                    <form method="POST" action="{{ route('events.register', $event) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-primary-custom w-100" style="padding:14px;">
                                            <i class="fas fa-ticket-alt me-2"></i>Register Now
                                        </button>
                                    </form>
                                @elseif($event->waitlist_enabled)
                                    <p style="color:var(--gray);font-size:0.9rem;margin-bottom:12px;">This event is full, but you can join the waitlist.</p>
                                    <form method="POST" action="{{ route('events.register', $event) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-custom w-100">Join Waitlist</button>
                                    </form>
                                @else
                                    <div style="background:#fee2e2;border-radius:10px;padding:16px;">
                                        <p style="margin:0;color:#991b1b;font-weight:600;"><i class="fas fa-ban me-2"></i>Event is full</p>
                                    </div>
                                @endif
                            @endif
                        @else
                            <p style="color:var(--gray);font-size:0.9rem;margin-bottom:16px;">Please log in or create an account to register for this event.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary-custom w-100 mb-2">Login to Register</a>
                            <a href="{{ route('register') }}" class="btn btn-outline-custom w-100">Create Account</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
