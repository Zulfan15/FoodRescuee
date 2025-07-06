@extends('layouts.app')

@section('title', 'Manage Users - Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Admin Sidebar -->
        <div class="col-md-3">
            <div class="sidebar bg-light p-3 rounded">
                <div class="text-center mb-4">
                    <div class="bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-shield fa-2x"></i>
                    </div>
                    <h5 class="mt-2 mb-1">{{ auth()->user()->name }}</h5>
                    <span class="badge bg-danger">Admin</span>
                </div>

                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.donations') }}">
                            <i class="fas fa-check-circle me-2"></i>Approve Donations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.users') }}">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.reports') }}">
                            <i class="fas fa-chart-bar me-2"></i>Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold">User Management</h2>
                    <p class="text-muted">Manage and monitor all platform users.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- User Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <h4>{{ $users->total() }}</h4>
                            <p class="mb-0">Total Users</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-hand-holding-heart fa-2x mb-2"></i>
                            <h4>{{ $users->where('role', 'donor')->count() }}</h4>
                            <p class="mb-0">Donors</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-hands-helping fa-2x mb-2"></i>
                            <h4>{{ $users->where('role', 'recipient')->count() }}</h4>
                            <p class="mb-0">Recipients</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-user-shield fa-2x mb-2"></i>
                            <h4>{{ $users->where('role', 'admin')->count() }}</h4>
                            <p class="mb-0">Admins</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Users</h5>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Verified</th>
                                        <th>Donations</th>
                                        <th>Requests</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>#{{ $user->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'donor' ? 'primary' : 'success') }} text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                                        <i class="fas fa-{{ $user->role === 'admin' ? 'user-shield' : ($user->role === 'donor' ? 'hand-holding-heart' : 'hands-helping') }}"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $user->name }}</strong>
                                                        @if($user->phone)
                                                            <br><small class="text-muted">{{ $user->phone }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'donor' ? 'primary' : 'success') }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($user->email_verified_at)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check"></i> Verified
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-clock"></i> Pending
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->role === 'donor')
                                                    <span class="badge bg-info">{{ $user->foodDonations->count() }}</span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->role === 'recipient')
                                                    <span class="badge bg-info">{{ $user->donationRequests->count() }}</span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $user->created_at->format('M d, Y') }}
                                                <br><small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                @if(!$user->email_verified_at && $user->id !== auth()->id())
                                                    <form action="{{ route('admin.users.verify', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Verify this user?')">
                                                            <i class="fas fa-check"></i> Verify
                                                        </button>
                                                    </form>
                                                @else
                                                    @if($user->id === auth()->id())
                                                        <span class="badge bg-secondary">You</span>
                                                    @else
                                                        <span class="badge bg-success">Verified</span>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No users found</h5>
                            <p class="text-muted">When users register on the platform, they will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Recent Registrations</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $recent_users = $users->where('created_at', '>=', now()->subDays(7))->take(5);
                            @endphp
                            @if($recent_users->count() > 0)
                                @foreach($recent_users as $user)
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'donor' ? 'primary' : 'success') }} text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                            <i class="fas fa-{{ $user->role === 'admin' ? 'user-shield' : ($user->role === 'donor' ? 'hand-holding-heart' : 'hands-helping') }}"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <strong>{{ $user->name }}</strong>
                                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'donor' ? 'primary' : 'success') }} ms-2">{{ ucfirst($user->role) }}</span>
                                            <br><small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted mb-0">No new registrations this week.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">User Activity Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <h4 class="text-primary">{{ $users->where('created_at', '>=', now()->subDays(30))->count() }}</h4>
                                    <p class="mb-0 small">New users this month</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <h4 class="text-success">{{ $users->where('email_verified_at', '!=', null)->count() }}</h4>
                                    <p class="mb-0 small">Verified users</p>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-info">{{ $users->where('created_at', '>=', now()->subDays(7))->count() }}</h4>
                                    <p class="mb-0 small">New users this week</p>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-warning">{{ $users->where('email_verified_at', null)->count() }}</h4>
                                    <p class="mb-0 small">Pending verification</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
