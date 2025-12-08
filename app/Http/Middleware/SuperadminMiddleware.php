<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SuperadminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Memeriksa apakah pengguna sudah login dan memiliki role superadmin (is_admin == 1)
        if (Auth::check() && Auth::user()->is_admin == 1) {
            return $next($request);
        }
    
        // Jika bukan superadmin, simpan pesan error di session dan arahkan kembali ke halaman login
        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
    
}
