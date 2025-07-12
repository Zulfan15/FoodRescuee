<?php

use App\Models\DonationRequest;
use App\Models\FoodDonation;
use App\Models\User;

echo "Creating additional donation requests for testing...\n\n";

$recipient = User::where('email', 'recipient@peduli.com')->first();
$donations = FoodDonation::where('status', 'approved')->take(5)->get();

$statuses = ['pending', 'approved', 'rejected', 'completed'];
$messages = [
    'We urgently need this food for our community kitchen.',
    'Our children shelter would greatly benefit from this donation.',
    'Thank you for helping families in need during these tough times.',
    'This will help feed 20+ homeless individuals in our area.',
    'Perfect timing! We can distribute this to elderly residents.'
];

$count = 0;
foreach ($donations as $index => $donation) {
    // Skip if we already have a request for this donation
    $existing = DonationRequest::where('food_donation_id', $donation->id)
        ->where('recipient_id', $recipient->id)
        ->first();
    
    if ($existing) {
        echo "⏭️  Skipping {$donation->title} - already has request\n";
        continue;
    }
    
    try {
        $status = $statuses[$count % count($statuses)];
        $message = $messages[$count % count($messages)];
        
        $request = DonationRequest::create([
            'food_donation_id' => $donation->id,
            'recipient_id' => $recipient->id,
            'requested_quantity' => min(2, $donation->getRemainingQuantity()),
            'message' => $message,
            'status' => $status,
            'is_priority' => $count % 3 === 0, // Make every 3rd request a priority
        ]);
        
        $count++;
        echo "✅ Created request #{$request->id} for '{$donation->title}' (Status: {$status})\n";
        
        if ($count >= 4) break; // Create 4 additional requests
        
    } catch (Exception $e) {
        echo "❌ Failed to create request for '{$donation->title}': " . $e->getMessage() . "\n";
    }
}

echo "\n📊 Summary:\n";
echo "   Additional requests created: {$count}\n";
echo "   Total requests for recipient: " . DonationRequest::where('recipient_id', $recipient->id)->count() . "\n";
echo "\n🎉 Test data ready! Visit /donation-requests to see the results.\n";
