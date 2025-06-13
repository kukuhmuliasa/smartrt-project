<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Pastikan model Rt di-import jika belum
// use App\Models\Rt; // Anda mungkin sudah menambahkannya atau IDE Anda menambahkannya otomatis

class User extends Authenticatable // Bisa juga implements MustVerifyEmail jika Anda akan menggunakan verifikasi email
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable; // Jika Anda menggunakan trait lain seperti MustVerifyEmail, tambahkan di sini

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string> // Di versi PHP lebih baru, bisa juga list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nik', // Tambahkan ini
        'nomor_kk', // Tambahkan ini
        'alamat_warga', // Tambahkan ini
        'telepon', // Tambahkan ini
        'role', // Tambahkan ini
        'rt_id', // Tambahkan ini
        'email_verified_at', // Biasanya tidak diisi manual, tapi bisa masuk fillable jika ada kasus tertentu
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // 'hashed' memastikan password otomatis di-hash saat diset
        ];
    }

    // Relasi ke RT yang dikelola oleh user ini (jika dia admin)
    public function rtYangDikelola()
    {
        // Pastikan namespace Rt sudah benar atau model Rt sudah di-import
        return $this->hasOne(Rt::class, 'ketua_rt_user_id');
    }

    // Relasi ke RT tempat user ini tinggal (jika dia warga)
    public function rtTempatTinggal()
    {
        // Pastikan namespace Rt sudah benar atau model Rt sudah di-import
        return $this->belongsTo(Rt::class, 'rt_id');
    }
    public function iuranYangDibayar() // Iuran yang dibayar oleh user ini (jika dia warga)
        {
    return $this->hasMany(IuranWarga::class, 'user_id');
        }

    public function iuranYangDicatat() // Iuran yang dicatat oleh user ini (jika dia admin)
    {
    return $this->hasMany(IuranWarga::class, 'dicatat_oleh_user_id');
    }
    public function pengajuanSuratSebagaiPemohon()
    {
    return $this->hasMany(PengajuanSurat::class, 'user_id_pemohon');
    }

    public function pengajuanSuratYangDiproses() // Jika user adalah admin
    {
    return $this->hasMany(PengajuanSurat::class, 'diproses_oleh_user_id');
    }

    // Anda bisa menambahkan method lain atau relasi lain di sini nanti
}