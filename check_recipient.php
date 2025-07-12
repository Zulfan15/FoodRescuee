<?php

use App\Models\User;

echo "Checking recipient user...\n";

$user = User::where('email', 'recipient@peduli.com')->first();

if ($user) {
    echo "User found: {$user->name} (Role: {$user->role})\n";
    echo "User ID: {$user->id}\n";
    echo "Email: {$user->email}\n";
    echo "Phone: {$user->phone}\n";
    echo "Address: {$user->address}\n";
} else {
    echo "User not found. Creating recipient user...\n";
    
    $user = User::create([
        'name' => 'Recipient User',
        'email' => 'recipient@peduli.com',
        'password' => bcrypt('password123'),
        'role' => 'recipient',
        'phone' => '081234567890',
        'address' => 'Jl. Peduli No. 1, Malang',
        'latitude' => '-7.9666',
        'longitude' => '112.6326',
        'is_active' => true
    ]);
    
    echo "Recipient user created: {$user->name} (ID: {$user->id})\n";
}

echo "\nUser is ready for testing donation requests!\n";
