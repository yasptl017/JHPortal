@extends('admin.layouts.app')

@section('title', 'Attendance Report - ' . $event->title)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="fas fa-chart-bar me-2"></i>Attendance Report</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5>{{ $event->title }}</h5>
            <p class="text-muted mb-0">
                <i class="fas fa-calendar me-2"></i>{{ $event->event_date->format('M d, Y H:i') }}
                <i class="fas fa-map-marker-alt ms-3 me-2"></i>{{ $event->location }}
            </p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Registered</h6>
                    <h3 class="mb-0">{{ $stats['total_registered'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success bg-opacity-10">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Present</h6>
                    <h3 class="mb-0 text-success">{{ $stats['present'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning bg-opacity-10">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Late</h6>
                    <h3 class="mb-0 text-warning">{{ $stats['late'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger bg-opacity-10">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Absent</h6>
                    <h3 class="mb-0 text-danger">{{ $stats['absent'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Attendance Rate</h6>
                    <h2 class="mb-0">{{ $stats['attendance_rate'] }}%</h2>
                    <div class="progress mt-3" style="height: 25px;">
                        <div class="progress-bar bg-success" style="width: {{ $stats['attendance_rate'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-3">Summary</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <span class="badge bg-success">{{ $stats['present'] }}</span>
                            <strong>Present</strong> - {{ round(($stats['present'] / max($stats['total_registered'], 1)) * 100, 1) }}%
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-warning">{{ $stats['late'] }}</span>
                            <strong>Late</strong> - {{ round(($stats['late'] / max($stats['total_registered'], 1)) * 100, 1) }}%
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-danger">{{ $stats['absent'] }}</span>
                            <strong>Absent</strong> - {{ round(($stats['absent'] / max($stats['total_registered'], 1)) * 100, 1) }}%
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Detailed Attendance Records</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Checked In</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendance as $record)
                            <tr>
                                <td><strong>{{ $record->user->name }}</strong></td>
                                <td>{{ $record->user->email }}</td>
                                <td>
                                    @if($record->status === 'present')
                                        <span class="badge bg-success">Present</span>
                                    @elseif($record->status === 'late')
                                        <span class="badge bg-warning">Late</span>
                                    @else
                                        <span class="badge bg-danger">Absent</span>
                                    @endif
                                </td>
                                <td>{{ $record->checked_in_at?->format('H:i') ?? '--' }}</td>
                                <td>{{ $record->notes ?? '--' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No attendance records
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
