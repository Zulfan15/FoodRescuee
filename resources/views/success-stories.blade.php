@extends('layouts.app')

@section('title', 'Success Stories - FoodRescue')

@section('content')
<div id="success-stories" class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary">Success Stories</h1>
        <p class="lead">Real stories from our community members</p>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Sarah Restaurant</h6>
                            <small class="text-muted">Food Donor</small>
                        </div>
                    </div>
                    <p class="card-text">"FoodRescue helped us reduce waste by 80%. Instead of throwing away surplus food, we now help feed families in need. It's incredibly rewarding!"</p>
                    <div class="text-warning">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Budi Santoso</h6>
                            <small class="text-muted">Food Recipient</small>
                        </div>
                    </div>
                    <p class="card-text">"During tough times, FoodRescue was a lifeline for my family. We received fresh vegetables and meals that helped us get back on our feet. Thank you!"</p>
                    <div class="text-warning">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Community Kitchen</h6>
                            <small class="text-muted">Organization</small>
                        </div>
                    </div>
                    <p class="card-text">"We've partnered with FoodRescue to feed over 500 people monthly. The platform makes it easy to coordinate and distribute food efficiently."</p>
                    <div class="text-warning">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <h3 class="fw-bold mb-3">Join Our Success Story</h3>
        <p class="lead mb-4">Be part of the movement to reduce food waste and help your community</p>
        <a href="{{ route('register') }}" class="btn btn-success btn-lg">
            <i class="fas fa-user-plus me-2"></i>Get Started Today
        </a>
    </div>
</div>
@endsection
