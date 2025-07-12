<?php

use App\Models\DonationRequest;
use App\Models\FoodDonation;
use App\Models\User;

echo "Creating a pending donation request for testing cancel functionality...\n\n";

$recipient = User::where('email', 'recipient@peduli.com')->first();
$donation = FoodDonation::where('status', 'approved')->first();

if ($recipient && $donation) {
    try {
        $request = DonationRequest::create([
            'food_donation_id' => $donation->id,
            'recipient_id' => $recipient->id,
            'requested_quantity' => 1,
            'message' => 'This is a test request that can be cancelled.',
            'status' => 'pending',
            'is_priority' => false,
        ]);
        
        echo "✅ Test pending request created successfully!\n";
        echo "   Request ID: {$request->id}\n";
        echo "   Donation: {$donation->title}\n";
        echo "   Status: {$request->status}\n";
        echo "   Recipient: {$recipient->name}\n\n";
        echo "🎯 This request can now be cancelled from the My Requests page.\n";
        
    } catch (Exception $e) {
        echo "❌ Error creating test request: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ Could not find recipient user or available donation.\n";
}

echo "\nTest request ready for cancel functionality testing!\n";
