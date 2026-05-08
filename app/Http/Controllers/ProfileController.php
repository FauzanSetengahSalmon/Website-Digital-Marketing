<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Verified;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profile customer
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update profile user
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validated();

        // Update data user
        $user->fill([
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'phone_number' => $validated['phone_number'] ?? null,
            'province'     => $validated['province'] ?? null,
            'city'         => $validated['city'] ?? null,
            'district'     => $validated['district'] ?? null,
            'address'      => $validated['address'] ?? null,
        ]);

        /**
         * Kalau email diganti:
         * - reset verifikasi email
         * - kirim ulang verifikasi
         */
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;

            // Kirim ulang verifikasi email
            $user->sendEmailVerificationNotification();
        }

        $user->save();

        /**
         * Kalau login google dan profile masih kosong
         * maka setelah update profile redirect dashboard
         */
        if (
            empty($user->phone_number) ||
            empty($user->address)
        ) {
            return Redirect::route('profile.edit')
                ->with('success', 'Profile berhasil dilengkapi.');
        }

        return Redirect::route('profile.edit')
            ->with('success', 'Profile berhasil diperbarui.');
    }

    /**
     * Profile khusus KWT
     */
    public function editKwt(Request $request): View
    {
        return view('kwt.profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Hapus akun
     */
    public function destroy(Request $request): RedirectResponse
    {
        /**
         * Kalau user login pakai Google
         * password bisa kosong
         */
        if ($request->user()->password) {

            $request->validateWithBag('userDeletion', [
                'password' => ['required', 'current_password'],
            ]);
        }

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}