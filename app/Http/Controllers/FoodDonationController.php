<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoodDonation;
use App\Models\User;
use App\Events\DonationCreated;
use App\Events\DonationUpdated;
use App\Events\DonationDeleted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class FoodDonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FoodDonation::with('donor')
            ->where('status', 'approved')
            ->where('expiry_date', '>', now());

        // Filter by location if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->latitude && $user->longitude) {
                $query->whereRaw(
                    "ST_Distance_Sphere(
                        POINT(pickup_longitude, pickup_latitude),
                        POINT(?, ?)
                    ) <= 5000", // 5km radius
                    [$user->longitude, $user->latitude]
                );
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('food_type', 'like', "%{$search}%");
            });
        }

        // Filter by food type
        if ($request->filled('food_type')) {
            $query->where('food_type', $request->food_type);
        }

        $donations = $query->latest()->paginate(12);
        
        // Get unique food types for filter
        $foodTypes = FoodDonation::where('status', 'approved')
            ->distinct()
            ->pluck('food_type')
            ->filter()
            ->sort();

        return view('donations.index', compact('donations', 'foodTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('donations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'food_type' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'expiry_date' => 'required|date|after:now',
            'pickup_time_start' => 'required|date|after:now',
            'pickup_time_end' => 'required|date|after:pickup_time_start',
            'pickup_location' => 'required|string',
            'pickup_latitude' => 'required|numeric|between:-90,90',
            'pickup_longitude' => 'required|numeric|between:-180,180',
            'is_perishable' => 'boolean',
            'special_instructions' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $donation = new FoodDonation();
        $donation->donor_id = Auth::id();
        $donation->title = $validated['title'];
        $donation->description = $validated['description'];
        $donation->food_type = $validated['food_type'];
        $donation->quantity = $validated['quantity'];
        $donation->unit = $validated['unit'];
        $donation->expiry_date = $validated['expiry_date'];
        $donation->pickup_time_start = $validated['pickup_time_start'];
        $donation->pickup_time_end = $validated['pickup_time_end'];
        $donation->pickup_location = $validated['pickup_location'];
        $donation->pickup_latitude = $validated['pickup_latitude'];
        $donation->pickup_longitude = $validated['pickup_longitude'];
        $donation->is_perishable = $request->has('is_perishable');
        $donation->special_instructions = $validated['special_instructions'];

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('donations', 'public');
                $imagePaths[] = $path;
            }
            $donation->images = $imagePaths;
        }

        $donation->save();

        // Broadcast event for real-time updates if approved
        if ($donation->status === 'approved') {
            broadcast(new DonationCreated($donation));
        }

        return redirect()->route('dashboard')
            ->with('success', 'Food donation posted successfully! It will be reviewed by admin before being published.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FoodDonation $donation)
    {
        $donation->load(['donor', 'donationRequests.recipient']);
        
        return view('donations.show', compact('donation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FoodDonation $donation): View
    {
        // Only donor and admin can edit
        /** @var User $user */
        $user = Auth::user();
        if (Auth::id() !== $donation->donor_id && !$user->isAdmin()) {
            abort(403);
        }

        return view('donations.edit', compact('donation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FoodDonation $donation): RedirectResponse
    {
        // Debug log
        Log::info('Update method called for donation ID: ' . $donation->id);
        Log::info('Request data: ', $request->all());

        // Only donor and admin can update
        /** @var User $user */
        $user = Auth::user();
        if (Auth::id() !== $donation->donor_id && !$user->isAdmin()) {
            Log::warning('Unauthorized update attempt by user ' . Auth::id() . ' for donation ' . $donation->id);
            abort(403, 'Unauthorized to update this donation.');
        }

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'food_type' => 'required|string|max:100',
                'quantity' => 'required|integer|min:1',
                'unit' => 'required|string|max:50',
                'expiry_date' => 'required|date',
                'pickup_time_start' => 'required|date',
                'pickup_time_end' => 'required|date|after:pickup_time_start',
                'pickup_location' => 'required|string',
                'pickup_latitude' => 'required|numeric|between:-90,90',
                'pickup_longitude' => 'required|numeric|between:-180,180',
                'is_perishable' => 'boolean',
                'special_instructions' => 'nullable|string',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Update basic fields
            $donation->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'food_type' => $validated['food_type'],
                'quantity' => $validated['quantity'],
                'unit' => $validated['unit'],
                'expiry_date' => $validated['expiry_date'],
                'pickup_time_start' => $validated['pickup_time_start'],
                'pickup_time_end' => $validated['pickup_time_end'],
                'pickup_location' => $validated['pickup_location'],
                'pickup_latitude' => $validated['pickup_latitude'],
                'pickup_longitude' => $validated['pickup_longitude'],
                'is_perishable' => $request->has('is_perishable'),
                'special_instructions' => $validated['special_instructions'],
            ]);

            // Handle new image uploads
            if ($request->hasFile('images')) {
                // Delete old images
                if ($donation->images) {
                    foreach ($donation->images as $imagePath) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }

                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('donations', 'public');
                    $imagePaths[] = $path;
                }
                $donation->images = $imagePaths;
                $donation->save();
            }

            // Broadcast update if approved (temporarily disabled for debug)
            // if ($donation->status === 'approved') {
            //     broadcast(new DonationUpdated($donation));
            // }

            return redirect()->route('donations.show', $donation)
                ->with('success', 'Donation updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update donation: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FoodDonation $donation): RedirectResponse
    {
        // Only donor and admin can delete
        /** @var User $user */
        $user = Auth::user();
        if (Auth::id() !== $donation->donor_id && !$user->isAdmin()) {
            abort(403);
        }

        // Delete images
        if ($donation->images) {
            foreach ($donation->images as $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $donationId = $donation->id;
        $donation->delete();

        // Broadcast deletion
        broadcast(new DonationDeleted($donationId));

        return redirect()->route('dashboard')
            ->with('success', 'Donation deleted successfully!');
    }

    /**
     * Get nearby donations for API calls
     */
    public function nearby(Request $request)
    {
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius ?? 5; // Default 5km

        $donations = FoodDonation::with('donor')
            ->where('status', 'approved')
            ->where('expiry_date', '>', now())
            ->whereRaw(
                "ST_Distance_Sphere(
                    POINT(pickup_longitude, pickup_latitude),
                    POINT(?, ?)
                ) <= ? * 1000",
                [$longitude, $latitude, $radius]
            )
            ->get();

        return response()->json($donations);
    }
}
