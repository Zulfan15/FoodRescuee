@extends('layouts.app')

@section('title', 'Emergency Food - FoodRescue')

@section('content')
<div id="emergency" class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-warning">
            <i class="fas fa-exclamation-triangle me-3"></i>Emergency Food
        </h1>
        <p class="lead">Food items that need immediate pickup - expiring soon!</p>
        <div class="alert alert-warning" role="alert">
            <i class="fas fa-clock me-2"></i>
            These items expire within 24 hours. Act fast to prevent waste!
        </div>
    </div>

    @if($emergency_donations && count($emergency_donations) > 0)
        <div class="row">
            @foreach($emergency_donations as $donation)
                <div class="col-lg-6 mb-4">
                    <div class="card border-warning shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">{{ $donation->title }}</h6>
                                <span class="badge bg-danger">
                                    Expires: {{ $donation->expiry_date->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="card-text">{{ Str::limit($donation->description, 100) }}</p>
                                    <p class="mb-2">
                                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                        <strong>Location:</strong> {{ $donation->pickup_location }}
                                    </p>
                                    <p class="mb-2">
                                        <i class="fas fa-weight text-primary me-2"></i>
                                        <strong>Quantity:</strong> {{ $donation->quantity }} {{ $donation->unit }}
                                    </p>
                                    <p class="mb-2">
                                        <i class="fas fa-user text-success me-2"></i>
                                        <strong>Donor:</strong> {{ $donation->donor->name }}
                                    </p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="bg-light rounded p-3 mb-3">
                                        <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                        <h6 class="text-danger fw-bold">
                                            {{ $donation->expiry_date->format('H:i') }}
                                        </h6>
                                        <small class="text-muted">{{ $donation->expiry_date->format('d M Y') }}</small>
                                    </div>
                                    @guest
                                        <a href="{{ route('login') }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-sign-in-alt me-1"></i>Login to Request
                                        </a>
                                    @else
                                        @if(auth()->user()->role === 'recipient')
                                            <button class="btn btn-warning btn-sm" 
                                                    onclick="showRequestModal({{ $donation->id }}, '{{ $donation->title }}', {{ $donation->getRemainingQuantity() }}, '{{ $donation->unit }}')">
                                                <i class="fas fa-hand-holding-heart me-1"></i>Request Now
                                            </button>
                                        @else
                                            <a href="{{ route('donations.show', $donation) }}" class="btn btn-outline-warning btn-sm">
                                                <i class="fas fa-eye me-1"></i>View Details
                                            </a>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
            <h3 class="fw-bold text-success">Great News!</h3>
            <p class="lead">No emergency food items at the moment. All donations are well within their expiry dates.</p>
            <a href="{{ route('donations.index') }}" class="btn btn-primary">
                <i class="fas fa-map me-2"></i>View All Available Food
            </a>
        </div>
    @endif

    <div class="mt-5 p-4 bg-light rounded">
        <h4 class="fw-bold mb-3">What is Emergency Food?</h4>
        <p>Emergency food refers to donations that are close to their expiry date and need immediate pickup to prevent waste. These items are still safe to consume but require quick action.</p>
        <div class="row">
            <div class="col-md-4">
                <h6 class="fw-bold text-warning">For Recipients:</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success me-2"></i>Quick pickup required</li>
                    <li><i class="fas fa-check text-success me-2"></i>Often larger quantities available</li>
                    <li><i class="fas fa-check text-success me-2"></i>Perfect for immediate use</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold text-primary">For Donors:</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success me-2"></i>Prevents food waste</li>
                    <li><i class="fas fa-check text-success me-2"></i>Helps community quickly</li>
                    <li><i class="fas fa-check text-success me-2"></i>Faster donation process</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold text-danger">Safety Notes:</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success me-2"></i>Check expiry dates carefully</li>
                    <li><i class="fas fa-check text-success me-2"></i>Inspect food condition</li>
                    <li><i class="fas fa-check text-success me-2"></i>Use or preserve immediately</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
