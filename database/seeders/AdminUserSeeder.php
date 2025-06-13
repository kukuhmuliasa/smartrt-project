<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan model User di-import
use Illuminate\Support\Facades\Hash; // Untuk hashing password

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin RT',
            'email' => 'admin@smartrt.com', // Ganti dengan email admin yang Anda inginkan
            'password' => Hash::make('admin123'), // Ganti dengan password yang aman
            'role' => 'admin',
            'email_verified_at' => now(), // Anggap email sudah terverifikasi
            // Anda bisa tambahkan NIK, nomor_kk, dll jika diperlukan untuk admin ini
        ]);
    }
}