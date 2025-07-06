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

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Food Donations Public Routes
Route::get('/donations', [FoodDonationController::class, 'index'])->name('donations.index');
Route::get('/donations/{donation}', [FoodDonationController::class, 'show'])->name('donations.show');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::put('/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
    
    // Food Donations - Authenticated Routes
    Route::middleware('role:donor,admin')->group(function () {
        Route::get('/donations/create', [FoodDonationController::class, 'create'])->name('donations.create');
        Route::post('/donations', [FoodDonationController::class, 'store'])->name('donations.store');
        Route::get('/donations/{donation}/edit', [FoodDonationController::class, 'edit'])->name('donations.edit');
        Route::put('/donations/{donation}', [FoodDonationController::class, 'update'])->name('donations.update');
        Route::delete('/donations/{donation}', [FoodDonationController::class, 'destroy'])->name('donations.destroy');
    });
    
    // Donation Requests
    Route::post('/donations/{donation}/request', [DonationRequestController::class, 'store'])->name('donation-requests.store');
    Route::get('/donation-requests', [DonationRequestController::class, 'index'])->name('donation-requests.index');
    Route::get('/donation-requests/{request}', [DonationRequestController::class, 'show'])->name('donation-requests.show');
    Route::put('/donation-requests/{request}', [DonationRequestController::class, 'update'])->name('donation-requests.update');
    
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

// API Routes for AJAX calls
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/donations/nearby', [FoodDonationController::class, 'nearby']);
    Route::get('/user/location', function () {
        return response()->json([
            'latitude' => auth()->user()->latitude,
            'longitude' => auth()->user()->longitude,
        ]);
    });
});

// Temporary debug route
Route::get('/test-donation-create', function () {
    if (!Auth::check()) {
        return 'Not authenticated';
    }
    
    $user = Auth::user();
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

// Simple test route without any middleware
Route::get('/simple-test', function () {
    return 'Simple test works!';
});

// Test route with auth only
Route::get('/auth-test', function () {
    if (!Auth::check()) {
        return 'Not authenticated';
    }
    return 'Authenticated as: ' . Auth::user()->email . ' (Role: ' . Auth::user()->role . ')';
})->middleware('auth');

// Direct test route for donations create without middleware
Route::get('/direct-donations-create', function () {
    try {
        $controller = new App\Http\Controllers\FoodDonationController();
        return $controller->create();
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

// Direct test route for donations store without middleware
Route::post('/direct-donations-store', function (Illuminate\Http\Request $request) {
    try {
        $controller = new App\Http\Controllers\FoodDonationController();
        return $controller->store($request);
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
