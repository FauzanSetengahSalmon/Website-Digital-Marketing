@extends('layouts.admin')

@section('content')
<style>
    :root {
        --kwt-green: #064e3b;
        --kwt-light: #10b981;
        --kwt-soft: #ecfdf5;
    }

    .profile-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }

    .profile-header-admin {
        background: linear-gradient(135deg, var(--kwt-green), #065f46);
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
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
    }

    .btn-thin {
        padding: 8px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .btn-update {
        background: var(--kwt-green);
        color: white;
        border: none;
    }

    .btn-update:hover {
        background: #065f46;
        transform: translateY(-1px);
        color: white;
    }

    .avatar-wrapper:hover .camera-overlay {
        opacity: 1 !important;
    }

    .admin-badge {
        background: #d1fae5;
        color: var(--kwt-green);
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-block;
    }

    .info-box {
        background: var(--kwt-soft);
        border: 1px solid #a7f3d0;
        color: #065f46;
    }

    /* Tambahan agar header tidak terlalu lebar di layar HP */
    @media (max-width: 768px) {
        .profile-header-admin {
            padding: 20px;
        }
    }
</style>

<div class="container-fluid py-4">

    <div class="mb-4">
        <h4 class="fw-bold mb-0">
            Profil <span style="color: var(--kwt-light);">Administrator</span>
        </h4>

        <p class="text-muted small">
            Kelola informasi akun administrator sistem.
        </p>
    </div>

    @if (session('success') || session('status') === 'profile-updated')
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4"
        role="alert"
        style="border-radius: 12px;">

        <i class="bi bi-check-circle-fill me-2"></i>

        <strong>Berhasil!</strong>
        {{ session('success') ?: 'Data profil berhasil diperbarui.' }}

        <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4"
        role="alert"
        style="border-radius: 12px;">

        <div class="d-flex">
            <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>

            <div>
                <strong>Terjadi Kesalahan!</strong>

                <ul class="mb-0 mt-2 small">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>
    </div>
    @endif

    <div class="row g-4">

        <div class="col-12 col-xl-8">

            <div class="profile-card shadow-sm mb-4 h-100">

                <div class="profile-header-admin text-white">

                    <div class="d-flex align-items-center gap-3">

                        <div class="bg-white rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width: 50px; height: 50px;">

                            <i class="bi bi-shield-lock fs-4"
                                style="color: var(--kwt-light);"></i>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-0">
                                Informasi Administrator
                            </h5>

                            <p class="mb-0 small opacity-75">
                                Data ini digunakan untuk identitas admin sistem.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-3 p-md-4">

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="row g-3">

                            <div class="col-12 col-md-6">
                                <label class="form-label">
                                    Nama Lengkap
                                </label>

                                <input type="text"
                                    name="name"
                                    class="form-control"
                                    value="{{ old('name', $user->name) }}"
                                    required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">
                                    Email Administrator
                                </label>

                                <input type="email"
                                    name="email"
                                    class="form-control"
                                    value="{{ old('email', $user->email) }}"
                                    required>
                            </div>

                            {{-- PHONE (Ditambahkan pembatasan angka & wajib diisi) --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label">
                                    Nomor Telepon
                                </label>

                                <input type="text"
                                    name="phone_number"
                                    class="form-control"
                                    value="{{ old('phone_number', $user->phone_number) }}"
                                    placeholder="08xxxxxxxxxx"
                                    inputmode="numeric"
                                    pattern="[0-9]*"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    minlength="9"
                                    maxlength="15"
                                    required>
                            </div>
                        </div>

                        <div class="mt-4 d-grid d-md-block">
                            <button type="submit"
                                class="btn-thin btn-update shadow-sm w-100 w-md-auto py-2">

                                <i class="bi bi-save me-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">

            <div class="profile-card shadow-sm p-4 text-center">

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
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                                class="w-100 h-100 object-fit-cover" alt="Foto Profil">
                            @else
                            <span class="fs-1 fw-bold"
                                style="color: var(--kwt-light);">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>
                            @endif

                        </div>

                        <button type="button"
                            class="btn btn-sm position-absolute bottom-0 end-0 rounded-circle shadow-sm"
                            style="width: 35px; height: 35px; border: 2px solid white; background: var(--kwt-green); color: white;"
                            onclick="document.getElementById('photoInput').click()">

                            <i class="bi bi-camera"></i>
                        </button>

                        <input type="file"
                            name="profile_photo"
                            id="photoInput"
                            class="d-none"
                            onchange="document.getElementById('photoForm').submit()">
                    </div>

                </form>

                <h5 class="fw-bold mb-1 mt-3 text-dark">
                    {{ Auth::user()->name }}
                </h5>

                <p class="admin-badge">
                    Administrator Sistem
                </p>

                <hr class="my-4 opacity-10">

                <div class="text-start small text-muted">

                    <p class="mb-2 d-flex align-items-center">
                        <i class="bi bi-calendar-check me-2"
                            style="color: var(--kwt-light);"></i>

                        <span>Bergabung: {{ Auth::user()->created_at ? Auth::user()->created_at->format('d M Y') : '-' }}</span>
                    </p>

                    <p class="mb-0 d-flex align-items-center text-wrap" style="word-break: break-all;">
                        <i class="bi bi-envelope me-2"
                            style="color: var(--kwt-light);"></i>

                        <span>{{ Auth::user()->email }}</span>
                    </p>

                </div>
            </div>

            <div class="mt-3 p-3 small rounded-3 info-box">
                <i class="bi bi-info-circle-fill me-2"></i>
                Klik foto profil untuk mengganti avatar administrator.
            </div>

        </div>
    </div>
</div>
@endsection