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
            'biaya_layanan' => 2000
        ]);

        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'tarif_per_km' => 'required|numeric|min:0',
            'minimal_km' => 'required|numeric|min:0',
            'maksimal_km' => 'required|numeric|min:1',
            'biaya_layanan' => 'required|numeric|min:0',
        ]);

        $setting = Setting::first();
        $setting->update($request->all());

        return back()->with('success', 'Pengaturan Aplikasi & Tarif Pengiriman berhasil diperbarui!');
    }
}
