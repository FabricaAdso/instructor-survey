<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsSuperuser
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_superuser) {
            return $next($request);
        }

        return redirect()->route('login.admin')->withErrors(['error' => 'No tienes permisos de administrador.']);
    }
}
