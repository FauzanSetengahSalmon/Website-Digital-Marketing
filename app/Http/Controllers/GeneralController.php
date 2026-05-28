<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GeneralController extends Controller
{
    public function sendBugReport(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'message' => 'required',
        ]);

        // Mengirim email (Pastikan .env sudah diatur konfigurasi emailnya)
        $data = [
            'email' => $request->email,
            'message' => $request->message
        ];

        // Contoh pengiriman email sederhana
        Mail::raw("Laporan dari: " . $data['email'] . "\n\nPesan: " . $data['message'], function ($message) use ($data) {
            $message->to('kwtdesacibiruwetanmart@gmail.com')
                ->subject('Laporan Bug dari Website KWT');
        });

        return back()->with('success', 'Laporan bug berhasil dikirim!');
    }
}
