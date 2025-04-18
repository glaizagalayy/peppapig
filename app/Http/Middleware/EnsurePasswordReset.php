<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePasswordReset
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->password_reset_required) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
