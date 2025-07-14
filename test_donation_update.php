<?php

require __DIR__ . '/vendor/autoload.php';

// Load Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "Testing Donation Update Debug...\n";

// Check donation 5
$donation = App\Models\FoodDonation::find(5);

if ($donation) {
    echo "✅ Found donation ID: " . $donation->id . "\n";
    echo "   - Title: " . $donation->title . "\n";
    echo "   - Donor ID: " . $donation->donor_id . "\n";
    echo "   - Status: " . $donation->status . "\n";
    echo "   - Created: " . $donation->created_at . "\n";
    echo "   - Updated: " . $donation->updated_at . "\n";
    
    // Check user
    $user = App\Models\User::where('email', 'donor@sakura.com')->first();
    if ($user) {
        echo "✅ Found user: " . $user->name . " (ID: " . $user->id . ")\n";
        echo "   - Can edit: " . ($user->id === $donation->donor_id ? 'YES' : 'NO') . "\n";
    } else {
        echo "❌ User not found\n";
    }
} else {
    echo "❌ Donation not found\n";
}

echo "\nTest completed!\n";
