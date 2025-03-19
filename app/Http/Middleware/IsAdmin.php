<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('IsAdmin Middleware triggered.');

        if (!Auth::check()) {
            Log::info('User not logged in. Redirecting to login.');
            return redirect()->route('login')->with('error', 'Please login first');
        }

        Log::info('User logged in:', ['user' => Auth::user()]);

        if (Auth::user()->role !== 'admin') {
            Log::info('User is not admin. Redirecting to login.');
            return redirect()->route('login')->with('error', 'Access denied! Admins only.');
        }

        Log::info('User is admin. Access granted.');
        return $next($request);
    }
}
