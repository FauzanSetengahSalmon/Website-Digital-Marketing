<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

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
     * Update profile user (Data Teks)
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Mengisi data berdasarkan apa yang sudah divalidasi di ProfileUpdateRequest
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
        }

        $user->save();

        // LOGIC PERBAIKAN: Redirect sesuai Role agar tidak nyasar
        if ($user->role === 'kwt') {
            return Redirect::route('kwt.profile')->with('success', 'Profil KWT berhasil diperbarui.');
        }

        return Redirect::route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update Foto Profile
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        try {
            $user = Auth::user();

            if ($request->hasFile('profile_photo')) {
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }

                $path = $request->file('profile_photo')->store('profile-photos', 'public');

                $user->profile_photo = $path;
                $user->save();

                return back()->with('success', 'Foto profil berhasil diperbarui!');
            }
            
            return back()->with('error', 'File tidak ditemukan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
        if ($request->user()->password) {
            $request->validateWithBag('userDeletion', [
                'password' => ['required', 'current_password'],
            ]);
        }

        $user = $request->user();
        Auth::logout();
        
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}