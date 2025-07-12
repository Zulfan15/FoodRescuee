<?php

use App\Models\FoodDonation;
use App\Models\User;

echo "=== DONASI MAKANAN YANG DIBUAT OLEH ZULFAN ===\n\n";

$user = User::where('email', 'Zulfan@gmail.com')->first();
$donations = FoodDonation::where('donor_id', $user->id)
    ->orderBy('created_at', 'desc')
    ->get();

echo "Total donasi yang dibuat: " . $donations->count() . "\n\n";

foreach ($donations as $index => $donation) {
    echo "🍽️  DONASI #" . ($index + 1) . "\n";
    echo "   ID: {$donation->id}\n";
    echo "   Judul: {$donation->title}\n";
    echo "   Jenis Makanan: {$donation->food_type}\n";
    echo "   Jumlah: {$donation->quantity} {$donation->unit}\n";
    echo "   Status: {$donation->status}\n";
    echo "   Lokasi: {$donation->pickup_location}\n";
    echo "   Expiry: {$donation->expiry_date}\n";
    echo "   Perishable: " . ($donation->is_perishable ? 'Ya' : 'Tidak') . "\n";
    echo "   Instruksi: {$donation->special_instructions}\n";
    echo "   Dibuat: {$donation->created_at}\n";
    echo "   " . str_repeat("-", 50) . "\n\n";
}

// Summary by food type
echo "📊 RINGKASAN BERDASARKAN JENIS MAKANAN:\n";
$summary = $donations->groupBy('food_type');
foreach ($summary as $type => $items) {
    echo "   - " . ucfirst(str_replace('_', ' ', $type)) . ": " . $items->count() . " donasi\n";
}

// Summary by status
echo "\n📋 RINGKASAN BERDASARKAN STATUS:\n";
$statusSummary = $donations->groupBy('status');
foreach ($statusSummary as $status => $items) {
    echo "   - " . ucfirst($status) . ": " . $items->count() . " donasi\n";
}
