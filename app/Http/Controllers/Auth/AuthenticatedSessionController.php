<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login user
     */
    public function store(Request $request): RedirectResponse
    {
        // VALIDASI
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // CARI USER BERDASARKAN EMAIL
        $user = User::where('email', $request->email)->first();

        // JIKA EMAIL TIDAK ADA
        if (!$user) {
            return back()
                ->withErrors([
                    'email' => 'Email belum terdaftar di sistem.',
                ])
                ->withInput();
        }

        // JIKA PASSWORD SALAH
        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors([
                    'password' => 'Password yang Anda masukkan salah.',
                ])
                ->withInput();
        }

        // LOGIN USER
        Auth::login($user);

        $request->session()->regenerate();

        // AMBIL USER LOGIN
        $user = Auth::user();

        // REDIRECT BERDASARKAN ROLE
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->role === 'kwt') {
            return redirect()->intended(route('kwt.dashboard'));
        }

        return redirect()->intended(route('home'));
    }

    /**
     * Logout user
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
