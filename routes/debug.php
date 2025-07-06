<?php

use Illuminate\Support\Facades\Route;

// Test route to debug
Route::get('/test-donation-create', function () {
    if (!auth()->check()) {
        return 'Not authenticated';
    }
    
    $user = auth()->user();
    $hasRole = in_array($user->role, ['donor', 'admin']);
    
    return response()->json([
        'authenticated' => true,
        'user_id' => $user->id,
        'user_email' => $user->email,
        'user_role' => $user->role,
        'has_required_role' => $hasRole,
        'required_roles' => ['donor', 'admin'],
        'route_exists' => Route::has('donations.create'),
        'current_time' => now()
    ]);
})->middleware('web');
