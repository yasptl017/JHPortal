@extends('frontend.layouts.app')
@section('title', 'Home')

@section('content')
<!-- Hero Slider -->
<div class="hero-slider" id="heroSlider">
    @forelse($sliders as $index => $slider)
        <div class="slide {{ $index === 0 ? 'active' : '' }}">
            <img src="{{ asset('storage/' . $slider->image) }}" alt="{{ $slider->title }}">
            <div class="slide-overlay"></div>
            <div class="slide-content">
                @if($slider->title)
                    <h1>{{ $slider->title }}</h1>
                @endif
                @if($slider->subtitle)
                    <p>{{ $slider->subtitle }}</p>
                @endif
                @if($slider->button_text && $slider->button_link)
                    <a href="{{ $slider->button_link }}" class="btn-hero">{{ $slider->button_text }}</a>
                @endif
            </div>
        </div>
    @empty
        <div class="slide active">
            <div class="slide-overlay" style="background: linear-gradient(135deg, rgba(37,99,235,0.9) 0%, rgba(124,58,237,0.9) 100%);"></div>
            <div class="slide-content">
                <h1>Welcome to Jewish House</h1>
                <p>Discover and join our community events, workshops, and programs designed to bring people together.</p>
                <a href="{{ route('events.index') }}" class="btn-hero">Explore Events</a>
            </div>
        </div>
    @endforelse

    @if($sliders->count() > 1)
        <div class="slider-dots">
            @foreach($sliders as $index => $slider)
                <div class="dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></div>
            @endforeach
        </div>
    @endif
</div>

<!-- Features Section -->
<section class="section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Why Join Our Community?</h2>
            <p class="section-subtitle">Connect, grow, and engage with us through diverse programs and events</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon blue"><i class="fas fa-calendar-check"></i></div>
                    <h5>Easy Registration</h5>
                    <p>Browse and register for events with just a few clicks. Get instant confirmation.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon green"><i class="fas fa-users"></i></div>
                    <h5>Community Events</h5>
                    <p>Discover workshops, programs, and gatherings tailored for our community.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon purple"><i class="fas fa-bell"></i></div>
                    <h5>Stay Updated</h5>
                    <p>Receive reminders and notifications so you never miss an important event.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon orange"><i class="fas fa-heart"></i></div>
                    <h5>Give Feedback</h5>
                    <p>Share your experience and help us improve our community programs.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Upcoming Events Section -->
<section class="section section-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h2 class="section-title mb-1">Upcoming Events</h2>
                <p class="section-subtitle mb-0">Don't miss out on our upcoming community gatherings</p>
            </div>
            <a href="{{ route('events.index') }}" class="btn-outline-custom">View All Events <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
        <div class="row g-4">
            @forelse($upcomingEvents as $event)
                <div class="col-lg-4 col-md-6">
                    <div class="event-card">
                        <div class="event-card-img" style="background: linear-gradient(135deg,
                            {{ match($event->category) {
                                'JH Kids' => '#2563eb, #3b82f6',
                                'SMART Recovery' => '#059669, #10b981',
                                'Movement Program' => '#d97706, #f59e0b',
                                'Parent Program' => '#7c3aed, #8b5cf6',
                                'Community Workshop' => '#db2777, #ec4899',
                                default => '#2563eb, #7c3aed'
                            } }});">
                            <i class="fas {{ match($event->category) {
                                'JH Kids' => 'fa-child',
                                'SMART Recovery' => 'fa-hand-holding-heart',
                                'Movement Program' => 'fa-running',
                                'Parent Program' => 'fa-people-arrows',
                                'Community Workshop' => 'fa-chalkboard-teacher',
                                default => 'fa-calendar-alt'
                            } }}"></i>
                            @if($event->category)
                                <span class="event-card-category">{{ $event->category }}</span>
                            @endif
                        </div>
                        <div class="event-card-body">
                            <h5>{{ $event->title }}</h5>
                            <div class="event-card-meta">
                                <div><i class="fas fa-calendar"></i> {{ $event->start_date->format('D, M d, Y') }}</div>
                                <div><i class="fas fa-clock"></i> {{ $event->start_date->format('g:i A') }}</div>
                                @if($event->location)
                                    <div><i class="fas fa-map-marker-alt"></i> {{ $event->location }}</div>
                                @endif
                            </div>
                            <div class="event-card-footer">
                                @php $spots = $event->spotsLeft(); @endphp
                                @if($spots === null)
                                    <span class="spots-badge spots-available">Open</span>
                                @elseif($spots > 10)
                                    <span class="spots-badge spots-available">{{ $spots }} spots left</span>
                                @elseif($spots > 0)
                                    <span class="spots-badge spots-limited">{{ $spots }} spots left</span>
                                @else
                                    <span class="spots-badge spots-full">Full</span>
                                @endif
                                <a href="{{ route('events.show', $event) }}" class="btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No upcoming events at this time</h5>
                    <p class="text-muted">Check back soon for new community events and programs!</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff;">
    <div class="container text-center">
        <h2 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 16px;">Ready to Join Our Community?</h2>
        <p style="font-size: 1.1rem; opacity: 0.9; margin-bottom: 32px; max-width: 600px; margin-left: auto; margin-right: auto;">
            Create your free account to register for events, receive notifications, and stay connected with Jewish House.
        </p>
        @guest
            <a href="{{ route('register') }}" class="btn-hero" style="background:#fff;color:#667eea;padding:14px 36px;border-radius:8px;font-weight:700;text-decoration:none;display:inline-block;">
                Get Started <i class="fas fa-arrow-right ms-2"></i>
            </a>
        @endguest
        @auth
            <a href="{{ route('events.index') }}" class="btn-hero" style="background:#fff;color:#667eea;padding:14px 36px;border-radius:8px;font-weight:700;text-decoration:none;display:inline-block;">
                Browse Events <i class="fas fa-arrow-right ms-2"></i>
            </a>
        @endauth
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Slider Auto-play
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    let currentSlide = 0;

    function showSlide(index) {
        slides.forEach(s => s.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));
        slides[index]?.classList.add('active');
        dots[index]?.classList.add('active');
        currentSlide = index;
    }

    if (slides.length > 1) {
        setInterval(() => {
            showSlide((currentSlide + 1) % slides.length);
        }, 5000);

        dots.forEach(dot => {
            dot.addEventListener('click', () => showSlide(parseInt(dot.dataset.index)));
        });
    }
</script>
@endsection
