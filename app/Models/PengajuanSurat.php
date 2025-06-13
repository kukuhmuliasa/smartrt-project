<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_surat'; // Eksplisit jika nama tabel berbeda

    protected $fillable = [
        'rt_id',
        'user_id_pemohon',
        'jenis_surat',
        'keperluan',
        'status_pengajuan', // ENUM('diajukan', 'diproses', 'disetujui', 'ditolak', 'selesai')
        'catatan_admin',
        'file_pendukung_pemohon',
        'file_surat_jadi',
        'tanggal_pengajuan', // Dikelola otomatis jika timestamp, atau perlu diisi manual
        'tanggal_selesai',
        'diproses_oleh_user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    /**
     * Mendapatkan data RT tempat surat diajukan.
     */
    public function rt()
    {
        return $this->belongsTo(Rt::class, 'rt_id');
    }

    /**
     * Mendapatkan data user (warga) yang mengajukan surat.
     */
    public function pemohon()
    {
        return $this->belongsTo(User::class, 'user_id_pemohon');
    }

    /**
     * Mendapatkan data user (admin) yang memproses surat.
     */
    public function diprosesOleh()
    {
        return $this->belongsTo(User::class, 'diproses_oleh_user_id');
    }
}
