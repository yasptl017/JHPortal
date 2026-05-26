@extends('admin.layouts.app')

@section('title', 'Waitlist Analytics')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="fas fa-chart-bar me-2"></i>Waitlist Analytics</h1>
        </div>
    </div>

    <!-- Event Selection -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Select Event</label>
                    <select name="event_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Choose an event --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" @if($selectedEvent?->id == $event->id) selected @endif>
                                {{ $event->title }} ({{ $event->start_date->format('M d, Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if($selectedEvent && !empty($analytics))
        <!-- Key Metrics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Total Waitlist</h6>
                        <h2 class="mb-0">{{ $analytics['total_waitlist'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Conversion Rate</h6>
                        <h2 class="mb-0 text-success">{{ $analytics['conversion_rate'] }}%</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Abandonment Rate</h6>
                        <h2 class="mb-0 text-danger">{{ $analytics['abandonment_rate'] }}%</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Avg Wait Time</h6>
                        <h2 class="mb-0">{{ $analytics['avg_wait_time'] }}h</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Distribution Chart -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Waitlist Status Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Status Breakdown -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Status Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Pending</span>
                                <span class="badge bg-warning">{{ $analytics['pending'] }}</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" style="width: {{ ($analytics['pending'] / $analytics['total_waitlist'] * 100) ?? 0 }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Notified</span>
                                <span class="badge bg-info">{{ $analytics['notified'] }}</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-info" style="width: {{ ($analytics['notified'] / $analytics['total_waitlist'] * 100) ?? 0 }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Confirmed</span>
                                <span class="badge bg-success">{{ $analytics['confirmed'] }}</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: {{ ($analytics['confirmed'] / $analytics['total_waitlist'] * 100) ?? 0 }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Cancelled</span>
                                <span class="badge bg-danger">{{ $analytics['cancelled'] }}</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-danger" style="width: {{ ($analytics['cancelled'] / $analytics['total_waitlist'] * 100) ?? 0 }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Expired</span>
                                <span class="badge bg-secondary">{{ $analytics['expired'] }}</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-secondary" style="width: {{ ($analytics['expired'] / $analytics['total_waitlist'] * 100) ?? 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trend Chart -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Waitlist Growth Trend (Last 30 Days)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>Select an event to view analytics.
        </div>
    @endif
</div>

@if($selectedEvent && !empty($chartData))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Status Distribution Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                data: @json($chartData['data']),
                backgroundColor: @json($chartData['colors']),
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Trend Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    fetch('{{ route("admin.waitlist.analytics.trend", ["event_id" => $selectedEvent->id]) }}')
        .then(response => response.json())
        .then(data => {
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: data.map(d => d.date),
                    datasets: [{
                        label: 'Waitlist Entries',
                        data: data.map(d => d.count),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
</script>
@endif
@endsection
