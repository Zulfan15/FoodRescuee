@extends('layouts.app')

@section('title', $donation->title . ' - FoodRescue')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card shadow">
                <!-- Image Gallery -->
                @if($donation->images && count($donation->images) > 0)
                    <div class="position-relative">
                        <div id="donationCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($donation->images as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $image) }}" 
                                             class="d-block w-100" 
                                             style="height: 400px; object-fit: cover;" 
                                             alt="{{ $donation->title }}">
                                    </div>
                                @endforeach
                            </div>
                            @if(count($donation->images) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#donationCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#donationCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            @endif
                        </div>
                        
                        <!-- Status Badge -->
                        <span class="position-absolute top-0 end-0 badge bg-{{ $donation->getStatusColor() }} m-3">
                            {{ ucfirst($donation->status) }}
                        </span>
                        
                        @if($donation->is_perishable)
                            <span class="position-absolute top-0 start-0 badge bg-warning m-3">
                                <i class="fas fa-clock me-1"></i>Perishable
                            </span>
                        @endif
                    </div>
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center position-relative" style="height: 400px;">
                        <i class="fas fa-utensils fa-5x text-muted"></i>
                        <span class="position-absolute top-0 end-0 badge bg-{{ $donation->getStatusColor() }} m-3">
                            {{ ucfirst($donation->status) }}
                        </span>
                        @if($donation->is_perishable)
                            <span class="position-absolute top-0 start-0 badge bg-warning m-3">
                                <i class="fas fa-clock me-1"></i>Perishable
                            </span>
                        @endif
                    </div>
                @endif

                <div class="card-body p-4">
                    <!-- Title and Food Type -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="h3 fw-bold mb-2">{{ $donation->title }}</h1>
                            <span class="badge bg-primary fs-6">{{ ucfirst($donation->food_type) }}</span>
                        </div>
                        @auth
                            @if(Auth::user()->role === 'donor' && Auth::id() === $donation->donor_id)
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('donations.edit', $donation) }}">
                                            <i class="fas fa-edit me-2"></i>Edit Donation
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('donations.destroy', $donation) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash me-2"></i>Delete Donation
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        @endauth
                    </div>

                    <!-- Quantity Info -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <h4 class="text-primary mb-1">{{ $donation->quantity }}</h4>
                                <small class="text-muted">Total {{ $donation->unit }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <h4 class="text-success mb-1">{{ $donation->getRemainingQuantity() }}</h4>
                                <small class="text-muted">Available {{ $donation->unit }}</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <h4 class="text-info mb-1">{{ $donation->donationRequests->where('status', 'approved')->count() }}</h4>
                                <small class="text-muted">Approved Requests</small>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5>Description</h5>
                        <p class="text-muted">{{ $donation->description }}</p>
                    </div>

                    <!-- Special Instructions -->
                    @if($donation->special_instructions)
                        <div class="mb-4">
                            <h5>Special Instructions</h5>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                {{ $donation->special_instructions }}
                            </div>
                        </div>
                    @endif

                    <!-- Time Information -->
                    <div class="mb-4">
                        <h5>Important Dates & Times</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock me-3 text-primary"></i>
                                    <div>
                                        <strong>Pickup Window</strong><br>
                                        <small class="text-muted">
                                            {{ $donation->pickup_time_start->format('M d, Y H:i') }} - 
                                            {{ $donation->pickup_time_end->format('H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-alt me-3 text-warning"></i>
                                    <div>
                                        <strong>Expiry Date</strong><br>
                                        <small class="text-muted">{{ $donation->expiry_date->format('M d, Y H:i') }}</small>
                                        @if($donation->expiry_date < now()->addDays(1))
                                            <span class="badge bg-danger ms-2">Expires Soon!</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if($donation->status === 'approved' && $donation->getRemainingQuantity() > 0)
                        <div class="mb-4">
                            @auth
                                @if(Auth::user()->role === 'recipient')
                                    <button type="button" 
                                            class="btn btn-success btn-lg w-100" 
                                            onclick="showRequestModal({{ $donation->id }}, '{{ $donation->title }}', {{ $donation->getRemainingQuantity() }}, '{{ $donation->unit }}')">
                                        <i class="fas fa-hand-paper me-2"></i>Request This Food
                                    </button>
                                @elseif(Auth::user()->role === 'donor' && Auth::id() !== $donation->donor_id)
                                    <div class="alert alert-info text-center">
                                        <i class="fas fa-info-circle me-2"></i>
                                        You cannot request your own donation
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login to Request This Food
                                </a>
                            @endauth
                        </div>
                    @elseif($donation->status === 'pending')
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-clock me-2"></i>
                            This donation is pending admin approval
                        </div>
                    @elseif($donation->getRemainingQuantity() <= 0)
                        <div class="alert alert-secondary text-center">
                            <i class="fas fa-check-circle me-2"></i>
                            All food from this donation has been claimed
                        </div>
                    @endif
                </div>
            </div>

            <!-- Requests Section (for donors) -->
            @if(Auth::check() && Auth::user()->role === 'donor' && Auth::id() === $donation->donor_id && $donation->donationRequests->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-hand-paper me-2"></i>
                            Requests for This Donation ({{ $donation->donationRequests->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($donation->donationRequests->sortBy('created_at') as $request)
                            <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $request->recipient->name }}</h6>
                                            <small class="text-muted">{{ $request->recipient->phone }}</small>
                                        </div>
                                        @if($request->is_priority)
                                            <span class="badge bg-warning ms-2">Priority</span>
                                        @endif
                                    </div>
                                    
                                    <div class="mb-2">
                                        <strong>Requested:</strong> {{ $request->requested_quantity }} {{ $donation->unit }}
                                        <br>
                                        <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                    </div>

                                    @if($request->message)
                                        <div class="mb-2">
                                            <strong>Message:</strong>
                                            <p class="text-muted mb-0">{{ $request->message }}</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="text-end">
                                    <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'pending' ? 'warning' : 'danger') }} mb-2">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                    
                                    @if($request->status === 'pending')
                                        <div class="btn-group-vertical">
                                            <form action="{{ route('donation-requests.update', $request) }}" method="POST" class="d-inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check me-1"></i>Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('donation-requests.update', $request) }}" method="POST" class="d-inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-times me-1"></i>Reject
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Donor Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-store me-2"></i>Donor Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-user fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $donation->donor->name }}</h6>
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $donation->donor->getAverageRating() ? '' : '-o' }}"></i>
                                @endfor
                                <small class="text-muted ms-1">({{ number_format($donation->donor->getAverageRating(), 1) }})</small>
                            </div>
                        </div>
                    </div>
                    
                    @auth
                        @if(Auth::user()->role === 'recipient' || Auth::user()->role === 'admin')
                            <div class="mb-3">
                                <div class="d-flex align-items-center text-muted mb-1">
                                    <i class="fas fa-phone me-2"></i>
                                    <span>{{ $donation->donor->phone }}</span>
                                </div>
                            </div>
                        @endif
                    @endauth

                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            Member since {{ $donation->donor->created_at->format('M Y') }}
                        </small>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-box me-1"></i>
                            {{ $donation->donor->foodDonations->where('status', 'completed')->count() }} completed donations
                        </small>
                    </div>
                </div>
            </div>

            <!-- Pickup Location -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>Pickup Location
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">{{ $donation->pickup_location }}</p>
                    
                    <!-- Map -->
                    <div id="pickupMap" style="height: 200px; border-radius: 8px;"></div>
                    
                    @auth
                        @if(Auth::user()->latitude && Auth::user()->longitude)
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-route me-1"></i>
                                Distance from your location: 
                                {{ number_format($donation->getDistanceFrom(Auth::user()->latitude, Auth::user()->longitude), 1) }} km
                            </small>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Safety Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Safety Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            This donation has been verified by our admin team for safety and quality.
                        </small>
                    </div>
                    
                    <ul class="list-unstyled small text-muted mb-0">
                        <li><i class="fas fa-check text-success me-2"></i>Food safety verified</li>
                        <li><i class="fas fa-check text-success me-2"></i>Donor identity confirmed</li>
                        <li><i class="fas fa-check text-success me-2"></i>Pickup location verified</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Request Modal -->
@auth
    @if(Auth::user()->role === 'recipient')
        <div class="modal fade" id="requestModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Request Food Donation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="requestForm" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <h6 id="donationTitle"></h6>
                                <p class="text-muted" id="availableQuantity"></p>
                            </div>
                            
                            <div class="mb-3">
                                <label for="requested_quantity" class="form-label">Quantity Needed</label>
                                <input type="number" class="form-control" id="requested_quantity" name="requested_quantity" min="1" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="message" class="form-label">Message (Optional)</label>
                                <textarea class="form-control" id="message" name="message" rows="3" placeholder="Tell the donor why you need this food..."></textarea>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_priority" name="is_priority">
                                <label class="form-check-label" for="is_priority">
                                    This is an emergency/priority request
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Send Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endauth
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize pickup location map
    const map = L.map('pickupMap').setView([{{ $donation->pickup_latitude }}, {{ $donation->pickup_longitude }}], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Add marker for pickup location
    L.marker([{{ $donation->pickup_latitude }}, {{ $donation->pickup_longitude }}])
        .addTo(map)
        .bindPopup(`
            <div class="text-center">
                <strong>{{ $donation->title }}</strong><br>
                <small>{{ $donation->pickup_location }}</small>
            </div>
        `);

    @auth
        @if(Auth::user()->latitude && Auth::user()->longitude)
            // Add user location marker
            L.marker([{{ Auth::user()->latitude }}, {{ Auth::user()->longitude }}], {
                icon: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                })
            })
            .addTo(map)
            .bindPopup('Your Location');
        @endif
    @endauth
});

@auth
    @if(Auth::user()->role === 'recipient')
        function showRequestModal(donationId, title, availableQuantity, unit) {
            document.getElementById('donationTitle').textContent = title;
            document.getElementById('availableQuantity').textContent = `Available: ${availableQuantity} ${unit}`;
            document.getElementById('requested_quantity').max = availableQuantity;
            document.getElementById('requestForm').action = `/donations/${donationId}/request`;
            
            const modal = new bootstrap.Modal(document.getElementById('requestModal'));
            modal.show();
        }
    @endif
@endauth
</script>
@endpush
