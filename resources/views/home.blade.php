@extends('layouts.app')

@section('title', 'Home - FoodRescue')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Rescue Food, Save Lives</h1>
                <p class="lead mb-4">Connect with your community to reduce food waste and help those in need. Join thousands of donors and recipients making a difference every day.</p>
                <div class="d-flex gap-3">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Join Now
                        </a>
                        <a href="{{ route('donations.index') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-map me-2"></i>View Food Map
                        </a>
                    @else
                        @if(Auth::user()->role === 'donor')
                            <a href="/createdonations" class="btn btn-light btn-lg">
                                <i class="fas fa-plus me-2"></i>Donate Food
                            </a>
                        @endif
                        <a href="{{ route('donations.index') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-map me-2"></i>Find Food
                        </a>
                    @endguest
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="stats-card">
                            <i class="fas fa-utensils fa-3x text-primary mb-3"></i>
                            <h3 class="text-primary">{{ $stats['total_donations'] ?? 0 }}</h3>
                            <p class="mb-0">Food Donations</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stats-card">
                            <i class="fas fa-users fa-3x text-success mb-3"></i>
                            <h3 class="text-success">{{ $stats['total_recipients'] ?? 0 }}</h3>
                            <p class="mb-0">People Helped</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stats-card">
                            <i class="fas fa-heart fa-3x text-danger mb-3"></i>
                            <h3 class="text-danger">{{ $stats['total_donors'] ?? 0 }}</h3>
                            <p class="mb-0">Active Donors</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stats-card">
                            <i class="fas fa-leaf fa-3x text-warning mb-3"></i>
                            <h3 class="text-warning">{{ $stats['food_saved'] ?? 0 }}kg</h3>
                            <p class="mb-0">Food Saved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">How It Works</h2>
            <p class="lead text-muted">Simple steps to make a difference</p>
        </div>
        
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-user-plus fa-2x"></i>
                        </div>
                        <h5 class="card-title">1. Register</h5>
                        <p class="card-text">Sign up as a donor or recipient. Verify your account and set your location.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 text-center mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-map-marker-alt fa-2x"></i>
                        </div>
                        <h5 class="card-title">2. Find or Share</h5>
                        <p class="card-text">Donors post available food. Recipients browse nearby donations within 5km radius.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 text-center mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-handshake fa-2x"></i>
                        </div>
                        <h5 class="card-title">3. Connect</h5>
                        <p class="card-text">Make requests, coordinate pickup, and leave reviews to build trust in the community.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recent Donations Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h3 class="fw-bold mb-4">Recent Food Donations</h3>
                @if($recent_donations && count($recent_donations) > 0)
                    @foreach($recent_donations as $donation)
                        <div class="card food-card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="card-title">{{ $donation->title }}</h6>
                                        <p class="card-text text-muted mb-2">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ Str::limit($donation->pickup_location, 30) }}
                                        </p>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $donation->created_at->diffForHumans() }}
                                            </small>
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-success status-badge">
                                            {{ ucfirst($donation->status) }}
                                        </span>
                                        <p class="text-muted mb-0 mt-1">
                                            <strong>{{ $donation->quantity }} {{ $donation->unit }}</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No recent donations available.</p>
                @endif
                
                <div class="text-center">
                    <a href="{{ route('donations.index') }}" class="btn btn-primary">
                        <i class="fas fa-eye me-2"></i>View All Donations
                    </a>
                </div>
            </div>
            
            <div class="col-lg-6">
                <h3 class="fw-bold mb-4">Interactive Food Map</h3>
                <div class="map-container" id="homeMap"></div>
                <div class="text-center mt-3">
                    <a href="{{ route('donations.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-expand me-2"></i>View Full Map
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="display-6 fw-bold mb-4">Ready to Make a Difference?</h2>
        <p class="lead mb-4">Join our community and start helping reduce food waste today!</p>
        @guest
            <a href="{{ route('register') }}" class="btn btn-light btn-lg me-3">
                <i class="fas fa-user-plus me-2"></i>Register as Donor
            </a>
            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-hand-holding-heart me-2"></i>Register as Recipient
            </a>
        @else
            @if(Auth::user()->role === 'donor')
                <a href="/createdonations" class="btn btn-light btn-lg">
                    <i class="fas fa-plus me-2"></i>Post Your First Donation
                </a>
            @else
                <a href="{{ route('donations.index') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-search me-2"></i>Find Food Near You
                </a>
            @endif
        @endguest
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('homeMap').setView([-7.9666, 112.6326], 12); // Malang coordinates
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Add sample markers for recent donations
    @if($recent_donations && count($recent_donations) > 0)
        @foreach($recent_donations as $donation)
            @if($donation->pickup_latitude && $donation->pickup_longitude)
                L.marker([{{ $donation->pickup_latitude }}, {{ $donation->pickup_longitude }}])
                    .addTo(map)
                    .bindPopup(`
                        <div class="popup-content">
                            <h6>{{ $donation->title }}</h6>
                            <p class="mb-1"><strong>{{ $donation->quantity }} {{ $donation->unit }}</strong></p>
                            <p class="mb-1"><small>{{ $donation->pickup_location }}</small></p>
                            <p class="mb-0"><small>{{ $donation->created_at->diffForHumans() }}</small></p>
                        </div>
                    `);
            @endif
        @endforeach
    @endif
});
</script>
@endpush
