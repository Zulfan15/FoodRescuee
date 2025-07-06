@extends('layouts.app')

@section('title', 'My Requests - FoodRescue')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold">
                        @if(Auth::user()->role === 'recipient')
                            My Food Requests
                        @elseif(Auth::user()->role === 'donor')
                            Requests for My Donations
                        @else
                            All Donation Requests
                        @endif
                    </h2>
                    <p class="text-muted">
                        @if(Auth::user()->role === 'recipient')
                            Track your food donation requests and their status
                        @elseif(Auth::user()->role === 'donor')
                            Manage requests from recipients for your food donations
                        @else
                            Overview of all donation requests in the system
                        @endif
                    </p>
                </div>
                
                @if(Auth::user()->role === 'recipient')
                    <a href="{{ route('donations.index') }}" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Find More Food
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Status Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a href="{{ route('donation-requests.index') }}" 
                           class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">
                            All Requests
                        </a>
                        <a href="{{ route('donation-requests.index', ['status' => 'pending']) }}" 
                           class="btn {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                            Pending
                        </a>
                        <a href="{{ route('donation-requests.index', ['status' => 'approved']) }}" 
                           class="btn {{ request('status') === 'approved' ? 'btn-success' : 'btn-outline-success' }}">
                            Approved
                        </a>
                        <a href="{{ route('donation-requests.index', ['status' => 'completed']) }}" 
                           class="btn {{ request('status') === 'completed' ? 'btn-info' : 'btn-outline-info' }}">
                            Completed
                        </a>
                        <a href="{{ route('donation-requests.index', ['status' => 'rejected']) }}" 
                           class="btn {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
                            Rejected
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests List -->
    <div class="row">
        @forelse($requests as $request)
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">{{ $request->foodDonation->title }}</h6>
                            <small class="text-muted">
                                Request #{{ $request->id }} • {{ $request->created_at->format('M d, Y H:i') }}
                            </small>
                        </div>
                        <span class="badge bg-{{ $this->getStatusColor($request->status) }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </div>
                    
                    <div class="card-body">
                        <!-- Food Details -->
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <strong>Requested:</strong><br>
                                    <span class="text-primary">{{ $request->requested_quantity }} {{ $request->foodDonation->unit }}</span>
                                </div>
                                <div class="col-6">
                                    <strong>Food Type:</strong><br>
                                    <span class="badge bg-secondary">{{ ucfirst($request->foodDonation->food_type) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Participant Info -->
                        <div class="mb-3">
                            @if(Auth::user()->role === 'recipient' || Auth::user()->role === 'admin')
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-store me-2 text-primary"></i>
                                    <div>
                                        <strong>Donor:</strong> {{ $request->foodDonation->donor->name }}<br>
                                        <small class="text-muted">{{ $request->foodDonation->donor->phone }}</small>
                                    </div>
                                </div>
                            @endif
                            
                            @if(Auth::user()->role === 'donor' || Auth::user()->role === 'admin')
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user me-2 text-success"></i>
                                    <div>
                                        <strong>Recipient:</strong> {{ $request->recipient->name }}<br>
                                        <small class="text-muted">{{ $request->recipient->phone }}</small>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Location & Time -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center text-muted mb-1">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                <small>{{ Str::limit($request->foodDonation->pickup_location, 50) }}</small>
                            </div>
                            <div class="d-flex align-items-center text-muted mb-1">
                                <i class="fas fa-clock me-2"></i>
                                <small>
                                    Pickup: {{ $request->foodDonation->pickup_time_start->format('M d, H:i') }} - 
                                    {{ $request->foodDonation->pickup_time_end->format('H:i') }}
                                </small>
                            </div>
                            @if($request->foodDonation->expiry_date)
                                <div class="d-flex align-items-center text-muted">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    <small>Expires: {{ $request->foodDonation->expiry_date->format('M d, Y H:i') }}</small>
                                </div>
                            @endif
                        </div>

                        <!-- Message -->
                        @if($request->message)
                            <div class="mb-3">
                                <strong>Message from {{ Auth::user()->role === 'donor' ? 'Recipient' : 'You' }}:</strong>
                                <p class="text-muted mb-0 mt-1">{{ $request->message }}</p>
                            </div>
                        @endif

                        <!-- Priority Badge -->
                        @if($request->is_priority)
                            <div class="mb-3">
                                <span class="badge bg-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Priority Request
                                </span>
                            </div>
                        @endif

                        <!-- Status-specific Info -->
                        @if($request->status === 'approved' && $request->approved_at)
                            <div class="alert alert-success mb-3">
                                <i class="fas fa-check-circle me-1"></i>
                                Approved on {{ $request->approved_at->format('M d, Y H:i') }}
                            </div>
                        @endif

                        @if($request->picked_up_at)
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-handshake me-1"></i>
                                Completed on {{ $request->picked_up_at->format('M d, Y H:i') }}
                            </div>
                        @endif

                        @if($request->pickup_notes)
                            <div class="mb-3">
                                <strong>Pickup Notes:</strong>
                                <p class="text-muted mb-0 mt-1">{{ $request->pickup_notes }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="card-footer bg-transparent">
                        <div class="d-flex gap-2">
                            <!-- View Details -->
                            <a href="{{ route('donations.show', $request->foodDonation) }}" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>View Food Details
                            </a>

                            <!-- Donor Actions -->
                            @if(Auth::user()->role === 'donor' && $request->status === 'pending')
                                <button type="button" 
                                        class="btn btn-success btn-sm" 
                                        onclick="showApprovalModal({{ $request->id }}, '{{ $request->foodDonation->title }}', {{ $request->requested_quantity }}, '{{ $request->foodDonation->unit }}')">
                                    <i class="fas fa-check me-1"></i>Approve
                                </button>
                                <button type="button" 
                                        class="btn btn-danger btn-sm" 
                                        onclick="showRejectionModal({{ $request->id }})">
                                    <i class="fas fa-times me-1"></i>Reject
                                </button>
                            @endif

                            <!-- Recipient Actions -->
                            @if(Auth::user()->role === 'recipient')
                                @if($request->status === 'pending')
                                    <form action="{{ route('donation-requests.destroy', $request) }}" 
                                          method="POST" 
                                          class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to cancel this request?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-times me-1"></i>Cancel
                                        </button>
                                    </form>
                                @elseif($request->status === 'approved')
                                    <form action="{{ route('donation-requests.update', $request) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="btn btn-info btn-sm">
                                            <i class="fas fa-check-double me-1"></i>Mark as Picked Up
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h4>No Requests Found</h4>
                        <p class="text-muted">
                            @if(Auth::user()->role === 'recipient')
                                You haven't made any food requests yet.
                            @elseif(Auth::user()->role === 'donor')
                                No one has requested your food donations yet.
                            @else
                                No donation requests in the system yet.
                            @endif
                        </p>
                        @if(Auth::user()->role === 'recipient')
                            <a href="{{ route('donations.index') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Find Food Donations
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($requests->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $requests->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Approval Modal -->
@if(Auth::user()->role === 'donor')
    <div class="modal fade" id="approvalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="approvalForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <h6 id="approvalTitle"></h6>
                            <p class="text-muted" id="approvalQuantity"></p>
                        </div>
                        
                        <input type="hidden" name="status" value="approved">
                        
                        <div class="mb-3">
                            <label for="pickup_notes" class="form-label">Pickup Instructions (Optional)</label>
                            <textarea class="form-control" id="pickup_notes" name="pickup_notes" rows="3" 
                                      placeholder="Any special instructions for the recipient..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Approve Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div class="modal fade" id="rejectionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="rejectionForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <p>Are you sure you want to reject this request?</p>
                        
                        <input type="hidden" name="status" value="rejected">
                        
                        <div class="mb-3">
                            <label for="rejection_notes" class="form-label">Reason for Rejection (Optional)</label>
                            <textarea class="form-control" id="rejection_notes" name="pickup_notes" rows="3" 
                                      placeholder="Let the recipient know why..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
@if(Auth::user()->role === 'donor')
    function showApprovalModal(requestId, title, quantity, unit) {
        document.getElementById('approvalTitle').textContent = title;
        document.getElementById('approvalQuantity').textContent = `Quantity: ${quantity} ${unit}`;
        document.getElementById('approvalForm').action = `/donation-requests/${requestId}`;
        
        const modal = new bootstrap.Modal(document.getElementById('approvalModal'));
        modal.show();
    }

    function showRejectionModal(requestId) {
        document.getElementById('rejectionForm').action = `/donation-requests/${requestId}`;
        
        const modal = new bootstrap.Modal(document.getElementById('rejectionModal'));
        modal.show();
    }
@endif

// Helper function for status colors (simulate blade function)
function getStatusColor(status) {
    const colors = {
        'pending': 'warning',
        'approved': 'success',
        'rejected': 'danger',
        'completed': 'info'
    };
    return colors[status] || 'secondary';
}
</script>
@endpush

@php
    // Helper function for status colors
    function getStatusColor($status) {
        return match($status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'completed' => 'info',
            default => 'secondary',
        };
    }
@endphp
