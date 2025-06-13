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
        Schema::create('iuran_warga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rt_id')->constrained('rts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Warga yang membayar
            $table->string('jenis_iuran');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->decimal('jumlah', 10, 2);
            $table->date('tanggal_bayar')->nullable();
            $table->enum('status_pembayaran', ['belum_lunas', 'lunas', 'menunggu_verifikasi'])->default('belum_lunas');
            $table->string('bukti_pembayaran')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('dicatat_oleh_user_id')->constrained('users')->onDelete('restrict'); // Admin yang mencatat
            $table->timestamps();
            $table->unique(['rt_id', 'user_id', 'jenis_iuran', 'bulan', 'tahun'], 'iuran_unique'); // Mencegah duplikasi iuran untuk periode yang sama
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iuran_warga');
    }
};
