<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DonationRequest;
use App\Models\FoodDonation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DonationRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();
        
        if ($user->role === 'recipient') {
            $requests = $user->donationRequests()
                ->with(['foodDonation.donor'])
                ->latest()
                ->paginate(10);
        } elseif ($user->role === 'donor') {
            $requests = DonationRequest::whereHas('foodDonation', function($query) use ($user) {
                $query->where('donor_id', $user->id);
            })->with(['recipient', 'foodDonation'])
            ->latest()
            ->paginate(10);
        } else {
            // Admin can see all requests
            $requests = DonationRequest::with(['recipient', 'foodDonation.donor'])
                ->latest()
                ->paginate(10);
        }

        return view('donation-requests.index', compact('requests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, FoodDonation $donation)
    {
        // Check if user is recipient
        if (Auth::user()->role !== 'recipient') {
            return back()->with('error', 'Only recipients can request food donations.');
        }

        // Check if donation is available and approved
        if (!$donation->canBeRequested() || $donation->status !== 'approved') {
            return back()->with('error', 'This donation is no longer available for requests.');
        }

        $validated = $request->validate([
            'requested_quantity' => 'required|integer|min:1|max:' . $donation->getRemainingQuantity(),
            'message' => 'nullable|string|max:500',
            'is_priority' => 'boolean',
        ]);

        // Check if user already has any active request for this donation
        $existingRequest = DonationRequest::where('food_donation_id', $donation->id)
            ->where('recipient_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingRequest) {
            $status = $existingRequest->status === 'pending' ? 'pending' : 'already approved';
            return back()->with('error', 'You already have a ' . $status . ' request for this donation.');
        }

        try {
            $donationRequest = DonationRequest::create([
                'food_donation_id' => $donation->id,
                'recipient_id' => Auth::id(),
                'requested_quantity' => $validated['requested_quantity'],
                'message' => $validated['message'],
                'is_priority' => $request->has('is_priority'),
                'status' => 'pending',
            ]);

            return back()->with('success', 'Your request has been sent to the donor successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create request. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DonationRequest $request)
    {
        $user = Auth::user();
        
        // Load the foodDonation relationship
        $request->load(['foodDonation.donor', 'recipient']);
        
        // Check if foodDonation exists
        if (!$request->foodDonation) {
            abort(404, 'Food donation not found.');
        }
        
        // Check authorization
        if ($user->role === 'recipient' && $request->recipient_id !== $user->id) {
            abort(403);
        } elseif ($user->role === 'donor' && $request->foodDonation->donor_id !== $user->id) {
            abort(403);
        }

        $request->load(['foodDonation.donor', 'recipient']);
        
        return view('donation-requests.show', compact('request'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DonationRequest $donationRequest)
    {
        $user = Auth::user();
        
        // Load the foodDonation relationship to avoid null errors
        $donationRequest->load('foodDonation');
        
        // Check if foodDonation exists
        if (!$donationRequest->foodDonation) {
            return back()->with('error', 'Food donation not found.');
        }
        
        // Donors can approve/reject requests for their donations
        if ($user->role === 'donor' && $donationRequest->foodDonation->donor_id === $user->id) {
            $validated = $request->validate([
                'status' => 'required|in:approved,rejected',
                'pickup_notes' => 'nullable|string|max:500',
            ]);

            // Check if request is still pending
            if ($donationRequest->status !== 'pending') {
                return back()->with('error', 'This request has already been processed.');
            }

            // Check if there's enough quantity available for approval
            if ($validated['status'] === 'approved') {
                $remainingQuantity = $donationRequest->foodDonation->getRemainingQuantity();
                if ($donationRequest->requested_quantity > $remainingQuantity) {
                    return back()->with('error', 'Not enough quantity available. Only ' . $remainingQuantity . ' ' . $donationRequest->foodDonation->unit . ' remaining.');
                }
            }

            try {
                $donationRequest->update([
                    'status' => $validated['status'],
                    'pickup_notes' => $validated['pickup_notes'] ?? null,
                    'approved_at' => $validated['status'] === 'approved' ? now() : null,
                ]);

                $message = $validated['status'] === 'approved' ? 'Request approved successfully!' : 'Request rejected.';
                return back()->with('success', $message);
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to update request. Please try again.');
            }
        }
        
        // Recipients can mark as picked up
        if ($user->role === 'recipient' && $donationRequest->recipient_id === $user->id) {
            $validated = $request->validate([
                'status' => 'required|in:completed',
            ]);

            if ($donationRequest->status !== 'approved') {
                return back()->with('error', 'Only approved requests can be marked as completed.');
            }

            try {
                $donationRequest->update([
                    'status' => 'completed',
                    'picked_up_at' => now(),
                ]);

                // Check if donation should be marked as completed
                $totalCompletedQuantity = $donationRequest->foodDonation->donationRequests()
                    ->where('status', 'completed')
                    ->sum('requested_quantity');
                
                if ($totalCompletedQuantity >= $donationRequest->foodDonation->quantity) {
                    $donationRequest->foodDonation->update(['status' => 'completed']);
                }

                return back()->with('success', 'Thank you! Request marked as completed.');
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to complete request. Please try again.');
            }
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DonationRequest $donationRequest)
    {
        $user = Auth::user();
        
        // Load the foodDonation relationship if needed for authorization
        $donationRequest->load('foodDonation');
        
        // Only recipient can cancel their own pending request
        if ($user->role === 'recipient' && 
            $donationRequest->recipient_id === $user->id && 
            $donationRequest->status === 'pending') {
            
            try {
                $donationRequest->delete();
                return back()->with('success', 'Request cancelled successfully.');
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to cancel request. Please try again.');
            }
        }

        abort(403, 'Unauthorized action.');
    }
}
