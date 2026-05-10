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
</style>

<div class="container-fluid py-4">
    <div class="mb-4">
        <h4 class="fw-bold mb-0">Profil <span class="text-success">KWT</span></h4>
        <p class="text-muted small">Kelola identitas kelompok tani Anda agar pelanggan mengenali produk Anda.</p>
    </div>

    <!-- NOTIFIKASI BERHASIL -->
    @if (session('success') || session('status') === 'profile-updated')
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
        <i class="bi bi-check-circle-fill me-2"></i>
        <strong>Berhasil!</strong> {{ session('success') ?: 'Data profil Anda telah diperbarui.' }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session('status') === 'password-updated')
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
        <i class="bi bi-shield-check me-2"></i>
        <strong>Berhasil!</strong> Password Anda telah diganti.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- NOTIFIKASI GAGAL (VERSI REVISI: MENAMPILKAN DETAIL ERROR) -->
    @if ($errors->any() || $errors->updatePassword->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
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
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-xl-8">
            <div class="profile-card shadow-sm mb-4">
                <div class="profile-header-kwt text-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-shop text-success fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Identitas Kelompok Tani</h5>
                            <p class="mb-0 small opacity-75">Informasi ini akan muncul di toko digital Anda.</p>
                        </div>
                    </div>
                </div>

                <div class="p-4">
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Kelompok / Ketua</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Operasional</label>
                                <input type="email" name="email" class="form-control bg-light @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                <small class="text-muted" style="font-size: 0.7rem;">*Email digunakan untuk login</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. WhatsApp KWT</label>
                                <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number', $user->phone_number) }}" placeholder="Contoh: 08123456789">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Wilayah (Kecamatan/Desa)</label>
                                <input type="text" name="district" class="form-control @error('district') is-invalid @enderror" value="{{ old('district', $user->district) }}" placeholder="Contoh: Desa Makmur Jaya">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Alamat Kantor/Kebun KWT</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" placeholder="Alamat lengkap kelompok tani...">{{ old('address', $user->address) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn-thin btn-update shadow-sm">
                                <i class="bi bi-save me-1"></i> Simpan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Bagian Password --}}
            <div class="profile-card shadow-sm border-0">
                <div class="p-4">
                    <h6 class="fw-bold text-dark"><i class="bi bi-shield-lock me-2 text-danger"></i>Keamanan Akun</h6>
                    <p class="text-muted small">Pastikan password Anda kuat untuk menjaga keamanan data KWT.</p>
                    <hr class="opacity-10">

                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Password Saat Ini</label>
                                <input type="password" name="current_password" class="form-control @if($errors->updatePassword->has('current_password')) is-invalid @endif">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="password" class="form-control @if($errors->updatePassword->has('password')) is-invalid @endif">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Konfirmasi Baru</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn-thin btn-dark mt-4">Ganti Password</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- SIDEBAR KANAN: FOTO PROFIL --}}
        <div class="col-xl-4">
            <div class="profile-card shadow-sm p-4 text-center">
                <form action="{{ route('profile.update.photo') }}" method="POST" enctype="multipart/form-data" id="photoForm">
                    @csrf
                    <div class="position-relative d-inline-block avatar-wrapper">
                        <div class="avatar-large mx-auto mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 120px; height: 120px; border: 4px solid #f8fafc; overflow: hidden; cursor: pointer;"
                             onclick="document.getElementById('photoInput').click()">
                            
                            @if(Auth::user()->profile_photo)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <span class="fs-1 fw-bold text-success">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            @endif
                        </div>
                        
                        <button type="button" class="btn btn-sm btn-success position-absolute bottom-0 end-0 rounded-circle shadow-sm" 
                                style="width: 35px; height: 35px; border: 2px solid white;"
                                onclick="document.getElementById('photoInput').click()">
                            <i class="bi bi-camera"></i>
                        </button>
                        
                        <input type="file" name="profile_photo" id="photoInput" class="d-none" onchange="document.getElementById('photoForm').submit()">
                    </div>
                </form>

                <h5 class="fw-bold mb-1 mt-3 text-dark">{{ Auth::user()->name }}</h5>
                <p class="badge bg-success-subtle text-success px-3" style="border-radius: 6px;">Status: Pengelola KWT</p>
                
                <hr class="my-4 opacity-10">
                <div class="text-start small text-muted">
                    <p class="mb-2">
                        <i class="bi bi-calendar-check me-2 text-success"></i>
                        Bergabung: {{ Auth::user()->created_at->format('d M Y') }}
                    </p>
                    <p class="mb-0">
                        <i class="bi bi-envelope me-2 text-success"></i>
                        {{ Auth::user()->email }}
                    </p>
                </div>
            </div>
            
            <div class="mt-3 p-3 bg-warning-subtle text-warning-emphasis small rounded-3 border border-warning-subtle">
                <i class="bi bi-info-circle-fill me-2"></i>
                Klik pada foto atau ikon kamera untuk mengganti foto profil Anda.
            </div>
        </div>
    </div>
</div>
@endsection