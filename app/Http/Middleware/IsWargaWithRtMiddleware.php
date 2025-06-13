<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsWargaWithRtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user && $user->role == 'warga' && $user->rt_id) {
            return $next($request);
        }
        // Jika tidak memenuhi, bisa redirect ke dashboard atau tampilkan error
        return redirect('/dashboard')->with('error', 'Fitur ini hanya untuk warga yang terdaftar di RT.');
    }
}