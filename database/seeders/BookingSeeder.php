<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\RevenueSharing;
use App\Models\Motor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil penyewa
        $penyewa1 = User::where('email', 'andi@gmail.com')->first();
        $penyewa2 = User::where('email', 'rina@gmail.com')->first();
        $penyewa3 = User::where('email', 'faisal@gmail.com')->first();
        $penyewa4 = User::where('email', 'maya@gmail.com')->first();

        // Ambil motor
        $motor1 = Motor::where('plate_number', 'B 1234 ABC')->first();
        $motor2 = Motor::where('plate_number', 'B 5678 DEF')->first();
        $motor3 = Motor::where('plate_number', 'B 9012 GHI')->first();
        $motor4 = Motor::where('plate_number', 'B 3456 JKL')->first();

        // Booking 1 - Completed
        $booking1 = Booking::create([
            'user_id' => $penyewa1->id,
            'motor_id' => $motor1->id,
            'rental_rate_id' => $motor1->rentalRates->first()->id,
            'start_date' => Carbon::now()->subDays(10),
            'end_date' => Carbon::now()->subDays(7),
            'total_days' => 3,
            'total_amount' => 225000, // 3 hari x 75000
            'status' => 'completed',
            'notes' => 'Untuk acara keluarga di Jakarta Selatan',
            'confirmed_by' => 1,
            'confirmed_at' => Carbon::now()->subDays(9),
        ]);

        $payment1 = Payment::create([
            'booking_id' => $booking1->id,
            'amount' => 225000,
            'payment_method' => 'bank_transfer',
            'status' => 'paid',
            'paid_at' => Carbon::now()->subDays(9),
        ]);

        RevenueSharing::create([
            'booking_id' => $booking1->id,
            'owner_id' => $motor1->owner_id,
            'total_amount' => 225000,
            'owner_share' => 157500, // 70%
            'admin_share' => 67500,  // 30%
        ]);

        // Booking 2 - Confirmed (Sedang Berlangsung)
        $booking2 = Booking::create([
            'user_id' => $penyewa2->id,
            'motor_id' => $motor2->id,
            'rental_rate_id' => $motor2->rentalRates->first()->id,
            'start_date' => Carbon::now()->subDays(2),
            'end_date' => Carbon::now()->addDays(3),
            'total_days' => 5,
            'total_amount' => 475000, // 5 hari x 95000
            'status' => 'confirmed',
            'notes' => 'Untuk liburan ke Bandung',
            'confirmed_by' => 1,
            'confirmed_at' => Carbon::now()->subDays(3),
        ]);

        $payment2 = Payment::create([
            'booking_id' => $booking2->id,
            'amount' => 475000,
            'payment_method' => 'e_wallet',
            'status' => 'paid',
            'paid_at' => Carbon::now()->subDays(3),
        ]);

        RevenueSharing::create([
            'booking_id' => $booking2->id,
            'owner_id' => $motor2->owner_id,
            'total_amount' => 475000,
            'owner_share' => 332500, // 70%
            'admin_share' => 142500,  // 30%
        ]);

        // Booking 3 - Pending (Menunggu Konfirmasi)
        $booking3 = Booking::create([
            'user_id' => $penyewa3->id,
            'motor_id' => $motor3->id,
            'rental_rate_id' => $motor3->rentalRates->first()->id,
            'start_date' => Carbon::now()->addDays(1),
            'end_date' => Carbon::now()->addDays(3),
            'total_days' => 2,
            'total_amount' => 130000, // 2 hari x 65000
            'status' => 'pending',
            'notes' => 'Untuk keperluan kerja di daerah Bekasi',
        ]);

        Payment::create([
            'booking_id' => $booking3->id,
            'amount' => 130000,
            'payment_method' => 'bank_transfer',
            'status' => 'paid',
            'paid_at' => Carbon::now()->subHours(2),
        ]);

        // Booking 4 - Pending (Belum Bayar)
        $booking4 = Booking::create([
            'user_id' => $penyewa4->id,
            'motor_id' => $motor4->id,
            'rental_rate_id' => $motor4->rentalRates->first()->id,
            'start_date' => Carbon::now()->addDays(5),
            'end_date' => Carbon::now()->addDays(7),
            'total_days' => 2,
            'total_amount' => 160000, // 2 hari x 80000
            'status' => 'pending',
            'notes' => 'Untuk menghadiri undangan pernikahan',
        ]);

        Payment::create([
            'booking_id' => $booking4->id,
            'amount' => 160000,
            'status' => 'pending',
        ]);

        // Booking 5 - Completed (History lama)
        $booking5 = Booking::create([
            'user_id' => $penyewa1->id,
            'motor_id' => $motor3->id,
            'rental_rate_id' => $motor3->rentalRates->first()->id,
            'start_date' => Carbon::now()->subDays(30),
            'end_date' => Carbon::now()->subDays(28),
            'total_days' => 2,
            'total_amount' => 130000, // 2 hari x 65000
            'status' => 'completed',
            'notes' => 'Test drive motor Honda Beat',
            'confirmed_by' => 1,
            'confirmed_at' => Carbon::now()->subDays(31),
        ]);

        $payment5 = Payment::create([
            'booking_id' => $booking5->id,
            'amount' => 130000,
            'payment_method' => 'cash',
            'status' => 'paid',
            'paid_at' => Carbon::now()->subDays(31),
        ]);

        RevenueSharing::create([
            'booking_id' => $booking5->id,
            'owner_id' => $motor3->owner_id,
            'total_amount' => 130000,
            'owner_share' => 91000,  // 70%
            'admin_share' => 39000,   // 30%
        ]);

        // Booking 6 - Cancelled
        $booking6 = Booking::create([
            'user_id' => $penyewa2->id,
            'motor_id' => $motor1->id,
            'rental_rate_id' => $motor1->rentalRates->first()->id,
            'start_date' => Carbon::now()->addDays(10),
            'end_date' => Carbon::now()->addDays(12),
            'total_days' => 2,
            'total_amount' => 150000, // 2 hari x 75000
            'status' => 'cancelled',
            'notes' => 'Untuk acara yang ternyata dibatalkan',
            'cancellation_reason' => 'Penyewa membatalkan karena ada perubahan rencana',
            'cancelled_by' => $penyewa2->id,
            'cancelled_at' => Carbon::now()->subHours(5),
        ]);

        Payment::create([
            'booking_id' => $booking6->id,
            'amount' => 150000,
            'status' => 'pending',
        ]);
    }
}
