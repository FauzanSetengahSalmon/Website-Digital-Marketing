<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();
        $user = User::where('email', $googleUser->email)->first();
        if (!$user) {

            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'email_verified_at' => now(),
                'phone_number' => null,
                'province' => null,
                'city' => null,
                'district' => null,
                'address' => null,
            ]);
        }

        Auth::login($user);

        if (!$user->phone_number || !$user->address) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Lengkapi profil terlebih dahulu.');
        }

        return redirect('/dashboard');
    }
}