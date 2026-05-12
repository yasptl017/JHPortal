@extends('admin.layouts.app')

@section('title', 'Registrations')
@section('breadcrumb', 'Registrations')

@section('content')
<div class="page-title">
    <i class="fas fa-user-check" style="margin-right: 10px; color: var(--primary-color);"></i>
    Registrations
</div>
<p class="page-subtitle">View and manage all event registrations</p>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-md-3">
        <input type="text" class="form-control" placeholder="Search registrations...">
    </div>
    <div class="col-md-3">
        <select class="form-control">
            <option value="">All Events</option>
            <option value="event1">Event 1</option>
        </select>
    </div>
    <div class="col-md-3">
        <select class="form-control">
            <option value="">All Status</option>
            <option value="confirmed">Confirmed</option>
            <option value="pending">Pending</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>
    <div class="col-md-3">
        <button class="btn btn-secondary w-100">
            <i class="fas fa-filter"></i> Filter
        </button>
    </div>
</div>

<!-- Registrations Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Event</th>
                        <th>Registration Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px;">
                            <i class="fas fa-inbox" style="font-size: 40px; color: #cbd5e1; margin-bottom: 15px; display: block;"></i>
                            <p style="color: #94a3b8; margin: 0;">No registrations yet</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
