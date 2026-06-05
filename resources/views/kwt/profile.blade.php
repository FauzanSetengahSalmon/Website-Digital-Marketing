@extends('layouts.kwt')

@section('content')
<style>
    :root {
        --kwt-green: #064e3b;
        --kwt-light: #10b981;
    }

    .profile-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }

    .profile-header-kwt {
        background: var(--kwt-green);
        padding: 30px;
        position: relative;
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #475569;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 10px 15px;
        font-size: 0.9rem;
    }

    .form-control:focus {
        border-color: var(--kwt-light);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .btn-thin {
        padding: 8px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .btn-update {
        background: var(--kwt-light);
        color: white;
        border: none;
    }

    .btn-update:hover {
        background: #0d9488;
        transform: translateY(-1px);
    }

    .avatar-wrapper:hover .camera-overlay {
        opacity: 1 !important;
    }

    .avatar-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .role-badge {
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 8px 14px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
</style>

@php
$roleLabel = match(Auth::user()->role) {
'admin' => 'Super Admin',
'kwt' => 'Pengelola KWT',
default => 'Customer'
};

$roleClass = match(Auth::user()->role) {
'admin' => 'bg-danger-subtle text-danger',
'kwt' => 'bg-success-subtle text-success',
default => 'bg-primary-subtle text-primary'
};
@endphp

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-0">
            Profil
            <span class="text-success">
                {{ Auth::user()->role === 'admin' ? 'Admin' : 'KWT' }}
            </span>
        </h4>

        <p class="text-muted small">
            Kelola identitas akun Anda agar data tetap aman dan terpercaya.
        </p>
    </div>

    {{-- SUCCESS --}}
    @if (session('success') || session('status') === 'profile-updated')
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4"
        role="alert"
        style="border-radius: 12px;">

        <i class="bi bi-check-circle-fill me-2"></i>

        <strong>Berhasil!</strong>
        {{ session('success') ?: 'Data profil Anda telah diperbarui.' }}

        <button type="button"
            class="btn-close"
            data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- PASSWORD SUCCESS --}}
    @if (session('status') === 'password-updated')
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4"
        role="alert"
        style="border-radius: 12px;">

        <i class="bi bi-shield-check me-2"></i>

        <strong>Berhasil!</strong>
        Password Anda telah diganti.

        <button type="button"
            class="btn-close"
            data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ERROR --}}
    @if ($errors->any() || $errors->updatePassword->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4"
        role="alert"
        style="border-radius: 12px;">

        <div class="d-flex">
            <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>

            <div>
                <strong>Gagal!</strong> Ada masalah dengan inputan Anda:

                <ul class="mb-0 mt-2 small">

                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach

                    @foreach ($errors->updatePassword->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach

                </ul>
            </div>
        </div>

        <button type="button"
            class="btn-close"
            data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">

        {{-- FORM PROFILE --}}
        <div class="col-12 col-lg-8">

            <div class="profile-card shadow-sm mb-0">

                <div class="profile-header-kwt text-white">
                    <div class="d-flex flex-column flex-sm-row align-items-center gap-3 text-center text-sm-start">

                        <div class="bg-white rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width: 50px; height: 50px;">

                            <i class="bi bi-person-vcard text-success fs-4"></i>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-1">
                                Informasi Akun
                            </h5>

                            <p class="mb-0 small opacity-75">
                                Data ini akan digunakan pada sistem.
                            </p>
                        </div>

                    </div>
                </div>

                <div class="p-4">

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="row g-3">

                            {{-- NAME --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label">
                                    Nama
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}"
                                    required>


                                <small class="text-muted"
                                    style="font-size: 0.72rem;">
                                    *Nama sesuai dengan KWTnya, agar mudah dikenali pelanggan
                                </small>
                            </div>

                            {{-- EMAIL --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control bg-light @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}"
                                    readonly
                                    required>

                                <small class="text-muted" style="font-size: 0.72rem;">
                                    *Email tidak dapat diubah karena digunakan untuk login
                                </small>
                            </div>

                            {{-- PHONE --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label">
                                    Nomor WhatsApp
                                </label>

                                <input
                                    type="text"
                                    name="phone_number"
                                    class="form-control @error('phone_number') is-invalid @enderror"
                                    value="{{ old('phone_number', $user->phone_number) }}"
                                    placeholder="08123456789">


                                <small class="text-muted"
                                    style="font-size: 0.72rem;">
                                    *Nomor WA digunakan untuk komunikasi dengan pelanggan
                                </small>
                            </div>
                        </div>

                        {{-- BUTTON --}}
                        <div class="mt-4 d-grid d-md-block">
                            <button type="submit"
                                class="btn-thin btn-update shadow-sm py-2">

                                <i class="bi bi-save me-1"></i>
                                Simpan Profil
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="col-12 col-lg-4">

            <div class="profile-card shadow-sm p-4 text-center">

                {{-- PHOTO FORM --}}
                <form action="{{ route('profile.update.photo') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    id="photoForm">

                    @csrf

                    <div class="position-relative d-inline-block avatar-wrapper">

                        <div class="avatar-large mx-auto mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 120px; height: 120px; border: 4px solid #f8fafc; overflow: hidden; cursor: pointer;"
                            onclick="document.getElementById('photoInput').click()">

                            @if(Auth::user()->profile_photo)

                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}">

                            @else

                            <span class="fs-1 fw-bold text-success">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>

                            @endif

                        </div>

                        {{-- CAMERA BUTTON --}}
                        <button type="button"
                            class="btn btn-sm btn-success position-absolute bottom-0 end-0 rounded-circle shadow-sm"
                            style="width: 35px; height: 35px; border: 2px solid white;"
                            onclick="document.getElementById('photoInput').click()">

                            <i class="bi bi-camera"></i>
                        </button>

                        <input
                            type="file"
                            name="profile_photo"
                            id="photoInput"
                            class="d-none"
                            onchange="document.getElementById('photoForm').submit()">

                    </div>

                </form>

                {{-- USER --}}
                <h5 class="fw-bold mb-1 mt-3 text-dark">
                    {{ Auth::user()->name }}
                </h5>

                {{-- ROLE --}}
                <div class="d-flex justify-content-center mt-2">
                    <span class="role-badge {{ $roleClass }}">
                        <i class="bi bi-person-badge-fill"></i>
                        {{ $roleLabel }}
                    </span>
                </div>

                <hr class="my-4 opacity-10">

                {{-- INFO --}}
                <div class="text-start small text-muted">

                    <p class="mb-2">
                        <i class="bi bi-calendar-check me-2 text-success"></i>
                        Bergabung: {{ Auth::user()->created_at->format('d M Y') }}
                    </p>

                    <p class="mb-0 text-break">
                        <i class="bi bi-envelope me-2 text-success"></i>
                        {{ Auth::user()->email }}
                    </p>

                </div>

            </div>

            {{-- INFO --}}
            <div class="mt-3 p-3 bg-warning-subtle text-warning-emphasis small rounded-3 border border-warning-subtle text-center text-md-start">
                <i class="bi bi-info-circle-fill me-2"></i>
                Klik foto atau ikon kamera untuk mengganti foto profil Anda.
            </div>

        </div>

    </div>
</div>
@endsection