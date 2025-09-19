<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin users
        User::create([
            'name' => 'Admin Sistem',
            'email' => 'admin@rentmotor.com',
            'phone' => '081234567890',
            'role' => 'admin',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
        ]);

        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@rentmotor.com',
            'phone' => '081234567891',
            'role' => 'admin',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
        ]);

        // Pemilik motor users
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'phone' => '081234567892',
            'role' => 'pemilik',
            'email_verified_at' => now(),
            'password' => Hash::make('pemilik123'),
        ]);

        User::create([
            'name' => 'Sari Puspita',
            'email' => 'sari@gmail.com',
            'phone' => '081234567893',
            'role' => 'pemilik',
            'email_verified_at' => now(),
            'password' => Hash::make('pemilik123'),
        ]);

        User::create([
            'name' => 'Ahmad Hidayat',
            'email' => 'ahmad@gmail.com',
            'phone' => '081234567894',
            'role' => 'pemilik',
            'email_verified_at' => now(),
            'password' => Hash::make('pemilik123'),
        ]);

        User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi@gmail.com',
            'phone' => '081234567895',
            'role' => 'pemilik',
            'email_verified_at' => now(),
            'password' => Hash::make('pemilik123'),
        ]);

        // Penyewa users
        User::create([
            'name' => 'Andi Pratama',
            'email' => 'andi@gmail.com',
            'phone' => '081234567896',
            'role' => 'penyewa',
            'email_verified_at' => now(),
            'password' => Hash::make('penyewa123'),
        ]);

        User::create([
            'name' => 'Rina Maharani',
            'email' => 'rina@gmail.com',
            'phone' => '081234567897',
            'role' => 'penyewa',
            'email_verified_at' => now(),
            'password' => Hash::make('penyewa123'),
        ]);

        User::create([
            'name' => 'Faisal Rahman',
            'email' => 'faisal@gmail.com',
            'phone' => '081234567898',
            'role' => 'penyewa',
            'email_verified_at' => now(),
            'password' => Hash::make('penyewa123'),
        ]);

        User::create([
            'name' => 'Maya Sari',
            'email' => 'maya@gmail.com',
            'phone' => '081234567899',
            'role' => 'penyewa',
            'email_verified_at' => now(),
            'password' => Hash::make('penyewa123'),
        ]);

        User::create([
            'name' => 'Rizki Perdana',
            'email' => 'rizki@gmail.com',
            'phone' => '081234567800',
            'role' => 'penyewa',
            'email_verified_at' => now(),
            'password' => Hash::make('penyewa123'),
        ]);

        User::create([
            'name' => 'Sinta Widya',
            'email' => 'sinta@gmail.com',
            'phone' => '081234567801',
            'role' => 'penyewa',
            'email_verified_at' => now(),
            'password' => Hash::make('penyewa123'),
        ]);
    }
}
