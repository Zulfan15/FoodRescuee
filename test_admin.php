<?php

// Simple test to check admin routes
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

echo "Testing Admin Routes...\n\n";

// Test if routes exist
$routes = [
    'admin.dashboard' => 'admin',
    'admin.donations' => 'admin.donations',
    'admin.users' => 'admin.users',
    'admin.reports' => 'admin.reports'
];

foreach ($routes as $routeName => $routePath) {
    try {
        $url = route($routeName);
        echo "✓ Route '{$routeName}' exists: {$url}\n";
    } catch (Exception $e) {
        echo "✗ Route '{$routeName}' error: " . $e->getMessage() . "\n";
    }
}

echo "\nTesting AdminController methods...\n\n";

// Test controller methods exist
$adminController = new App\Http\Controllers\Admin\AdminController();
$methods = ['dashboard', 'donations', 'users', 'reports'];

foreach ($methods as $method) {
    if (method_exists($adminController, $method)) {
        echo "✓ Method '{$method}' exists in AdminController\n";
    } else {
        echo "✗ Method '{$method}' missing in AdminController\n";
    }
}

echo "\nTesting admin views...\n\n";

$views = ['admin.dashboard', 'admin.donations', 'admin.users', 'admin.reports'];

foreach ($views as $view) {
    if (view()->exists($view)) {
        echo "✓ View '{$view}' exists\n";
    } else {
        echo "✗ View '{$view}' missing\n";
    }
}

echo "\nAdmin functionality test completed!\n";
