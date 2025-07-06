@extends('layouts.app')

@section('title', 'About Us - FoodRescue')

@section('content')
<div id="about" class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary">About FoodRescue</h1>
        <p class="lead">Connecting communities to reduce food waste and fight hunger</p>
    </div>

    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
            <h2 class="fw-bold mb-3">Our Mission</h2>
            <p class="lead">To create a sustainable food sharing ecosystem that reduces waste while addressing food insecurity in Indonesian communities.</p>
            <p>Every day, tons of perfectly good food goes to waste while millions of people go hungry. FoodRescue bridges this gap by connecting food donors with those in need through our innovative platform.</p>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <i class="fas fa-seedling fa-4x text-success mb-3"></i>
                    <h4 class="fw-bold">Sustainable Future</h4>
                    <p>Building a world where no food goes to waste and no one goes hungry.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-heart fa-3x text-danger mb-3"></i>
                    <h5 class="fw-bold">Community First</h5>
                    <p>We believe in the power of community to solve local problems and create lasting change.</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                    <h5 class="fw-bold">Trust & Safety</h5>
                    <p>Our verification system and review process ensure safe and reliable food sharing experiences.</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-globe fa-3x text-success mb-3"></i>
                    <h5 class="fw-bold">Environmental Impact</h5>
                    <p>Reducing food waste helps combat climate change and preserves our planet for future generations.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto text-center">
            <h2 class="fw-bold mb-3">Get Involved</h2>
            <p class="lead mb-4">Whether you're a restaurant, organization, or individual, there's a place for you in our community.</p>
            <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                <a href="{{ route('register') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-user-plus me-2"></i>Join as Donor
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-hand-holding-heart me-2"></i>Join as Recipient
                </a>
                <a href="mailto:contact@foodrescue.com" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-envelope me-2"></i>Contact Us
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
