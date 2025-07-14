<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\FoodDonation;

class BandungDonorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create new Bandung donor
        $bandungDonor = User::create([
            'name' => 'Warung Nasi Padang Minang',
            'email' => 'donor.bandung@minang.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
            'role' => 'donor',
            'phone' => '082234567890',
            'address' => 'Jl. Braga No. 45, Sumur Bandung, Kota Bandung, Jawa Barat',
        ]);

        $this->command->info("✅ Created Bandung donor: {$bandungDonor->name} (ID: {$bandungDonor->id})");

        // Food types to create donations for
        $foodTypes = [
            'vegetables' => [
                'title' => 'Sayuran Segar Sisa Restoran',
                'description' => 'Bayam, kangkung, wortel, dan buncis yang masih sangat segar dari warung. Cocok untuk masak sayur bening atau tumisan. Sudah dicuci bersih dan siap olah.',
                'quantity' => 15,
                'unit' => 'kg'
            ],
            'fruits' => [
                'title' => 'Buah-buahan Segar Lokal Bandung',
                'description' => 'Jeruk Garut, pisang raja, dan pepaya California yang masih sangat manis. Dari supplier lokal Bandung yang berkualitas tinggi.',
                'quantity' => 25,
                'unit' => 'kg'
            ],
            'meat' => [
                'title' => 'Daging Sapi dan Ayam Halal',
                'description' => 'Daging sapi rendang dan ayam goreng yang belum terpakai dari warung. Sudah dipotong siap masak, halal, dan masih dalam kondisi sangat baik.',
                'quantity' => 8,
                'unit' => 'kg'
            ],
            'dairy' => [
                'title' => 'Produk Susu dan Keju Fresh',
                'description' => 'Susu segar, keju mozarella, dan yogurt plain dari supplier terpercaya. Masih dalam kemasan asli dan belum dibuka.',
                'quantity' => 20,
                'unit' => 'pieces'
            ],
            'grains' => [
                'title' => 'Beras Premium dan Mie Instan',
                'description' => 'Beras premium cap Raja Lele 5kg dan berbagai mie instan berkualitas. Masih dalam kemasan utuh dan layak konsumsi jangka panjang.',
                'quantity' => 50,
                'unit' => 'packs'
            ],
            'prepared_food' => [
                'title' => 'Nasi Padang Lengkap Siap Santap',
                'description' => 'Nasi padang komplit dengan rendang, gulai ayam, sambal lado, dan lalapan. Masih hangat dan siap disantap langsung.',
                'quantity' => 40,
                'unit' => 'portions'
            ],
            'baked_goods' => [
                'title' => 'Roti dan Kue Tradisional Bandung',
                'description' => 'Roti manis, donat kentang, dan pisang molen khas Bandung. Dibuat pagi ini dan masih sangat segar untuk dinikmati.',
                'quantity' => 60,
                'unit' => 'pieces'
            ],
            'beverages' => [
                'title' => 'Minuman Segar dan Jus Buah',
                'description' => 'Es teh manis, jus jeruk segar, dan air mineral kemasan. Semua minuman masih dalam kondisi dingin dan segar.',
                'quantity' => 30,
                'unit' => 'liters'
            ],
            'snacks' => [
                'title' => 'Camilan Tradisional Bandung',
                'description' => 'Keripik singkong, onde-onde, dan cireng khas Bandung. Camilan tradisional yang renyah dan masih hangat.',
                'quantity' => 35,
                'unit' => 'packs'
            ],
            'other' => [
                'title' => 'Bumbu Dapur dan Sambal Khas',
                'description' => 'Sambal terasi, sambal lado, bumbu rendang instant, dan rempah-rempah khas Padang. Cocok untuk masak di rumah.',
                'quantity' => 25,
                'unit' => 'bottles'
            ]
        ];

        // Bandung coordinates (approximately Braga Street area)
        $bandungLat = -6.9175;
        $bandungLng = 107.6191;

        $createdDonations = [];

        foreach ($foodTypes as $type => $data) {
            $donation = FoodDonation::create([
                'donor_id' => $bandungDonor->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'food_type' => $type,
                'quantity' => $data['quantity'],
                'unit' => $data['unit'],
                'expiry_date' => '2025-07-30 20:00:00',
                'pickup_time_start' => '2025-07-15 08:00:00',
                'pickup_time_end' => '2025-07-30 18:00:00',
                'pickup_location' => 'Warung Nasi Padang Minang, Jl. Braga No. 45, Sumur Bandung, Kota Bandung. Parkir tersedia di depan warung. Hubungi Pak Amin: 082234567890',
                'pickup_latitude' => $bandungLat + (rand(-100, 100) / 10000), // Small variation around Bandung
                'pickup_longitude' => $bandungLng + (rand(-100, 100) / 10000),
                'status' => 'approved', // Pre-approved for immediate availability
                'admin_notes' => 'Approved by admin - verified donor from Bandung',
                'approved_by' => 1, // Assuming admin user ID is 1
                'approved_at' => now(),
                'is_perishable' => in_array($type, ['meat', 'dairy', 'prepared_food', 'beverages']),
                'special_instructions' => 'Harap datang tepat waktu. Untuk makanan berkuah, bawa wadah sendiri. Parkir gratis tersedia.',
            ]);
            
            $createdDonations[] = $donation;
            $this->command->info("✅ Created donation: {$donation->title} (ID: {$donation->id}, Type: {$type})");
        }

        $this->command->info("\n🎉 Successfully created:");
        $this->command->info("- 1 Bandung donor account");
        $this->command->info("- " . count($createdDonations) . " food donations (all food types)");
        $this->command->info("- All donations expire on July 30, 2025");
        $this->command->info("- All donations are pre-approved and ready for requests");
        
        $this->command->info("\n📍 Location: Bandung, Jawa Barat");
        $this->command->info("📧 Login: donor.bandung@minang.com / password123");
    }
}
