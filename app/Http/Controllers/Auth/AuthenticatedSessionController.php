<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Ambil data user yang baru saja login
        $user = Auth::user();

        // PENGALIHAN KHUSUS BERDASARKAN ROLE
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        } 
        
        if ($user->role === 'kwt') {
            // Ini akan melempar KWT langsung ke http://127.0.0.1:8000/kwt/dashboard
            return redirect()->intended(route('kwt.dashboard'));
        }

        // Jika dia Customer biasa, ke Home atau Dashboard biasa
        return redirect()->intended(route('home'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}