<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $pageSlug)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasPermission($pageSlug)) {
            return redirect()->route('dashboard')->with('error', '🔒 Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
