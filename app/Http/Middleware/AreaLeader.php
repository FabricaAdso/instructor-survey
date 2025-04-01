<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AreaLeader
{
    public function handle(Request $request, Closure $next)
    {
        dd('AreaLeader middleware');
        if (Auth::check() && Auth::user()->is_area_leader) {
            return $next($request);
        }

        return redirect()->route('login.admin')->withErrors(['error' => 'No tienes permisos de líder de área.'
        ]);
    }
}
