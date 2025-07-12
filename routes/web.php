<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FoodDonationController;
use App\Http\Controllers\DonationRequestController;
use App\Http\Controllers\Admin\AdminController;

// Public Routes
Route::get('/', function () {
    $stats = [
        'total_donations' => \App\Models\FoodDonation::count(),
        'total_recipients' => \App\Models\User::where('role', 'recipient')->count(),
        'total_donors' => \App\Models\User::where('role', 'donor')->count(),
        'food_saved' => \App\Models\FoodDonation::where('status', 'completed')->sum('quantity'),
    ];
    
    $recent_donations = \App\Models\FoodDonation::with('donor')
        ->where('status', 'approved')
        ->latest()
        ->take(5)
        ->get();
    
    return view('home', compact('stats', 'recent_donations'));
})->name('home');

// Additional pages
Route::get('/how-it-works', function () {
    return view('how-it-works');
})->name('how-it-works');

Route::get('/impact', function () {
    $impact_stats = [
        'total_donations' => \App\Models\FoodDonation::count(),
        'food_saved_kg' => \App\Models\FoodDonation::where('status', 'completed')->sum('quantity'),
        'people_helped' => \App\Models\DonationRequest::where('status', 'completed')->count(),
        'co2_saved' => \App\Models\FoodDonation::where('status', 'completed')->sum('quantity') * 2.5, // estimate
        'money_saved' => \App\Models\FoodDonation::where('status', 'completed')->sum('quantity') * 15000, // IDR per kg
    ];
    return view('impact', compact('impact_stats'));
})->name('impact');

Route::get('/success-stories', function () {
    return view('success-stories');
})->name('success-stories');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/emergency', function () {
    $emergency_donations = \App\Models\FoodDonation::with('donor')
        ->where('status', 'approved')
        ->where('expiry_date', '>', now())
        ->where('expiry_date', '<=', now()->addHours(24)) // Expiring within 24 hours
        ->latest()
        ->get();
    return view('emergency', compact('emergency_donations'));
})->name('emergency');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Simple test route to check if routing works
Route::get('/test-simple', function() {
    return response()->json(['message' => 'Simple route works!', 'time' => now()]);
});

// Manual login route that sets session properly
Route::get('/createdonations', function () {
    // Auto login user
    $user = \App\Models\User::where('email', 'Zulfan@gmail.com')->first();
    if ($user) {
        Auth::login($user, true); // Remember user
        
        // Check role
        if (in_array($user->role, ['donor', 'admin'])) {
            return view('donations.create');
        } else {
            return response()->json(['error' => 'Unauthorized role: ' . $user->role]);
        }
    }
    return response()->json(['error' => 'User not found']);
});

// Auto login as recipient for testing
Route::get('/loginrecipient', function () {
    $user = \App\Models\User::where('email', 'recipient@peduli.com')->first();
    if ($user) {
        Auth::login($user, true);
        return redirect()->route('donations.index')->with('success', 'Logged in as recipient successfully!');
    }
    return response()->json(['error' => 'Recipient user not found']);
});

// Food Donations Public Routes
Route::get('/donations', [FoodDonationController::class, 'index'])->name('donations.index');
Route::get('/donations/{donation}', [FoodDonationController::class, 'show'])->name('donations.show');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::put('/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
    
    // Donation Requests
    Route::post('/donations/{donation}/request', [DonationRequestController::class, 'store'])->name('donation-requests.store');
    Route::get('/donation-requests', [DonationRequestController::class, 'index'])->name('donation-requests.index');
    Route::get('/donation-requests/{request}', [DonationRequestController::class, 'show'])->name('donation-requests.show');
    Route::put('/donation-requests/{request}', [DonationRequestController::class, 'update'])->name('donation-requests.update');
    Route::delete('/donation-requests/{request}', [DonationRequestController::class, 'destroy'])->name('donation-requests.destroy');
    
    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/donations', [AdminController::class, 'donations'])->name('donations');
        Route::put('/donations/{donation}/approve', [AdminController::class, 'approveDonation'])->name('donations.approve');
        Route::put('/donations/{donation}/reject', [AdminController::class, 'rejectDonation'])->name('donations.reject');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::put('/users/{user}/verify', [AdminController::class, 'verifyUser'])->name('users.verify');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    });
});

// Donations routes - separate from auth middleware to avoid conflicts
Route::middleware('role:donor,admin')->group(function () {
    Route::get('/donations/create', [FoodDonationController::class, 'create'])->name('donations.create');
    Route::post('/donations', [FoodDonationController::class, 'store'])->name('donations.store');
    Route::get('/donations/{donation}/edit', [FoodDonationController::class, 'edit'])->name('donations.edit');
    Route::put('/donations/{donation}', [FoodDonationController::class, 'update'])->name('donations.update');
    Route::delete('/donations/{donation}', [FoodDonationController::class, 'destroy'])->name('donations.destroy');
});

// API Routes for AJAX calls
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/donations/nearby', [FoodDonationController::class, 'nearby']);
    Route::get('/user/location', function () {
        return response()->json([
            'latitude' => Auth::user()->latitude,
            'longitude' => Auth::user()->longitude,
        ]);
    });
});
