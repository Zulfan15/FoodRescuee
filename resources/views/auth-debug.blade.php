@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Authentication Debug</div>
                <div class="card-body">
                    <h5>Authentication Status:</h5>
                    <ul>
                        <li><strong>Is Authenticated:</strong> {{ Auth::check() ? 'Yes' : 'No' }}</li>
                        @if(Auth::check())
                            <li><strong>User ID:</strong> {{ Auth::id() }}</li>
                            <li><strong>User Name:</strong> {{ Auth::user()->name }}</li>
                            <li><strong>User Role:</strong> {{ Auth::user()->role ?? 'No role' }}</li>
                        @endif
                        <li><strong>Guest:</strong> {{ Auth::guest() ? 'Yes' : 'No' }}</li>
                    </ul>

                    <h5 class="mt-4">What should be visible in navbar:</h5>
                    @guest
                        <div class="alert alert-info">
                            <strong>GUEST VIEW:</strong> Should see Theme Toggle, Emergency Food, Login, and Register buttons
                        </div>
                    @else
                        <div class="alert alert-success">
                            <strong>AUTHENTICATED VIEW:</strong> Should see Theme Toggle, Emergency Food, Notifications, and Profile dropdown
                        </div>
                    @endguest

                    <hr>
                    <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
