<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (session('user_role') !== $role) {
            return redirect()->route('EvaluasiPasca.homepage')->with('error', 'Anda tidak memiliki akses.');
        }

        return $next($request);
    }
}
