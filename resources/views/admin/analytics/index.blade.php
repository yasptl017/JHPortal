@extends('admin.layouts.app')

@section('title', 'Analytics & Reports')
@section('breadcrumb', 'Analytics & Reports')

@section('content')
<style>
    .analytics-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 28px;
        flex-wrap: wrap;
    }

    .analytics-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .overview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }

    .overview-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 22px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .overview-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }

    .overview-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .overview-card.blue::before { background: linear-gradient(90deg, #2563eb, #3b82f6); }
    .overview-card.green::before { background: linear-gradient(90deg, #059669, #10b981); }
    .overview-card.orange::before { background: linear-gradient(90deg, #d97706, #f59e0b); }
    .overview-card.purple::before { background: linear-gradient(90deg, #7c3aed, #8b5cf6); }
    .overview-card.red::before { background: linear-gradient(90deg, #dc2626, #ef4444); }
    .overview-card.teal::before { background: linear-gradient(90deg, #0d9488, #14b8a6); }

    .overview-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 14px;
    }

    .overview-card.blue .overview-icon { background: #dbeafe; color: #2563eb; }
    .overview-card.green .overview-icon { background: #dcfce7; color: #059669; }
    .overview-card.orange .overview-icon { background: #fed7aa; color: #d97706; }
    .overview-card.purple .overview-icon { background: #ede9fe; color: #7c3aed; }
    .overview-card.red .overview-icon { background: #fee2e2; color: #dc2626; }
    .overview-card.teal .overview-icon { background: #ccfbf1; color: #0d9488; }

    .overview-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .overview-value {
        font-size: 30px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .overview-sub {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 4px;
    }

    .chart-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 22px;
        margin-bottom: 28px;
    }

    .chart-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .chart-card:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    }

    .chart-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 22px;
        border-bottom: 1px solid #f1f5f9;
        background: #fafbfc;
    }

    .chart-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chart-card-title i {
        color: #2563eb;
        font-size: 14px;
    }

    .chart-card-body {
        padding: 22px;
    }

    .chart-canvas-wrapper {
        position: relative;
        width: 100%;
        min-height: 280px;
    }

    .chart-full-width {
        grid-column: 1 / -1;
    }

    /* Popular Events Table */
    .popular-events-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .popular-events-table th {
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
    }

    .popular-events-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        color: #475569;
    }

    .popular-events-table tr:last-child td {
        border-bottom: none;
    }

    .popular-events-table tr:hover td {
        background: #f8fafc;
    }

    .rank-badge {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 12px;
    }

    .rank-1 { background: #fef3c7; color: #92400e; }
    .rank-2 { background: #e2e8f0; color: #475569; }
    .rank-3 { background: #fed7aa; color: #9a3412; }
    .rank-default { background: #f1f5f9; color: #64748b; }

    .progress-bar-custom {
        height: 8px;
        border-radius: 4px;
        background: #e2e8f0;
        overflow: hidden;
        min-width: 100px;
    }

    .progress-bar-fill {
        height: 100%;
        border-radius: 4px;
        background: linear-gradient(90deg, #2563eb, #3b82f6);
        transition: width 0.8s ease;
    }

    /* Repeat Attendees */
    .attendee-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid #f1f5f9;
        transition: all 0.2s;
    }

    .attendee-card:hover {
        background: #f8fafc;
        border-color: #e2e8f0;
    }

    .attendee-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        color: white;
        flex-shrink: 0;
    }

    .attendee-name {
        font-weight: 600;
        font-size: 13px;
        color: #1e293b;
    }

    .attendee-count {
        font-size: 11px;
        color: #64748b;
    }

    /* Recent Activity */
    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #2563eb;
        margin-top: 6px;
        flex-shrink: 0;
    }

    .activity-text {
        font-size: 13px;
        color: #475569;
        line-height: 1.5;
    }

    .activity-text strong {
        color: #1e293b;
    }

    .activity-time {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 2px;
    }

    .export-dropdown .dropdown-menu {
        min-width: 200px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        border: 1px solid #e2e8f0;
        padding: 6px;
    }

    .export-dropdown .dropdown-item {
        border-radius: 6px;
        font-size: 13px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .export-dropdown .dropdown-item:hover {
        background: #f1f5f9;
    }

    .empty-chart-msg {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 200px;
        color: #94a3b8;
        font-size: 14px;
    }

    .empty-chart-msg i {
        font-size: 40px;
        margin-bottom: 12px;
        opacity: 0.4;
    }

    @media (max-width: 992px) {
        .chart-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .analytics-header {
            flex-direction: column;
        }

        .overview-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .overview-value {
            font-size: 24px;
        }
    }

    @media (max-width: 480px) {
        .overview-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Header -->
<div class="analytics-header">
    <div>
        <div class="page-title">
            <i class="fas fa-chart-bar" style="margin-right: 10px; color: var(--primary-color);"></i>
            Analytics & Reports
        </div>
        <p class="page-subtitle">Visual analytics for registrations, attendance, popular categories, and repeat attendees</p>
    </div>
    <div class="analytics-actions">
        <div class="export-dropdown dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-download"></i> Export Report
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('admin.analytics.export', ['type' => 'registrations']) }}">
                        <i class="fas fa-user-check text-primary"></i> Registrations CSV
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.analytics.export', ['type' => 'attendance']) }}">
                        <i class="fas fa-clipboard-check text-success"></i> Attendance CSV
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.analytics.export', ['type' => 'feedback']) }}">
                        <i class="fas fa-star text-warning"></i> Feedback CSV
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Overview Cards -->
<div class="overview-grid">
    <div class="overview-card blue">
        <div class="overview-icon"><i class="fas fa-calendar-alt"></i></div>
        <div class="overview-label">Total Events</div>
        <div class="overview-value">{{ $totalEvents }}</div>
        <div class="overview-sub">{{ $upcomingEvents }} upcoming · {{ $pastEvents }} past</div>
    </div>

    <div class="overview-card green">
        <div class="overview-icon"><i class="fas fa-user-check"></i></div>
        <div class="overview-label">Total Registrations</div>
        <div class="overview-value">{{ $totalRegistrations }}</div>
        <div class="overview-sub">Across all events</div>
    </div>

    <div class="overview-card purple">
        <div class="overview-icon"><i class="fas fa-users"></i></div>
        <div class="overview-label">Community Members</div>
        <div class="overview-value">{{ $totalUsers }}</div>
        <div class="overview-sub">Registered users</div>
    </div>

    <div class="overview-card teal">
        <div class="overview-icon"><i class="fas fa-clipboard-check"></i></div>
        <div class="overview-label">Attendance Rate</div>
        <div class="overview-value">{{ $attendanceRate }}%</div>
        <div class="overview-sub">{{ $totalAttendance }} total present</div>
    </div>

    <div class="overview-card orange">
        <div class="overview-icon"><i class="fas fa-star"></i></div>
        <div class="overview-label">Avg. Feedback Rating</div>
        <div class="overview-value">{{ $avgRating }}<small style="font-size: 14px; color: #94a3b8;">/5</small></div>
        <div class="overview-sub">{{ $totalFeedbacks }} reviews submitted</div>
    </div>

    <div class="overview-card red">
        <div class="overview-icon"><i class="fas fa-envelope"></i></div>
        <div class="overview-label">Emails Sent</div>
        <div class="overview-value">{{ $totalEmailsSent }}</div>
        <div class="overview-sub">{{ $emailFailRate }}% failure rate</div>
    </div>
</div>

<!-- Charts Row 1: Registration Trends + Category Distribution -->
<div class="chart-grid">
    <div class="chart-card">
        <div class="chart-card-header">
            <div class="chart-card-title">
                <i class="fas fa-chart-line"></i> Registration Trends
            </div>
            <span style="font-size: 11px; color: #94a3b8;">Last 6 months</span>
        </div>
        <div class="chart-card-body">
            <div class="chart-canvas-wrapper">
                <canvas id="registrationTrendChart"></canvas>
            </div>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-card-header">
            <div class="chart-card-title">
                <i class="fas fa-chart-pie"></i> Event Categories
            </div>
            <span style="font-size: 11px; color: #94a3b8;">Distribution</span>
        </div>
        <div class="chart-card-body">
            <div class="chart-canvas-wrapper">
                @if($categoryDistribution->count() > 0)
                    <canvas id="categoryChart"></canvas>
                @else
                    <div class="empty-chart-msg">
                        <i class="fas fa-chart-pie"></i>
                        <span>No category data available yet</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Charts Row 2: Attendance Trends + Rating Distribution -->
<div class="chart-grid">
    <div class="chart-card">
        <div class="chart-card-header">
            <div class="chart-card-title">
                <i class="fas fa-chart-area"></i> Attendance Trends
            </div>
            <span style="font-size: 11px; color: #94a3b8;">Last 6 months</span>
        </div>
        <div class="chart-card-body">
            <div class="chart-canvas-wrapper">
                <canvas id="attendanceTrendChart"></canvas>
            </div>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-card-header">
            <div class="chart-card-title">
                <i class="fas fa-star-half-alt"></i> Feedback Rating Distribution
            </div>
            <span style="font-size: 11px; color: #94a3b8;">All events</span>
        </div>
        <div class="chart-card-body">
            <div class="chart-canvas-wrapper">
                @if($ratingDistribution->count() > 0)
                    <canvas id="ratingChart"></canvas>
                @else
                    <div class="empty-chart-msg">
                        <i class="fas fa-star"></i>
                        <span>No feedback ratings yet</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Charts Row 3: Capacity Comparison (full width) -->
@if($capacityComparison->count() > 0)
<div class="chart-grid">
    <div class="chart-card chart-full-width">
        <div class="chart-card-header">
            <div class="chart-card-title">
                <i class="fas fa-chart-bar"></i> Registration vs Capacity
            </div>
            <span style="font-size: 11px; color: #94a3b8;">Top events by registrations</span>
        </div>
        <div class="chart-card-body">
            <div class="chart-canvas-wrapper" style="min-height: 320px;">
                <canvas id="capacityChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Bottom Section: Popular Events + Repeat Attendees + Recent Activity -->
<div class="chart-grid" style="grid-template-columns: 1.5fr 1fr;">
    <!-- Popular Events -->
    <div class="chart-card">
        <div class="chart-card-header">
            <div class="chart-card-title">
                <i class="fas fa-trophy"></i> Top Popular Events
            </div>
            <span style="font-size: 11px; color: #94a3b8;">By registration count</span>
        </div>
        <div class="chart-card-body" style="padding: 0;">
            @if($popularEvents->count() > 0)
                <table class="popular-events-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Rank</th>
                            <th>Event</th>
                            <th>Category</th>
                            <th style="width: 100px;">Registrations</th>
                            <th style="width: 140px;">Fill Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($popularEvents as $index => $event)
                        <tr>
                            <td>
                                <span class="rank-badge {{ $index < 3 ? 'rank-' . ($index + 1) : 'rank-default' }}">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td>
                                <strong style="color: #1e293b;">{{ Str::limit($event->title, 35) }}</strong>
                                <div style="font-size: 11px; color: #94a3b8;">
                                    {{ $event->start_date ? $event->start_date->format('d M Y') : 'TBA' }}
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $event->category ?? 'General' }}</span>
                            </td>
                            <td>
                                <strong>{{ $event->registrations_count }}</strong>
                            </td>
                            <td>
                                @php
                                    $fillRate = $event->capacity ? round(($event->registrations_count / $event->capacity) * 100) : 0;
                                @endphp
                                <div class="progress-bar-custom">
                                    <div class="progress-bar-fill" style="width: {{ min($fillRate, 100) }}%;
                                        {{ $fillRate >= 90 ? 'background: linear-gradient(90deg, #dc2626, #ef4444);' : '' }}
                                        {{ $fillRate >= 70 && $fillRate < 90 ? 'background: linear-gradient(90deg, #d97706, #f59e0b);' : '' }}
                                    "></div>
                                </div>
                                <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">
                                    {{ $event->capacity ? $fillRate . '% of ' . $event->capacity : 'No limit' }}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-chart-msg" style="min-height: 160px;">
                    <i class="fas fa-trophy"></i>
                    <span>No events with registrations yet</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column: Repeat Attendees + Recent Activity -->
    <div style="display: flex; flex-direction: column; gap: 22px;">
        <!-- Repeat Attendees -->
        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title">
                    <i class="fas fa-user-friends"></i> Repeat Attendees
                </div>
                <span style="font-size: 11px; color: #94a3b8;">2+ events</span>
            </div>
            <div class="chart-card-body">
                @if($repeatAttendees->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        @php
                            $colors = ['#2563eb', '#059669', '#d97706', '#7c3aed', '#dc2626', '#0d9488', '#db2777', '#6366f1', '#ea580c', '#0891b2'];
                        @endphp
                        @foreach($repeatAttendees as $index => $user)
                            <div class="attendee-card">
                                <div class="attendee-avatar" style="background: {{ $colors[$index % count($colors)] }};">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div class="attendee-name">{{ Str::limit($user->name, 20) }}</div>
                                    <div class="attendee-count">{{ $user->event_registrations_count }} events attended</div>
                                </div>
                                <span class="badge badge-info">{{ $user->event_registrations_count }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-chart-msg" style="min-height: 120px;">
                        <i class="fas fa-user-friends"></i>
                        <span>No repeat attendees yet</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="chart-card">
            <div class="chart-card-header">
                <div class="chart-card-title">
                    <i class="fas fa-clock"></i> Recent Registrations
                </div>
                <a href="{{ route('admin.registrations') }}" style="font-size: 11px; color: #2563eb; text-decoration: none;">View all</a>
            </div>
            <div class="chart-card-body">
                @if($recentRegistrations->count() > 0)
                    @foreach($recentRegistrations as $reg)
                        <div class="activity-item">
                            <div class="activity-dot"></div>
                            <div>
                                <div class="activity-text">
                                    <strong>{{ $reg->user->name ?? 'Unknown' }}</strong> registered for
                                    <strong>{{ Str::limit($reg->event->title ?? 'Unknown Event', 25) }}</strong>
                                </div>
                                <div class="activity-time">
                                    {{ $reg->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-chart-msg" style="min-height: 120px;">
                        <i class="fas fa-clock"></i>
                        <span>No recent activity</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Waitlist Summary Section -->
<div class="chart-grid" style="grid-template-columns: 1fr 1fr; margin-top: 6px;">
    <div class="chart-card">
        <div class="chart-card-header">
            <div class="chart-card-title">
                <i class="fas fa-list-ol"></i> Waitlist Overview
            </div>
        </div>
        <div class="chart-card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
                <div style="text-align: center; padding: 16px; border-radius: 10px; background: #eff6ff;">
                    <div style="font-size: 28px; font-weight: 800; color: #2563eb;">{{ $totalWaitlisted }}</div>
                    <div style="font-size: 12px; color: #64748b; font-weight: 600;">Currently Waiting</div>
                </div>
                <div style="text-align: center; padding: 16px; border-radius: 10px; background: #dcfce7;">
                    <div style="font-size: 28px; font-weight: 800; color: #059669;">{{ $waitlistConverted }}</div>
                    <div style="font-size: 12px; color: #64748b; font-weight: 600;">Converted to Registration</div>
                </div>
            </div>
            @php
                $conversionRate = ($totalWaitlisted + $waitlistConverted) > 0
                    ? round(($waitlistConverted / ($totalWaitlisted + $waitlistConverted)) * 100, 1)
                    : 0;
            @endphp
            <div style="margin-top: 18px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                    <span style="font-size: 12px; color: #64748b; font-weight: 600;">Conversion Rate</span>
                    <span style="font-size: 12px; font-weight: 700; color: #1e293b;">{{ $conversionRate }}%</span>
                </div>
                <div class="progress-bar-custom" style="height: 10px;">
                    <div class="progress-bar-fill" style="width: {{ $conversionRate }}%; background: linear-gradient(90deg, #059669, #10b981);"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-card-header">
            <div class="chart-card-title">
                <i class="fas fa-clipboard-check"></i> Attendance Breakdown
            </div>
        </div>
        <div class="chart-card-body">
            @if($attendanceByStatus->count() > 0)
                <div class="chart-canvas-wrapper" style="min-height: 200px;">
                    <canvas id="attendanceStatusChart"></canvas>
                </div>
            @else
                <div class="empty-chart-msg" style="min-height: 200px;">
                    <i class="fas fa-clipboard-check"></i>
                    <span>No attendance data yet</span>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // ── Chart.js Global Configuration ──
    Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#64748b';
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.padding = 16;

    // ── 1. Registration Trends (Line Chart) ──
    const regCtx = document.getElementById('registrationTrendChart');
    if (regCtx) {
        new Chart(regCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode($registrationTrends['labels']) !!},
                datasets: [{
                    label: 'Registrations',
                    data: {!! json_encode($registrationTrends['data']) !!},
                    borderColor: '#2563eb',
                    backgroundColor: (ctx) => {
                        const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, 280);
                        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.25)');
                        gradient.addColorStop(1, 'rgba(37, 99, 235, 0.02)');
                        return gradient;
                    },
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#2563eb',
                    pointHoverBorderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleFont: { weight: '700' },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 },
                        grid: { color: '#f1f5f9' },
                        border: { display: false },
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                    }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });
    }

    // ── 2. Category Distribution (Doughnut Chart) ──
    @if($categoryDistribution->count() > 0)
    const catCtx = document.getElementById('categoryChart');
    if (catCtx) {
        const categoryColors = ['#2563eb', '#059669', '#d97706', '#7c3aed', '#db2777', '#0d9488', '#ea580c', '#6366f1'];
        new Chart(catCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryDistribution->pluck('category')) !!},
                datasets: [{
                    data: {!! json_encode($categoryDistribution->pluck('count')) !!},
                    backgroundColor: categoryColors.slice(0, {{ $categoryDistribution->count() }}),
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverBorderWidth: 0,
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 14, font: { weight: '600', size: 12 } }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = total ? ((context.raw / total) * 100).toFixed(1) : 0;
                                return ` ${context.label}: ${context.raw} (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
    @endif

    // ── 3. Attendance Trends (Stacked Bar Chart) ──
    const attCtx = document.getElementById('attendanceTrendChart');
    if (attCtx) {
        new Chart(attCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($attendanceTrends['labels']) !!},
                datasets: [
                    {
                        label: 'Present',
                        data: {!! json_encode($attendanceTrends['present']) !!},
                        backgroundColor: '#10b981',
                        borderRadius: 4,
                        barPercentage: 0.7,
                    },
                    {
                        label: 'Late',
                        data: {!! json_encode($attendanceTrends['late']) !!},
                        backgroundColor: '#f59e0b',
                        borderRadius: 4,
                        barPercentage: 0.7,
                    },
                    {
                        label: 'Absent',
                        data: {!! json_encode($attendanceTrends['absent']) !!},
                        backgroundColor: '#ef4444',
                        borderRadius: 4,
                        barPercentage: 0.7,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', align: 'end' },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        stacked: true,
                        ticks: { precision: 0 },
                        grid: { color: '#f1f5f9' },
                        border: { display: false },
                    },
                    x: {
                        stacked: true,
                        grid: { display: false },
                        border: { display: false },
                    }
                }
            }
        });
    }

    // ── 4. Rating Distribution (Bar Chart) ──
    @if($ratingDistribution->count() > 0)
    const ratingCtx = document.getElementById('ratingChart');
    if (ratingCtx) {
        const ratingColors = ['#ef4444', '#f97316', '#f59e0b', '#84cc16', '#10b981'];
        const ratingLabels = ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'];
        const ratingData = [0, 0, 0, 0, 0];

        @foreach($ratingDistribution as $rd)
            ratingData[{{ $rd->rating }} - 1] = {{ $rd->count }};
        @endforeach

        new Chart(ratingCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ratingLabels,
                datasets: [{
                    label: 'Reviews',
                    data: ratingData,
                    backgroundColor: ratingColors,
                    borderRadius: 6,
                    barPercentage: 0.65,
                    maxBarThickness: 50,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = total ? ((context.raw / total) * 100).toFixed(1) : 0;
                                return ` ${context.raw} reviews (${pct}%)`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { precision: 0 },
                        grid: { color: '#f1f5f9' },
                        border: { display: false },
                    },
                    y: {
                        grid: { display: false },
                        border: { display: false },
                    }
                }
            }
        });
    }
    @endif

    // ── 5. Capacity Comparison (Grouped Bar Chart) ──
    @if($capacityComparison->count() > 0)
    const capCtx = document.getElementById('capacityChart');
    if (capCtx) {
        new Chart(capCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($capacityComparison->pluck('title')->map(fn($t) => Str::limit($t, 25))) !!},
                datasets: [
                    {
                        label: 'Registrations',
                        data: {!! json_encode($capacityComparison->pluck('registrations_count')) !!},
                        backgroundColor: '#2563eb',
                        borderRadius: 6,
                        barPercentage: 0.7,
                    },
                    {
                        label: 'Capacity',
                        data: {!! json_encode($capacityComparison->pluck('capacity')) !!},
                        backgroundColor: '#e2e8f0',
                        borderRadius: 6,
                        barPercentage: 0.7,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', align: 'end' },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 },
                        grid: { color: '#f1f5f9' },
                        border: { display: false },
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { maxRotation: 45, minRotation: 0 }
                    }
                }
            }
        });
    }
    @endif

    // ── 6. Attendance Status (Doughnut Chart) ──
    @if($attendanceByStatus->count() > 0)
    const attStatusCtx = document.getElementById('attendanceStatusChart');
    if (attStatusCtx) {
        const statusColorMap = {
            'present': '#10b981',
            'absent': '#ef4444',
            'late': '#f59e0b',
        };
        const statusLabels = {!! json_encode($attendanceByStatus->pluck('status')) !!};
        const statusData = {!! json_encode($attendanceByStatus->pluck('count')) !!};
        const statusColors = statusLabels.map(s => statusColorMap[s] || '#94a3b8');

        new Chart(attStatusCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: statusLabels.map(s => s.charAt(0).toUpperCase() + s.slice(1)),
                datasets: [{
                    data: statusData,
                    backgroundColor: statusColors,
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '58%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 14, font: { weight: '600', size: 12 } }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        cornerRadius: 8,
                    }
                }
            }
        });
    }
    @endif
</script>
@endsection
