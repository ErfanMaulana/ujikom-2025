<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Motor;
use App\Models\User;

// Find the user with role 'pemilik'
$pemilik = User::where('role', 'pemilik')->first();

if (!$pemilik) {
    echo "No pemilik user found! Creating one...\n";
    $pemilik = User::create([
        'name' => 'Test Pemilik',
        'email' => 'pemilik.test@example.com',
        'password' => bcrypt('password'),
        'role' => 'pemilik',
        'phone' => '081234567890'
    ]);
}

// Create a test motor with pending verification status
$motor = Motor::create([
    'owner_id' => $pemilik->id,  // Use owner_id instead of user_id
    'brand' => 'Kawasaki Test',
    'type_cc' => '125cc',
    'plate_number' => 'Z TEST 123',
    'description' => 'Motor test untuk verifikasi harga oleh admin',
    'status' => 'pending_verification',
    'photo' => null
]);

echo "Test motor created successfully!\n";
echo "Motor ID: {$motor->id}\n";
echo "Brand: {$motor->brand}\n";
echo "Status: {$motor->status}\n";
echo "Owner: {$pemilik->name}\n";
echo "\nYou can now test the verification with pricing in admin panel.\n";