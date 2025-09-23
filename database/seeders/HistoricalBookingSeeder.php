<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Motor;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\RevenueSharing;
use Carbon\Carbon;

class HistoricalBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil users dan motors yang ada
        $penyewa = User::where('role', 'penyewa')->get();
        $motors = Motor::all();

        if ($penyewa->count() == 0) {
            $this->command->info('Tidak ada penyewa yang tersedia. Membuat data penyewa terlebih dahulu...');
            
            // Buat beberapa penyewa
            $penyewa1 = User::create([
                'name' => 'Andi Pratama',
                'email' => 'andi@penyewa.com',
                'password' => bcrypt('password'),
                'role' => 'penyewa',
                'phone' => '081234567890',
                'verified' => true,
                'email_verified_at' => now(),
            ]);

            $penyewa2 = User::create([
                'name' => 'Budi Santoso',
                'email' => 'budi@penyewa.com',
                'password' => bcrypt('password'),
                'role' => 'penyewa',
                'phone' => '081234567891',
                'verified' => true,
                'email_verified_at' => now(),
            ]);

            $penyewa3 = User::create([
                'name' => 'Citra Dewi',
                'email' => 'citra@penyewa.com',
                'password' => bcrypt('password'),
                'role' => 'penyewa',
                'phone' => '081234567892',
                'verified' => true,
                'email_verified_at' => now(),
            ]);

            $penyewa = collect([$penyewa1, $penyewa2, $penyewa3]);
        }

        if ($motors->count() == 0) {
            $this->command->error('Tidak ada motor yang tersedia. Jalankan MotorSeeder terlebih dahulu.');
            return;
        }

        // Data booking historis untuk 3 bulan terakhir
        $historicalBookings = [
            // Booking bulan lalu (sudah selesai)
            [
                'start_date' => Carbon::now()->subDays(45),
                'end_date' => Carbon::now()->subDays(42),
                'total_price' => 300000,
                'status' => 'completed',
                'package' => 'daily'
            ],
            [
                'start_date' => Carbon::now()->subDays(40),
                'end_date' => Carbon::now()->subDays(35),
                'total_price' => 750000,
                'status' => 'completed',
                'package' => 'weekly'
            ],
            [
                'start_date' => Carbon::now()->subDays(35),
                'end_date' => Carbon::now()->subDays(32),
                'total_price' => 300000,
                'status' => 'completed',
                'package' => 'daily'
            ],
            
            // Booking 2 bulan lalu
            [
                'start_date' => Carbon::now()->subDays(60),
                'end_date' => Carbon::now()->subDays(56),
                'total_price' => 400000,
                'status' => 'completed',
                'package' => 'daily'
            ],
            [
                'start_date' => Carbon::now()->subDays(55),
                'end_date' => Carbon::now()->subDays(48),
                'total_price' => 1050000,
                'status' => 'completed',
                'package' => 'weekly'
            ],
            [
                'start_date' => Carbon::now()->subDays(50),
                'end_date' => Carbon::now()->subDays(47),
                'total_price' => 300000,
                'status' => 'completed',
                'package' => 'daily'
            ],

            // Booking 3 bulan lalu
            [
                'start_date' => Carbon::now()->subDays(90),
                'end_date' => Carbon::now()->subDays(60),
                'total_price' => 4500000,
                'status' => 'completed',
                'package' => 'monthly'
            ],
            [
                'start_date' => Carbon::now()->subDays(85),
                'end_date' => Carbon::now()->subDays(82),
                'total_price' => 300000,
                'status' => 'completed',
                'package' => 'daily'
            ],

            // Booking minggu lalu (baru selesai)
            [
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->subDays(7),
                'total_price' => 300000,
                'status' => 'completed',
                'package' => 'daily'
            ],
            [
                'start_date' => Carbon::now()->subDays(14),
                'end_date' => Carbon::now()->subDays(7),
                'total_price' => 1050000,
                'status' => 'completed',
                'package' => 'weekly'
            ],
        ];

        foreach ($historicalBookings as $index => $bookingData) {
            // Pilih penyewa dan motor secara random
            $selectedPenyewa = $penyewa->random();
            $selectedMotor = $motors->random();

            // Buat booking
            $booking = Booking::create([
                'renter_id' => $selectedPenyewa->id,
                'motor_id' => $selectedMotor->id,
                'start_date' => $bookingData['start_date'],
                'end_date' => $bookingData['end_date'],
                'price' => $bookingData['total_price'],
                'duration_type' => $bookingData['package'],
                'status' => $bookingData['status'],
                'confirmed_at' => $bookingData['start_date']->copy()->subHours(rand(1, 12)),
                'created_at' => $bookingData['start_date']->copy()->subDays(rand(1, 3)),
                'updated_at' => $bookingData['end_date']->copy()->addHours(rand(1, 12)),
            ]);

            // Buat payment untuk booking ini
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $bookingData['total_price'],
                'method' => collect(['bank_transfer', 'cash', 'e_wallet'])->random(),
                'status' => 'paid',
                'paid_at' => $bookingData['start_date']->copy()->subHours(rand(1, 24)),
                'created_at' => $bookingData['start_date']->copy()->subDays(rand(1, 2)),
                'updated_at' => $bookingData['start_date']->copy()->subHours(rand(1, 12)),
            ]);

            // Buat revenue sharing untuk booking yang sudah selesai
            if ($bookingData['status'] === 'completed') {
                $ownerAmount = $bookingData['total_price'] * 0.7; // 70% untuk pemilik
                $adminAmount = $bookingData['total_price'] * 0.3; // 30% untuk admin

                RevenueSharing::create([
                    'booking_id' => $booking->id,
                    'owner_id' => $selectedMotor->owner_id,
                    'total_amount' => $bookingData['total_price'],
                    'owner_amount' => $ownerAmount,
                    'admin_commission' => $adminAmount,
                    'owner_percentage' => 70.00,
                    'admin_percentage' => 30.00,
                    'status' => 'paid',
                    'settled_at' => $bookingData['end_date']->copy()->addHours(rand(6, 24)),
                    'created_at' => $bookingData['end_date']->copy()->addHours(rand(1, 6)),
                    'updated_at' => $bookingData['end_date']->copy()->addHours(rand(6, 24)),
                ]);
            }

            $this->command->info("Created booking #{$booking->id} for {$selectedPenyewa->name} with {$selectedMotor->brand} {$selectedMotor->model}");
        }

        $this->command->info('Historical booking data created successfully!');
        $this->command->info('Total bookings created: ' . count($historicalBookings));
        $this->command->info('All bookings have completed status with payments and revenue sharing records.');
    }
}