<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IuranWarga;
use App\Models\User; // Untuk mengambil daftar warga
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;


class IuranWargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $admin = Auth::user();
        $rtId = $admin->rt_id;

        // Query dasar untuk mengambil iuran di RT admin
        $query = IuranWarga::where('rt_id', $rtId)
                            ->with(['warga', 'pencatat']) // Eager load relasi untuk efisiensi
                            ->orderBy('tahun', 'desc')
                            ->orderBy('bulan', 'desc')
                            ->orderBy('tanggal_bayar', 'desc');

        // TODO: Tambahkan fungsionalitas filter di sini (berdasarkan warga, bulan, tahun, jenis iuran, status)
        // Misalnya:
        // if ($request->has('warga_id') && $request->warga_id != '') {
        //    $query->where('user_id', $request->warga_id);
        // }
        // if ($request->has('bulan') && $request->bulan != '') {
        //    $query->where('bulan', $request->bulan);
        // }
        // ... dan seterusnya

        $iurans = $query->paginate(15); // Paginasi daftar iuran

        // Ambil daftar warga di RT admin untuk filter (jika diperlukan)
        // $wargas = User::where('rt_id', $rtId)->where('role', 'warga')->orderBy('name')->get();

        return view('admin.iuran.index', compact('iurans' /*, 'wargas'*/));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      $admin = Auth::user();
        $rtId = $admin->rt_id;

        // Ambil daftar warga dari RT admin untuk dropdown
        $wargas = User::where('rt_id', $rtId)
                        ->where('role', 'warga')
                        ->orderBy('name', 'asc')
                        ->get();

        // Data lain yang mungkin diperlukan untuk form, contoh:
        $jenisIuranOptions = ['Keamanan', 'Kebersihan', 'Kas RT', 'Lainnya']; // Bisa dari database nanti
        $bulanOptions = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $tahunSekarang = date('Y');
        $statusPembayaranOptions = [
            'belum_lunas' => 'Belum Lunas',
            'lunas' => 'Lunas',
            'menunggu_verifikasi' => 'Menunggu Verifikasi'
        ];


        if ($wargas->isEmpty()) {
            return redirect()->route('admin.iuran.index')->with('warning', 'Belum ada data warga di RT Anda. Silakan tambahkan data warga terlebih dahulu.');
        }

        return view('admin.iuran.create', compact(
            'wargas',
            'jenisIuranOptions',
            'bulanOptions',
            'tahunSekarang',
            'statusPembayaranOptions'
        ));
       //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $admin = Auth::user();
        $rtId = $admin->rt_id;

        $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')->where(function ($query) use ($rtId) {
                // Pastikan user_id adalah warga di RT admin saat ini
                return $query->where('rt_id', $rtId)->where('role', 'warga');
            })],
            'jenis_iuran' => ['required', 'string', 'max:255'],
            'bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 5)],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'tanggal_bayar' => ['required', 'date'],
            'status_pembayaran' => ['required', 'string', Rule::in(['belum_lunas', 'lunas', 'menunggu_verifikasi'])],
            'bukti_pembayaran' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'], // Validasi untuk gambar maks 2MB
            'keterangan' => ['nullable', 'string'],
        ]);

        // Cek duplikasi iuran (opsional, tergantung aturan bisnis)
        // Contoh: Mencegah duplikasi jenis iuran yang sama untuk warga, bulan, dan tahun yang sama
        $existingIuran = IuranWarga::where('user_id', $request->user_id)
                                ->where('rt_id', $rtId)
                                ->where('jenis_iuran', $request->jenis_iuran)
                                ->where('bulan', $request->bulan)
                                ->where('tahun', $request->tahun)
                                ->first();

        if ($existingIuran) {
            return redirect()->back()->withInput()->with('error', 'Catatan iuran untuk jenis, warga, bulan, dan tahun yang sama sudah ada.');
        }

        $filePath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            // Simpan file bukti pembayaran
            // Nama file unik: nik_warga_jenis_iuran_bulan_tahun.extensi
            $warga = User::find($request->user_id);
            $fileName = $warga->nik . '_' . str_replace(' ', '_', $request->jenis_iuran) . '_' . $request->bulan . '_' . $request->tahun . '.' . $request->file('bukti_pembayaran')->getClientOriginalExtension();
            $filePath = $request->file('bukti_pembayaran')->storeAs('bukti_iuran', $fileName, 'public'); // Simpan di storage/app/public/bukti_iuran
        }

        IuranWarga::create([
            'rt_id' => $rtId,
            'user_id' => $request->user_id,
            'jenis_iuran' => $request->jenis_iuran,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'jumlah' => $request->jumlah,
            'tanggal_bayar' => $request->tanggal_bayar,
            'status_pembayaran' => $request->status_pembayaran,
            'bukti_pembayaran' => $filePath,
            'keterangan' => $request->keterangan,
            'dicatat_oleh_user_id' => $admin->id,
        ]);

        // Jika menggunakan penyimpanan 'public', jalankan `php artisan storage:link`
        // agar file bisa diakses dari URL public.

        return redirect()->route('admin.iuran.index')->with('success', 'Catatan iuran berhasil ditambahkan.');
     //
    }

    /**
     * Display the specified resource.
     */
    public function show(IuranWarga $iuranWarga)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IuranWarga $iuranWarga)
    {
      $admin = Auth::user();
        $rtId = $admin->rt_id;

        // Pastikan iuran yang diedit adalah milik RT admin
        if ($iuranWarga->rt_id != $rtId) {
            return redirect()->route('admin.iuran.index')->with('error', 'Data iuran tidak ditemukan atau Anda tidak berhak mengubahnya.');
        }

        // Ambil daftar warga dari RT admin untuk dropdown
        $wargas = User::where('rt_id', $rtId)
                        ->where('role', 'warga')
                        ->orderBy('name', 'asc')
                        ->get();

        $jenisIuranOptions = ['Keamanan', 'Kebersihan', 'Kas RT', 'Lainnya'];
        $bulanOptions = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $tahunSekarang = date('Y');
        $statusPembayaranOptions = [
            'belum_lunas' => 'Belum Lunas',
            'lunas' => 'Lunas',
            'menunggu_verifikasi' => 'Menunggu Verifikasi'
        ];

        return view('admin.iuran.edit', compact(
            'iuranWarga', // Data iuran yang akan diedit
            'wargas',
            'jenisIuranOptions',
            'bulanOptions',
            'tahunSekarang',
            'statusPembayaranOptions'
        ));
      //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IuranWarga $iuranWarga)
    {
       $admin = Auth::user();
        $rtId = $admin->rt_id;

        // Pastikan iuran yang diedit adalah milik RT admin
        if ($iuranWarga->rt_id != $rtId) {
            return redirect()->route('admin.iuran.index')->with('error', 'Data iuran tidak ditemukan atau Anda tidak berhak mengubahnya.');
        }

        $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')->where(function ($query) use ($rtId) {
                return $query->where('rt_id', $rtId)->where('role', 'warga');
            })],
            'jenis_iuran' => ['required', 'string', 'max:255'],
            'bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 5)],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'tanggal_bayar' => ['required', 'date'],
            'status_pembayaran' => ['required', 'string', Rule::in(['belum_lunas', 'lunas', 'menunggu_verifikasi'])],
            'bukti_pembayaran' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            'keterangan' => ['nullable', 'string'],
        ]);

        // Cek duplikasi iuran (opsional, mirip dengan di store, tapi abaikan record saat ini)
        $existingIuran = IuranWarga::where('user_id', $request->user_id)
                                ->where('rt_id', $rtId)
                                ->where('jenis_iuran', $request->jenis_iuran)
                                ->where('bulan', $request->bulan)
                                ->where('tahun', $request->tahun)
                                ->where('id', '!=', $iuranWarga->id) // Abaikan record saat ini
                                ->first();

        if ($existingIuran) {
            return redirect()->back()->withInput()->with('error', 'Catatan iuran untuk jenis, warga, bulan, dan tahun yang sama sudah ada untuk entri lain.');
        }

        $updateData = $request->only([
            'user_id', 'jenis_iuran', 'bulan', 'tahun', 'jumlah',
            'tanggal_bayar', 'status_pembayaran', 'keterangan'
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            // Hapus file bukti lama jika ada dan file baru diupload
            if ($iuranWarga->bukti_pembayaran) {
                Storage::disk('public')->delete($iuranWarga->bukti_pembayaran);
            }
            // Simpan file bukti pembayaran baru
            $warga = User::find($request->user_id); // Ambil NIK warga untuk nama file
            $fileName = ($warga ? $warga->nik : 'unknown') . '_' . str_replace(' ', '_', $request->jenis_iuran) . '_' . $request->bulan . '_' . $request->tahun . '.' . $request->file('bukti_pembayaran')->getClientOriginalExtension();
            $filePath = $request->file('bukti_pembayaran')->storeAs('bukti_iuran', $fileName, 'public');
            $updateData['bukti_pembayaran'] = $filePath;
        }

        // Update data utama
        $iuranWarga->update($updateData);

        return redirect()->route('admin.iuran.index')->with('success', 'Catatan iuran berhasil diperbarui.'); //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IuranWarga $iuranWarga)
    {
       $admin = Auth::user();
        $rtId = $admin->rt_id;

        // Pastikan iuran yang dihapus adalah milik RT admin
        if ($iuranWarga->rt_id != $rtId) {
            return redirect()->route('admin.iuran.index')->with('error', 'Data iuran tidak ditemukan atau Anda tidak berhak menghapusnya.');
        }

        // Hapus file bukti pembayaran dari storage jika ada
        if ($iuranWarga->bukti_pembayaran) {
            Storage::disk('public')->delete($iuranWarga->bukti_pembayaran);
        }

        // Hapus record iuran dari database
        $iuranWarga->delete();

        return redirect()->route('admin.iuran.index')->with('success', 'Catatan iuran berhasil dihapus.'); //
    }
}
