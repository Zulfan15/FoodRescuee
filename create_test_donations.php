<?php

use App\Models\FoodDonation;
use App\Models\User;

// Get Zulfan user
$user = User::where('email', 'Zulfan@gmail.com')->first();

if (!$user) {
    echo "User Zulfan not found!\n";
    exit;
}

echo "Creating donations for user: {$user->name} (ID: {$user->id})\n\n";

// Sample donations with various food types
$donations = [
    [
        'title' => 'Fresh Vegetables from Restaurant Kitchen',
        'food_type' => 'vegetables',
        'description' => 'Fresh mixed vegetables including carrots, broccoli, and bell peppers. These are surplus from our restaurant kitchen, still in excellent condition and perfect for cooking.',
        'quantity' => 5,
        'unit' => 'kg',
        'expiry_date' => '2025-07-14 18:00:00',
        'pickup_time_start' => '2025-07-13 09:00:00',
        'pickup_time_end' => '2025-07-13 17:00:00',
        'pickup_location' => 'Jl. Veteran No. 12, Klojen, Malang, East Java',
        'pickup_latitude' => -7.9666,
        'pickup_longitude' => 112.6326,
        'is_perishable' => true,
        'special_instructions' => 'Please bring insulated bags. Vegetables are stored in refrigerated area.',
    ],
    [
        'title' => 'Seasonal Fresh Fruits - Apples & Oranges',
        'food_type' => 'fruits',
        'description' => 'Fresh seasonal fruits including red apples and sweet oranges. These are from our grocery store surplus, slightly overripe but still delicious and nutritious.',
        'quantity' => 20,
        'unit' => 'pieces',
        'expiry_date' => '2025-07-15 20:00:00',
        'pickup_time_start' => '2025-07-13 10:00:00',
        'pickup_time_end' => '2025-07-13 16:00:00',
        'pickup_location' => 'Jl. Soekarno Hatta No. 45, Lowokwaru, Malang, East Java',
        'pickup_latitude' => -7.9553,
        'pickup_longitude' => 112.6175,
        'is_perishable' => true,
        'special_instructions' => 'Fruits are in good condition, consume within 2-3 days for best quality.',
    ],
    [
        'title' => 'Homemade Bread and Pastries',
        'food_type' => 'baked_goods',
        'description' => 'Fresh baked bread, croissants, and pastries from our bakery. Made this morning with high-quality ingredients. Perfect for breakfast or snacks.',
        'quantity' => 3,
        'unit' => 'boxes',
        'expiry_date' => '2025-07-14 12:00:00',
        'pickup_time_start' => '2025-07-13 08:00:00',
        'pickup_time_end' => '2025-07-13 14:00:00',
        'pickup_location' => 'Jl. Ijen Boulevard No. 88, Klojen, Malang, East Java',
        'pickup_latitude' => -7.9757,
        'pickup_longitude' => 112.6304,
        'is_perishable' => false,
        'special_instructions' => 'Each box contains assorted bread and pastries. Can be frozen if needed.',
    ],
    [
        'title' => 'Prepared Meals - Nasi Gudeg & Lauk',
        'food_type' => 'prepared_food',
        'description' => 'Traditional Indonesian prepared meals including Nasi Gudeg, ayam bakar, and vegetables. Freshly cooked today and properly stored in food-safe containers.',
        'quantity' => 15,
        'unit' => 'portions',
        'expiry_date' => '2025-07-13 21:00:00',
        'pickup_time_start' => '2025-07-13 11:00:00',
        'pickup_time_end' => '2025-07-13 19:00:00',
        'pickup_location' => 'Jl. Tugu No. 3, Klojen, Malang, East Java',
        'pickup_latitude' => -7.9797,
        'pickup_longitude' => 112.6326,
        'is_perishable' => true,
        'special_instructions' => 'Meals are in sealed containers. Please consume within 6 hours or refrigerate immediately.',
    ],
    [
        'title' => 'Dairy Products - Milk & Yogurt',
        'food_type' => 'dairy',
        'description' => 'Fresh dairy products including whole milk cartons and assorted yogurt cups. All products are within expiry date and have been properly refrigerated.',
        'quantity' => 2,
        'unit' => 'boxes',
        'expiry_date' => '2025-07-16 23:59:00',
        'pickup_time_start' => '2025-07-13 08:00:00',
        'pickup_time_end' => '2025-07-13 18:00:00',
        'pickup_location' => 'Jl. Bend. Sigura-Gura No. 2, Klojen, Malang, East Java',
        'pickup_latitude' => -7.9788,
        'pickup_longitude' => 112.6278,
        'is_perishable' => true,
        'special_instructions' => 'Keep refrigerated at all times. Each box contains 6 milk cartons and 12 yogurt cups.',
    ],
    [
        'title' => 'Rice & Grain Supplies',
        'food_type' => 'grains',
        'description' => 'High-quality white rice and mixed grains including quinoa and oats. These are surplus from our wholesale business, all in sealed packages.',
        'quantity' => 10,
        'unit' => 'kg',
        'expiry_date' => '2025-12-31 23:59:00',
        'pickup_time_start' => '2025-07-13 09:00:00',
        'pickup_time_end' => '2025-07-13 17:00:00',
        'pickup_location' => 'Jl. Raya Langsep No. 15, Sukun, Malang, East Java',
        'pickup_latitude' => -8.0048,
        'pickup_longitude' => 112.6074,
        'is_perishable' => false,
        'special_instructions' => 'All grains are in original sealed packaging. Store in dry place.',
    ],
    [
        'title' => 'Healthy Snacks & Energy Bars',
        'food_type' => 'snacks',
        'description' => 'Assorted healthy snacks including granola bars, nuts, and dried fruits. Perfect for quick energy boost and healthy snacking.',
        'quantity' => 4,
        'unit' => 'bags',
        'expiry_date' => '2025-08-15 23:59:00',
        'pickup_time_start' => '2025-07-13 10:00:00',
        'pickup_time_end' => '2025-07-13 16:00:00',
        'pickup_location' => 'Jl. Dewi Sartika No. 77, Klojen, Malang, East Java',
        'pickup_latitude' => -7.9835,
        'pickup_longitude' => 112.6312,
        'is_perishable' => false,
        'special_instructions' => 'All snacks are individually wrapped and sealed.',
    ],
    [
        'title' => 'Fresh Beverages & Juices',
        'food_type' => 'beverages',
        'description' => 'Fresh fruit juices and healthy beverages including orange juice, apple juice, and herbal teas. All are properly sealed and refrigerated.',
        'quantity' => 12,
        'unit' => 'liters',
        'expiry_date' => '2025-07-15 18:00:00',
        'pickup_time_start' => '2025-07-13 09:00:00',
        'pickup_time_end' => '2025-07-13 15:00:00',
        'pickup_location' => 'Jl. Jaksa Agung Suprapto No. 25, Klojen, Malang, East Java',
        'pickup_latitude' => -7.9698,
        'pickup_longitude' => 112.6284,
        'is_perishable' => true,
        'special_instructions' => 'Keep beverages chilled. Each container is 1 liter.',
    ]
];

$success_count = 0;
$total_count = count($donations);

foreach ($donations as $index => $donationData) {
    try {
        $donation = FoodDonation::create([
            'donor_id' => $user->id,
            'title' => $donationData['title'],
            'description' => $donationData['description'],
            'food_type' => $donationData['food_type'],
            'quantity' => $donationData['quantity'],
            'unit' => $donationData['unit'],
            'expiry_date' => $donationData['expiry_date'],
            'pickup_time_start' => $donationData['pickup_time_start'],
            'pickup_time_end' => $donationData['pickup_time_end'],
            'pickup_location' => $donationData['pickup_location'],
            'pickup_latitude' => $donationData['pickup_latitude'],
            'pickup_longitude' => $donationData['pickup_longitude'],
            'is_perishable' => $donationData['is_perishable'],
            'special_instructions' => $donationData['special_instructions'],
            'status' => 'pending', // Will be reviewed by admin
        ]);
        
        $success_count++;
        echo "✅ Donation " . ($index + 1) . " created successfully: {$donation->title} (ID: {$donation->id})\n";
        
    } catch (Exception $e) {
        echo "❌ Failed to create donation " . ($index + 1) . ": {$donationData['title']}\n";
        echo "   Error: " . $e->getMessage() . "\n";
    }
}

echo "\n📊 Summary:\n";
echo "   Total donations attempted: {$total_count}\n";
echo "   Successfully created: {$success_count}\n";
echo "   Failed: " . ($total_count - $success_count) . "\n";

if ($success_count > 0) {
    echo "\n🎉 Donations created successfully! They are now pending admin approval.\n";
    echo "   User {$user->name} now has " . FoodDonation::where('donor_id', $user->id)->count() . " total donations.\n";
}
