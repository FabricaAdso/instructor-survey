<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureCodeIsVerified
{
    public function handle(Request $request, Closure $next)
{
    if (!Auth::check()) {
        \Log::info('Middleware: Usuario no autenticado');
        return redirect()->route('login');
    }

    if (!session('code_verified')) {
        \Log::info('Middleware: Código no verificado', ['user_id' => Auth::id()]);
        return redirect()->route('login')->withErrors(['error' => 'Debes verificar tu código primero.']);
    }

    \Log::info('Middleware: Código verificado', ['user_id' => Auth::id()]);
    return $next($request);
}
}
