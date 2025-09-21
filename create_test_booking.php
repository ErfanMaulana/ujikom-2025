<?php

use App\Models\Booking;
use App\Models\User;
use App\Models\Motor;
use Carbon\Carbon;

// Ambil user penyewa dan motor available
$renter = User::where('role', 'penyewa')->first();
$motor = Motor::where('status', 'available')->first();

if ($renter && $motor) {
    $booking = Booking::create([
        'renter_id' => $renter->id,
        'motor_id' => $motor->id,
        'package_type' => 'daily',
        'duration_days' => 3,
        'start_date' => Carbon::now()->addDays(1),
        'end_date' => Carbon::now()->addDays(3),
        'price' => 450000,
        'status' => 'pending',
        'notes' => 'Test booking dari script'
    ]);
    echo 'Booking berhasil dibuat dengan ID: ' . $booking->id . PHP_EOL;
    echo 'Renter: ' . $renter->name . PHP_EOL;
    echo 'Motor: ' . $motor->brand . PHP_EOL;
} else {
    echo 'User penyewa atau motor available tidak ditemukan' . PHP_EOL;
    echo 'Renter found: ' . ($renter ? 'Yes' : 'No') . PHP_EOL;
    echo 'Motor found: ' . ($motor ? 'Yes' : 'No') . PHP_EOL;
}