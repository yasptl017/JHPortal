@extends('admin.layouts.app')

@section('title', 'Analytics & Reports')
@section('breadcrumb', 'Analytics')

@section('content')
<div class="page-title">
    <i class="fas fa-chart-bar" style="margin-right: 10px; color: var(--primary-color);"></i>
    Analytics & Reports
</div>
<p class="page-subtitle">View detailed analytics and performance metrics</p>

<!-- Analytics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card blue">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-label">Conversion Rate</div>
            <div class="stat-value">0%</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card green">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-label">Completion Rate</div>
            <div class="stat-value">0%</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card orange">
            <div class="stat-icon">
                <i class="fas fa-eye"></i>
            </div>
            <div class="stat-label">Total Views</div>
            <div class="stat-value">0</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card red">
            <div class="stat-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-label">Bounce Rate</div>
            <div class="stat-value">0%</div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 style="margin: 0;">Registration Trends</h5>
            </div>
            <div class="card-body">
                <canvas id="registrationChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 style="margin: 0;">Event Categories</h5>
            </div>
            <div class="card-body">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Registration Chart
    const registrationCtx = document.getElementById('registrationChart').getContext('2d');
    new Chart(registrationCtx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6'],
            datasets: [{
                label: 'Registrations',
                data: [0, 0, 0, 0, 0, 0],
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#2563eb',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Workshop', 'Meeting', 'Program'],
            datasets: [{
                data: [0, 0, 0],
                backgroundColor: [
                    '#2563eb',
                    '#10b981',
                    '#f59e0b'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection
