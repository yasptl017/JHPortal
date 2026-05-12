@extends('admin.layouts.app')

@section('title', 'Users Management')
@section('breadcrumb', 'Users')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div>
        <div class="page-title">
            <i class="fas fa-users" style="margin-right: 10px; color: var(--primary-color);"></i>
            Users Management
        </div>
        <p class="page-subtitle">Manage portal users and permissions</p>
    </div>
    <a href="#" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Add User
    </a>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-md-3">
        <input type="text" class="form-control" placeholder="Search users...">
    </div>
    <div class="col-md-3">
        <select class="form-control">
            <option value="">All Roles</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
            <option value="moderator">Moderator</option>
        </select>
    </div>
    <div class="col-md-3">
        <select class="form-control">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>
    <div class="col-md-3">
        <button class="btn btn-secondary w-100">
            <i class="fas fa-filter"></i> Filter
        </button>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px;">
                            <i class="fas fa-inbox" style="font-size: 40px; color: #cbd5e1; margin-bottom: 15px; display: block;"></i>
                            <p style="color: #94a3b8; margin: 0;">No users created yet</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
