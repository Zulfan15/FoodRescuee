@extends('layouts.app')

@section('title', 'Dashboard - FoodRescue')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="sidebar bg-light p-3 rounded">
                <div class="text-center mb-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-user fa-2x"></i>
                    </div>
                    <h5 class="mt-2 mb-1">{{ $user->name }}</h5>
                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'donor' ? 'primary' : 'success') }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>

                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    
                    @if($user->role === 'donor')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('donations.create') }}">
                                <i class="fas fa-plus me-2"></i>New Donation
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('donations.index') }}">
                                <i class="fas fa-list me-2"></i>My Donations
                            </a>
                        </li>
                    @endif

                    @if($user->role === 'recipient')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('donations.index') }}">
                                <i class="fas fa-search me-2"></i>Find Food
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('donation-requests.index') }}">
                                <i class="fas fa-hand-paper me-2"></i>My Requests
                            </a>
                        </li>
                    @endif

                    @if($user->role === 'admin')
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
                    @endif

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.profile') }}">
                            <i class="fas fa-user-edit me-2"></i>Profile
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold">Welcome back, {{ $user->name }}!</h2>
                    <p class="text-muted">Here's what's happening with your {{ $user->role }} account.</p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                @if($user->role === 'donor')
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-utensils fa-2x mb-2"></i>
                                <h3>{{ $data['total_donations'] }}</h3>
                                <p class="mb-0">Total Donations</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <h3>{{ $data['active_donations'] }}</h3>
                                <p class="mb-0">Active Donations</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-info text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-handshake fa-2x mb-2"></i>
                                <h3>{{ $data['completed_donations'] }}</h3>
                                <p class="mb-0">Completed</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                                <h3>{{ $data['pending_requests'] }}</h3>
                                <p class="mb-0">Pending Requests</p>
                            </div>
                        </div>
                    </div>
                @elseif($user->role === 'recipient')
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-hand-paper fa-2x mb-2"></i>
                                <h3>{{ $data['total_requests'] }}</h3>
                                <p class="mb-0">Total Requests</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <h3>{{ $data['approved_requests'] }}</h3>
                                <p class="mb-0">Approved</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-info text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-handshake fa-2x mb-2"></i>
                                <h3>{{ $data['completed_requests'] }}</h3>
                                <p class="mb-0">Completed</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                                <h3>{{ count($data['nearby_donations']) }}</h3>
                                <p class="mb-0">Nearby Food</p>
                            </div>
                        </div>
                    </div>
                @elseif($user->role === 'admin')
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <h3>{{ $data['total_users'] }}</h3>
                                <p class="mb-0">Total Users</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-hand-holding-heart fa-2x mb-2"></i>
                                <h3>{{ $data['total_donors'] }}</h3>
                                <p class="mb-0">Donors</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-info text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-hands-helping fa-2x mb-2"></i>
                                <h3>{{ $data['total_recipients'] }}</h3>
                                <p class="mb-0">Recipients</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                                <h3>{{ $data['pending_donations'] }}</h3>
                                <p class="mb-0">Pending Approvals</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                @if($user->role === 'donor')
                                    Recent Donations
                                @elseif($user->role === 'recipient')
                                    Recent Requests
                                @else
                                    Recent System Activity
                                @endif
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($user->role === 'donor' && isset($data['recent_donations']))
                                @foreach($data['recent_donations'] as $donation)
                                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                        <div>
                                            <h6 class="mb-1">{{ $donation->title }}</h6>
                                            <p class="text-muted mb-1">{{ $donation->quantity }} {{ $donation->unit }}</p>
                                            <small class="text-muted">{{ $donation->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-{{ $donation->status === 'approved' ? 'success' : ($donation->status === 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($donation->status) }}
                                            </span>
                                            @if($donation->donationRequests->count() > 0)
                                                <p class="mb-0 mt-1">
                                                    <small class="text-muted">{{ $donation->donationRequests->count() }} requests</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @elseif($user->role === 'recipient' && isset($data['recent_requests']))
                                @foreach($data['recent_requests'] as $request)
                                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                        <div>
                                            <h6 class="mb-1">{{ $request->foodDonation->title }}</h6>
                                            <p class="text-muted mb-1">Requested: {{ $request->requested_quantity }} {{ $request->foodDonation->unit }}</p>
                                            <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @elseif($user->role === 'admin' && isset($data['recent_donations']))
                                @foreach($data['recent_donations'] as $donation)
                                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                        <div>
                                            <h6 class="mb-1">{{ $donation->title }}</h6>
                                            <p class="text-muted mb-1">by {{ $donation->donor->name }} - {{ $donation->quantity }} {{ $donation->unit }}</p>
                                            <small class="text-muted">{{ $donation->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-{{ $donation->status === 'approved' ? 'success' : ($donation->status === 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($donation->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted text-center py-4">No recent activity to display.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($user->role === 'donor')
                                    <div class="col-md-6 mb-3">
                                        <a href="/direct-donations-create" class="btn btn-primary w-100">
                                            <i class="fas fa-plus me-2"></i>Create New Donation
                                        </a>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <a href="{{ route('donations.index') }}" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-list me-2"></i>View All My Donations
                                        </a>
                                    </div>
                                @elseif($user->role === 'recipient')
                                    <div class="col-md-6 mb-3">
                                        <a href="{{ route('donations.index') }}" class="btn btn-primary w-100">
                                            <i class="fas fa-search me-2"></i>Find Food Near Me
                                        </a>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <a href="{{ route('donation-requests.index') }}" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-hand-paper me-2"></i>My Food Requests
                                        </a>
                                    </div>
                                @elseif($user->role === 'admin')
                                    <div class="col-md-4 mb-3">
                                        <a href="{{ route('admin.donations') }}" class="btn btn-primary w-100">
                                            <i class="fas fa-check-circle me-2"></i>Review Donations
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="{{ route('admin.users') }}" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-users me-2"></i>Manage Users
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary w-100">
                                            <i class="fas fa-chart-bar me-2"></i>View Reports
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
