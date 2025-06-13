<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rt extends Model
{
 use HasFactory;

    protected $fillable = [
        'nama_rt',
        'alamat_rt',
        'ketua_rt_user_id',
    ];

    // Relasi ke User (Ketua RT)
    public function ketuaRt()
    {
        return $this->belongsTo(User::class, 'ketua_rt_user_id');
    }

    // Relasi ke Users (Warga RT)
    public function warga()
    {
        return $this->hasMany(User::class, 'rt_id');
    }
    public function iuranDiRt()
        {
    return $this->hasMany(IuranWarga::class, 'rt_id');
        }   
    public function pengajuanSuratDiRt()
    {
    return $this->hasMany(PengajuanSurat::class, 'rt_id');
    }
    //
}
