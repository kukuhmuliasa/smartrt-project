<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rts', function (Blueprint $table) {
            $table->id(); // BIGINT, Primary Key, Auto Increment
            $table->string('nama_rt');
            $table->text('alamat_rt');
            // ketua_rt_user_id akan ditambahkan setelah tabel users dibuat
            // karena ada foreign key constraint.
            // Untuk sementara, kita bisa tambahkan sebagai unsignedBigInteger
            // dan definisikan foreign key di migrasi users atau migrasi terpisah.
            // Atau, kita bisa buat migrasi users dulu.
            // Mari kita buat users dulu agar lebih mudah.
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rts');
    }
};
