<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Motor;
use App\Models\User;

// Cari user Eka untuk jadi pemilik motor test
$eka = User::where('name', 'Eka')->first();

if (!$eka) {
    echo "User Eka tidak ditemukan!\n";
    exit(1);
}

// Ubah status motor ID 1 menjadi pending_verification
Motor::where('id', 1)->update(['status' => 'pending_verification']);

echo "Motor ID 1 diubah ke status pending_verification untuk test verifikasi individual.\n";

// Buat motor baru untuk test
$motor = Motor::create([
    'brand' => 'Suzuki',
    'model' => '150cc',
    'year' => 2023,
    'type' => 'matic',
    'price_per_day' => 75000,
    'status' => 'pending_verification',
    'description' => 'Motor test untuk verifikasi',
    'owner_id' => $eka->id,
    'images' => json_encode(['test-image.jpg'])
]);

echo "Motor baru dibuat (ID: {$motor->id}) untuk test verifikasi.\n";
echo "Status: {$motor->status}\n";