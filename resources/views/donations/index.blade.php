@extends('layouts.app')

@section('title', 'Food Map - FoodRescue')

@push('styles')
<style>
    .current-location-marker {
        background: none;
        border: none;
    }
    
    .current-location-icon {
        position: relative;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .current-location-icon i {
        color: #007bff;
        font-size: 12px;
        position: relative;
        z-index: 2;
        text-shadow: 0 0 4px rgba(255, 255, 255, 0.8);
    }
    
    .current-location-icon::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 16px;
        height: 16px;
        background: #007bff;
        border-radius: 50%;
        opacity: 0.3;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            transform: translate(-50%, -50%) scale(1);
            opacity: 0.6;
        }
        70% {
            transform: translate(-50%, -50%) scale(2);
            opacity: 0;
        }
        100% {
            transform: translate(-50%, -50%) scale(2);
            opacity: 0;
        }
    }
    
    #currentLocationBtn:disabled {
        opacity: 0.6;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold">Interactive Food Map</h2>
                    <p class="text-muted">Discover available food donations near you within 5km radius</p>
                </div>
                @auth
                    @if(Auth::user()->role === 'donor')
                        <a href="/createdonations" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Donation
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('donations.index') }}">
                        <div class="row align-items-end">
                            <div class="col-md-4 mb-3">
                                <label for="search" class="form-label">Search Food</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="search" 
                                           name="search" 
                                           value="{{ request('search') }}" 
                                           placeholder="Search by title, description, or food type...">
                                </div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="food_type" class="form-label">Food Type</label>
                                <select class="form-select" id="food_type" name="food_type">
                                    <option value="">All Types</option>
                                    @foreach($foodTypes as $type)
                                        <option value="{{ $type }}" {{ request('food_type') === $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-filter me-1"></i>Filter
                                </button>
                                <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Clear
                                </a>
                            </div>
                            
                            <div class="col-md-2 mb-3">
                                <button type="button" class="btn btn-outline-info w-100" onclick="toggleMapView()">
                                    <i class="fas fa-map me-1"></i>Map View
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section (Hidden by default, shown when Map View is clicked) -->
    <div class="row mb-4" id="mapSection" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>Food Donations Map
                        </h5>
                        <button id="currentLocationBtn" class="btn btn-outline-primary btn-sm" onclick="getCurrentLocation()">
                            <i class="fas fa-location-arrow me-1"></i>My Location
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="donationsMap" style="height: 500px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Count -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5>Available Food Donations</h5>
                    <p class="text-muted mb-0">Found {{ $donations->total() }} donations</p>
                </div>
                <div>
                    @auth
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Showing donations within 5km of your location
                        </small>
                    @else
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            <a href="{{ route('login') }}">Login</a> to see donations near you
                        </small>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Donations Grid -->
    <div class="row">
        @forelse($donations as $donation)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 food-card">
                    @if($donation->images && count($donation->images) > 0)
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $donation->images[0]) }}" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: cover;" 
                                 alt="{{ $donation->title }}">
                            @if($donation->is_perishable)
                                <span class="position-absolute top-0 end-0 badge bg-warning m-2">
                                    <i class="fas fa-clock me-1"></i>Perishable
                                </span>
                            @endif
                        </div>
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-utensils fa-3x text-muted"></i>
                            @if($donation->is_perishable)
                                <span class="position-absolute top-0 end-0 badge bg-warning m-2">
                                    <i class="fas fa-clock me-1"></i>Perishable
                                </span>
                            @endif
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title">{{ $donation->title }}</h5>
                            <span class="badge bg-primary">{{ $donation->food_type }}</span>
                        </div>
                        
                        <p class="card-text text-muted mb-2">{{ Str::limit($donation->description, 100) }}</p>
                        
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="border-end">
                                    <h6 class="text-primary mb-0">{{ $donation->quantity }}</h6>
                                    <small class="text-muted">{{ $donation->unit }}</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h6 class="text-success mb-0">{{ $donation->getRemainingQuantity() }}</h6>
                                <small class="text-muted">Available</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-center text-muted mb-1">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                <small>{{ Str::limit($donation->pickup_location, 40) }}</small>
                            </div>
                            <div class="d-flex align-items-center text-muted mb-1">
                                <i class="fas fa-clock me-2"></i>
                                <small>Pickup: {{ $donation->pickup_time_start->format('M d, H:i') }} - {{ $donation->pickup_time_end->format('H:i') }}</small>
                            </div>
                            <div class="d-flex align-items-center text-muted">
                                <i class="fas fa-calendar-alt me-2"></i>
                                <small>Expires: {{ $donation->expiry_date->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                    <i class="fas fa-user fa-sm"></i>
                                </div>
                                <div>
                                    <small class="fw-bold">{{ $donation->donor->name }}</small>
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $donation->donor->getAverageRating() ? '' : '-o' }}"></i>
                                        @endfor
                                        <small class="text-muted">({{ number_format($donation->donor->getAverageRating(), 1) }})</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <div class="d-flex gap-2">
                            <a href="{{ route('donations.show', $donation) }}" class="btn btn-outline-primary btn-sm flex-fill">
                                <i class="fas fa-eye me-1"></i>View Details
                            </a>
                            @auth
                                @if(Auth::user()->role === 'recipient' && $donation->getRemainingQuantity() > 0)
                                    <button type="button" 
                                            class="btn btn-success btn-sm flex-fill" 
                                            onclick="showRequestModal({{ $donation->id }}, '{{ $donation->title }}', {{ $donation->getRemainingQuantity() }}, '{{ $donation->unit }}')">
                                        <i class="fas fa-hand-paper me-1"></i>Request
                                    </button>
                                @elseif(Auth::user()->role === 'donor' && Auth::id() === $donation->donor_id)
                                    <a href="{{ route('donations.edit', $donation) }}" class="btn btn-warning btn-sm flex-fill">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-success btn-sm flex-fill">
                                    <i class="fas fa-sign-in-alt me-1"></i>Login to Request
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4>No Food Donations Found</h4>
                        <p class="text-muted">There are no available food donations matching your criteria.</p>
                        @auth
                            @if(Auth::user()->role === 'donor')
                                <a href="/createdonations" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Post First Donation
                                </a>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Join FoodRescue
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($donations->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $donations->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    @endif
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
let map;
let mapVisible = false;
let currentLocationMarker = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize empty map
    initializeMap();
});

function getCurrentLocation() {
    const btn = document.getElementById('currentLocationBtn');
    const originalContent = btn.innerHTML;
    
    // Show loading state
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Finding...';
    btn.disabled = true;
    
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                // Remove existing current location marker
                if (currentLocationMarker) {
                    map.removeLayer(currentLocationMarker);
                }
                
                // Add current location marker with custom blue icon
                currentLocationMarker = L.marker([lat, lng], {
                    icon: L.divIcon({
                        className: 'current-location-marker',
                        html: '<div class="current-location-icon"><i class="fas fa-circle"></i></div>',
                        iconSize: [20, 20],
                        iconAnchor: [10, 10]
                    })
                }).addTo(map);
                
                // Add popup to current location marker
                currentLocationMarker.bindPopup(`
                    <div class="text-center">
                        <strong><i class="fas fa-map-marker-alt text-primary me-1"></i>Your Current Location</strong><br>
                        <small class="text-muted">Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}</small>
                    </div>
                `);
                
                // Center map on current location
                map.setView([lat, lng], 15);
                
                // Restore button state
                btn.innerHTML = '<i class="fas fa-check me-1"></i>Located!';
                
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                }, 2000);
                
                // Show success message
                showToast('Location found successfully!', 'success');
            },
            function(error) {
                console.error('Error getting location:', error);
                
                // Restore button state
                btn.innerHTML = originalContent;
                btn.disabled = false;
                
                let errorMessage = 'Unable to get your location';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = 'Location access denied. Please enable location services.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = 'Location information unavailable.';
                        break;
                    case error.TIMEOUT:
                        errorMessage = 'Location request timed out.';
                        break;
                }
                
                showToast(errorMessage, 'error');
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 60000
            }
        );
    } else {
        btn.innerHTML = originalContent;
        btn.disabled = false;
        showToast('Geolocation is not supported by this browser.', 'error');
    }
}

function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 5000);
}

function toggleMapView() {
    const mapSection = document.getElementById('mapSection');
    
    if (mapVisible) {
        mapSection.style.display = 'none';
        mapVisible = false;
        document.querySelector('[onclick="toggleMapView()"]').innerHTML = '<i class="fas fa-map me-1"></i>Map View';
    } else {
        mapSection.style.display = 'block';
        mapVisible = true;
        document.querySelector('[onclick="toggleMapView()"]').innerHTML = '<i class="fas fa-list me-1"></i>List View';
        
        // Reinitialize map when shown
        setTimeout(() => {
            map.invalidateSize();
            loadDonationsOnMap();
        }, 100);
    }
}

function initializeMap() {
    map = L.map('donationsMap').setView([-7.9666, 112.6326], 12); // Malang coordinates
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
}

function loadDonationsOnMap() {
    // Add markers for each donation
    @foreach($donations as $donation)
        @if($donation->pickup_latitude && $donation->pickup_longitude)
            L.marker([{{ $donation->pickup_latitude }}, {{ $donation->pickup_longitude }}])
                .addTo(map)
                .bindPopup(`
                    <div class="popup-content">
                        <h6>{{ $donation->title }}</h6>
                        <p class="mb-1"><strong>{{ $donation->quantity }} {{ $donation->unit }}</strong> ({{ $donation->getRemainingQuantity() }} available)</p>
                        <p class="mb-1"><small>{{ $donation->pickup_location }}</small></p>
                        <p class="mb-1"><small>By: {{ $donation->donor->name }}</small></p>
                        <div class="d-flex gap-1 mt-2">
                            <a href="{{ route('donations.show', $donation) }}" class="btn btn-sm btn-primary">View</a>
                            @auth
                                @if(Auth::user()->role === 'recipient' && $donation->getRemainingQuantity() > 0)
                                    <button class="btn btn-sm btn-success" onclick="showRequestModal({{ $donation->id }}, '{{ $donation->title }}', {{ $donation->getRemainingQuantity() }}, '{{ $donation->unit }}')">Request</button>
                                @endif
                            @endauth
                        </div>
                    </div>
                `);
        @endif
    @endforeach
}

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
