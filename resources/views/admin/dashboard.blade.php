@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="page-title">
    <i class="fas fa-chart-line" style="margin-right: 10px; color: var(--primary-color);"></i>
    Dashboard
</div>
<p class="page-subtitle">Welcome back! Here's what's happening with your events today.</p>

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

<!-- Charts and Recent Activity -->
<div class="row">
    <!-- Recent Events -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 style="margin: 0;">
                    <i class="fas fa-calendar-check" style="margin-right: 8px; color: var(--primary-color);"></i>
                    Upcoming Events
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Event Name</th>
                                <th>Date</th>
                                <th>Attendees</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($upcomingEvents as $event)
                                <tr>
                                    <td>{{ $event->title }}</td>
                                    <td>{{ $event->start_date?->format('d M Y') }}</td>
                                    <td>0{{ $event->capacity ? ' / ' . $event->capacity : '' }}</td>
                                    <td>
                                        <span class="badge {{ $event->status === 'published' ? 'badge-success' : 'badge-warning' }}">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 40px;">
                                        <i class="fas fa-inbox" style="font-size: 40px; color: #cbd5e1; margin-bottom: 15px; display: block;"></i>
                                        <p style="color: #94a3b8; margin: 0;">No events yet. <a href="{{ route('admin.events.create') }}" style="color: var(--primary-color);">Create your first event</a></p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
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

@endsection
