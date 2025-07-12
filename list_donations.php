<?php

use App\Models\FoodDonation;

echo "Available donations for testing:\n\n";

$donations = FoodDonation::where('status', 'approved')->get(['id', 'title', 'quantity', 'unit']);

foreach ($donations as $donation) {
    echo "ID: {$donation->id} - {$donation->title} ({$donation->quantity} {$donation->unit})\n";
}

echo "\nYou can access any donation detail page at: http://127.0.0.1:8090/donations/{id}\n";
