<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isMember()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }
        return $next($request);
    }
}
