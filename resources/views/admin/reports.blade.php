@extends('layouts.app')

@section('title', 'Reports - Admin')

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
                        <a class="nav-link" href="{{ route('admin.users') }}">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.reports') }}">
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
                    <h2 class="fw-bold">Platform Reports</h2>
                    <p class="text-muted">Comprehensive analytics and statistics for FoodRescue platform.</p>
                </div>
                <div>
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print Report
                    </button>
                </div>
            </div>

            <!-- Overall Statistics -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-utensils fa-2x mb-2"></i>
                            <h3>{{ number_format($total_stats['total_donations']) }}</h3>
                            <p class="mb-0">Total Donations</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <h3>{{ number_format($total_stats['total_users']) }}</h3>
                            <p class="mb-0">Total Users</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-hand-paper fa-2x mb-2"></i>
                            <h3>{{ number_format($total_stats['total_requests']) }}</h3>
                            <p class="mb-0">Total Requests</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-weight fa-2x mb-2"></i>
                            <h3>{{ number_format($total_stats['food_saved_kg']) }} kg</h3>
                            <p class="mb-0">Food Saved</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Donation Statistics -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Donation Status Overview</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <h4 class="text-success">{{ $total_stats['completed_donations'] }}</h4>
                                    <p class="mb-0">Completed</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <h4 class="text-warning">{{ $total_stats['pending_donations'] }}</h4>
                                    <p class="mb-0">Pending</p>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-primary">{{ $total_stats['approved_donations'] }}</h4>
                                    <p class="mb-0">Approved</p>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-danger">{{ $total_stats['rejected_donations'] }}</h4>
                                    <p class="mb-0">Rejected</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">User Distribution</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4 mb-3">
                                    <h4 class="text-primary">{{ $total_stats['donors'] }}</h4>
                                    <p class="mb-0">Donors</p>
                                </div>
                                <div class="col-4 mb-3">
                                    <h4 class="text-success">{{ $total_stats['recipients'] }}</h4>
                                    <p class="mb-0">Recipients</p>
                                </div>
                                <div class="col-4 mb-3">
                                    <h4 class="text-danger">{{ $total_stats['admins'] }}</h4>
                                    <p class="mb-0">Admins</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Impact Metrics -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Impact Metrics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3 mb-3">
                                    <div class="bg-light p-3 rounded">
                                        <i class="fas fa-leaf fa-2x text-success mb-2"></i>
                                        <h4>{{ number_format($total_stats['food_saved_kg']) }} kg</h4>
                                        <p class="mb-0">Food Rescued</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="bg-light p-3 rounded">
                                        <i class="fas fa-star fa-2x text-warning mb-2"></i>
                                        <h4>{{ number_format($total_stats['avg_rating'] ?? 0, 1) }}/5</h4>
                                        <p class="mb-0">Average Rating</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="bg-light p-3 rounded">
                                        <i class="fas fa-comments fa-2x text-info mb-2"></i>
                                        <h4>{{ number_format($total_stats['total_reviews']) }}</h4>
                                        <p class="mb-0">Total Reviews</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="bg-light p-3 rounded">
                                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                        <h4>{{ number_format($total_stats['completed_requests']) }}</h4>
                                        <p class="mb-0">Completed Requests</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Trends -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Monthly Trends (Last 12 Months)</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>New Donations</th>
                                            <th>New Users</th>
                                            <th>New Requests</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($monthly_stats as $month)
                                            <tr>
                                                <td><strong>{{ $month['month'] }}</strong></td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $month['donations'] }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success">{{ $month['users'] }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning">{{ $month['requests'] }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Performers -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Top Donors</h5>
                        </div>
                        <div class="card-body">
                            @if($top_donors->count() > 0)
                                @foreach($top_donors as $index => $donor)
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-{{ $index < 3 ? ['warning', 'secondary', 'dark'][$index] : 'light' }} text-{{ $index < 3 ? 'white' : 'dark' }} rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                            <strong>#{{ $index + 1 }}</strong>
                                        </div>
                                        <div class="flex-grow-1">
                                            <strong>{{ $donor->name }}</strong>
                                            <br><small class="text-muted">{{ $donor->donations_count }} donations</small>
                                        </div>
                                        @if($index < 3)
                                            <i class="fas fa-{{ $index === 0 ? 'crown' : ($index === 1 ? 'medal' : 'award') }} text-{{ $index === 0 ? 'warning' : ($index === 1 ? 'secondary' : 'dark') }}"></i>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted mb-0">No donors yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Most Active Recipients</h5>
                        </div>
                        <div class="card-body">
                            @if($top_recipients->count() > 0)
                                @foreach($top_recipients as $index => $recipient)
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-{{ $index < 3 ? ['warning', 'secondary', 'dark'][$index] : 'light' }} text-{{ $index < 3 ? 'white' : 'dark' }} rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                            <strong>#{{ $index + 1 }}</strong>
                                        </div>
                                        <div class="flex-grow-1">
                                            <strong>{{ $recipient->name }}</strong>
                                            <br><small class="text-muted">{{ $recipient->requests_count }} requests</small>
                                        </div>
                                        @if($index < 3)
                                            <i class="fas fa-{{ $index === 0 ? 'crown' : ($index === 1 ? 'medal' : 'award') }} text-{{ $index === 0 ? 'warning' : ($index === 1 ? 'secondary' : 'dark') }}"></i>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted mb-0">No recipients yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5 class="mb-3">Platform Summary</h5>
                            <p class="mb-0">
                                FoodRescue has successfully facilitated <strong>{{ number_format($total_stats['total_donations']) }}</strong> food donations, 
                                serving <strong>{{ number_format($total_stats['total_users']) }}</strong> registered users. 
                                We've rescued <strong>{{ number_format($total_stats['food_saved_kg']) }} kg</strong> of food from going to waste,
                                with an average user satisfaction rating of <strong>{{ number_format($total_stats['avg_rating'] ?? 0, 1) }}/5</strong>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .sidebar, .btn, .nav-pills {
        display: none !important;
    }
    .col-md-9 {
        width: 100% !important;
    }
}
</style>
@endsection
