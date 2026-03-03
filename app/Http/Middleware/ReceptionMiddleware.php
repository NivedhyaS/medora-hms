<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceptionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && (Auth::user()->role === 'reception' || Auth::user()->role === 'admin')) {
            return $next($request);
        }

        abort(403, 'Unauthorized access');
    }
}
