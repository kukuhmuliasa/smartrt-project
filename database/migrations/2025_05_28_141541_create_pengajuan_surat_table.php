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
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rt_id')->constrained('rts')->onDelete('cascade');
            $table->foreignId('user_id_pemohon')->constrained('users')->onDelete('cascade');
            $table->string('jenis_surat');
            $table->text('keperluan');
            $table->enum('status_pengajuan', ['diajukan', 'diproses', 'disetujui', 'ditolak', 'selesai'])->default('diajukan');
            $table->text('catatan_admin')->nullable();
            $table->string('file_pendukung_pemohon')->nullable();
            $table->string('file_surat_jadi')->nullable();
            $table->timestamp('tanggal_pengajuan')->useCurrent();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->foreignId('diproses_oleh_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat');
    }
};
