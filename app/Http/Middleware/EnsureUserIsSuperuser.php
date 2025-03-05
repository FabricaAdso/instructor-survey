<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsSuperuser
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica si el usuario está autenticado y es superusuario
        if (Auth::check() && Auth::user()->is_superuser) {
            return $next($request);
        }

        // Si no es superusuario, redirige al login del administrador con un mensaje de error
        return redirect()->route('login.admin')->withErrors(['error' => 'No tienes permisos de administrador.']);
    }
}
