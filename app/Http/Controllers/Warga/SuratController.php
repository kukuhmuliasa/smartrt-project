<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Auth; // <--- Penting untuk Auth::user()

class SuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $user = Auth::user();
        $pengajuanSurats = PengajuanSurat::where('user_id_pemohon', $user->id)
                                        ->orderBy('tanggal_pengajuan', 'desc')
                                        ->paginate(10);

        return view('warga.surat.index', compact('pengajuanSurats')); //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Anda bisa definisikan jenis surat yang umum di sini atau ambil dari database/config
        $jenisSuratOptions = [
            'Surat Pengantar KTP',
            'Surat Pengantar KK',
            'Surat Keterangan Domisili',
            'Surat Keterangan Tidak Mampu',
            'Surat Keterangan Usaha',
            'Surat Keterangan Kematian',
            'Lainnya'
        ];
        return view('warga.surat.create', compact('jenisSuratOptions'));//
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'jenis_surat' => ['required', 'string', 'max:255'],
            'keperluan' => ['required', 'string', 'max:1000'],
            'file_pendukung_pemohon' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'], // Contoh validasi
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung_pemohon')) {
            // Simpan file pendukung
            // Nama file unik: nik_pemohon_jenis_surat_timestamp.extensi
            $fileName = $user->nik . '_' . str_replace(' ', '_', $request->jenis_surat) . '_' . time() . '.' . $request->file('file_pendukung_pemohon')->getClientOriginalExtension();
            $filePath = $request->file('file_pendukung_pemohon')->storeAs('file_pendukung_surat', $fileName, 'public'); // Simpan di storage/app/public/file_pendukung_surat
        }

        PengajuanSurat::create([
            'rt_id' => $user->rt_id, // rt_id diambil dari data user yang login
            'user_id_pemohon' => $user->id,
            'jenis_surat' => $request->jenis_surat,
            'keperluan' => $request->keperluan,
            'status_pengajuan' => 'diajukan', // Status awal
            'file_pendukung_pemohon' => $filePath,
            'tanggal_pengajuan' => now(),//
            ]);
            return redirect()->route('warga.surat.index')->with('success', 'Pengajuan surat Anda berhasil dikirim.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PengajuanSurat $pengajuanSurat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PengajuanSurat $pengajuanSurat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PengajuanSurat $pengajuanSurat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PengajuanSurat $pengajuanSurat)
    {
        //
    }
}
