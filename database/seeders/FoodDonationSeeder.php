<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FoodDonation;
use App\Models\User;
use Carbon\Carbon;

class FoodDonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing donors, or create some if none exist
        $donors = User::where('role', 'donor')->get();
        
        if ($donors->isEmpty()) {
            // Create some donor users if none exist
            $donors = collect([
                User::create([
                    'name' => 'Restaurant Sakura',
                    'email' => 'donor@sakura.com',
                    'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                    'role' => 'donor',
                    'phone' => '081234567891',
                    'address' => 'Jl. Ijen No. 10, Malang',
                    'latitude' => -7.9553,
                    'longitude' => 112.6145,
                    'is_active' => true,
                    'is_verified' => true,
                ]),
                User::create([
                    'name' => 'Warung Makan Sederhana',
                    'email' => 'donor@sederhana.com',
                    'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                    'role' => 'donor',
                    'phone' => '081234567893',
                    'address' => 'Jl. Kawi No. 15, Malang',
                    'latitude' => -7.9397,
                    'longitude' => 112.6278,
                    'is_active' => true,
                    'is_verified' => true,
                ]),
                User::create([
                    'name' => 'Toko Roti Manis',
                    'email' => 'donor@rotimanis.com',
                    'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                    'role' => 'donor',
                    'phone' => '081234567894',
                    'address' => 'Jl. Veteran No. 8, Malang',
                    'latitude' => -7.9756,
                    'longitude' => 112.6244,
                    'is_active' => true,
                    'is_verified' => true,
                ])
            ]);
        }

        // Sample donation data with realistic information for Malang area
        $donations = [
            [
                'donor_id' => $donors->random()->id,
                'title' => 'Nasi Kotak Sisa Acara Kantor',
                'description' => 'Nasi kotak lengkap dengan lauk pauk dari acara rapat kantor. Masih fresh dan layak konsumsi. Terdiri dari nasi putih, ayam goreng, tempe, sayur asem.',
                'food_type' => 'Makanan Siap Saji',
                'quantity' => 25,
                'unit' => 'kotak',
                'expiry_date' => Carbon::now()->addHours(6),
                'pickup_location' => 'Jl. Veteran No. 12, Malang. Harap datang tepat waktu. Parkir tersedia di depan gedung.',
                'pickup_latitude' => -7.9744,
                'pickup_longitude' => 112.6321,
                'pickup_time_start' => Carbon::now()->setTime(14, 0),
                'pickup_time_end' => Carbon::now()->setTime(18, 0),
                'special_instructions' => 'Harap datang tepat waktu. Parkir tersedia di depan gedung. Hubungi Budi Santoso: 081234567801',
                'status' => 'approved',
                'is_perishable' => true,
                'approved_at' => Carbon::now(),
                'images' => null,
            ],
            [
                'donor_id' => $donors->random()->id,
                'title' => 'Roti Tawar dan Kue Sisa Produksi',
                'description' => 'Roti tawar gandum dan berbagai macam kue dari sisa produksi hari ini. Kualitas masih sangat baik, hanya tidak terjual habis.',
                'food_type' => 'Roti & Kue',
                'quantity' => 45,
                'unit' => 'bungkus',
                'expiry_date' => Carbon::now()->addDays(2),
                'pickup_location' => 'Jl. Kawi No. 25, Malang. Bawa tas/container untuk membawa roti dan kue.',
                'pickup_latitude' => -7.9398,
                'pickup_longitude' => 112.6289,
                'pickup_time_start' => Carbon::now()->setTime(16, 0),
                'pickup_time_end' => Carbon::now()->setTime(20, 0),
                'special_instructions' => 'Bawa tas/container untuk membawa roti dan kue. Hubungi Sari Dewi: 081234567802',
                'status' => 'approved',
                'is_perishable' => false,
                'approved_at' => Carbon::now(),
                'images' => null,
            ],
            [
                'donor_id' => $donors->random()->id,
                'title' => 'Sayuran Segar dari Pasar',
                'description' => 'Sayuran segar seperti kangkung, bayam, tomat, dan timun dari pedagang pasar yang tidak habis terjual. Masih sangat segar.',
                'food_type' => 'Sayuran',
                'quantity' => 20,
                'unit' => 'kg',
                'expiry_date' => Carbon::now()->addDays(1),
                'pickup_location' => 'Pasar Besar Malang, Jl. Pasar Besar, Malang. Masuk lewat pintu samping pasar.',
                'pickup_latitude' => -7.9797,
                'pickup_longitude' => 112.6304,
                'pickup_time_start' => Carbon::now()->setTime(15, 0),
                'pickup_time_end' => Carbon::now()->setTime(17, 0),
                'special_instructions' => 'Masuk lewat pintu samping pasar. Sayuran sudah dikemas per porsi. Hubungi Pak Joko: 081234567803',
                'status' => 'approved',
                'is_perishable' => true,
                'approved_at' => Carbon::now(),
                'images' => null,
            ],
            [
                'donor_id' => $donors->random()->id,
                'title' => 'Nasi Gudeg Jogja Sisa Katering',
                'description' => 'Nasi gudeg lengkap dengan ayam, telur, dan sambal krecek dari sisa pesanan katering pernikahan. Porsi besar dan masih hangat.',
                'food_type' => 'Makanan Tradisional',
                'quantity' => 40,
                'unit' => 'porsi',
                'expiry_date' => Carbon::now()->addHours(8),
                'pickup_location' => 'Jl. Sumbersari No. 45, Malang. Masakan sudah dalam kemasan styrofoam.',
                'pickup_latitude' => -7.9515,
                'pickup_longitude' => 112.6147,
                'pickup_time_start' => Carbon::now()->setTime(13, 0),
                'pickup_time_end' => Carbon::now()->setTime(16, 0),
                'special_instructions' => 'Masakan sudah dalam kemasan styrofoam. Ada tempat parkir luas. Hubungi Ibu Retno: 081234567804',
                'status' => 'approved',
                'is_perishable' => true,
                'approved_at' => Carbon::now(),
                'images' => null,
            ],
            [
                'donor_id' => $donors->random()->id,
                'title' => 'Buah-buahan Segar',
                'description' => 'Pisang, apel, jeruk, dan pepaya yang masih sangat segar. Dari toko buah yang ingin berbagi dengan yang membutuhkan.',
                'food_type' => 'Buah-buahan',
                'quantity' => 15,
                'unit' => 'kg',
                'expiry_date' => Carbon::now()->addDays(3),
                'pickup_location' => 'Jl. Gajayana No. 102, Malang. Buah sudah dikemas dalam keranjang.',
                'pickup_latitude' => -7.9520,
                'pickup_longitude' => 112.6170,
                'pickup_time_start' => Carbon::now()->setTime(9, 0),
                'pickup_time_end' => Carbon::now()->setTime(12, 0),
                'special_instructions' => 'Buah sudah dikemas dalam keranjang. Bisa diambil pagi hari. Hubungi Pak Danu: 081234567805',
                'status' => 'approved',
                'is_perishable' => true,
                'approved_at' => Carbon::now(),
                'images' => null,
            ],
            [
                'donor_id' => $donors->random()->id,
                'title' => 'Mi Ayam dan Bakso Sisa Jualan',
                'description' => 'Mi ayam dan bakso yang masih tersisa dari pedagang kaki lima. Kuah masih panas dan mie masih fresh.',
                'food_type' => 'Makanan Berkuah',
                'quantity' => 20,
                'unit' => 'porsi',
                'expiry_date' => Carbon::now()->addHours(4),
                'pickup_location' => 'Jl. Dinoyo No. 88, Malang. Lokasi di pinggir jalan, mudah diakses.',
                'pickup_latitude' => -7.9345,
                'pickup_longitude' => 112.6198,
                'pickup_time_start' => Carbon::now()->setTime(19, 0),
                'pickup_time_end' => Carbon::now()->setTime(21, 0),
                'special_instructions' => 'Bawa container untuk kuah. Lokasi di pinggir jalan, mudah diakses. Hubungi Bang Rizki: 081234567806',
                'status' => 'approved',
                'is_perishable' => true,
                'approved_at' => Carbon::now(),
                'images' => null,
            ],
            [
                'donor_id' => $donors->random()->id,
                'title' => 'Nasi Padang Lengkap',
                'description' => 'Nasi Padang dengan berbagai lauk seperti rendang, ayam gulai, sambal, dan sayur nangka. Dari restoran Padang terkenal.',
                'food_type' => 'Makanan Siap Saji',
                'quantity' => 35,
                'unit' => 'porsi',
                'expiry_date' => Carbon::now()->addHours(10),
                'pickup_location' => 'Jl. Ijen No. 67, Malang. Lauk dan nasi sudah dikemas terpisah.',
                'pickup_latitude' => -7.9588,
                'pickup_longitude' => 112.6205,
                'pickup_time_start' => Carbon::now()->setTime(14, 30),
                'pickup_time_end' => Carbon::now()->setTime(17, 30),
                'special_instructions' => 'Lauk dan nasi sudah dikemas terpisah. Ada kulkas untuk menjaga kesegaran. Hubungi Pak Andi: 081234567807',
                'status' => 'approved',
                'is_perishable' => true,
                'approved_at' => Carbon::now(),
                'images' => null,
            ],
            [
                'donor_id' => $donors->random()->id,
                'title' => 'Snack dan Kue Kering Lebaran',
                'description' => 'Berbagai macam kue kering seperti kastengel, nastar, putri salju dari sisa lebaran. Masih dalam kemasan rapi.',
                'food_type' => 'Camilan',
                'quantity' => 25,
                'unit' => 'toples',
                'expiry_date' => Carbon::now()->addDays(7),
                'pickup_location' => 'Jl. Bendungan Sutami No. 34, Malang. Kue dalam toples plastik yang bisa dibawa pulang.',
                'pickup_latitude' => -7.9512,
                'pickup_longitude' => 112.6089,
                'pickup_time_start' => Carbon::now()->setTime(10, 0),
                'pickup_time_end' => Carbon::now()->setTime(15, 0),
                'special_instructions' => 'Kue dalam toples plastik yang bisa dibawa pulang. Kondisi sangat baik. Hubungi Ibu Sinta: 081234567808',
                'status' => 'approved',
                'is_perishable' => false,
                'approved_at' => Carbon::now(),
                'images' => null,
            ],
            [
                'donor_id' => $donors->random()->id,
                'title' => 'Soto Ayam dan Lontong',
                'description' => 'Soto ayam dengan lontong, telur, dan kerupuk dari warung soto yang tutup lebih awal. Kuah masih hangat dan segar.',
                'food_type' => 'Makanan Berkuah',
                'quantity' => 18,
                'unit' => 'porsi',
                'expiry_date' => Carbon::now()->addHours(5),
                'pickup_location' => 'Jl. Sukarno Hatta No. 156, Malang. Soto dalam termos besar.',
                'pickup_latitude' => -7.9422,
                'pickup_longitude' => 112.6456,
                'pickup_time_start' => Carbon::now()->setTime(15, 0),
                'pickup_time_end' => Carbon::now()->setTime(18, 0),
                'special_instructions' => 'Soto dalam termos besar. Lontong dan lauk dalam kemasan terpisah. Hubungi Mas Doni: 081234567809',
                'status' => 'approved',
                'is_perishable' => true,
                'approved_at' => Carbon::now(),
                'images' => null,
            ],
            [
                'donor_id' => $donors->random()->id,
                'title' => 'Paket Sembako dan Beras',
                'description' => 'Paket sembako berisi beras 5kg, minyak goreng, gula, mie instan, dan bumbu masak dari donasi perusahaan.',
                'food_type' => 'Sembako',
                'quantity' => 12,
                'unit' => 'paket',
                'expiry_date' => Carbon::now()->addMonths(6),
                'pickup_location' => 'Jl. Raya Tlogomas No. 99, Malang. Paket sudah dikemas rapi dalam kardus.',
                'pickup_latitude' => -7.9289,
                'pickup_longitude' => 112.5987,
                'pickup_time_start' => Carbon::now()->setTime(8, 0),
                'pickup_time_end' => Carbon::now()->setTime(16, 0),
                'special_instructions' => 'Paket sudah dikemas rapi dalam kardus. Bisa diambil kapan saja dalam jam kerja. Hubungi Ibu Maya: 081234567810',
                'status' => 'approved',
                'is_perishable' => false,
                'approved_at' => Carbon::now(),
                'images' => null,
            ]
        ];

        // Create the donations
        foreach ($donations as $donation) {
            FoodDonation::create($donation);
        }

        $this->command->info('✅ 10 sample food donations created successfully!');
        $this->command->info('🗺️  Donations are spread across Malang city and ready to be viewed on the donation map.');
        $this->command->info('📍 All donations have status "approved" so recipients can see them immediately.');
    }
}
