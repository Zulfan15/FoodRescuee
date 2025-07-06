@extends('layouts.app')

@section('title', 'Our Impact - FoodRescue')

@section('content')
<div id="impact" class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary">Our Impact</h1>
        <p class="lead">See how we're making a difference together</p>
    </div>

    <div class="row text-center">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <i class="fas fa-utensils fa-3x text-primary mb-3"></i>
                    <h2 class="display-6 fw-bold text-primary">{{ number_format($impact_stats['total_donations']) }}</h2>
                    <p class="text-muted">Food Donations</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <i class="fas fa-weight fa-3x text-success mb-3"></i>
                    <h2 class="display-6 fw-bold text-success">{{ number_format($impact_stats['food_saved_kg']) }}</h2>
                    <p class="text-muted">Kg Food Saved</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <i class="fas fa-users fa-3x text-warning mb-3"></i>
                    <h2 class="display-6 fw-bold text-warning">{{ number_format($impact_stats['people_helped']) }}</h2>
                    <p class="text-muted">People Helped</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <i class="fas fa-leaf fa-3x text-info mb-3"></i>
                    <h2 class="display-6 fw-bold text-info">{{ number_format($impact_stats['co2_saved']) }}</h2>
                    <p class="text-muted">Kg CO2 Saved</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-3">Environmental Impact</h3>
                    <p>By preventing food waste, we've helped reduce greenhouse gas emissions equivalent to taking cars off the road for months.</p>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-success" style="width: 75%"></div>
                    </div>
                    <small class="text-muted">75% reduction in food waste among our users</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-3">Economic Value</h3>
                    <p>The food rescued through our platform represents significant economic value saved from going to waste.</p>
                    <h4 class="text-success">Rp {{ number_format($impact_stats['money_saved']) }}</h4>
                    <small class="text-muted">Total economic value saved</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
