<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Logika Pengaman HTTPS Bawaan Kamu
        if (config('app.env') === 'local' || env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verifikasi Alamat Email Akun KWT Cibiru')
                ->greeting('Halo, Selamat Datang!')
                ->line('Silakan klik tombol di bawah ini untuk memverifikasi alamat email akun Anda agar dapat mulai bertransaksi komoditas tani.')
                ->action('Verifikasi Email Saya', $url)
                ->line('Jika Anda tidak merasa mendaftarkan akun di aplikasi KWT Cibiru, abaikan saja email ini.')
                ->salutation("Salam Hangat,\nPengurus KWT Cibiru");
        });
    }
}