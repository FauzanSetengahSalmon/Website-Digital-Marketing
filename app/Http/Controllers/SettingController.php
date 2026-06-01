<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Ambil data. Jika belum ada di tabel, otomatis buatkan defaultnya.
        $setting = Setting::firstOrCreate(['id' => 1], [
            'tarif_per_km' => 2000,
            'minimal_km' => 1,
            'maksimal_km' => 15,
            'biaya_layanan' => 2000,
            'batas_jumlah_barang' => 0, 
            'biaya_tambahan_per_barang' => 0 
        ]);

        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'tarif_per_km' => 'required|numeric|min:0',
            'minimal_km' => 'required|numeric|min:0',
            'maksimal_km' => 'required|numeric|min:1',
            'biaya_layanan' => 'required|numeric|min:0',
            'batas_jumlah_barang' => 'required|numeric|min:0',
            'biaya_tambahan_per_barang' => 'required|numeric|min:0',
        ]);

        $setting = Setting::first();

        // Update menggunakan data yang sudah tervalidasi agar lebih aman
        $setting->update($validated);

        return back()->with('success', 'Pengaturan Aplikasi & Tarif Pengiriman berhasil diperbarui!');
    }
}
