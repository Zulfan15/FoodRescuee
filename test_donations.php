<?php
// Script untuk testing donasi makanan dengan berbagai variasi
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Http;

// Data donasi dengan berbagai jenis makanan
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
        'special_instructions' => 'Please bring insulated bags. Vegetables are stored in refrigerated area.'
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
        'special_instructions' => 'Fruits are in good condition, consume within 2-3 days for best quality.'
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
        'special_instructions' => 'Each box contains assorted bread and pastries. Can be frozen if needed.'
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
        'special_instructions' => 'Meals are in sealed containers. Please consume within 6 hours or refrigerate immediately.'
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
        'special_instructions' => 'Keep refrigerated at all times. Each box contains 6 milk cartons and 12 yogurt cups.'
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
        'special_instructions' => 'All grains are in original sealed packaging. Store in dry place.'
    ]
];

echo "Test Donations Data Prepared:\n";
foreach ($donations as $index => $donation) {
    echo ($index + 1) . ". " . $donation['title'] . " (" . $donation['food_type'] . ")\n";
}
?>
