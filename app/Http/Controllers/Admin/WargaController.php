<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Model User untuk warga
use App\Models\Rt;   // Mungkin diperlukan untuk info RT
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Untuk password
use Illuminate\Validation\Rules; // Untuk aturan validasi password jika diperlukan
use Illuminate\Validation\Rule; // Untuk validasi unique dengan ignore

class WargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admin = Auth::user();
        // Ambil semua user dengan role 'warga' yang rt_id nya sama dengan rt_id milik admin
        $wargas = User::where('rt_id', $admin->rt_id)
                        ->where('role', 'warga')
                        ->orderBy('name', 'asc') // Urutkan berdasarkan nama
                        ->paginate(10); // Gunakan paginasi

        return view('admin.warga.index', compact('wargas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tidak ada data khusus yang perlu dikirim ke view untuk form create saat ini
        return view('admin.warga.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class], // Pastikan email unik di tabel users
            'nik' => ['required', 'string', 'max:16', 'min:16', 'unique:'.User::class], // NIK unik dan 16 digit
            'nomor_kk' => ['nullable', 'string', 'max:16', 'min:16'],
            'telepon' => ['nullable', 'string', 'max:15'],
            'alamat_warga' => ['nullable', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()], // 'confirmed' akan mencari field password_confirmation
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nik' => $request->nik,
            'nomor_kk' => $request->nomor_kk,
            'telepon' => $request->telepon,
            'alamat_warga' => $request->alamat_warga,
            'password' => Hash::make($request->password),
            'role' => 'warga', // Otomatis set role sebagai warga
            'rt_id' => $admin->rt_id, // Set rt_id sesuai dengan RT yang dikelola admin
            'email_verified_at' => now(), // Anggap email langsung terverifikasi, atau buat proses verifikasi terpisah
        ]);

        return redirect()->route('admin.warga.index')->with('success', 'Data warga berhasil ditambahkan.');
    }
    public function edit(User $user) // $user adalah model Warga yang akan diedit
    {
        // Pastikan admin hanya bisa mengedit warga yang ada di RT-nya
        $adminRtId = Auth::user()->rt_id;
        if ($user->rt_id != $adminRtId || $user->role != 'warga') {
            // Jika warga tidak ditemukan di RT admin atau role-nya bukan warga, kembalikan error atau redirect
            return redirect()->route('admin.warga.index')->with('error', 'Data warga tidak ditemukan atau Anda tidak berhak mengubahnya.');
        }

        return view('admin.warga.edit', compact('user')); // Kirim data $user ke view
    }
    public function update(Request $request, User $user) // $user adalah model Warga yang akan diupdate
    {
        // Pastikan admin hanya bisa mengupdate warga yang ada di RT-nya
        $adminRtId = Auth::user()->rt_id;
        if ($user->rt_id != $adminRtId || $user->role != 'warga') {
            return redirect()->route('admin.warga.index')->with('error', 'Data warga tidak ditemukan atau Anda tidak berhak mengubahnya.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Rule::unique('users')->ignore($user->id) artinya email harus unik, kecuali untuk user ini sendiri
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nik' => ['required', 'string', 'max:16', 'min:16', Rule::unique('users')->ignore($user->id)],
            'nomor_kk' => ['nullable', 'string', 'max:16', 'min:16'],
            'telepon' => ['nullable', 'string', 'max:15'],
            'alamat_warga' => ['nullable', 'string'],
            // Password hanya divalidasi jika diisi (nullable, tapi jika diisi, harus confirmed dan memenuhi rules)
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $userData = $request->only(['name', 'email', 'nik', 'nomor_kk', 'telepon', 'alamat_warga']);

        // Hanya update password jika field password diisi
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('admin.warga.index')->with('success', 'Data warga berhasil diperbarui.');
    }
    public function destroy(User $user) // $user adalah model Warga yang akan dihapus
    {
        // Pastikan admin hanya bisa menghapus warga yang ada di RT-nya
        $adminRtId = Auth::user()->rt_id;
        if ($user->rt_id != $adminRtId || $user->role != 'warga') {
            // Jika warga tidak ditemukan di RT admin atau role-nya bukan warga, kembalikan error atau redirect
            return redirect()->route('admin.warga.index')->with('error', 'Data warga tidak ditemukan atau Anda tidak berhak menghapusnya.');
        }

        // Tambahan: Pastikan admin tidak menghapus dirinya sendiri jika admin juga terdaftar sebagai warga (seharusnya tidak terjadi dengan filter role 'warga')
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.warga.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri melalui menu ini.');
        }

        // Hapus user (warga)
        $user->delete();

        return redirect()->route('admin.warga.index')->with('success', 'Data warga berhasil dihapus.');
    }
    // Method edit, update, destroy akan kita isi nanti
}