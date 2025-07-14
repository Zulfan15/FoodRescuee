<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FoodDonation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function getNearbyDonations(Request $request)
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $radius = $request->get('radius', 5); // Default 5km
        
        if (!$lat || !$lng) {
            return response()->json(['error' => 'Latitude and longitude are required'], 400);
        }
        
        // Calculate distance using Haversine formula in SQL
        $donations = FoodDonation::selectRaw("
                *,
                (6371 * acos(
                    cos(radians(?)) * 
                    cos(radians(pickup_latitude)) * 
                    cos(radians(pickup_longitude) - radians(?)) + 
                    sin(radians(?)) * 
                    sin(radians(pickup_latitude))
                )) AS distance
            ", [$lat, $lng, $lat])
            ->where('status', 'approved')
            ->whereNotNull('pickup_latitude')
            ->whereNotNull('pickup_longitude')
            ->having('distance', '<=', $radius)
            ->with('donor:id,name')
            ->orderBy('distance')
            ->get()
            ->map(function ($donation) {
                return [
                    'id' => $donation->id,
                    'title' => $donation->title,
                    'description' => $donation->description,
                    'quantity' => $donation->quantity,
                    'unit' => $donation->unit,
                    'remaining_quantity' => $donation->getRemainingQuantity(),
                    'food_type' => $donation->food_type,
                    'pickup_latitude' => $donation->pickup_latitude,
                    'pickup_longitude' => $donation->pickup_longitude,
                    'pickup_location' => $donation->pickup_location,
                    'donor_name' => $donation->donor->name,
                    'distance' => round($donation->distance, 2),
                    'images' => $donation->images,
                    'created_at' => $donation->created_at,
                ];
            });
        
        return response()->json([
            'donations' => $donations,
            'count' => $donations->count(),
            'radius' => $radius,
            'center' => ['lat' => $lat, 'lng' => $lng]
        ]);
    }

    public function subscribeToUpdates(Request $request, FoodDonation $donation)
    {
        // This would be used for WebSocket subscriptions
        // For now, just return success
        return response()->json([
            'message' => 'Subscribed to donation updates',
            'donation_id' => $donation->id
        ]);
    }
}
