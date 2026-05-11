@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<style>
    :root {
        --green-dark: #065f46;
        --green-light: #10b981;
        --green-soft: #ecfdf5;
    }

    body {
        background: #f8fafc;
    }

    .profile-container {
        max-width: 1320px;
        margin: auto;
    }

    .profile-card {
        background: #fff;
        border-radius: 22px;
        border: 1px solid #edf2f7;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
    }

    /* HEADER */
    .profile-header {
        background: var(--green-dark);
        padding: 28px 32px;
    }

    .header-profile {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        overflow: hidden;
        background: white;
        flex-shrink: 0;
        border: 3px solid rgba(255, 255, 255, .15);
    }

    .header-profile img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .header-profile-letter {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--green-light);
    }

    .header-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: white;
        margin-bottom: 2px;
    }

    .header-subtitle {
        color: rgba(255, 255, 255, .75);
        font-size: .9rem;
        margin: 0;
    }

    /* FORM */
    .section-space {
        padding: 30px;
    }

    .form-label {
        font-size: .84rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 12px;
        border: 1px solid #dbe4ee;
        padding: 12px 15px;
        font-size: .92rem;
        background: white;
    }

    .form-control:focus {
        border-color: var(--green-light);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, .10);
    }

    textarea.form-control {
        resize: none;
    }

    /* BUTTON */
    .btn-save {
        background: var(--green-light);
        color: white;
        border: none;
        padding: 11px 22px;
        border-radius: 10px;
        font-size: .88rem;
        font-weight: 600;
        transition: .2s;
    }

    .btn-save:hover {
        background: #059669;
    }

    /* RIGHT SIDE */
    .profile-side {
        background: white;
    }

    .avatar-box {
        width: 125px;
        height: 125px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid #f8fafc;
        background: #f1f5f9;
        cursor: pointer;
        margin: auto;
    }

    .avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-letter {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.7rem;
        font-weight: 700;
        color: var(--green-light);
    }

    .camera-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 3px solid white;
    }

    .profile-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
    }

    .status-badge {
        background: #dcfce7;
        color: #059669;
        padding: 7px 16px;
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 700;
        display: inline-block;
    }

    /* INFO ITEM */
    .info-item {
        background: #f8fafc;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 14px;
        border: 1px solid #eef2f7;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: white;
        color: #10b981;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .95rem;
        flex-shrink: 0;
        border: 1px solid #ecf0f4;
    }

    .info-title {
        font-size: .74rem;
        color: #64748b;
        margin-bottom: 3px;
    }

    .info-value {
        font-size: .92rem;
        font-weight: 600;
        color: #0f172a;
        line-height: 1.5;
    }

    .security-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
    }

    @media(max-width:768px) {

        .profile-header .d-flex {
            flex-direction: column;
            text-align: center;
        }

        .section-space {
            padding: 22px;
        }

        .header-title {
            font-size: 1.35rem;
        }
    }
</style>

<div class="container py-5">
    <div class="profile-container">

        {{-- TITLE --}}
        <div class="mb-4">
            <h2 class="fw-bold mb-1">
                Profil <span class="text-success">Customer</span>
            </h2>

            <p class="text-muted mb-0">
                Kelola informasi akun dan alamat pengiriman Anda.
            </p>
        </div>

        {{-- SUCCESS --}}
        @if(session('success') || session('status') === 'profile-updated')
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') ?: 'Profil berhasil diperbarui.' }}
        </div>
        @endif

        {{-- ERROR --}}
        @if ($errors->any() || $errors->updatePassword->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">

            <strong>Terjadi Kesalahan:</strong>

            <ul class="mb-0 mt-2 small">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach

                @foreach ($errors->updatePassword->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
        @endif

        <div class="row justify-content-center g-4">

            {{-- LEFT --}}
            <div class="col-xl-8 col-lg-7">

                {{-- PROFILE --}}
                <div class="profile-card mb-4">

                    {{-- HEADER --}}
                    <div class="profile-header">

                        <div class="d-flex align-items-center gap-4">

                            {{-- FOTO PROFIL --}}
                            <div class="header-profile">

                                @if(Auth::user()->profile_photo)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}">
                                @else
                                <div class="header-profile-letter">
                                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                                </div>
                                @endif

                            </div>

                            {{-- TEXT --}}
                            <div>
                                <div class="header-title">
                                    Informasi Customer
                                </div>

                                <p class="header-subtitle">
                                    Data digunakan untuk pengiriman pesanan dan informasi akun Anda.
                                </p>
                            </div>

                        </div>

                    </div>

                    {{-- FORM --}}
                    <div class="section-space">

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PATCH')

                            <div class="row g-4">

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Nama Lengkap
                                    </label>

                                    <input type="text"
                                        name="name"
                                        class="form-control"
                                        value="{{ old('name', $user->name) }}"
                                        required>
                                </div>

                                {{-- FIX EMAIL --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Email
                                    </label>

                                    <input type="email"
                                        name="email"
                                        class="form-control bg-light"
                                        value="{{ old('email', $user->email) }}"
                                        readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Nomor WhatsApp
                                    </label>

                                    <input type="text"
                                        name="phone_number"
                                        class="form-control"
                                        value="{{ old('phone_number', $user->phone_number) }}"
                                        placeholder="08xxxxxxxxxx">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Kecamatan
                                    </label>

                                    <input type="text"
                                        name="district"
                                        class="form-control"
                                        value="{{ old('district', $user->district) }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Kota / Kabupaten
                                    </label>

                                    <input type="text"
                                        name="city"
                                        class="form-control"
                                        value="{{ old('city', $user->city) }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Provinsi
                                    </label>

                                    <input type="text"
                                        name="province"
                                        class="form-control"
                                        value="{{ old('province', $user->province) }}">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">
                                        Alamat Lengkap
                                    </label>

                                    <textarea
                                        name="address"
                                        rows="4"
                                        class="form-control"
                                        placeholder="Masukkan alamat lengkap">{{ old('address', $user->address) }}</textarea>
                                </div>

                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn-save shadow-sm">
                                    <i class="bi bi-save me-1"></i>
                                    Simpan Profil
                                </button>
                            </div>

                        </form>

                    </div>

                </div>

                {{-- PASSWORD --}}
                <div class="profile-card">

                    <div class="section-space">

                        <div class="security-title mb-1">
                            <i class="bi bi-shield-lock-fill text-danger me-2"></i>
                            Keamanan Akun
                        </div>

                        <p class="text-muted small mb-4">
                            Gunakan password yang kuat untuk menjaga keamanan akun Anda.
                        </p>

                        <form method="POST"
                            action="{{ route('password.update') }}">

                            @csrf
                            @method('PUT')

                            <div class="row g-3">

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Password Lama
                                    </label>

                                    <input type="password"
                                        name="current_password"
                                        class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Password Baru
                                    </label>

                                    <input type="password"
                                        name="password"
                                        class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Konfirmasi Password
                                    </label>

                                    <input type="password"
                                        name="password_confirmation"
                                        class="form-control">
                                </div>

                            </div>

                            <button type="submit"
                                class="btn btn-dark rounded-4 px-4 py-2 mt-4">
                                Ganti Password
                            </button>

                        </form>

                    </div>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="col-xl-4 col-lg-5">

                <div class="profile-card profile-side p-4 text-center">

                    {{-- PHOTO --}}
                    <form action="{{ route('profile.update.photo') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        id="photoForm">

                        @csrf

                        <div class="position-relative d-inline-block">

                            <div class="avatar-box"
                                onclick="document.getElementById('photoInput').click()">

                                @if(Auth::user()->profile_photo)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}">
                                @else
                                <div class="avatar-letter">
                                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                                </div>
                                @endif

                            </div>

                            <button type="button"
                                class="btn btn-success camera-btn position-absolute bottom-0 end-0 shadow"
                                onclick="document.getElementById('photoInput').click()">

                                <i class="bi bi-camera-fill"></i>
                            </button>

                            <input type="file"
                                name="profile_photo"
                                id="photoInput"
                                class="d-none"
                                onchange="document.getElementById('photoForm').submit()">

                        </div>

                    </form>

                    <div class="profile-name mt-4">
                        {{ Auth::user()->name }}
                    </div>

                    <div class="status-badge mt-2">
                        Customer Aktif
                    </div>

                    <hr class="my-4">

                    {{-- INFO --}}
                    <div class="text-start">

                        <div class="info-item d-flex align-items-start gap-3">

                            <div class="info-icon">
                                <i class="bi bi-envelope-fill"></i>
                            </div>

                            <div>
                                <div class="info-title">
                                    Email
                                </div>

                                <div class="info-value">
                                    {{ Auth::user()->email }}
                                </div>
                            </div>

                        </div>

                        <div class="info-item d-flex align-items-start gap-3">

                            <div class="info-icon">
                                <i class="bi bi-whatsapp"></i>
                            </div>

                            <div>
                                <div class="info-title">
                                    Nomor WhatsApp
                                </div>

                                <div class="info-value">
                                    {{ Auth::user()->phone_number ?: 'Belum diisi' }}
                                </div>
                            </div>

                        </div>

                        <div class="info-item d-flex align-items-start gap-3">

                            <div class="info-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>

                            <div>
                                <div class="info-title">
                                    Alamat Pengiriman
                                </div>

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

                        <div class="info-item mb-0 d-flex align-items-start gap-3">

                            <div class="info-icon">
                                <i class="bi bi-calendar-check-fill"></i>
                            </div>

                            <div>
                                <div class="info-title">
                                    Bergabung Sejak
                                </div>

                                <div class="info-value">
                                    {{ Auth::user()->created_at->format('d M Y') }}
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
@endsection