<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        \App\Models\User::create([
            'name' => 'Admin FoodRescue',
            'email' => 'admin@foodrescue.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1, Malang',
            'latitude' => -7.9666,
            'longitude' => 112.6326,
            'is_active' => true,
            'is_verified' => true,
        ]);

        // Create Sample Donor
        \App\Models\User::create([
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
        ]);

        // Create Sample Recipient
        \App\Models\User::create([
            'name' => 'Komunitas Peduli',
            'email' => 'recipient@peduli.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'recipient',
            'phone' => '081234567892',
            'address' => 'Jl. Dinoyo No. 5, Malang',
            'latitude' => -7.9344,
            'longitude' => 112.6197,
            'is_active' => true,
            'is_verified' => true,
        ]);
    }
}
