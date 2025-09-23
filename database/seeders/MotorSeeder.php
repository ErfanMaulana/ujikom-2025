<?php

namespace Database\Seeders;

use App\Models\Motor;
use App\Models\RentalRate;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil pemilik motor dari user yang ada
        $pemilik = User::where('email', 'pemilik@gmail.com')->first();
        $admin = User::where('email', 'admin@fannrental.com')->first();

        if (!$pemilik || !$admin) {
            return; // Skip jika user tidak ada
        }

        // Motor 1 - Honda Vario
        $motor1 = Motor::create([
            'owner_id' => $pemilik->id,
            'brand' => 'Honda',
            'model' => 'Vario 125',
            'type_cc' => '125cc',
            'year' => '2022',
            'color' => 'Hitam',
            'plate_number' => 'B 1234 ABC',
            'description' => 'Motor matic Honda Vario 125 tahun 2022, kondisi terawat dan siap pakai untuk perjalanan dalam kota.',
            'photo' => null,
            'status' => 'available',
            'verified_by' => $admin->id,
            'verified_at' => now(),
        ]);

        RentalRate::create([
            'motor_id' => $motor1->id,
            'daily_rate' => 75000,
            'weekly_rate' => 500000,
            'monthly_rate' => 2000000,
        ]);

        // Motor 2 - Yamaha NMAX
        $motor2 = Motor::create([
            'owner_id' => $pemilik->id,
            'brand' => 'Yamaha',
            'model' => 'NMAX 155',
            'type_cc' => '150cc',
            'year' => '2023',
            'color' => 'Putih',
            'plate_number' => 'B 5678 DEF',
            'description' => 'Yamaha NMAX 155 terbaru dengan fitur lengkap dan nyaman untuk perjalanan jauh.',
            'photo' => null,
            'status' => 'available',
            'verified_by' => $admin->id,
            'verified_at' => now(),
        ]);

        RentalRate::create([
            'motor_id' => $motor2->id,
            'daily_rate' => 95000,
            'weekly_rate' => 650000,
            'monthly_rate' => 2700000,
        ]);

        // Motor 3 - Honda Beat
        $motor3 = Motor::create([
            'owner_id' => $pemilik->id,
            'brand' => 'Honda',
            'model' => 'Beat 110',
            'type_cc' => '100cc',
            'year' => '2021',
            'color' => 'Merah',
            'plate_number' => 'B 9012 GHI',
            'description' => 'Honda Beat 110 warna merah, irit bensin dan mudah dikendarai untuk pemula.',
            'photo' => null,
            'status' => 'available',
            'verified_by' => $admin->id,
            'verified_at' => now(),
        ]);

        RentalRate::create([
            'motor_id' => $motor3->id,
            'daily_rate' => 65000,
            'weekly_rate' => 430000,
            'monthly_rate' => 1700000,
        ]);

        // Motor 4 - Yamaha Mio
        $motor4 = Motor::create([
            'owner_id' => $pemilik->id,
            'brand' => 'Yamaha',
            'model' => 'Mio S',
            'type_cc' => '125cc',
            'year' => '2022',
            'color' => 'Biru',
            'plate_number' => 'B 3456 JKL',
            'description' => 'Yamaha Mio S 125 stylish dengan desain sporty dan performa handal.',
            'photo' => null,
            'status' => 'available',
            'verified_by' => $admin->id,
            'verified_at' => now(),
        ]);

        RentalRate::create([
            'motor_id' => $motor4->id,
            'daily_rate' => 80000,
            'weekly_rate' => 540000,
            'monthly_rate' => 2200000,
        ]);

        // Motor 5 - Motor pending verifikasi
        Motor::create([
            'owner_id' => $pemilik->id,
            'brand' => 'Honda',
            'model' => 'Scoopy',
            'type_cc' => '100cc',
            'year' => '2023',
            'color' => 'Pink',
            'plate_number' => 'B 8642 YZA',
            'description' => 'Honda Scoopy 110 warna pink, stylish dan cocok untuk wanita muda.',
            'photo' => null,
            'status' => 'pending_verification',
        ]);

        // Motor 6 - Motor pending verifikasi
        Motor::create([
            'owner_id' => $pemilik->id,
            'brand' => 'Yamaha',
            'model' => 'Fino',
            'type_cc' => '125cc',
            'year' => '2022',
            'color' => 'Kuning',
            'plate_number' => 'B 1973 BCD',
            'description' => 'Yamaha Fino 125 bergaya vintage dengan sentuhan modern.',
            'photo' => null,
            'status' => 'pending_verification',
        ]);
    }
}
