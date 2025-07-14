<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$admin = User::find(1);
$admin->update(['email_verified_at' => now()]);
echo "Admin verified successfully\n";
