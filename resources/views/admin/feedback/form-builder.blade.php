@extends('admin.layouts.app')

@section('title', 'Feedback Form Builder - ' . $event->title)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="fas fa-wpforms me-2"></i>Feedback Form Builder</h1>
            <p class="text-muted mt-2">{{ $event->title }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.feedback-form.store', $event) }}">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-bold">Form Title</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                           value="{{ old('title', $feedbackForm?->title ?? 'Event Feedback') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Form Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                              rows="3" placeholder="Optional description for the feedback form">{{ old('description', $feedbackForm?->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="card bg-light mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Standard Fields</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input type="checkbox" id="include_rating" name="include_rating" value="1" 
                                   class="form-check-input" {{ old('include_rating', $feedbackForm?->include_rating ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="include_rating">
                                <strong>Star Rating (1-5)</strong>
                                <small class="d-block text-muted">Allow users to rate the event</small>
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" id="include_comments" name="include_comments" value="1" 
                                   class="form-check-input" {{ old('include_comments', $feedbackForm?->include_comments ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="include_comments">
                                <strong>Comments/Feedback</strong>
                                <small class="d-block text-muted">Allow users to provide detailed comments</small>
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" id="include_attendance" name="include_attendance" value="1" 
                                   class="form-check-input" {{ old('include_attendance', $feedbackForm?->include_attendance ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="include_attendance">
                                <strong>Attendance Confirmation</strong>
                                <small class="d-block text-muted">Confirm if user attended the event</small>
                            </label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" id="include_would_attend_again" name="include_would_attend_again" value="1" 
                                   class="form-check-input" {{ old('include_would_attend_again', $feedbackForm?->include_would_attend_again ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="include_would_attend_again">
                                <strong>Would Attend Again</strong>
                                <small class="d-block text-muted">Ask if they would attend similar events</small>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('admin.feedback.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Feedback Form
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
