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
            $table->id();
            $table->string('nama_rt');
            $table->text('alamat_rt');
            $table->unsignedBigInteger('ketua_rt_user_id')->nullable(); // Admin bisa daftar RT dulu, lalu assign dirinya, atau superadmin assign
            $table->foreign('ketua_rt_user_id')->references('id')->on('users')->onDelete('set null'); // atau cascade jika RT harus dihapus jika ketua RT dihapus
            $table->timestamps();
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
