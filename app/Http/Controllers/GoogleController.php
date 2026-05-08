<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Redirect Google
    |--------------------------------------------------------------------------
    */

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /*
    |--------------------------------------------------------------------------
    | Callback Google
    |--------------------------------------------------------------------------
    */

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        /*
        |--------------------------------------------------------------------------
        | CARI USER BERDASARKAN EMAIL
        |--------------------------------------------------------------------------
        */

        $user = User::where('email', $googleUser->email)->first();

        /*
        |--------------------------------------------------------------------------
        | EMAIL BELUM TERDAFTAR
        |--------------------------------------------------------------------------
        */

        if (!$user) {

            return redirect('/login')
                ->withErrors([
                    'email' => 'Email Google belum terdaftar.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | EMAIL BELUM VERIFY
        |--------------------------------------------------------------------------
        */

        if (!$user->hasVerifiedEmail()) {

            return redirect('/login')
                ->withErrors([
                    'email' => 'Silakan verifikasi email terlebih dahulu.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE GOOGLE ID
        |--------------------------------------------------------------------------
        */

        if (!$user->google_id) {

            $user->update([
                'google_id' => $googleUser->id,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | LOGIN
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        /*
        |--------------------------------------------------------------------------
        | PROFILE CHECK
        |--------------------------------------------------------------------------
        */

        if (
            !$user->phone_number ||
            !$user->province ||
            !$user->city ||
            !$user->district ||
            !$user->address
        ) {

            return redirect()
                ->route('profile.edit')
                ->with('warning', 'Lengkapi profil terlebih dahulu.');
        }

        return redirect('/dashboard');
    }
}