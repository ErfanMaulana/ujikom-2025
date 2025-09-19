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
        // Ambil pemilik motor
        $pemilik1 = User::where('email', 'budi@gmail.com')->first();
        $pemilik2 = User::where('email', 'sari@gmail.com')->first();
        $pemilik3 = User::where('email', 'ahmad@gmail.com')->first();
        $pemilik4 = User::where('email', 'dewi@gmail.com')->first();

        // Motor untuk Budi Santoso
        $motor1 = Motor::create([
            'owner_id' => $pemilik1->id,
            'brand' => 'Honda Vario 125',
            'type_cc' => '125cc',
            'plate_number' => 'B 1234 ABC',
            'description' => 'Motor matic Honda Vario 125 tahun 2022, kondisi terawat dan siap pakai untuk perjalanan dalam kota.',
            'photo' => null,
            'status' => 'available',
            'verified_by' => 1,
            'verified_at' => now(),
        ]);

        RentalRate::create([
            'motor_id' => $motor1->id,
            'daily_rate' => 75000,
            'weekly_rate' => 500000,
            'monthly_rate' => 2000000,
        ]);

        $motor2 = Motor::create([
            'owner_id' => $pemilik1->id,
            'brand' => 'Yamaha NMAX 155',
            'type_cc' => '150cc',
            'plate_number' => 'B 5678 DEF',
            'description' => 'Yamaha NMAX 155 terbaru dengan fitur lengkap dan nyaman untuk perjalanan jauh.',
            'photo' => null,
            'status' => 'available',
            'verified_by' => 1,
            'verified_at' => now(),
        ]);

        RentalRate::create([
            'motor_id' => $motor2->id,
            'daily_rate' => 95000,
            'weekly_rate' => 650000,
            'monthly_rate' => 2700000,
        ]);

        // Motor untuk Sari Puspita
        $motor3 = Motor::create([
            'owner_id' => $pemilik2->id,
            'brand' => 'Honda Beat 110',
            'type_cc' => '100cc',
            'plate_number' => 'B 9012 GHI',
            'description' => 'Honda Beat 110 warna putih, irit bensin dan mudah dikendarai untuk pemula.',
            'photo' => null,
            'status' => 'available',
            'verified_by' => 1,
            'verified_at' => now(),
        ]);

        RentalRate::create([
            'motor_id' => $motor3->id,
            'daily_rate' => 65000,
            'weekly_rate' => 430000,
            'monthly_rate' => 1700000,
        ]);

        $motor4 = Motor::create([
            'owner_id' => $pemilik2->id,
            'brand' => 'Yamaha Mio S 125',
            'type_cc' => '125cc',
            'plate_number' => 'B 3456 JKL',
            'description' => 'Yamaha Mio S 125 stylish dengan desain sporty dan performa handal.',
            'photo' => null,
            'status' => 'available',
            'verified_by' => 1,
            'verified_at' => now(),
        ]);

        RentalRate::create([
            'motor_id' => $motor4->id,
            'daily_rate' => 80000,
            'weekly_rate' => 540000,
            'monthly_rate' => 2200000,
        ]);

        // Motor untuk Ahmad Hidayat
        $motor5 = Motor::create([
            'owner_id' => $pemilik3->id,
            'brand' => 'Honda PCX 160',
            'type_cc' => '150cc',
            'plate_number' => 'B 7890 MNO',
            'description' => 'Honda PCX 160 premium dengan fitur canggih dan kenyamanan berkendara maksimal.',
            'photo' => null,
            'status' => 'available',
            'verified_by' => 1,
            'verified_at' => now(),
        ]);

        RentalRate::create([
            'motor_id' => $motor5->id,
            'daily_rate' => 110000,
            'weekly_rate' => 750000,
            'monthly_rate' => 3000000,
        ]);

        $motor6 = Motor::create([
            'owner_id' => $pemilik3->id,
            'brand' => 'Suzuki Address 110',
            'type_cc' => '100cc',
            'plate_number' => 'B 2468 PQR',
            'description' => 'Suzuki Address 110 dengan bagasi luas, cocok untuk keperluan belanja atau kerja.',
            'photo' => null,
            'status' => 'available',
            'verified_by' => 1,
            'verified_at' => now(),
        ]);

        RentalRate::create([
            'motor_id' => $motor6->id,
            'daily_rate' => 70000,
            'weekly_rate' => 470000,
            'monthly_rate' => 1900000,
        ]);

        // Motor untuk Dewi Lestari
        $motor7 = Motor::create([
            'owner_id' => $pemilik4->id,
            'brand' => 'Yamaha Aerox 155',
            'type_cc' => '150cc',
            'plate_number' => 'B 1357 STU',
            'description' => 'Yamaha Aerox 155 sporty dengan performa tinggi dan tampilan agresif.',
            'photo' => null,
            'status' => 'available',
            'verified_by' => 1,
            'verified_at' => now(),
        ]);

        RentalRate::create([
            'motor_id' => $motor7->id,
            'daily_rate' => 100000,
            'weekly_rate' => 680000,
            'monthly_rate' => 2800000,
        ]);

        $motor8 = Motor::create([
            'owner_id' => $pemilik4->id,
            'brand' => 'Honda Genio 110',
            'type_cc' => '100cc',
            'plate_number' => 'B 9753 VWX',
            'description' => 'Honda Genio 110 retro modern dengan desain unik dan konsumsi BBM efisien.',
            'photo' => null,
            'status' => 'available',
            'verified_by' => 1,
            'verified_at' => now(),
        ]);

        RentalRate::create([
            'motor_id' => $motor8->id,
            'daily_rate' => 68000,
            'weekly_rate' => 450000,
            'monthly_rate' => 1800000,
        ]);

        // Motor pending verifikasi
        Motor::create([
            'owner_id' => $pemilik4->id,
            'brand' => 'Honda Scoopy 110',
            'type_cc' => '100cc',
            'plate_number' => 'B 8642 YZA',
            'description' => 'Honda Scoopy 110 warna pink, stylish dan cocok untuk wanita muda.',
            'photo' => null,
            'status' => 'pending_verification',
        ]);

        Motor::create([
            'owner_id' => $pemilik3->id,
            'brand' => 'Yamaha Fino 125',
            'type_cc' => '125cc',
            'plate_number' => 'B 1973 BCD',
            'description' => 'Yamaha Fino 125 bergaya vintage dengan sentuhan modern.',
            'photo' => null,
            'status' => 'pending_verification',
        ]);
    }
}
