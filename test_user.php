<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "All users in database:\n";
$users = App\Models\User::all();
foreach($users as $user) {
    echo "- " . $user->email . " (" . $user->name . ") - Role: " . $user->role . "\n";
}

echo "\nLooking for donor user:\n";
$user = App\Models\User::where('email', 'donor@foodrescue.com')->first();
if ($user) {
    echo "User found: " . $user->name . "\n";
    echo "Role: " . $user->role . "\n";
    echo "ID: " . $user->id . "\n";
} else {
    echo "donor@foodrescue.com not found\n";
}

// Test route
echo "\nTesting route access...\n";
$route = app('router')->getRoutes()->getByName('donations.create');
if ($route) {
    echo "Route found: " . $route->uri() . "\n";
    echo "Middleware: " . implode(', ', $route->middleware()) . "\n";
} else {
    echo "Route not found\n";
}
