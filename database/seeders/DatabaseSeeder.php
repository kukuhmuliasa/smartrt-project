<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder; // Hapus 'use Illuminate\Database\Console\Seeds\WithoutModelEvents;' jika tidak digunakan

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create(); // Baris ini bisa Anda hapus atau komentari jika tidak perlu user factory

        $this->call([
            AdminUserSeeder::class,
            // Anda bisa menambahkan Seeder lain di sini nanti
        ]);
    }
}