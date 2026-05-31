<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectHomeBerdasarkanRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika ada user yang login mencoba mengakses halaman utama '/'
        if (Auth::check()) {
            $user = Auth::user();

            // Usir Admin ke dashboard Admin
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Usir KWT ke dashboard KWT
            if ($user->role === 'kwt') {
                return redirect()->route('kwt.dashboard');
            }

            // Jika rolenya customer, biarkan (tidak di-redirect)
        }
        return $next($request);
    }
}
