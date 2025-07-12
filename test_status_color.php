<?php

use App\Models\DonationRequest;

echo "Testing getStatusColor method:\n\n";

$requests = DonationRequest::take(5)->get();

foreach($requests as $req) {
    echo "Request #{$req->id} - Status: {$req->status} - Color: {$req->getStatusColor()}\n";
}

echo "\nMethod test completed successfully!\n";
