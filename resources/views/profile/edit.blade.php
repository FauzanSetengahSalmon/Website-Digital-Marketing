@extends('layouts.app')

@section('title', 'Profil Saya - KWT Cibiru')

@push('styles')
<style>
    :root {
        --green-dark: #166534;
        --green-primary: #22c55e;
        --green-soft: #dcfce7;
        --green-light: #f0fdf4;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --border-soft: #e2e8f0;
    }

    body {
        background: #f8fafc;
    }

    .profile-wrapper {
        animation: fadeUp .5s ease;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(18px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .profile-card {
        background: white;
        border-radius: 28px;
        overflow: hidden;
        border: 1px solid #edf2f7;
    }

    .profile-header {
        background:
            linear-gradient(rgba(0, 0, 0, .28), rgba(0, 0, 0, .28)),
            linear-gradient(135deg, #166534, #22c55e);
        padding: 42px;
        position: relative;
    }

    .profile-header::after {
        content: '';
        position: absolute;
        right: -50px;
        top: -50px;
        width: 180px;
        height: 180px;
        background: rgba(255, 255, 255, .08);
        border-radius: 50%;
    }

    .profile-avatar {
        width: 68px;
        height: 68px;
        border-radius: 20px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .profile-avatar i {
        font-size: 30px;
        color: var(--green-dark);
    }

    .profile-body {
        padding: 38px;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 20px;
    }

    .form-label {
        font-size: .87rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 15px;
        border: 1.5px solid var(--border-soft);
        background: #f8fafc;
        padding: 13px 15px;
        font-size: .92rem;
        transition: .25s;
    }

    .form-control:focus {
        border-color: var(--green-primary);
        box-shadow: 0 0 0 4px rgba(34, 197, 94, .12);
        background: white;
    }

    textarea.form-control {
        resize: none;
    }

    .btn-save {
        background: var(--green-dark);
        border: none;
        color: white;
        padding: 12px 28px;
        border-radius: 14px;
        font-weight: 700;
        transition: .25s;
    }

    .btn-save:hover {
        background: #15803d;
        transform: translateY(-2px);
    }

    .side-card {
        background: white;
        border-radius: 28px;
        border: 1px solid #edf2f7;
        overflow: hidden;
    }

    .avatar-big {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
        border: 5px solid rgba(255, 255, 255, .25);
        box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
    }

    .avatar-big span {
        font-size: 42px;
        font-weight: 800;
        color: var(--green-dark);
    }

    .info-box {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 15px;
        border-radius: 18px;
        background: #f8fafc;
        margin-bottom: 15px;
    }

    .info-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: var(--green-light);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .info-icon i {
        color: var(--green-dark);
        font-size: 1rem;
    }

    .info-title {
        font-size: .75rem;
        color: var(--text-muted);
        margin-bottom: 3px;
    }

    .info-value {
        font-size: .92rem;
        font-weight: 600;
        color: var(--text-dark);
        line-height: 1.5;
        word-break: break-word;
    }

    .security-box {
        border-top: 1px dashed #e2e8f0;
        margin-top: 38px;
        padding-top: 30px;
    }

    .alert-custom {
        border-radius: 18px;
        border: none;
    }

    .badge-customer {
        background: rgba(255, 255, 255, .18);
        border: 1px solid rgba(255, 255, 255, .18);
        color: white;
        padding: 8px 16px;
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 700;
        display: inline-block;
        backdrop-filter: blur(10px);
    }
</style>
@endpush

@section('content')

<div class="container py-5 profile-wrapper">

    <div class="mb-4">
        <h3 class="fw-bold text-dark mb-1">
            Profil <span class="text-success">Pelanggan</span>
        </h3>

        <p class="text-muted mb-0">
            Kelola informasi akun dan alamat pengiriman Anda dengan mudah.
        </p>
    </div>

    {{-- ALERT --}}
    @if (session('status') === 'profile-updated')
    <div class="alert alert-success alert-dismissible fade show shadow-sm alert-custom mb-4">
        <i class="bi bi-check-circle-fill me-2"></i>
        Profil berhasil diperbarui.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session('status') === 'password-updated')
    <div class="alert alert-success alert-dismissible fade show shadow-sm alert-custom mb-4">
        <i class="bi bi-shield-check me-2"></i>
        Password berhasil diperbarui.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">

        {{-- LEFT --}}
        <div class="col-xl-8">

            <div class="profile-card shadow-sm">

                {{-- HEADER --}}
                <div class="profile-header text-white">
                    <div class="d-flex align-items-center gap-3 position-relative" style="z-index:2;">

                        <div class="profile-avatar shadow-sm">
                            <i class="bi bi-person-fill"></i>
                        </div>

                        <div>
                            <h4 class="fw-bold mb-1">
                                Detail Profil Customer
                            </h4>

                            <p class="mb-0 opacity-75 small">
                                Pastikan data pengiriman sudah benar agar produk sampai tepat waktu.
                            </p>
                        </div>

                    </div>
                </div>

                {{-- BODY --}}
                <div class="profile-body">

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="section-title">
                            Informasi Akun
                        </div>

                        <div class="row g-4">

                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    value="{{ old('name', $user->name) }}"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>

                                <input
                                    type="email"
                                    class="form-control bg-light"
                                    value="{{ $user->email }}"
                                    readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nomor WhatsApp</label>

                                <input
                                    type="text"
                                    name="phone_number"
                                    class="form-control"
                                    value="{{ old('phone_number', $user->phone_number) }}"
                                    placeholder="08xxxxxxxxxx">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Provinsi</label>

                                <input
                                    type="text"
                                    name="province"
                                    class="form-control"
                                    value="{{ old('province', $user->province) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kota / Kabupaten</label>

                                <input
                                    type="text"
                                    name="city"
                                    class="form-control"
                                    value="{{ old('city', $user->city) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kecamatan</label>

                                <input
                                    type="text"
                                    name="district"
                                    class="form-control"
                                    value="{{ old('district', $user->district) }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Alamat Lengkap</label>

                                <textarea
                                    name="address"
                                    rows="4"
                                    class="form-control"
                                    placeholder="Masukkan alamat lengkap">{{ old('address', $user->address) }}</textarea>
                            </div>

                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn-save shadow-sm">
                                <i class="bi bi-floppy me-1"></i>
                                Simpan Profil
                            </button>
                        </div>

                    </form>

                    {{-- PASSWORD --}}
                    <div class="security-box">

                        <h6 class="fw-bold text-dark mb-1">
                            <i class="bi bi-shield-lock-fill text-danger me-2"></i>
                            Keamanan Akun
                        </h6>

                        <p class="text-muted small mb-4">
                            Gunakan password yang kuat untuk menjaga keamanan akun Anda.
                        </p>

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')

                            <div class="row g-3">

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Password Saat Ini
                                    </label>

                                    <input
                                        type="password"
                                        name="current_password"
                                        class="form-control @if($errors->updatePassword->has('current_password')) is-invalid @endif">

                                    @if($errors->updatePassword->has('current_password'))
                                    <div class="invalid-feedback">
                                        Password saat ini salah.
                                    </div>
                                    @endif
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Password Baru
                                    </label>

                                    <input
                                        type="password"
                                        name="password"
                                        class="form-control @if($errors->updatePassword->has('password')) is-invalid @endif">

                                    @if($errors->updatePassword->has('password'))
                                    <div class="invalid-feedback">
                                        Password minimal 8 karakter.
                                    </div>
                                    @endif
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Konfirmasi Password
                                    </label>

                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        class="form-control">
                                </div>

                            </div>

                            <button type="submit" class="btn btn-dark rounded-4 mt-4 px-4 py-2 fw-semibold">
                                Ganti Password
                            </button>

                        </form>

                    </div>

                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="col-xl-4">

            <div class="side-card shadow-sm border-0 overflow-hidden">

                {{-- TOP --}}
                <div class="text-center px-4 pt-5 pb-4"
                    style="background: linear-gradient(135deg,#166534,#22c55e);">

                    <div class="avatar-big mb-3">
                        <span>
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                    </div>

                    <h4 class="fw-bold text-white mb-1">
                        {{ Auth::user()->name }}
                    </h4>

                    <p class="text-white opacity-75 small mb-3">
                        Pelanggan KWT Cibiru
                    </p>

                    <div class="badge-customer">
                        Customer Aktif
                    </div>

                </div>

                {{-- CONTENT --}}
                <div class="p-4">

                    <div class="info-box">
                        <div class="info-icon">
                            <i class="bi bi-envelope-fill"></i>
                        </div>

                        <div>
                            <div class="info-title">Email</div>

                            <div class="info-value">
                                {{ Auth::user()->email }}
                            </div>
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-icon">
                            <i class="bi bi-telephone-fill"></i>
                        </div>

                        <div>
                            <div class="info-title">Nomor Telepon</div>

                            <div class="info-value">
                                {{ Auth::user()->phone_number ?: 'Belum diisi' }}
                            </div>
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>

                        <div>
                            <div class="info-title">Alamat Pengiriman</div>

                            <div class="info-value">

                                @if(
                                Auth::user()->address ||
                                Auth::user()->district ||
                                Auth::user()->city ||
                                Auth::user()->province
                                )

                                {{ Auth::user()->address }}

                                @if(Auth::user()->district)
                                , {{ Auth::user()->district }}
                                @endif

                                @if(Auth::user()->city)
                                , {{ Auth::user()->city }}
                                @endif

                                @if(Auth::user()->province)
                                , {{ Auth::user()->province }}
                                @endif

                                @else
                                Belum diisi
                                @endif

                            </div>
                        </div>
                    </div>

                    <div class="info-box mb-0">
                        <div class="info-icon">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>

                        <div>
                            <div class="info-title">Bergabung Sejak</div>

                            <div class="info-value">
                                {{ Auth::user()->created_at->translatedFormat('d F Y') }}
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>
@endsection