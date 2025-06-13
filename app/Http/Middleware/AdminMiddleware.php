<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role == 'admin') {
            return $next($request);
        }

        // Jika bukan admin, redirect ke halaman dashboard biasa atau halaman lain
        // atau tampilkan error 403 (Forbidden)
        // return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses admin.');
        abort(403, 'ANDA TIDAK MEMILIKI AKSES SEBAGAI ADMIN.');
    }
}