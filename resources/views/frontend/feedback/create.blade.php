@extends('frontend.layouts.app')

@section('title', 'Event Feedback - ' . $event->title)

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Share Your Feedback</h1>
        <p>Help us improve by sharing your experience at {{ $event->title }}</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <h4 class="mb-2">{{ $event->title }}</h4>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar me-2"></i>{{ $event->start_date?->format('M d, Y H:i') ?? 'TBD' }}
                                <i class="fas fa-map-marker-alt ms-3 me-2"></i>{{ $event->location }}
                            </p>
                        </div>

                        @if($feedbackForm)
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                {{ $feedbackForm->description ?? 'Please share your feedback about this event.' }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('feedback.store', $event) }}">
                            @csrf

                            @if($feedbackForm?->include_rating ?? true)
                            <div class="mb-4">
                                <label class="form-label fw-bold">How would you rate this event?</label>
                                <div class="rating-input">
                                    @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" id="rating{{ $i }}" name="rating" value="{{ $i }}" class="d-none" required>
                                        <label for="rating{{ $i }}" class="rating-label" style="font-size: 2rem; cursor: pointer; color: #ddd; transition: color 0.2s;">
                                            <i class="fas fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                                @error('rating')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif

                            @if($feedbackForm?->include_comments ?? true)
                            <div class="mb-4">
                                <label class="form-label fw-bold">Additional Comments</label>
                                <textarea name="comments" class="form-control @error('comments') is-invalid @enderror" rows="5" placeholder="Share your thoughts about the event..."></textarea>
                                @error('comments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif

                            @if($feedbackForm?->include_attendance ?? true)
                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="checkbox" id="attended" name="attended" value="1" class="form-check-input" checked>
                                    <label class="form-check-label" for="attended">
                                        I attended this event
                                    </label>
                                </div>
                            </div>
                            @endif

                            @if($feedbackForm?->include_would_attend_again ?? true)
                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="checkbox" id="would_attend" name="would_attend_again" value="1" class="form-check-input" checked>
                                    <label class="form-check-label" for="would_attend">
                                        I would attend similar events in the future
                                    </label>
                                </div>
                            </div>
                            @endif

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Feedback
                                </button>
                                <a href="{{ route('events.show', $event) }}" class="btn btn-outline-secondary">
                                    Back to Event
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .rating-label {
        display: inline-block;
        margin: 0 5px;
    }

    input[type="radio"]:checked + .rating-label,
    .rating-label:hover,
    .rating-label:hover ~ .rating-label {
        color: #ffc107 !important;
    }
</style>
@endsection
