@extends('layouts.admin')

@section('content')
<style>
    :root {
        --admin-dark: #111827;
        --admin-primary: #2563eb;
    }

    .profile-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }

    .profile-header-admin {
        background: linear-gradient(135deg, #111827, #1e3a8a);
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
        border-color: var(--admin-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .btn-thin {
        padding: 8px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .btn-update {
        background: var(--admin-primary);
        color: white;
        border: none;
    }

    .btn-update:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }

    .avatar-wrapper:hover .camera-overlay {
        opacity: 1 !important;
    }

    .admin-badge {
        background: #dbeafe;
        color: #1d4ed8;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-block;
    }
</style>

<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="mb-4">
        <h4 class="fw-bold mb-0">
            Profil <span class="text-primary">Administrator</span>
        </h4>

        <p class="text-muted small">
            Kelola informasi akun administrator sistem.
        </p>
    </div>

    <!-- SUCCESS -->
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

    <!-- ERROR -->
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

    <div class="row">

        <!-- FORM -->
        <div class="col-xl-8">

            <div class="profile-card shadow-sm mb-4">

                <!-- HEADER -->
                <div class="profile-header-admin text-white">

                    <div class="d-flex align-items-center gap-3">

                        <div class="bg-white rounded-3 d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">

                            <i class="bi bi-shield-lock text-primary fs-4"></i>
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

                <!-- BODY -->
                <div class="p-4">

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="row g-3">

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

                            <div class="col-md-6">
                                <label class="form-label">
                                    Email Administrator
                                </label>

                                <input type="email"
                                    name="email"
                                    class="form-control"
                                    value="{{ old('email', $user->email) }}"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Nomor Telepon
                                </label>

                                <input type="text"
                                    name="phone_number"
                                    class="form-control"
                                    value="{{ old('phone_number', $user->phone_number) }}"
                                    placeholder="08xxxxxxxxxx">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Role Akun
                                </label>

                                <input type="text"
                                    class="form-control bg-light"
                                    value="Administrator"
                                    readonly>
                            </div>

                            <div class="col-12">
                                <label class="form-label">
                                    Alamat
                                </label>

                                <textarea name="address"
                                    rows="3"
                                    class="form-control"
                                    placeholder="Masukkan alamat lengkap...">{{ old('address', $user->address) }}</textarea>
                            </div>

                        </div>

                        <div class="mt-4">
                            <button type="submit"
                                class="btn-thin btn-update shadow-sm">

                                <i class="bi bi-save me-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="col-xl-4">

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
                                class="w-100 h-100 object-fit-cover">
                            @else
                            <span class="fs-1 fw-bold text-primary">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>
                            @endif

                        </div>

                        <button type="button"
                            class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle shadow-sm"
                            style="width: 35px; height: 35px; border: 2px solid white;"
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

                    <p class="mb-2">
                        <i class="bi bi-calendar-check me-2 text-primary"></i>

                        Bergabung:
                        {{ Auth::user()->created_at->format('d M Y') }}
                    </p>

                    <p class="mb-0">
                        <i class="bi bi-envelope me-2 text-primary"></i>

                        {{ Auth::user()->email }}
                    </p>

                </div>
            </div>

            <div class="mt-3 p-3 bg-primary-subtle text-primary-emphasis small rounded-3 border border-primary-subtle">
                <i class="bi bi-info-circle-fill me-2"></i>
                Klik foto profil untuk mengganti avatar administrator.
            </div>

        </div>
    </div>
</div>
@endsection