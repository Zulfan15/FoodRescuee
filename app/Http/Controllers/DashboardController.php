<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\FoodDonation;
use App\Models\DonationRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role
     */
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $data = [];

        switch ($user->role) {
            case 'donor':
                $data = [
                    'total_donations' => $user->foodDonations()->count(),
                    'active_donations' => $user->foodDonations()->where('status', 'approved')->count(),
                    'completed_donations' => $user->foodDonations()->where('status', 'completed')->count(),
                    'pending_requests' => DonationRequest::whereHas('foodDonation', function($query) use ($user) {
                        $query->where('donor_id', $user->id);
                    })->where('status', 'pending')->count(),
                    'recent_donations' => $user->foodDonations()->with('donationRequests')->latest()->take(5)->get(),
                ];
                break;

            case 'recipient':
                $data = [
                    'total_requests' => $user->donationRequests()->count(),
                    'approved_requests' => $user->donationRequests()->where('status', 'approved')->count(),
                    'completed_requests' => $user->donationRequests()->where('status', 'completed')->count(),
                    'nearby_donations' => FoodDonation::where('status', 'approved')
                        ->whereRaw(
                            "ST_Distance_Sphere(
                                POINT(pickup_longitude, pickup_latitude),
                                POINT(?, ?)
                            ) <= 5000",
                            [$user->longitude, $user->latitude]
                        )->take(5)->get(),
                    'recent_requests' => $user->donationRequests()->with('foodDonation')->latest()->take(5)->get(),
                ];
                break;

            case 'admin':
                $data = [
                    'total_users' => User::count(),
                    'total_donors' => User::where('role', 'donor')->count(),
                    'total_recipients' => User::where('role', 'recipient')->count(),
                    'pending_donations' => FoodDonation::where('status', 'pending')->count(),
                    'total_donations' => FoodDonation::count(),
                    'recent_donations' => FoodDonation::with('donor')->latest()->take(10)->get(),
                ];
                break;
        }

        return view('dashboard.index', compact('user', 'data'));
    }

    /**
     * Show user profile form
     */
    public function profile(): View
    {
        return view('dashboard.profile', ['user' => Auth::user()]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Update basic info
        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        // Update password if provided
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            
            $user->update(['password' => Hash::make($validated['new_password'])]);
        }

        return back()->with('success', 'Profile updated successfully!');
    }
}
