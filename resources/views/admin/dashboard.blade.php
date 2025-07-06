@extends('layouts.app')

@section('title', 'Admin Dashboard - FoodRescue')

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
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.donations') }}">
                            <i class="fas fa-check-circle me-2"></i>Approve Donations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users') }}">
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
                    <h2 class="fw-bold">Admin Dashboard</h2>
                    <p class="text-muted">Manage and monitor the FoodRescue platform.</p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-utensils fa-2x mb-2"></i>
                            <h3>{{ $stats['total_donations'] }}</h3>
                            <p class="mb-0">Total Donations</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-warning text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <h3>{{ $stats['pending_donations'] }}</h3>
                            <p class="mb-0">Pending Approvals</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <h3>{{ $stats['total_users'] }}</h3>
                            <p class="mb-0">Total Users</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-hand-paper fa-2x mb-2"></i>
                            <h3>{{ $stats['total_requests'] }}</h3>
                            <p class="mb-0">Total Requests</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-check fa-2x mb-2"></i>
                            <h3>{{ $stats['completed_donations'] }}</h3>
                            <p class="mb-0">Completed Donations</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-secondary text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-user-plus fa-2x mb-2"></i>
                            <h3>{{ $stats['active_users'] }}</h3>
                            <p class="mb-0">New Users (30 days)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.donations') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-check-circle me-2"></i>Review Pending Donations
                                </a>
                                <a href="{{ route('admin.users') }}" class="btn btn-outline-success">
                                    <i class="fas fa-users me-2"></i>Manage Users
                                </a>
                                <a href="{{ route('admin.reports') }}" class="btn btn-outline-info">
                                    <i class="fas fa-chart-bar me-2"></i>View Reports
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">System Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="text-success">
                                        <i class="fas fa-check-circle fa-2x"></i>
                                        <p class="mb-0 mt-1">Database</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-success">
                                        <i class="fas fa-check-circle fa-2x"></i>
                                        <p class="mb-0 mt-1">Storage</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-success">
                                        <i class="fas fa-check-circle fa-2x"></i>
                                        <p class="mb-0 mt-1">API</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Recent Donations</h5>
                        </div>
                        <div class="card-body">
                            @if($recent_donations->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Donor</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recent_donations->take(5) as $donation)
                                                <tr>
                                                    <td>{{ Str::limit($donation->title, 20) }}</td>
                                                    <td>{{ $donation->donor->name }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $donation->status === 'approved' ? 'success' : ($donation->status === 'pending' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($donation->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $donation->created_at->format('M d') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted mb-0">No donations yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Recent Users</h5>
                        </div>
                        <div class="card-body">
                            @if($recent_users->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Joined</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recent_users->take(5) as $user)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ Str::limit($user->email, 20) }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'donor' ? 'primary' : 'success') }}">
                                                            {{ ucfirst($user->role) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $user->created_at->format('M d') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted mb-0">No users yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
