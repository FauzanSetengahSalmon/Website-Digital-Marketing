@component('mail::message')
# Verifikasi Email KWT Cibiru

Halo, selamat bergabung!

Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda agar dapat mengakses sistem manajemen KWT Cibiru.

@component('mail::button', ['url' => $url])
Verifikasi Email Saya
@endcomponent

Terima kasih,<br>
**Admin KWT Cibiru**
@endcomponent