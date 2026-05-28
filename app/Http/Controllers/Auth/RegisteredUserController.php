<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Show register page
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle register
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],

            'phone_number' => ['required'],

            // Perbaikan: Diubah menjadi nullable agar tidak wajib diisi saat pendaftaran awal
            'province' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'district' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CREATE USER
        |--------------------------------------------------------------------------
        */

        $user = User::create([
            'name' => $request->name,

            'email' => $request->email,

            'password' => Hash::make($request->password),

            'phone_number' => $request->phone_number,

            // Perbaikan: Jika data kosong, otomatis diisi NULL agar database tidak error
            'province' => $request->province ?? null,
            'city' => $request->city ?? null,
            'district' => $request->district ?? null,
            'address' => $request->address ?? null,

            'role' => 'customer',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SEND VERIFY EMAIL
        |--------------------------------------------------------------------------
        */

        event(new Registered($user));

        /*
        |--------------------------------------------------------------------------
        | LOGIN
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        return redirect()->route('verification.notice');
    }
}
