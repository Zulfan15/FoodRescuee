@extends('layouts.app')

@section('title', 'Approve Donations - Admin')

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
                        <a class="nav-link active" href="{{ route('admin.donations') }}">
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
                    <h2 class="fw-bold">Donation Management</h2>
                    <p class="text-muted">Review and approve food donations submitted by donors.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filter Tabs -->
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="donationTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-donations" type="button" role="tab">
                                All Donations ({{ $donations->total() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending-donations" type="button" role="tab">
                                Pending ({{ $donations->where('status', 'pending')->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved-donations" type="button" role="tab">
                                Approved ({{ $donations->where('status', 'approved')->count() }})
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="donationTabsContent">
                        <!-- All Donations Tab -->
                        <div class="tab-pane fade show active" id="all-donations" role="tabpanel">
                            @if($donations->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Title</th>
                                                <th>Donor</th>
                                                <th>Quantity</th>
                                                <th>Expiry Date</th>
                                                <th>Status</th>
                                                <th>Requests</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($donations as $donation)
                                                <tr>
                                                    <td>#{{ $donation->id }}</td>
                                                    <td>
                                                        <strong>{{ $donation->title }}</strong>
                                                        @if($donation->description)
                                                            <br><small class="text-muted">{{ Str::limit($donation->description, 50) }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $donation->donor->name }}
                                                        <br><small class="text-muted">{{ $donation->donor->email }}</small>
                                                    </td>
                                                    <td>{{ $donation->quantity }} {{ $donation->unit }}</td>
                                                    <td>
                                                        {{ $donation->expiry_date ? $donation->expiry_date->format('M d, Y') : 'N/A' }}
                                                        @if($donation->expiry_date && $donation->expiry_date->isPast())
                                                            <br><small class="text-danger">Expired</small>
                                                        @elseif($donation->expiry_date && $donation->expiry_date->diffInDays() <= 1)
                                                            <br><small class="text-warning">Expires soon</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $donation->status === 'approved' ? 'success' : ($donation->status === 'pending' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($donation->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $donation->donationRequests->count() }}</td>
                                                    <td>{{ $donation->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        @if($donation->status === 'pending')
                                                            <div class="btn-group btn-group-sm" role="group">
                                                                <form action="{{ route('admin.donations.approve', $donation) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this donation?')">
                                                                        <i class="fas fa-check"></i>
                                                                    </button>
                                                                </form>
                                                                <form action="{{ route('admin.donations.reject', $donation) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject this donation?')">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @else
                                                            <button class="btn btn-outline-secondary btn-sm" disabled>
                                                                {{ ucfirst($donation->status) }}
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center">
                                    {{ $donations->links() }}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No donations found</h5>
                                    <p class="text-muted">When users submit food donations, they will appear here for review.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <h4>{{ $donations->where('status', 'pending')->count() }}</h4>
                            <p class="mb-0">Pending Review</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-check fa-2x mb-2"></i>
                            <h4>{{ $donations->where('status', 'approved')->count() }}</h4>
                            <p class="mb-0">Approved</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-times fa-2x mb-2"></i>
                            <h4>{{ $donations->where('status', 'rejected')->count() }}</h4>
                            <p class="mb-0">Rejected</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-list fa-2x mb-2"></i>
                            <h4>{{ $donations->total() }}</h4>
                            <p class="mb-0">Total Donations</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-refresh pending donations every 30 seconds
setInterval(function() {
    if (document.querySelector('#pending-tab').classList.contains('active')) {
        location.reload();
    }
}, 30000);
</script>
@endsection
