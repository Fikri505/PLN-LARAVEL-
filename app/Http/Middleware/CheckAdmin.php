<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', '🔒 Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
