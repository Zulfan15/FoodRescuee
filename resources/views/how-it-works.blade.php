@extends('layouts.app')

@section('title', 'How It Works - FoodRescue')

@section('content')
<div id="how-it-works" class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary">How FoodRescue Works</h1>
        <p class="lead">Simple steps to make a difference in your community</p>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-user-plus fa-2x"></i>
                            </div>
                            <h5 class="card-title">1. Register</h5>
                            <p class="card-text">Create your account and choose your role as a donor or recipient. Verify your location for better matches.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 text-center mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-utensils fa-2x"></i>
                            </div>
                            <h5 class="card-title">2. Share or Find</h5>
                            <p class="card-text">Donors post available food with details. Recipients browse and search for food donations nearby.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 text-center mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-handshake fa-2x"></i>
                            </div>
                            <h5 class="card-title">3. Connect</h5>
                            <p class="card-text">Coordinate pickup times, complete the transfer, and rate your experience to build community trust.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
