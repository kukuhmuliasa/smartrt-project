<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminHasRtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role == 'admin' && !Auth::user()->rt_id) {
            // Jika admin belum punya RT, redirect ke halaman pendaftaran RT
            return redirect()->route('admin.rt.create')->with('warning', 'Anda harus mendaftarkan RT terlebih dahulu untuk mengelola data warga.');
        }
        return $next($request);
    }
}