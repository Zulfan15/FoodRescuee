<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoodDonation;
use App\Models\User;
use App\Models\DonationRequest;
use App\Models\Review;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_donations' => FoodDonation::count(),
            'pending_donations' => FoodDonation::where('status', 'pending')->count(),
            'total_users' => User::count(),
            'total_requests' => DonationRequest::count(),
            'completed_donations' => FoodDonation::where('status', 'completed')->count(),
            'active_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
        ];
        
        $recent_donations = FoodDonation::with('donor')
            ->latest()
            ->take(10)
            ->get();
            
        $recent_users = User::latest()->take(10)->get();
        
        return view('admin.dashboard', compact('stats', 'recent_donations', 'recent_users'));
    }

    public function donations()
    {
        $donations = FoodDonation::with(['donor', 'donationRequests'])
            ->latest()
            ->paginate(20);
            
        return view('admin.donations', compact('donations'));
    }

    public function approveDonation(FoodDonation $donation)
    {
        $donation->update(['status' => 'approved']);
        
        return redirect()->back()->with('success', 'Donation approved successfully!');
    }

    public function rejectDonation(FoodDonation $donation)
    {
        $donation->update(['status' => 'rejected']);
        
        return redirect()->back()->with('success', 'Donation rejected successfully!');
    }

    public function users()
    {
        $users = User::with(['foodDonations', 'donationRequests'])
            ->latest()
            ->paginate(20);
            
        return view('admin.users', compact('users'));
    }

    public function verifyUser(User $user)
    {
        $user->update(['email_verified_at' => now()]);
        
        return redirect()->back()->with('success', 'User verified successfully!');
    }

    public function reports()
    {
        // Monthly statistics
        $monthly_stats = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthly_stats[] = [
                'month' => $date->format('M Y'),
                'donations' => FoodDonation::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'users' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'requests' => DonationRequest::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        }

        // Overall statistics
        $total_stats = [
            'total_donations' => FoodDonation::count(),
            'completed_donations' => FoodDonation::where('status', 'completed')->count(),
            'pending_donations' => FoodDonation::where('status', 'pending')->count(),
            'approved_donations' => FoodDonation::where('status', 'approved')->count(),
            'rejected_donations' => FoodDonation::where('status', 'rejected')->count(),
            'total_users' => User::count(),
            'donors' => User::where('role', 'donor')->count(),
            'recipients' => User::where('role', 'recipient')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'total_requests' => DonationRequest::count(),
            'completed_requests' => DonationRequest::where('status', 'completed')->count(),
            'pending_requests' => DonationRequest::where('status', 'pending')->count(),
            'food_saved_kg' => FoodDonation::where('status', 'completed')->sum('quantity'),
            'avg_rating' => Review::avg('rating'),
            'total_reviews' => Review::count(),
        ];

        // Top performers
        $top_donors = User::where('role', 'donor')
            ->withCount(['foodDonations as donations_count'])
            ->orderBy('donations_count', 'desc')
            ->take(10)
            ->get();

        $top_recipients = User::where('role', 'recipient')
            ->withCount(['donationRequests as requests_count'])
            ->orderBy('requests_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports', compact('monthly_stats', 'total_stats', 'top_donors', 'top_recipients'));
    }
}
