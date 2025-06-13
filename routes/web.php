<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\RtController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\WargaController;
use App\Http\Controllers\Admin\IuranWargaController;
use App\Http\Controllers\Admin\PengajuanSuratController as AdminPengajuanSuratController;
use App\Http\Controllers\Warga\SuratController as WargaSuratController;
use App\Http\Controllers\ChatbotController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->role == 'admin') { // Lebih aman tambahkan auth()->check()
        // Logika dashboard admin
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute untuk Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... (semua rute admin Anda tetap di sini) ...
    // Rute untuk mengelola RT
    Route::get('/rt/create', [RtController::class, 'create'])->name('rt.create');
    Route::post('/rt', [RtController::class, 'store'])->name('rt.store');
    Route::get('/rts', [RtController::class, 'index'])->name('rt.index');

    // Rute untuk mengelola Warga (CRUD)
    Route::middleware(['checkAdminHasRt'])->group(function () {
        Route::get('/warga', [WargaController::class, 'index'])->name('warga.index');
        Route::get('/warga/create', [WargaController::class, 'create'])->name('warga.create');
        Route::post('/warga', [WargaController::class, 'store'])->name('warga.store');
        Route::get('/warga/{user}/edit', [WargaController::class, 'edit'])->name('warga.edit');
        Route::put('/warga/{user}', [WargaController::class, 'update'])->name('warga.update');
        Route::delete('/warga/{user}', [WargaController::class, 'destroy'])->name('warga.destroy');
    });

    // Rute untuk mengelola Iuran Warga (CRUD)
    Route::middleware(['checkAdminHasRt'])->group(function () {
        Route::get('/iuran', [IuranWargaController::class, 'index'])->name('iuran.index');
        Route::get('/iuran/create', [IuranWargaController::class, 'create'])->name('iuran.create');
        Route::post('/iuran', [IuranWargaController::class, 'store'])->name('iuran.store');
        Route::get('/iuran/{iuranWarga}/edit', [IuranWargaController::class, 'edit'])->name('iuran.edit');
        Route::put('/iuran/{iuranWarga}', [IuranWargaController::class, 'update'])->name('iuran.update');
        Route::delete('/iuran/{iuranWarga}', [IuranWargaController::class, 'destroy'])->name('iuran.destroy');
    });
    // Rute untuk mengelola Pengajuan Surat oleh Admin
    Route::middleware(['checkAdminHasRt'])->group(function () {
        Route::get('/surat', [AdminPengajuanSuratController::class, 'index'])->name('surat.index');
        Route::get('/surat/{pengajuanSurat}', [AdminPengajuanSuratController::class, 'show'])->name('surat.show');
        Route::get('/surat/{pengajuanSurat}/edit', [AdminPengajuanSuratController::class, 'edit'])->name('surat.edit');
        Route::put('/surat/{pengajuanSurat}', [AdminPengajuanSuratController::class, 'update'])->name('surat.update');
    });
}); // AKHIR GRUP ADMIN UTAMA


// Rute untuk Warga (HANYA FITUR SPESIFIK WARGA SEPERTI PENGAJUAN SURAT)
Route::middleware(['auth', 'verified'])->prefix('warga')->name('warga.')->group(function () {
    Route::middleware(['isWargaWithRt'])->group(function() {
        Route::get('/surat', [WargaSuratController::class, 'index'])->name('surat.index'); // Daftar surat saya
        Route::get('/surat/create', [WargaSuratController::class, 'create'])->name('surat.create'); // Form ajukan surat
        Route::post('/surat', [WargaSuratController::class, 'store'])->name('surat.store'); // Simpan pengajuan surat
        // Rute chatbot sudah dipindahkan
    });
});

// Rute yang Membutuhkan Autentikasi Umum (termasuk Chatbot dan Profile)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // == PINDAHKAN RUTE CHATBOT KE SINI ==
    Route::post('/chatbot/send', [ChatbotController::class, 'sendMessage'])->name('chatbot.send');
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
});

require __DIR__.'/auth.php'; // Perbaikan typo _DIR menjadi _DIR_