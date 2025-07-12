<?php

use App\Models\FoodDonation;
use App\Models\DonationRequest;
use App\Models\User;

echo "Testing donation request functionality...\n\n";

// Get recipient user
$recipient = User::where('email', 'recipient@peduli.com')->first();
echo "Recipient: {$recipient->name} (ID: {$recipient->id})\n\n";

// Get an available donation
$donation = FoodDonation::where('status', 'approved')
    ->where('expiry_date', '>', now())
    ->first();

if (!$donation) {
    echo "No available donations found!\n";
    exit;
}

echo "=== Testing Donation ===\n";
echo "Title: {$donation->title}\n";
echo "Quantity: {$donation->quantity} {$donation->unit}\n";
echo "Status: {$donation->status}\n";
echo "Donor: {$donation->donor->name}\n";
echo "Can be requested: " . ($donation->canBeRequested() ? 'Yes' : 'No') . "\n\n";

if ($donation->canBeRequested()) {
    // Check if recipient already has a pending request for this donation
    $existingRequest = DonationRequest::where('food_donation_id', $donation->id)
        ->where('recipient_id', $recipient->id)
        ->where('status', 'pending')
        ->first();
    
    if ($existingRequest) {
        echo "❌ Recipient already has a pending request for this donation\n";
        echo "Request ID: {$existingRequest->id}\n";
        echo "Requested quantity: {$existingRequest->requested_quantity}\n";
        echo "Status: {$existingRequest->status}\n";
    } else {
        echo "✅ Creating new donation request...\n";
        
        try {
            $request = DonationRequest::create([
                'food_donation_id' => $donation->id,
                'recipient_id' => $recipient->id,
                'requested_quantity' => min(3, $donation->getRemainingQuantity()), // Request 3 or whatever is available
                'message' => 'Test request for urgent food need. Thank you for your donation!',
                'is_priority' => false,
                'status' => 'pending'
            ]);
            
            echo "✅ Donation request created successfully!\n";
            echo "Request ID: {$request->id}\n";
            echo "Requested quantity: {$request->requested_quantity}\n";
            echo "Message: {$request->message}\n";
            echo "Status: {$request->status}\n";
            
        } catch (Exception $e) {
            echo "❌ Error creating donation request: " . $e->getMessage() . "\n";
        }
    }
} else {
    echo "❌ Donation cannot be requested\n";
    echo "Reasons:\n";
    echo "- Status approved: " . ($donation->status === 'approved' ? 'Yes' : 'No') . "\n";
    echo "- Not expired: " . (!$donation->isExpired() ? 'Yes' : 'No') . "\n";
    echo "- Has remaining quantity: " . ($donation->getRemainingQuantity() > 0 ? 'Yes' : 'No') . "\n";
    echo "- Pickup time still valid: " . (now() <= $donation->pickup_time_end ? 'Yes' : 'No') . "\n";
}

echo "\nTest completed!\n";
