<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        \App\Models\User::create([
            'name' => 'Admin FannRental',
            'email' => 'admin@fannrental.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'email_verified_at' => now(),
        ]);

        // Pemilik Motor User
        \App\Models\User::create([
            'name' => 'Pemilik Motor',
            'email' => 'pemilik@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'pemilik',
            'phone' => '081234567891',
            'email_verified_at' => now(),
        ]);

        // Penyewa User
        \App\Models\User::create([
            'name' => 'Penyewa Motor',
            'email' => 'penyewa@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'penyewa',
            'phone' => '081234567892',
            'email_verified_at' => now(),
        ]);

        // Extra Admin
        \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@fannrental.com',
            'password' => \Illuminate\Support\Facades\Hash::make('superadmin123'),
            'role' => 'admin',
            'phone' => '081234567893',
            'email_verified_at' => now(),
        ]);

        // Extra Pemilik
        \App\Models\User::create([
            'name' => 'Erfan Maulana',
            'email' => 'erfan@pemilik.com',
            'password' => \Illuminate\Support\Facades\Hash::make('erfan123'),
            'role' => 'pemilik',
            'phone' => '081234567894',
            'email_verified_at' => now(),
        ]);
    }
}
