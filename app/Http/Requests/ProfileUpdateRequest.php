<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],

            // Email harus unik kecuali untuk user itu sendiri
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],

            // Dibuat nullable agar tidak wajib diisi semua
            'phone_number' => ['nullable', 'string', 'max:25'],
            'province' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],

            // TAMBAHAN BARU: RT, RW, dan Detail Tambahan Alamat
            'rt' => ['nullable', 'string', 'max:5'], // Validasi RT (Maksimal 5 karakter)
            'rw' => ['nullable', 'string', 'max:5'], // Validasi RW (Maksimal 5 karakter)
            'address_detail' => ['nullable', 'string', 'max:255'], // Validasi No.Rumah/Blok/Patokan

            // TAMBAHAN UNTUK OPENSTREETMAP & DETAIL LOKASI
            'subdistrict' => ['nullable', 'string', 'max:255'], // Kelurahan / Desa
            'latitude' => ['nullable', 'numeric', 'between:-90,90'], // Titik koordinat Lintang
            'longitude' => ['nullable', 'numeric', 'between:-180,180'], // Titik koordinat Bujur
        ];
    }
}
