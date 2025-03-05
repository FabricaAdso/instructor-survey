<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureCodeIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Verifica si el usuario ha pasado la verificación del código
        if (!session('code_verified')) {
            return redirect()->route('login')->withErrors(['error' => 'Debes verificar tu código primero.']);
        }

        return $next($request);
    }
}
