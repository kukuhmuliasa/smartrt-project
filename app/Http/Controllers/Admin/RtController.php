<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- TAMBAHKAN BARIS INI
use App\Models\Rt;                   // <--- TAMBAHKAN BARIS INI

class RtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       // Tampilkan RT yang dikelola oleh admin ini
        // $rt = Auth::user()->rtYangDikelola; // Jika satu admin hanya kelola satu RT
        // Atau jika admin bisa kelola banyak RT (desain saat ini satu admin satu RT)
        $rts = Rt::where('ketua_rt_user_id', Auth::id())->get();
        if ($rts->isEmpty()) {
            // Jika admin belum punya RT, mungkin arahkan ke halaman create
            return redirect()->route('admin.rt.create')->with('info', 'Anda belum mendaftarkan RT. Silakan daftarkan RT Anda.');
        }
        return view('admin.rt.index', compact('rts')); // Buat view ini nanti //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      // Cek apakah admin sudah punya RT, jika ya, mungkin tidak boleh buat lagi (tergantung aturan bisnis)
        if (Auth::user()->rtYangDikelola()->exists()) {
             return redirect()->route('dashboard')->with('warning', 'Anda sudah mendaftarkan RT.');
        }
        return view('admin.rt.create'); // Buat view ini nanti  //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate([
            'nama_rt' => 'required|string|max:255',
            'alamat_rt' => 'required|string',
        ]);

        // Cek lagi, jaga-jaga jika admin mencoba post berkali-kali
        if (Auth::user()->rtYangDikelola()->exists()) {
            return redirect()->route('dashboard')->with('warning', 'Anda sudah mendaftarkan RT.');
        }

        $rt = Rt::create([
            'nama_rt' => $request->nama_rt,
            'alamat_rt' => $request->alamat_rt,
            'ketua_rt_user_id' => Auth::id(), // Set admin yang sedang login sebagai ketua RT
        ]);

        // Update juga rt_id di tabel users untuk admin ini
        $adminUser = Auth::user();
        $adminUser->rt_id = $rt->id;
        $adminUser->save();

        return redirect()->route('dashboard')->with('success', 'RT berhasil didaftarkan!');
        // Atau redirect ke halaman detail RT atau index RT admin
        // return redirect()->route('admin.rt.index')->with('success', 'RT berhasil didaftarkan!'); //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
