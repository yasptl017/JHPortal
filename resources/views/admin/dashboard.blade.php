@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<style>
    .dashboard-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 22px;
    }

    .dashboard-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .fc {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #1e293b;
    }

    .fc .fc-toolbar {
        gap: 14px;
        align-items: center;
        margin-bottom: 20px;
    }

    .fc .fc-toolbar-title {
        color: #0f172a;
        font-size: 24px;
        font-weight: 800;
        letter-spacing: 0;
    }

    .fc .fc-button {
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        padding: 8px 12px;
        box-shadow: none;
    }
    
    .fc .fc-button-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .fc .fc-button-primary:hover {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
    }
    
    .fc .fc-button-primary.fc-button-active {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
    }

    .fc .fc-button-primary:disabled {
        background: #dbeafe;
        border-color: #dbeafe;
        color: #1d4ed8;
        opacity: 1;
    }

    .fc .fc-col-header-cell {
        background: #f8fafc;
        border-color: #e2e8f0;
        padding: 12px 0;
    }

    .fc .fc-col-header-cell-cushion {
        color: #475569;
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
        text-transform: uppercase;
    }

    .fc .fc-scrollgrid,
    .fc .fc-scrollgrid-section > td,
    .fc .fc-daygrid-day,
    .fc-theme-standard td,
    .fc-theme-standard th {
        border-color: #e2e8f0;
    }

    .fc .fc-daygrid-day-frame {
        min-height: 112px;
        padding: 6px;
    }

    .fc .fc-daygrid-day-top {
        justify-content: flex-start;
    }

    .fc .fc-daygrid-day-number {
        align-items: center;
        border-radius: 999px;
        color: #475569;
        display: inline-flex;
        font-size: 13px;
        font-weight: 800;
        height: 28px;
        justify-content: center;
        text-decoration: none;
        width: 28px;
    }
    
    .fc .fc-daygrid-day.fc-day-other {
        background-color: #f8fafc;
    }
    
    .fc .fc-daygrid-day:hover {
        background-color: #f8fbff;
    }

    .fc .fc-day-today {
        background: #eff6ff !important;
    }

    .fc .fc-day-today .fc-daygrid-day-number {
        background: var(--primary-color);
        color: white;
    }
    
    .fc .fc-event {
        cursor: pointer;
        border: 0;
        border-radius: 7px;
        box-shadow: 0 6px 12px rgba(15, 23, 42, 0.08);
        margin-top: 5px;
        overflow: hidden;
    }
    
    .fc .fc-event:hover {
        filter: brightness(0.96);
        transform: translateY(-1px);
    }

    .calendar-event-pill {
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
        padding: 7px 8px;
    }

    .calendar-event-time {
        color: rgba(255, 255, 255, 0.82);
        font-size: 11px;
        font-weight: 700;
        line-height: 1.1;
    }

    .calendar-event-title {
        color: white;
        font-size: 12px;
        font-weight: 800;
        line-height: 1.2;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .fc .fc-list {
        border-color: #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
    }

    .fc .fc-list-day-cushion {
        background: #f8fafc !important;
        color: #0f172a;
        font-weight: 800;
    }

    .calendar-shell {
        display: grid;
        gap: 20px;
        grid-template-columns: minmax(0, 1fr) 280px;
    }

    .calendar-side {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .calendar-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 18px;
    }

    .calendar-panel-title {
        align-items: center;
        color: #0f172a;
        display: flex;
        font-size: 15px;
        font-weight: 800;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .calendar-legend {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin: 0;
        padding: 0;
    }

    .calendar-legend li {
        align-items: center;
        color: #475569;
        display: flex;
        font-size: 13px;
        font-weight: 700;
        gap: 9px;
    }

    .legend-dot {
        border-radius: 999px;
        display: inline-flex;
        height: 10px;
        width: 10px;
    }

    .upcoming-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .upcoming-item {
        border: 1px solid #e2e8f0;
        border-left: 4px solid var(--primary-color);
        border-radius: 8px;
        color: inherit;
        display: block;
        padding: 12px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .upcoming-item:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: inherit;
        transform: translateY(-1px);
    }

    .upcoming-date {
        color: #2563eb;
        font-size: 12px;
        font-weight: 800;
        margin-bottom: 4px;
        text-transform: uppercase;
    }

    .upcoming-title {
        color: #0f172a;
        font-size: 14px;
        font-weight: 800;
        line-height: 1.3;
        margin-bottom: 5px;
    }

    .upcoming-meta {
        color: #64748b;
        font-size: 12px;
    }

    .empty-upcoming {
        color: #64748b;
        font-size: 13px;
        line-height: 1.5;
        margin: 0;
    }
    
    .event-modal-header {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding-bottom: 16px;
        padding: 18px;
        margin-bottom: 16px;
    }
    
    .event-modal-title {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }
    
    .event-modal-meta {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }
    
    .event-modal-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #64748b;
    }
    
    .event-modal-meta-item i {
        color: var(--primary-color);
        min-width: 16px;
    }
    
    .event-modal-section {
        margin-bottom: 20px;
    }
    
    .event-modal-section-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .event-modal-section-content {
        color: #475569;
        font-size: 14px;
        line-height: 1.6;
    }
    
    .calendar-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 18px 35px rgba(15, 23, 42, 0.08);
        padding: 24px;
        border: 1px solid var(--light-border);
    }

    @media (max-width: 1200px) {
        .calendar-shell {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .dashboard-heading {
            flex-direction: column;
        }

        .dashboard-actions {
            justify-content: flex-start;
            width: 100%;
        }

        .fc .fc-toolbar {
            align-items: stretch;
            flex-direction: column;
        }

        .fc .fc-toolbar-chunk {
            display: flex;
            justify-content: center;
        }

        .fc .fc-toolbar-title {
            font-size: 20px;
            text-align: center;
        }

        .calendar-card {
            padding: 16px;
        }
    }
</style>

<div class="dashboard-heading">
    <div>
        <div class="page-title">
            <i class="fas fa-chart-line" style="margin-right: 10px; color: var(--primary-color);"></i>
            Dashboard
        </div>
        <p class="page-subtitle">Welcome back! Here's what's happening with your events.</p>
    </div>
    <div class="dashboard-actions">
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Create Event
        </a>
        <a href="{{ route('admin.events') }}" class="btn btn-secondary">
            <i class="fas fa-calendar-alt"></i> Manage Events
        </a>
    </div>
</div>

<!-- Stats Grid -->
<div class="row mb-30">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card blue">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-label">Total Events</div>
            <div class="stat-value">{{ $stats['total_events'] ?? 0 }}</div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="stat-card green">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-label">Total Registrations</div>
            <div class="stat-value">{{ $stats['total_registrations'] ?? 0 }}</div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="stat-card orange">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-label">Total Attendees</div>
            <div class="stat-value">{{ $stats['total_attendees'] ?? 0 }}</div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="stat-card red">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-label">Pending Approvals</div>
            <div class="stat-value">{{ $stats['pending_approvals'] ?? 0 }}</div>
        </div>
    </div>
</div>

<!-- Main Calendar Section -->
<div class="calendar-shell">
    <div class="calendar-card">
        <div id="calendar"></div>
    </div>

    <div class="calendar-side">
        <div class="calendar-panel">
            <div class="calendar-panel-title">
                <span>Categories</span>
            </div>
            <ul class="calendar-legend">
                <li><span class="legend-dot" style="background: #2563eb;"></span> JH Kids</li>
                <li><span class="legend-dot" style="background: #059669;"></span> SMART Recovery</li>
                <li><span class="legend-dot" style="background: #d97706;"></span> Movement Program</li>
                <li><span class="legend-dot" style="background: #7c3aed;"></span> Parent Program</li>
                <li><span class="legend-dot" style="background: #db2777;"></span> Community Workshop</li>
            </ul>
        </div>

        <div class="calendar-panel">
            <div class="calendar-panel-title">
                <span>Upcoming</span>
                <a href="{{ route('admin.events') }}" style="font-size: 12px; color: var(--primary-color); text-decoration: none;">View all</a>
            </div>
            <div class="upcoming-list">
                @forelse ($upcomingEvents as $event)
                    <a class="upcoming-item" href="{{ route('admin.events.edit', $event) }}">
                        <div class="upcoming-date">{{ $event->start_date?->format('D, d M') ?? 'Date TBA' }}</div>
                        <div class="upcoming-title">{{ $event->title }}</div>
                        <div class="upcoming-meta">{{ $event->category ?? 'General Event' }}{{ $event->location ? ' · ' . $event->location : '' }}</div>
                    </a>
                @empty
                    <p class="empty-upcoming">No upcoming events yet. Create an event to see it on the calendar.</p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 style="margin: 0;">
                    <i class="fas fa-bolt" style="margin-right: 8px; color: var(--primary-color);"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.events.create') }}" class="btn btn-primary w-100 mb-3">
                    <i class="fas fa-plus-circle"></i> Create New Event
                </a>
                <a href="{{ route('admin.events') }}" class="btn btn-secondary w-100 mb-3">
                    <i class="fas fa-calendar-alt"></i> Manage Events
                </a>
                <a href="{{ route('admin.users') }}" class="btn btn-secondary w-100 mb-3">
                    <i class="fas fa-users"></i> Manage Users
                </a>
                <a href="{{ route('admin.analytics') }}" class="btn btn-secondary w-100 mb-3">
                    <i class="fas fa-chart-bar"></i> View Analytics
                </a>
                <a href="{{ route('admin.settings') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </div>
        </div>

        <!-- System Status -->
        <div class="card" style="margin-top: 20px;">
            <div class="card-header">
                <h5 style="margin: 0;">
                    <i class="fas fa-server" style="margin-right: 8px; color: var(--primary-color);"></i>
                    System Status
                </h5>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                        <span style="font-size: 13px; color: #64748b;">Database</span>
                        <span class="badge badge-success">Operational</span>
                    </div>
                </div>
                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                        <span style="font-size: 13px; color: #64748b;">Email Service</span>
                        <span class="badge badge-success">Operational</span>
                    </div>
                </div>
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                        <span style="font-size: 13px; color: #64748b;">API</span>
                        <span class="badge badge-success">Operational</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Event Details Modal -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 1px solid var(--light-border);">
                <h5 class="modal-title">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="eventModalBody">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="eventEditBtn" class="btn btn-primary">Edit Event</a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            buttonText: {
                today: 'Today',
                month: 'Month',
                week: 'Week',
                list: 'List'
            },
            events: {
                url: '{{ route("admin.events.calendar") }}'
            },
            eventClick: function(info) {
                displayEventDetails(info.event);
            },
            eventContent: function(arg) {
                const wrapper = document.createElement('div');
                const time = document.createElement('div');
                const title = document.createElement('div');

                wrapper.className = 'calendar-event-pill';
                time.className = 'calendar-event-time';
                title.className = 'calendar-event-title';

                time.textContent = arg.timeText || 'All day';
                title.textContent = arg.event.title;

                wrapper.append(time, title);

                return { domNodes: [wrapper] };
            },
            eventDisplay: 'block',
            height: 'auto',
            contentHeight: 'auto',
            dayMaxEventRows: 3,
            moreLinkClick: 'popover',
            nowIndicator: true,
            navLinks: true
        });
        
        calendar.render();
        
        function displayEventDetails(event) {
            const modal = new bootstrap.Modal(document.getElementById('eventModal'));
            const escapeHtml = (value) => {
                const div = document.createElement('div');
                div.textContent = value || '';
                return div.innerHTML;
            };

            const startDate = event.start ? new Date(event.start).toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }) : 'TBA';
            
            const endDate = event.end ? new Date(event.end).toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }) : '';
            
            const props = event.extendedProps || {};
            const title = escapeHtml(event.title);
            const category = escapeHtml(props.category || 'Event');
            const location = escapeHtml(props.location || '');
            const description = escapeHtml(props.description || '');
            const status = props.status || 'draft';
            const statusLabel = status.charAt(0).toUpperCase() + status.slice(1);
            
            const html = `
                <div class="event-modal-header">
                    <div class="event-modal-title">${title}</div>
                    <div class="event-modal-meta">
                        <div class="event-modal-meta-item">
                            <i class="fas fa-tag"></i>
                            <span>${category}</span>
                        </div>
                        <div class="event-modal-meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>${startDate}</span>
                        </div>
                        ${props.location ? `
                            <div class="event-modal-meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>${location}</span>
                            </div>
                        ` : ''}
                    </div>
                </div>
                
                ${props.description ? `
                    <div class="event-modal-section">
                        <div class="event-modal-section-title">Description</div>
                        <div class="event-modal-section-content">${description}</div>
                    </div>
                ` : ''}
                
                <div class="event-modal-section">
                    <div class="event-modal-section-title">Event Information</div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <span style="font-size: 12px; color: #64748b; font-weight: 600;">Status</span>
                            <div style="margin-top: 4px;">
                                <span class="badge ${props.status === 'published' ? 'badge-success' : 'badge-warning'}">
                                    ${statusLabel}
                                </span>
                            </div>
                        </div>
                        <div>
                            <span style="font-size: 12px; color: #64748b; font-weight: 600;">Visibility</span>
                            <div style="margin-top: 4px; font-size: 14px; font-weight: 600; color: #1e293b;">
                                ${props.visibility === 'private' ? 'Private' : 'Public'}
                            </div>
                        </div>
                        ${props.capacity ? `
                            <div>
                                <span style="font-size: 12px; color: #64748b; font-weight: 600;">Capacity</span>
                                <div style="margin-top: 4px; font-size: 14px; font-weight: 600; color: #1e293b;">${props.capacity} attendees</div>
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
            
            document.getElementById('eventModalBody').innerHTML = html;
            document.getElementById('eventEditBtn').href = `/admin/events/${event.id}/edit`;
            modal.show();
        }
    });
</script>

@endsection
