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
    
    /* Enhanced styles for nearby donations */
    .food-card {
        transition: all 0.3s ease;
    }
    
    .food-card.nearby {
        border: 2px solid #28a745 !important;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2) !important;
        transform: translateY(-2px);
    }
    
    .food-card.distant {
        opacity: 0.6;
        transform: none;
    }
    
    .distance-badge {
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 15px !important;
        padding: 0.25rem 0.5rem !important;
    }
    
    /* Custom marker styles */
    .custom-div-icon {
        background: transparent !important;
        border: none !important;
    }
    
    /* Radius circle info */
    .radius-info {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(0, 123, 255, 0.9);
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        z-index: 1000;
    }
    
    /* Real-time animation styles */
    @keyframes slideInUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: scale(1);
        }
        to {
            opacity: 0;
            transform: scale(0.8);
        }
    }
    
    @keyframes flash {
        0%, 100% { opacity: 0; }
        50% { opacity: 1; }
    }
    
    @keyframes bounce {
        0%, 20%, 60%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        80% {
            transform: translateY(-5px);
        }
    }
    
    .new-donation {
        animation: slideInUp 0.6s ease-out;
    }
    
    .new-marker {
        animation: bounce 0.6s ease-in-out;
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
                                <button type="button" 
                                        class="btn btn-outline-info w-100" 
                                        onclick="toggleMapView()" 
                                        id="mapToggleBtn">
                                    <i class="fas fa-map me-1"></i>Map View
                                </button>
                            </div>
                            
                            <div class="col-md-12 mb-2">
                                <div id="debugPanel" style="display: none; background: #f8f9fa; padding: 10px; border-radius: 5px; font-size: 12px;">
                                    <strong>🐛 Debug Info:</strong>
                                    <div id="debugInfo">Loading...</div>
                                    <button type="button" class="btn btn-sm btn-secondary mt-1" onclick="toggleDebug()">Hide Debug</button>
                                    <button type="button" class="btn btn-sm btn-primary mt-1 ms-1" onclick="testMapToggle()">Test Map Toggle</button>
                                    <button type="button" class="btn btn-sm btn-success mt-1 ms-1" onclick="window.forceToggleMap()">Force Toggle</button>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleDebug()">
                                    <i class="fas fa-bug me-1"></i>Debug
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
                            <small class="text-muted">
                                <span id="radiusInfo" style="display: none;">
                                    <i class="fas fa-circle-dot me-1"></i>5km radius
                                </span>
                            </small>
                        </h5>
                        <div class="d-flex gap-2">
                            <button id="currentLocationBtn" class="btn btn-outline-primary btn-sm" onclick="getCurrentLocation()">
                                <i class="fas fa-location-arrow me-1"></i>My Location
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="simulateNewDonation()" title="Test real-time feature">
                                <i class="fas fa-plus me-1"></i>Test New
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="donationsMap" style="height: 500px; position: relative;">
                        <div id="mapLoadingIndicator" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; display: none;">
                            <div class="text-center">
                                <i class="fas fa-spinner fa-spin fa-2x text-primary mb-2"></i>
                                <div>Loading map...</div>
                            </div>
                        </div>
                        <div id="mapErrorIndicator" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; display: none;">
                            <div class="text-center">
                                <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                                <div>Map failed to load</div>
                                <button class="btn btn-sm btn-primary mt-2" onclick="retryMapLoad()">Retry</button>
                            </div>
                        </div>
                    </div>
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
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
// Debug: Log current page info
console.log('🔍 Page Info:', {
    url: window.location.href,
    page: new URLSearchParams(window.location.search).get('page') || '1',
    timestamp: new Date().toISOString()
});

let map;
let mapVisible = false;
let currentLocationMarker = null;
let radiusCircle = null;
let userLocation = null;
let donationMarkers = []; // Track all donation markers
const RADIUS_KM = 5;

// Initialize real-time updates (simplified without Pusher for now)
let realTimeEnabled = false;

// Define toggleMapView function immediately to ensure global accessibility
window.toggleMapView = function() {
    console.log('🗺️ toggleMapView() called from page:', new URLSearchParams(window.location.search).get('page') || '1');
    
    const mapSection = document.getElementById('mapSection');
    const mapButton = document.querySelector('[onclick="toggleMapView()"]') || document.getElementById('mapToggleBtn');
    
    console.log('🔍 Debug info:');
    console.log('  - mapSection found:', !!mapSection);
    console.log('  - mapButton found:', !!mapButton);
    console.log('  - current mapVisible state:', mapVisible);
    console.log('  - current page:', new URLSearchParams(window.location.search).get('page') || '1');
    
    if (!mapSection) {
        console.error('❌ Map section not found!');
        showToast('Error: Map section not found', 'error');
        return;
    }
    
    if (mapVisible) {
        mapSection.style.display = 'none';
        mapVisible = false;
        if (mapButton) mapButton.innerHTML = '<i class="fas fa-map me-1"></i>Map View';
        console.log('📱 Switched to List View');
        showToast('Switched to List View', 'info');
    } else {
        mapSection.style.display = 'block';
        mapVisible = true;
        if (mapButton) mapButton.innerHTML = '<i class="fas fa-list me-1"></i>List View';
        console.log('🗺️ Switched to Map View');
        showToast('Switched to Map View', 'info');
        
        // Initialize or refresh map when shown
        setTimeout(() => {
            try {
                if (!map) {
                    console.log('🆕 Map not exists, initializing...');
                    initializeMap();
                } else {
                    console.log('🔄 Map exists, refreshing...');
                    map.invalidateSize();
                    loadDonationsOnMap();
                }
            } catch (error) {
                console.error('❌ Map toggle error:', error);
                showToast('Error displaying map. Reinitializing...', 'warning');
                // Force reinitialize
                map = null;
                initializeMap();
            }
        }, 300);
    }
};

// Also define as regular function for fallback
function toggleMapView() {
    return window.toggleMapView();
}

document.addEventListener('DOMContentLoaded', function() {
    const currentPage = new URLSearchParams(window.location.search).get('page') || '1';
    console.log('🚀 DOM Content Loaded for page:', currentPage);
    console.log('📍 Leaflet available:', typeof L !== 'undefined');
    console.log('🗺️ Map container exists:', !!document.getElementById('donationsMap'));
    console.log('🖥️ Bootstrap available:', typeof bootstrap !== 'undefined');
    
    // Make sure toggleMapView is available globally
    if (typeof window.toggleMapView !== 'function') {
        console.warn('⚠️ toggleMapView not found on window, defining it...');
        window.toggleMapView = toggleMapView;
    }
    
    // Add error handler for debugging
    window.onerror = function(msg, url, lineNo, columnNo, error) {
        console.error('🚨 JavaScript Error on page ' + currentPage + ':', {
            message: msg,
            source: url,
            line: lineNo,
            column: columnNo,
            error: error
        });
        return false;
    };
    
    // Test if toggleMapView function is available
    console.log('🔍 Function availability check for page ' + currentPage + ':');
    console.log('  - toggleMapView:', typeof toggleMapView);
    console.log('  - window.toggleMapView:', typeof window.toggleMapView);
    console.log('  - initializeMap:', typeof initializeMap);
    
    // Setup button event listeners with retry mechanism
    function setupMapButton() {
        const mapButton = document.querySelector('[onclick="toggleMapView()"]');
        const mapButtonById = document.getElementById('mapToggleBtn');
        
        console.log('🔘 Setting up map button for page ' + currentPage);
        console.log('🔘 Map button found (onclick):', !!mapButton);
        console.log('🔘 Map button found (id):', !!mapButtonById);
        
        if (mapButton) {
            console.log('🔘 Button text:', mapButton.textContent.trim());
            console.log('🔘 Button onclick:', mapButton.getAttribute('onclick'));
            
            // Remove existing listeners to prevent duplicates
            mapButton.replaceWith(mapButton.cloneNode(true));
            const newMapButton = document.querySelector('[onclick="toggleMapView()"]');
            
            // Add event listener as backup
            newMapButton.addEventListener('click', function(e) {
                console.log('🖱️ Button clicked via event listener (onclick selector) on page ' + currentPage);
                if (typeof toggleMapView === 'function') {
                    e.preventDefault();
                    toggleMapView();
                } else if (typeof window.toggleMapView === 'function') {
                    e.preventDefault();
                    window.toggleMapView();
                } else {
                    console.error('❌ toggleMapView function not available on page ' + currentPage);
                }
            });
            console.log('✅ Added backup event listener to map button (onclick)');
        }
        
        if (mapButtonById) {
            // Remove existing listeners to prevent duplicates
            mapButtonById.replaceWith(mapButtonById.cloneNode(true));
            const newMapButtonById = document.getElementById('mapToggleBtn');
            
            newMapButtonById.addEventListener('click', function(e) {
                console.log('🖱️ Button clicked via ID selector on page ' + currentPage);
                if (typeof toggleMapView === 'function') {
                    e.stopPropagation();
                    toggleMapView();
                } else if (typeof window.toggleMapView === 'function') {
                    e.stopPropagation();
                    window.toggleMapView();
                } else {
                    console.error('❌ toggleMapView function not available on page ' + currentPage);
                    // Try to call it directly from window
                    try {
                        window.eval('toggleMapView()');
                    } catch (error) {
                        console.error('❌ Failed to call toggleMapView via eval on page ' + currentPage + ':', error);
                    }
                }
            });
            console.log('✅ Added backup event listener to map button (ID)');
        }
        
        if (!mapButton && !mapButtonById) {
            console.warn('⚠️ No map button found on page ' + currentPage + ', retrying in 500ms...');
            setTimeout(setupMapButton, 500);
        }
    }
    
    // Setup button with delay to ensure DOM is ready
    setTimeout(setupMapButton, 200);
    
    // Don't initialize map immediately since it's hidden by default
    // Map will be initialized when user clicks "Map View"
    console.log('⏸️ Map initialization deferred until Map View is clicked');
    
    startPeriodicUpdates();
    requestNotificationPermission();
});

// Request notification permission
function requestNotificationPermission() {
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
}

// Periodic updates every 30 seconds for real-time simulation
function startPeriodicUpdates() {
    setInterval(() => {
        if (mapVisible && userLocation && realTimeEnabled) {
            refreshNearbyDonations();
        }
    }, 30000); // 30 seconds
}

// Simulate real-time donation detection
function refreshNearbyDonations() {
    if (!userLocation) {
        console.log('No user location available for refresh');
        return;
    }

    const url = `/api/donations/nearby?lat=${userLocation.lat}&lng=${userLocation.lng}&radius=${RADIUS_KM}`;
    console.log('Refreshing donations from:', url);

    fetch(url, {
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
        .then(response => {
            console.log('API response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('API response data:', data);
            // Compare with existing and add any missing
            if (data.donations && Array.isArray(data.donations)) {
                data.donations.forEach(donation => {
                    const exists = donationMarkers.find(m => m.id == donation.id);
                    if (!exists) {
                        const distance = calculateDistance(
                            userLocation.lat, 
                            userLocation.lng, 
                            parseFloat(donation.pickup_latitude), 
                            parseFloat(donation.pickup_longitude)
                        );
                        
                        // This simulates a new donation
                        console.log('Found new donation:', donation.title);
                        handleNewDonation(donation);
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error refreshing donations:', error);
            // Don't show error toast to avoid spam, just log
        });
}

// Handle new donation in real-time
function handleNewDonation(donation) {
    // Check if donation is within radius
    if (userLocation && donation.pickup_latitude && donation.pickup_longitude) {
        const distance = calculateDistance(
            userLocation.lat, 
            userLocation.lng, 
            parseFloat(donation.pickup_latitude), 
            parseFloat(donation.pickup_longitude)
        );
        
        if (distance <= RADIUS_KM) {
            // Add marker to map if map is visible
            if (mapVisible) {
                addDonationMarker(donation, distance);
            }
            
            // Add card to donation list
            addDonationCard(donation, distance);
            
            // Show notification for nearby donations
            showToast(`🎉 New donation within ${distance.toFixed(1)}km: ${donation.title}`, 'success');
            
            // Flash notification
            flashNotification();
            
            // Browser notification
            showBrowserNotification(`New donation available: ${donation.title}`, `${distance.toFixed(1)}km away`);
        }
    }
}

// Add new donation marker to map
function addDonationMarker(donation, distance) {
    const markerIcon = L.divIcon({
        className: 'custom-div-icon new-marker',
        html: `<div style="background-color: #ff6b35; width: 28px; height: 28px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(255,107,53,0.4); display: flex; align-items: center; justify-content: center;">
                 <i class="fas fa-utensils" style="color: white; font-size: 12px;"></i>
               </div>`,
        iconSize: [28, 28],
        iconAnchor: [14, 14]
    });
    
    const marker = L.marker([parseFloat(donation.pickup_latitude), parseFloat(donation.pickup_longitude)], { 
        icon: markerIcon 
    }).addTo(map);
    
    marker.bindPopup(`
        <div class="popup-content">
            <div class="d-flex align-items-center mb-2">
                <span class="badge bg-warning me-2">NEW!</span>
                <h6 class="mb-0">${donation.title}</h6>
            </div>
            <p class="mb-1"><strong>${donation.quantity} ${donation.unit}</strong> (${donation.quantity} available)</p>
            <p class="mb-1"><small><i class="fas fa-map-marker-alt text-success me-1"></i>${distance.toFixed(2)} km away</small></p>
            <p class="mb-1"><small>${donation.pickup_location}</small></p>
            <p class="mb-1"><small>By: ${donation.donor_name || 'Anonymous'}</small></p>
            <div class="d-flex gap-1 mt-2">
                <a href="/donations/${donation.id}" class="btn btn-sm btn-primary">View</a>
                @auth
                    @if(Auth::user()->role === 'recipient')
                        <button class="btn btn-sm btn-success" onclick="showRequestModal(${donation.id}, '${donation.title}', ${donation.quantity}, '${donation.unit}')">Request</button>
                    @endif
                @endauth
            </div>
        </div>
    `);
    
    // Store marker reference
    donationMarkers.push({
        id: donation.id,
        marker: marker
    });
    
    // Auto-open popup for new donations
    setTimeout(() => {
        marker.openPopup();
    }, 1000);
    
    // Remove new styling after animation
    setTimeout(() => {
        marker.getElement().classList.remove('new-marker');
    }, 600);
}

// Add donation card to list
function addDonationCard(donation, distance) {
    const donationsGrid = document.querySelector('.row:has(.food-card)');
    if (!donationsGrid) return;
    
    const cardHTML = `
        <div class="col-lg-4 col-md-6 mb-4 new-donation" data-donation-id="${donation.id}">
            <div class="card h-100 food-card nearby" style="border: 2px solid #ff6b35;">
                <div class="position-relative">
                    ${donation.images && donation.images.length > 0 ? 
                        `<img src="/storage/${donation.images[0]}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="${donation.title}">` :
                        `<div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-utensils fa-3x text-muted"></i>
                        </div>`
                    }
                    <span class="badge bg-warning position-absolute" style="top: 10px; right: 10px;">NEW!</span>
                    <span class="badge bg-success distance-badge position-absolute" style="top: 10px; left: 10px; z-index: 10;">
                        <i class="fas fa-location-arrow me-1"></i>${distance.toFixed(1)} km
                    </span>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title">${donation.title}</h5>
                        <span class="badge bg-primary">${donation.food_type || 'Food'}</span>
                    </div>
                    <p class="card-text text-muted mb-2">${(donation.description || '').substring(0, 100)}...</p>
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="text-primary mb-0">${donation.quantity}</h6>
                                <small class="text-muted">${donation.unit}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-success mb-0">${donation.quantity}</h6>
                            <small class="text-muted">Available</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center text-muted mb-1">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <small>${(donation.pickup_location || '').substring(0, 40)}</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex gap-2">
                        <a href="/donations/${donation.id}" class="btn btn-outline-primary btn-sm flex-fill">
                            <i class="fas fa-eye me-1"></i>View Details
                        </a>
                        @auth
                            @if(Auth::user()->role === 'recipient')
                                <button type="button" class="btn btn-success btn-sm flex-fill" onclick="showRequestModal(${donation.id}, '${donation.title}', ${donation.quantity}, '${donation.unit}')">
                                    <i class="fas fa-hand-paper me-1"></i>Request
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Add to beginning of grid
    donationsGrid.insertAdjacentHTML('afterbegin', cardHTML);
    
    // Remove "NEW!" badge after 10 seconds
    setTimeout(() => {
        const newCard = document.querySelector(`.new-donation[data-donation-id="${donation.id}"]`);
        if (newCard) {
            const newBadge = newCard.querySelector('.badge.bg-warning');
            if (newBadge) newBadge.remove();
            newCard.classList.remove('new-donation');
            newCard.style.border = '2px solid #28a745';
        }
    }, 10000);
}

// Flash notification for new donations
function flashNotification() {
    // Create flash effect
    const flash = document.createElement('div');
    flash.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(40, 167, 69, 0.1);
        z-index: 9998;
        animation: flash 0.6s ease-out;
        pointer-events: none;
    `;
    
    document.body.appendChild(flash);
    
    setTimeout(() => {
        flash.remove();
    }, 600);
}

// Show browser notification
function showBrowserNotification(title, body) {
    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification(title, {
            body: body,
            icon: '/favicon.ico',
            badge: '/favicon.ico'
        });
    }
}

// Simulate new donation for testing (you can remove this)
function simulateNewDonation() {
    if (!userLocation) {
        showToast('Please enable location first to test real-time feature', 'warning');
        return;
    }
    
    // Create a fake donation near user location
    const fakeDonation = {
        id: Date.now(),
        title: `Test Donation ${Math.floor(Math.random() * 100)}`,
        description: 'This is a simulated real-time donation for testing purposes',
        quantity: Math.floor(Math.random() * 10) + 1,
        unit: 'portions',
        food_type: 'cooked',
        pickup_latitude: userLocation.lat + (Math.random() - 0.5) * 0.01, // Within ~500m
        pickup_longitude: userLocation.lng + (Math.random() - 0.5) * 0.01,
        pickup_location: 'Test Location',
        donor_name: 'Test Donor',
        images: []
    };
    
    handleNewDonation(fakeDonation);
}

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
                userLocation = { lat, lng };
                
                // Remove existing markers and circle
                if (currentLocationMarker) {
                    map.removeLayer(currentLocationMarker);
                }
                if (radiusCircle) {
                    map.removeLayer(radiusCircle);
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
                
                // Add 5km radius circle
                radiusCircle = L.circle([lat, lng], {
                    color: '#007bff',
                    fillColor: '#007bff',
                    fillOpacity: 0.1,
                    radius: RADIUS_KM * 1000, // Convert to meters
                    weight: 2,
                    dashArray: '5, 5'
                }).addTo(map);
                
                // Add popup to current location marker
                currentLocationMarker.bindPopup(`
                    <div class="text-center">
                        <strong><i class="fas fa-map-marker-alt text-primary me-1"></i>Your Current Location</strong><br>
                        <small class="text-muted">Showing donations within ${RADIUS_KM}km radius</small><br>
                        <small class="text-muted">Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}</small>
                    </div>
                `);
                
                // Center map on current location with appropriate zoom
                map.setView([lat, lng], 13);
                
                // Show radius info
                document.getElementById('radiusInfo').style.display = 'inline';
                
                // Enable real-time detection
                realTimeEnabled = true;
                
                // Filter and highlight donations within radius
                filterDonationsInRadius();
                
                // Update donations list with distance info
                updateDonationsWithDistance();
                
                // Restore button state
                btn.innerHTML = '<i class="fas fa-check me-1"></i>Located!';
                
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                }, 2000);
                
                // Show success message with count
                const nearbyCount = countDonationsInRadius();
                showToast(`Location found! Found ${nearbyCount} donations within ${RADIUS_KM}km radius.`, 'success');
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

// Calculate distance between two coordinates using Haversine formula
function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius of the Earth in kilometers
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = 
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
        Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    const distance = R * c; // Distance in kilometers
    return distance;
}

// Check if donation is within radius
function isDonationInRadius(donationLat, donationLng) {
    if (!userLocation) return false;
    const distance = calculateDistance(userLocation.lat, userLocation.lng, donationLat, donationLng);
    return distance <= RADIUS_KM;
}

// Count donations within radius
function countDonationsInRadius() {
    if (!userLocation) return 0;
    let count = 0;
    
    @foreach($donations as $donation)
        @if($donation->pickup_latitude && $donation->pickup_longitude)
            if (isDonationInRadius({{ $donation->pickup_latitude }}, {{ $donation->pickup_longitude }})) {
                count++;
            }
        @endif
    @endforeach
    
    return count;
}

// Filter and highlight donations within radius on map
function filterDonationsInRadius() {
    if (!userLocation) return;
    
    // Clear existing markers first (except current location and radius circle)
    map.eachLayer(function(layer) {
        if (layer instanceof L.Marker && layer !== currentLocationMarker) {
            map.removeLayer(layer);
        }
    });
    
    // Add markers only for donations within radius
    @foreach($donations as $donation)
        @if($donation->pickup_latitude && $donation->pickup_longitude)
            const donationLat{{ $donation->id }} = {{ $donation->pickup_latitude }};
            const donationLng{{ $donation->id }} = {{ $donation->pickup_longitude }};
            const distance{{ $donation->id }} = calculateDistance(userLocation.lat, userLocation.lng, donationLat{{ $donation->id }}, donationLng{{ $donation->id }});
            
            if (distance{{ $donation->id }} <= RADIUS_KM) {
                // Create marker with different color for nearby donations
                const markerIcon{{ $donation->id }} = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background-color: #28a745; width: 25px; height: 25px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;">
                             <i class="fas fa-utensils" style="color: white; font-size: 10px;"></i>
                           </div>`,
                    iconSize: [25, 25],
                    iconAnchor: [12, 12]
                });
                
                L.marker([donationLat{{ $donation->id }}, donationLng{{ $donation->id }}], { icon: markerIcon{{ $donation->id }} })
                    .addTo(map)
                    .bindPopup(`
                        <div class="popup-content">
                            <h6>{{ $donation->title }}</h6>
                            <p class="mb-1"><strong>{{ $donation->quantity }} {{ $donation->unit }}</strong> ({{ $donation->getRemainingQuantity() }} available)</p>
                            <p class="mb-1"><small><i class="fas fa-map-marker-alt text-success me-1"></i>${distance{{ $donation->id }}.toFixed(2)} km away</small></p>
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
            }
        @endif
    @endforeach
}

// Update donation cards with distance information
function updateDonationsWithDistance() {
    if (!userLocation) return;
    
    const donationCards = document.querySelectorAll('.food-card');
    donationCards.forEach((card, index) => {
        @foreach($donations as $index => $donation)
            @if($donation->pickup_latitude && $donation->pickup_longitude)
                if (index === {{ $loop->index }}) {
                    const distance = calculateDistance(
                        userLocation.lat, 
                        userLocation.lng, 
                        {{ $donation->pickup_latitude }}, 
                        {{ $donation->pickup_longitude }}
                    );
                    
                    // Add distance badge to card
                    const existingBadge = card.querySelector('.distance-badge');
                    if (existingBadge) {
                        existingBadge.remove();
                    }
                    
                    if (distance <= RADIUS_KM) {
                        const distanceBadge = document.createElement('span');
                        distanceBadge.className = 'badge bg-success distance-badge position-absolute';
                        distanceBadge.style.cssText = 'top: 10px; left: 10px; z-index: 10;';
                        distanceBadge.innerHTML = `<i class="fas fa-location-arrow me-1"></i>${distance.toFixed(1)} km`;
                        
                        const cardBody = card.querySelector('.position-relative') || card.querySelector('.card-img-top').parentNode;
                        if (cardBody) {
                            cardBody.style.position = 'relative';
                            cardBody.appendChild(distanceBadge);
                        }
                        
                        // Highlight the card
                        card.classList.add('nearby');
                        card.style.border = '2px solid #28a745';
                        card.style.boxShadow = '0 4px 8px rgba(40, 167, 69, 0.2)';
                    } else {
                        // Dim the card if outside radius
                        card.classList.add('distant');
                        card.style.opacity = '0.6';
                        card.style.border = '1px solid #dee2e6';
                    }
                }
            @endif
        @endforeach
    });
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
    console.log('🗺️ toggleMapView() called');
    
    const mapSection = document.getElementById('mapSection');
    const mapButton = document.querySelector('[onclick="toggleMapView()"]');
    
    console.log('� Debug info:');
    console.log('  - mapSection found:', !!mapSection);
    console.log('  - mapButton found:', !!mapButton);
    console.log('  - current mapVisible state:', mapVisible);
    
    if (!mapSection) {
        console.error('❌ Map section not found!');
        showToast('Error: Map section not found', 'error');
        return;
    }
    
    if (mapVisible) {
        mapSection.style.display = 'none';
        mapVisible = false;
        if (mapButton) mapButton.innerHTML = '<i class="fas fa-map me-1"></i>Map View';
        console.log('📱 Switched to List View');
        showToast('Switched to List View', 'info');
    } else {
        mapSection.style.display = 'block';
        mapVisible = true;
        if (mapButton) mapButton.innerHTML = '<i class="fas fa-list me-1"></i>List View';
        console.log('🗺️ Switched to Map View');
        showToast('Switched to Map View', 'info');
        
        // Initialize or refresh map when shown
        setTimeout(() => {
            try {
                if (!map) {
                    console.log('🆕 Map not exists, initializing...');
                    initializeMap();
                } else {
                    console.log('🔄 Map exists, refreshing...');
                    map.invalidateSize();
                    loadDonationsOnMap();
                }
            } catch (error) {
                console.error('❌ Map toggle error:', error);
                showToast('Error displaying map. Reinitializing...', 'warning');
                // Force reinitialize
                map = null;
                initializeMap();
            }
        }, 300);
    }
}

function initializeMap() {
    try {
        // Show loading indicator
        const loadingIndicator = document.getElementById('mapLoadingIndicator');
        const errorIndicator = document.getElementById('mapErrorIndicator');
        
        if (loadingIndicator) loadingIndicator.style.display = 'block';
        if (errorIndicator) errorIndicator.style.display = 'none';

        // Check if map container exists
        const mapContainer = document.getElementById('donationsMap');
        if (!mapContainer) {
            console.error('Map container not found');
            throw new Error('Map container not found');
        }

        // Check if Leaflet is loaded
        if (typeof L === 'undefined') {
            console.error('Leaflet library not loaded');
            throw new Error('Leaflet library not loaded');
        }

        // Remove existing map if any
        if (map) {
            try {
                map.remove();
            } catch (e) {
                console.warn('Error removing existing map:', e);
            }
        }

        // Wait a bit for container to be ready
        setTimeout(() => {
            try {
                // Initialize new map
                map = L.map('donationsMap').setView([-7.9666, 112.6326], 12); // Malang coordinates
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(map);

                // Hide loading indicator
                if (loadingIndicator) loadingIndicator.style.display = 'none';

                console.log('✅ Map initialized successfully');
                
                // Add some basic markers if available
                setTimeout(() => {
                    loadDonationsOnMap();
                }, 500);

            } catch (error) {
                console.error('❌ Error in map initialization timeout:', error);
                showMapError();
            }
        }, 100);

    } catch (error) {
        console.error('❌ Error initializing map:', error);
        showMapError();
    }
}

function showMapError() {
    const loadingIndicator = document.getElementById('mapLoadingIndicator');
    const errorIndicator = document.getElementById('mapErrorIndicator');
    
    if (loadingIndicator) loadingIndicator.style.display = 'none';
    if (errorIndicator) errorIndicator.style.display = 'block';
    
    showToast('Failed to initialize map. Please try again.', 'error');
}

// Debug functions
function toggleDebug() {
    const panel = document.getElementById('debugPanel');
    if (panel.style.display === 'none') {
        panel.style.display = 'block';
        updateDebugInfo();
    } else {
        panel.style.display = 'none';
    }
}

function updateDebugInfo() {
    const info = document.getElementById('debugInfo');
    if (!info) return;
    
    info.innerHTML = `
        <div>📍 <strong>Leaflet:</strong> ${typeof L !== 'undefined' ? `✅ v${L.version}` : '❌ Not loaded'}</div>
        <div>�️ <strong>Map container:</strong> ${document.getElementById('donationsMap') ? '✅ Found' : '❌ Missing'}</div>
        <div>🎯 <strong>Map instance:</strong> ${map ? '✅ Created' : '❌ Not created'}</div>
        <div>👀 <strong>Map visible:</strong> ${mapVisible ? '✅ Yes' : '❌ No'}</div>
        <div>📍 <strong>User location:</strong> ${userLocation ? `✅ ${userLocation.lat.toFixed(4)}, ${userLocation.lng.toFixed(4)}` : '❌ Not set'}</div>
        <div>🚀 <strong>Real-time:</strong> ${realTimeEnabled ? '✅ Enabled' : '❌ Disabled'}</div>
        <div>📊 <strong>Markers count:</strong> ${donationMarkers.length}</div>
        <div>🔔 <strong>Notifications:</strong> ${typeof Notification !== 'undefined' && Notification.permission === 'granted' ? '✅ Allowed' : '❌ Not allowed'}</div>
        <div>⏰ <strong>Current time:</strong> ${new Date().toLocaleTimeString()}</div>
    `;
}

function loadDonationsOnMap() {
    // Add markers for each donation (this will be filtered by radius if user location is available)
    try {
        if (!map) {
            console.error('Map not initialized');
            return;
        }

        // Clear existing markers first (except current location and radius circle)
        map.eachLayer(function(layer) {
            if (layer instanceof L.Marker && layer !== currentLocationMarker) {
                map.removeLayer(layer);
            }
        });

        if (userLocation) {
            filterDonationsInRadius();
        } else {
            // Show all donations if no user location
            @foreach($donations as $donation)
                @if($donation->pickup_latitude && $donation->pickup_longitude)
                    try {
                        const marker = L.marker([{{ $donation->pickup_latitude }}, {{ $donation->pickup_longitude }}])
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
                    } catch (error) {
                        console.error('Error adding marker for donation {{ $donation->id }}:', error);
                    }
                @endif
            @endforeach
        }
        
        console.log('Donations loaded on map successfully');
    } catch (error) {
        console.error('Error loading donations on map:', error);
        showToast('Error loading donation markers', 'error');
    }
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

// Debug functions
function retryMapLoad() {
    console.log('🔄 Retrying map load...');
    initializeMap();
}

function toggleDebug() {
    const panel = document.getElementById('debugPanel');
    if (panel.style.display === 'none') {
        panel.style.display = 'block';
        updateDebugInfo();
    } else {
        panel.style.display = 'none';
    }
}

function updateDebugInfo() {
    const info = document.getElementById('debugInfo');
    if (!info) return;
    
    const mapButton = document.querySelector('[onclick="toggleMapView()"]');
    const mapSection = document.getElementById('mapSection');
    
    info.innerHTML = `
        <div>📍 <strong>Leaflet:</strong> ${typeof L !== 'undefined' ? `✅ v${L.version}` : '❌ Not loaded'}</div>
        <div>🗺️ <strong>Map container:</strong> ${document.getElementById('donationsMap') ? '✅ Found' : '❌ Missing'}</div>
        <div>🎯 <strong>Map instance:</strong> ${map ? '✅ Created' : '❌ Not created'}</div>
        <div>👀 <strong>Map visible:</strong> ${mapVisible ? '✅ Yes' : '❌ No'}</div>
        <div>� <strong>Map section:</strong> ${mapSection ? (mapSection.style.display === 'none' ? '❌ Hidden' : '✅ Visible') : '❌ Missing'}</div>
        <div>🔘 <strong>Map button:</strong> ${mapButton ? '✅ Found' : '❌ Missing'}</div>
        <div>�📍 <strong>User location:</strong> ${userLocation ? `✅ ${userLocation.lat.toFixed(4)}, ${userLocation.lng.toFixed(4)}` : '❌ Not set'}</div>
        <div>🚀 <strong>Real-time:</strong> ${realTimeEnabled ? '✅ Enabled' : '❌ Disabled'}</div>
        <div>📊 <strong>Markers count:</strong> ${donationMarkers.length}</div>
        <div>🔔 <strong>Notifications:</strong> ${typeof Notification !== 'undefined' && Notification.permission === 'granted' ? '✅ Allowed' : '❌ Not allowed'}</div>
        <div>⏰ <strong>Current time:</strong> ${new Date().toLocaleTimeString()}</div>
        <div>🎯 <strong>Function check:</strong> ${typeof toggleMapView === 'function' ? '✅ toggleMapView exists' : '❌ toggleMapView missing'}</div>
    `;
}

function testMapToggle() {
    const currentPage = new URLSearchParams(window.location.search).get('page') || '1';
    console.log('🧪 Testing map toggle function on page:', currentPage);
    try {
        if (typeof window.toggleMapView === 'function') {
            window.toggleMapView();
            console.log('✅ Map toggle test successful via window.toggleMapView');
        } else if (typeof toggleMapView === 'function') {
            toggleMapView();
            console.log('✅ Map toggle test successful via toggleMapView');
        } else {
            throw new Error('toggleMapView function not found');
        }
        showToast('Map toggle test completed - check console for details', 'success');
    } catch (error) {
        console.error('❌ Map toggle test failed:', error);
        showToast(`Map toggle test failed: ${error.message}`, 'error');
    }
    updateDebugInfo();
}

// Additional fallback: Create a global function that can be called from anywhere
window.forceToggleMap = function() {
    const currentPage = new URLSearchParams(window.location.search).get('page') || '1';
    console.log('🔧 Force toggle map called on page:', currentPage);
    
    const mapSection = document.getElementById('mapSection');
    if (!mapSection) {
        console.error('❌ Map section not found!');
        showToast('Map section not found!', 'error');
        return;
    }
    
    if (mapSection.style.display === 'none' || mapSection.style.display === '') {
        mapSection.style.display = 'block';
        mapVisible = true;
        console.log('🗺️ Force opened map view');
        showToast('Map view opened', 'success');
        
        // Update button if found
        const button = document.querySelector('[onclick="toggleMapView()"]') || document.getElementById('mapToggleBtn');
        if (button) {
            button.innerHTML = '<i class="fas fa-list me-1"></i>List View';
        }
        
        // Initialize map
        setTimeout(() => {
            if (!map) {
                console.log('🆕 Force initializing map...');
                initializeMap();
            }
        }, 300);
    } else {
        mapSection.style.display = 'none';
        mapVisible = false;
        console.log('📱 Force closed map view');
        showToast('Map view closed', 'info');
        
        // Update button if found
        const button = document.querySelector('[onclick="toggleMapView()"]') || document.getElementById('mapToggleBtn');
        if (button) {
            button.innerHTML = '<i class="fas fa-map me-1"></i>Map View';
        }
    }
};
</script>
@endpush
