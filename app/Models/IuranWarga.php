<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IuranWarga extends Model
{
    use HasFactory;

    protected $table = 'iuran_warga'; // Eksplisit menyebut nama tabel jika berbeda dari penamaan jamak model

    protected $fillable = [
        'rt_id',
        'user_id', // Warga yang membayar
        'jenis_iuran',
        'bulan',
        'tahun',
        'jumlah',
        'tanggal_bayar',
        'status_pembayaran',
        'bukti_pembayaran', // Path ke file jika ada
        'keterangan',
        'dicatat_oleh_user_id', // Admin yang mencatat
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah' => 'decimal:2', // Jika Anda menyimpan sebagai decimal
    ];

    /**
     * Mendapatkan data RT terkait iuran ini.
     */
    public function rt()
    {
        return $this->belongsTo(Rt::class, 'rt_id');
    }

    /**
     * Mendapatkan data warga yang membayar iuran ini.
     */
    public function warga()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Mendapatkan data admin yang mencatat iuran ini.
     */
    public function pencatat()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh_user_id');
    }
}
