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

    /* Button yang lebih tipis dan elegan */
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
</style>

<div class="container-fluid py-4">
    <div class="mb-4">
        <h4 class="fw-bold mb-0">Profil <span class="text-success">KWT</span></h4>
        <p class="text-muted small">Kelola identitas kelompok tani Anda agar pelanggan mengenali produk Anda.</p>
    </div>

    <!-- NOTIFIKASI BERHASIL -->
    @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
        <i class="bi bi-check-circle-fill me-2"></i>
        <strong>Berhasil!</strong> Data Anda telah diperbarui.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- NOTIFIKASI GAGAL (KHUSUS PASSWORD) -->
    @if ($errors->updatePassword->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Gagal!</strong> Password saat ini salah atau data tidak valid.
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
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Operasional</label>
                                <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. WhatsApp KWT</label>
                                <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Wilayah (Kecamatan/Desa)</label>
                                <input type="text" name="district" class="form-control" value="{{ old('district', $user->district) }}" placeholder="Contoh: Desa Makmur Jaya">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Alamat Kantor/Kebun KWT</label>
                                <textarea name="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn-thin btn-update shadow-sm">Simpan Profil</button>
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
                                @if($errors->updatePassword->has('current_password'))
                                <div class="invalid-feedback" style="font-size: 0.75rem;">Password lama salah.</div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="password" class="form-control @if($errors->updatePassword->has('password')) is-invalid @endif">
                                @if($errors->updatePassword->has('password'))
                                <div class="invalid-feedback" style="font-size: 0.75rem;">Password minimal 8 karakter.</div>
                                @endif
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

        <div class="col-xl-4">
            <div class="profile-card shadow-sm p-4 text-center">
                <div class="avatar-large mx-auto mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; border: 4px solid #f8fafc;">
                    <span class="fs-1 fw-bold text-success">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <h5 class="fw-bold mb-1 text-dark">{{ Auth::user()->name }}</h5>
                <p class="badge bg-success-subtle text-success px-3" style="border-radius: 6px;">Status: Pengelola KWT</p>
                <hr class="my-4 opacity-10">
                <div class="text-start small text-muted">
                    <p class="mb-2"><i class="bi bi-calendar-check me-2 text-success"></i>Bergabung: {{ Auth::user()->created_at->format('d M Y') }}</p>
                    <p class="mb-0"><i class="bi bi-envelope me-2 text-success"></i>{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection