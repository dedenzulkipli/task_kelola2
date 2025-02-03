<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next, $role)
    {
        // Cek apakah pengguna terautentikasi dan memiliki role yang sesuai
        if (Auth::check() && Auth::user()->role_name === $role) {
            return $next($request);
        }

        // Jika tidak sesuai, redirect ke login dengan pesan error
        return redirect('login')->with('error', 'Unauthorized access.');
    }
}
