<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PengajuanSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $admin = Auth::user();
        $rtId = $admin->rt_id;

        $query = PengajuanSurat::where('rt_id', $rtId)
                                ->with(['pemohon']) // Eager load relasi pemohon
                                ->orderBy('status_pengajuan', 'asc') // Prioritaskan yang belum diproses
                                ->orderBy('tanggal_pengajuan', 'desc');

        // TODO: Tambahkan filter berdasarkan status, jenis surat, tanggal, dll.
        // if ($request->filled('status')) {
        //    $query->where('status_pengajuan', $request->status);
        // }

        $pengajuanSurats = $query->paginate(10);

        return view('admin.surat.index', compact('pengajuanSurats'));//
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PengajuanSurat $pengajuanSurat)
    {
       $admin = Auth::user();
        $rtId = $admin->rt_id;

        // Pastikan pengajuan surat yang dilihat adalah milik RT admin
        if ($pengajuanSurat->rt_id != $rtId) {
            return redirect()->route('admin.surat.index')->with('error', 'Data pengajuan surat tidak ditemukan.');
        }

        // Eager load relasi yang dibutuhkan jika belum otomatis
        $pengajuanSurat->load(['pemohon', 'diprosesOleh']);

        // Opsi status untuk form update oleh admin
        $statusOptions = [
            'diajukan' => 'Diajukan',
            'diproses' => 'Diproses',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'selesai' => 'Selesai (Sudah Diambil/Dikirim)', // Atau status lain yang relevan
        ];

        return view('admin.surat.show', compact('pengajuanSurat', 'statusOptions')); //
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
       $admin = Auth::user();
        $rtId = $admin->rt_id;

        // Pastikan pengajuan surat yang diproses adalah milik RT admin
        if ($pengajuanSurat->rt_id != $rtId) {
            return redirect()->route('admin.surat.index')->with('error', 'Data pengajuan surat tidak ditemukan.');
        }

        $request->validate([
            'status_pengajuan' => ['required', 'string', Rule::in(['diajukan', 'diproses', 'disetujui', 'ditolak', 'selesai'])],
            'catatan_admin' => ['nullable', 'string', 'max:1000'],
            'file_surat_jadi' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $updateData = [
            'status_pengajuan' => $request->status_pengajuan,
            'catatan_admin' => $request->catatan_admin,
            'diproses_oleh_user_id' => $admin->id, // Catat siapa admin yang memproses
        ];

        // Jika status diubah menjadi 'selesai' atau 'disetujui' (tergantung alur), set tanggal selesai
        if (in_array($request->status_pengajuan, ['selesai', 'disetujui', 'ditolak'])) {
            $updateData['tanggal_selesai'] = now();
        } else {
            // Jika status kembali ke 'diproses' atau 'diajukan', null kan tanggal selesai
            $updateData['tanggal_selesai'] = null;
        }


        if ($request->hasFile('file_surat_jadi')) {
            // Hapus file surat jadi lama jika ada dan file baru diupload
            if ($pengajuanSurat->file_surat_jadi) {
                Storage::disk('public')->delete($pengajuanSurat->file_surat_jadi);
            }
            // Simpan file surat jadi baru
            $pemohon = $pengajuanSurat->pemohon;
            $fileName = 'SURATJADI_' . ($pemohon ? $pemohon->nik : 'unknown') . '_' . str_replace(' ', '_', $pengajuanSurat->jenis_surat) . '_' . time() . '.' . $request->file('file_surat_jadi')->getClientOriginalExtension();
            $filePath = $request->file('file_surat_jadi')->storeAs('surat_jadi', $fileName, 'public');
            $updateData['file_surat_jadi'] = $filePath;
        }

        $pengajuanSurat->update($updateData);

        // TODO: Kirim notifikasi ke warga (akan dibahas di tahap selanjutnya jika diperlukan)

        return redirect()->route('admin.surat.show', $pengajuanSurat->id)->with('success', 'Status pengajuan surat berhasil diperbarui.'); //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PengajuanSurat $pengajuanSurat)
    {
        //
    }
}
