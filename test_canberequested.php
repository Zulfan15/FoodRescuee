<?php

use App\Models\FoodDonation;

echo "Testing canBeRequested() method...\n\n";

// Get some approved donations
$donations = FoodDonation::where('status', 'approved')->take(3)->get();

foreach ($donations as $donation) {
    echo "=== Donation: {$donation->title} ===\n";
    echo "Status: {$donation->status}\n";
    echo "Expiry Date: {$donation->expiry_date}\n";
    echo "Pickup End: {$donation->pickup_time_end}\n";
    echo "Quantity: {$donation->quantity} {$donation->unit}\n";
    echo "Remaining Quantity: " . $donation->getRemainingQuantity() . "\n";
    echo "Is Expired: " . ($donation->isExpired() ? 'Yes' : 'No') . "\n";
    echo "Can Be Requested: " . ($donation->canBeRequested() ? 'Yes' : 'No') . "\n";
    echo str_repeat("-", 50) . "\n\n";
}

echo "Method test completed successfully!\n";
