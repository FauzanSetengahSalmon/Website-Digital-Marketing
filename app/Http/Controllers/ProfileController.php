<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * PROFILE CUSTOMER
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * PROFILE KWT
     */
    public function editKwt(Request $request): View
    {
        return view('kwt.profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * PROFILE ADMIN
     */
    public function editAdmin(Request $request): View
    {
        return view('admin.profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * UPDATE PROFILE
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Update data
        $user->fill($request->validated());

        // Jika email berubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        /**
         * REDIRECT BERDASARKAN ROLE
         */
        if ($user->role === 'admin') {
            return Redirect::route('admin.profile')
                ->with('success', 'Profil admin berhasil diperbarui.');
        }

        if ($user->role === 'kwt') {
            return Redirect::route('kwt.profile')
                ->with('success', 'Profil KWT berhasil diperbarui.');
        }

        return Redirect::route('profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * UPDATE FOTO PROFILE
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_photo' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048'
            ],
        ]);

        try {

            $user = Auth::user();

            if ($request->hasFile('profile_photo')) {

                // Hapus foto lama
                if ($user->profile_photo) {
                    Storage::disk('public')
                        ->delete($user->profile_photo);
                }

                // Upload baru
                $path = $request->file('profile_photo')
                    ->store('profile-photos', 'public');

                $user->profile_photo = $path;
                $user->save();

                return back()->with(
                    'success',
                    'Foto profil berhasil diperbarui.'
                );
            }

            return back()->with(
                'error',
                'File foto tidak ditemukan.'
            );
        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Terjadi kesalahan saat upload foto.'
            );
        }
    }

    /**
     * HAPUS AKUN
     */
    public function destroy(Request $request): RedirectResponse
    {
        if ($request->user()->password) {

            $request->validateWithBag('userDeletion', [
                'password' => ['required', 'current_password'],
            ]);
        }

        $user = $request->user();

        Auth::logout();

        // Hapus foto profile
        if ($user->profile_photo) {
            Storage::disk('public')
                ->delete($user->profile_photo);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}