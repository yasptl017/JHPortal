@extends('admin.layouts.app')

@php
    $isEditing = filled($event);
    $field = fn ($name, $default = null) => old($name, $event->{$name} ?? $default);
    $dateField = fn ($name) => old($name, optional($event?->{$name})->format('Y-m-d\TH:i'));
@endphp

@section('title', $isEditing ? 'Edit Event' : 'Create Event')
@section('breadcrumb', $isEditing ? 'Events / Edit' : 'Events / Create')

@section('content')
<style>
    .event-form-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .event-tabs {
        border-bottom: 1px solid var(--light-border);
        gap: 6px;
    }

    .event-tabs .nav-link {
        border: 0;
        border-radius: 8px 8px 0 0;
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
        padding: 14px 16px;
    }

    .event-tabs .nav-link.active {
        background: #eff6ff;
        color: var(--primary-color);
        border-bottom: 3px solid var(--primary-color);
    }

    .event-section-title {
        color: #1e293b;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .event-section-subtitle {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 22px;
    }

    .form-hint {
        color: #64748b;
        font-size: 12px;
        margin-top: 6px;
    }

    .tab-actions {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--light-border);
    }

    @media (max-width: 768px) {
        .event-form-header {
            flex-direction: column;
        }

        .event-tabs .nav-link {
            width: 100%;
            text-align: left;
            border-radius: 8px;
        }

        .tab-actions {
            flex-direction: column-reverse;
        }
    }
</style>

<div class="event-form-header">
    <div>
        <div class="page-title">
            <i class="fas fa-calendar-plus" style="margin-right: 10px; color: var(--primary-color);"></i>
            {{ $isEditing ? 'Edit Event' : 'Create Event' }}
        </div>
        <p class="page-subtitle">Set up event details, capacity, waitlist rules, and automated messages.</p>
    </div>
    <a href="{{ route('admin.events') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Events
    </a>
</div>

@if ($errors->any())
    <div class="alert-box alert-danger fade-in">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            <div style="font-weight: 700; margin-bottom: 6px;">Please review the highlighted fields and try again.</div>
            <ul style="margin: 0; padding-left: 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<form method="POST" action="{{ $isEditing ? route('admin.events.update', $event) : route('admin.events.store') }}" id="createEventForm">
    @csrf
    @if ($isEditing)
        @method('PUT')
    @endif
    <input type="hidden" id="statusField" name="status" value="draft">

    <div class="card">
        <div class="card-body" style="padding: 0;">
            <ul class="nav nav-tabs event-tabs px-3 pt-3" id="eventTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">
                        <i class="fas fa-pen-to-square"></i> Details
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="schedule-tab" data-bs-toggle="tab" data-bs-target="#schedule" type="button" role="tab">
                        <i class="fas fa-clock"></i> Schedule & Venue
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="registration-tab" data-bs-toggle="tab" data-bs-target="#registration" type="button" role="tab">
                        <i class="fas fa-users"></i> Registration
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button" role="tab">
                        <i class="fas fa-envelope"></i> Communications
                    </button>
                </li>
            </ul>

            <div class="tab-content p-4" id="eventTabsContent">
                <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                    <div class="event-section-title">Event Details</div>
                    <p class="event-section-subtitle">Capture the public information community members will use to discover and understand the event.</p>

                    <div class="row">
                        <div class="col-lg-8 mb-3">
                            <label class="form-label" for="title">Event Title <span style="color: var(--danger-color);">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ $field('title') }}" placeholder="e.g. JH Kids Creative Workshop">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="form-label" for="category">Category</label>
                            <select class="form-control @error('category') is-invalid @enderror" id="category" name="category">
                                <option value="">Select category</option>
                                <option value="JH Kids" @selected($field('category') == 'JH Kids')>JH Kids</option>
                                <option value="SMART Recovery" @selected($field('category') == 'SMART Recovery')>SMART Recovery</option>
                                <option value="Movement Program" @selected($field('category') == 'Movement Program')>Movement Program</option>
                                <option value="Parent Program" @selected($field('category') == 'Parent Program')>Parent Program</option>
                                <option value="Community Workshop" @selected($field('category') == 'Community Workshop')>Community Workshop</option>
                            </select>
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" placeholder="Describe who the event is for, what participants can expect, and any important preparation notes.">{{ $field('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="audience">Target Audience</label>
                            <input type="text" class="form-control" id="audience" name="audience" value="{{ $field('audience') }}" placeholder="Families, parents, young people, community members">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="visibility">Visibility</label>
                            <select class="form-control @error('visibility') is-invalid @enderror" id="visibility" name="visibility">
                                <option value="public" @selected($field('visibility', 'public') == 'public')>Public calendar</option>
                                <option value="private" @selected($field('visibility') == 'private')>Private admin-only draft</option>
                            </select>
                            @error('visibility') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="schedule" role="tabpanel" aria-labelledby="schedule-tab">
                    <div class="event-section-title">Schedule & Venue</div>
                    <p class="event-section-subtitle">Add timing and location information for calendar display, reminders, and attendance tracking.</p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="start_date">Start Date & Time <span style="color: var(--danger-color);">*</span></label>
                            <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ $dateField('start_date') }}">
                            @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="end_date">End Date & Time</label>
                            <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ $dateField('end_date') }}">
                            @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-7 mb-3">
                            <label class="form-label" for="location">Location</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ $field('location') }}" placeholder="Jewish House community room, online, or external venue">
                            @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-lg-5 mb-3">
                            <label class="form-label" for="meeting_link">Online Meeting Link</label>
                            <input type="url" class="form-control" id="meeting_link" name="meeting_link" value="{{ $field('meeting_link') }}" placeholder="https://">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="venue_notes">Venue Notes</label>
                        <textarea class="form-control" id="venue_notes" name="venue_notes" rows="3" placeholder="Parking, accessibility, check-in desk, or room directions.">{{ $field('venue_notes') }}</textarea>
                    </div>
                </div>

                <div class="tab-pane fade" id="registration" role="tabpanel" aria-labelledby="registration-tab">
                    <div class="event-section-title">Registration & Waitlist</div>
                    <p class="event-section-subtitle">Control RSVP capacity, waitlist behaviour, and the information required from participants.</p>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="capacity">Event Capacity</label>
                            <input type="number" min="1" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ $field('capacity') }}" placeholder="30">
                            @error('capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="registration_limit">Max Tickets per User</label>
                            <input type="number" min="1" class="form-control @error('registration_limit') is-invalid @enderror" id="registration_limit" name="registration_limit" value="{{ $field('registration_limit', 1) }}">
                            @error('registration_limit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="registration_close_date">Registration Closes</label>
                            <input type="datetime-local" class="form-control" id="registration_close_date" name="registration_close_date" value="{{ $dateField('registration_close_date') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label" for="approval_mode">Registration Approval</label>
                            <select class="form-control" id="approval_mode" name="approval_mode">
                                <option value="automatic" @selected($field('approval_mode', 'automatic') == 'automatic')>Automatic confirmation</option>
                                <option value="manual" @selected($field('approval_mode') == 'manual')>Manual admin approval</option>
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label" for="external_id">CRM / Salesforce Reference</label>
                            <input type="text" class="form-control @error('external_id') is-invalid @enderror" id="external_id" name="external_id" value="{{ $field('external_id') }}" placeholder="Optional campaign or event reference">
                            @error('external_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="waitlist" name="waitlist" value="1" @checked(old('waitlist', $event?->waitlist_enabled))>
                        <label class="form-check-label form-label" for="waitlist">Enable waitlist when capacity is reached</label>
                        <div class="form-hint">Waitlisted users can be notified automatically when places become available.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="required_fields">Required Participant Details</label>
                        <textarea class="form-control" id="required_fields" name="required_fields" rows="3" placeholder="Name, email, phone, dietary needs, emergency contact, consent notes.">{{ $field('required_fields') }}</textarea>
                    </div>
                </div>

                <div class="tab-pane fade" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                    <div class="event-section-title">Automated Communications</div>
                    <p class="event-section-subtitle">Prepare the notifications described in the project requirements: confirmation, reminder, vacancy, and feedback collection.</p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="confirmation_message">Confirmation Email Message</label>
                            <textarea class="form-control" id="confirmation_message" name="confirmation_message" rows="5" placeholder="Thanks for registering. We look forward to seeing you at the event.">{{ $field('confirmation_message') }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="reminder_message">Reminder Email Message</label>
                            <textarea class="form-control" id="reminder_message" name="reminder_message" rows="5" placeholder="This is a friendly reminder about your upcoming Jewish House event.">{{ $field('reminder_message') }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="waitlist_message">Waitlist Vacancy Message</label>
                            <textarea class="form-control" id="waitlist_message" name="waitlist_message" rows="4" placeholder="A place has opened for this event. Please confirm your attendance.">{{ $field('waitlist_message') }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="feedback_message">Post-Event Feedback Request</label>
                            <textarea class="form-control" id="feedback_message" name="feedback_message" rows="4" placeholder="Thank you for attending. Please share your feedback with Jewish House.">{{ $field('feedback_message') }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="reminder_offset">Reminder Timing</label>
                            <select class="form-control" id="reminder_offset" name="reminder_offset">
                                <option value="24h" @selected($field('reminder_offset', '24h') == '24h')>24 hours before</option>
                                <option value="48h" @selected($field('reminder_offset') == '48h')>48 hours before</option>
                                <option value="7d" @selected($field('reminder_offset') == '7d')>7 days before</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="feedback_offset">Feedback Timing</label>
                            <select class="form-control" id="feedback_offset" name="feedback_offset">
                                <option value="2h" @selected($field('feedback_offset') == '2h')>2 hours after event</option>
                                <option value="24h" @selected($field('feedback_offset', '24h') == '24h')>24 hours after event</option>
                                <option value="48h" @selected($field('feedback_offset') == '48h')>48 hours after event</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="email_status">Email Automation</label>
                            <select class="form-control" id="email_status" name="email_status">
                                <option value="enabled" @selected($field('email_status', 'enabled') == 'enabled')>Enabled</option>
                                <option value="disabled" @selected($field('email_status') == 'disabled')>Disabled until reviewed</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-actions">
        <a href="{{ route('admin.events') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancel
        </a>
        <div style="display: flex; gap: 10px; flex-wrap: wrap; justify-content: flex-end;">
            <button type="submit" class="btn btn-secondary" id="saveDraftBtn" onclick="setStatus('draft')">
                <i class="fas fa-save"></i> Save as Draft
            </button>
            <button type="submit" class="btn btn-primary" id="publishBtn" onclick="setStatus('published')">
                <i class="fas fa-check-circle"></i> {{ $isEditing ? 'Update & Publish' : 'Publish Event' }}
            </button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    function setStatus(status) {
        document.getElementById('statusField').value = status;
    }

    const tabButtons = Array.from(document.querySelectorAll('#eventTabs button[data-bs-toggle="tab"]'));

    function currentTabIndex() {
        return tabButtons.findIndex((button) => button.classList.contains('active'));
    }

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.altKey) {
            if (e.key === 'ArrowRight' || e.key === '.') {
                const index = currentTabIndex();
                if (index < tabButtons.length - 1) {
                    e.preventDefault();
                    bootstrap.Tab.getOrCreateInstance(tabButtons[index + 1]).show();
                }
            } else if (e.key === 'ArrowLeft' || e.key === ',') {
                const index = currentTabIndex();
                if (index > 0) {
                    e.preventDefault();
                    bootstrap.Tab.getOrCreateInstance(tabButtons[index - 1]).show();
                }
            }
        }
    });

    const firstInvalidField = document.querySelector('.tab-pane .is-invalid');

    if (firstInvalidField) {
        const invalidTabPane = firstInvalidField.closest('.tab-pane');
        const invalidTabButton = document.querySelector(`[data-bs-target="#${invalidTabPane.id}"]`);

        if (invalidTabButton) {
            bootstrap.Tab.getOrCreateInstance(invalidTabButton).show();
            firstInvalidField.focus({ preventScroll: true });
        }
    }
</script>
@endsection
