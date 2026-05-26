@extends('admin.layouts.app')
@section('title', 'Users')
@section('breadcrumb', 'Users')

@section('content')
<div class="mb-4">
    <h1 class="page-title">User Management</h1>
    <p class="page-subtitle">View registered community members</p>
</div>

<div class="card">
    <div class="card-body">
        @if($users->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No registered users yet</h5>
                <p class="text-muted">Users will appear here when they create accounts.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Phone</th>
                            <th>Registrations</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#7c3aed);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight:600;">{{ $user->name }}</div>
                                            <div style="font-size:0.8rem;color:#64748b;">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="color:#64748b;">{{ $user->phone ?? '-' }}</td>
                                <td><span class="badge bg-primary">{{ $user->event_registrations_count }}</span></td>
                                <td style="font-size:0.85rem;color:#64748b;">{{ $user->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
