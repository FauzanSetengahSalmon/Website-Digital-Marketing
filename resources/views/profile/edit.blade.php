@extends('layouts.app')

@section('title', 'Profil Saya - EFood')

@push('styles')
<style>
    :root {
        --green-dark: #2d7a22;
        --green-primary: #4caf50;
        --green-light: #d6f0c2;
        --text-dark: #1f2937;
        --text-light: #6b7280;
        --bg-soft: #f8fafc;
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .profile-card {
        background: white;
        border-radius: 25px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 30px;
    }

    .profile-header {
        background: linear-gradient(135deg, var(--green-dark), var(--green-primary));
        height: 100px;
        position: relative;
    }

    .profile-body {
        padding: 40px;
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 12px;
        padding: 12px 15px;
        border: 1.5px solid #e5e7eb;
        background-color: var(--bg-soft);
        transition: 0.3s;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background-color: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
    }

    .btn-save {
        background: var(--green-dark);
        color: white;
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 700;
        border: none;
        transition: 0.3s;
    }

    .btn-save:hover {
        background: var(--green-primary);
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="container py-5 fade-in-up">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Pengaturan <span class="text-success">Akun</span></h3>
        <p class="text-muted">Halo, {{ Auth::user()->name }}! Kelola detail profil dan alamat pengiriman Anda di sini.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="profile-card shadow-sm">
                <div class="profile-header d-flex align-items-center px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="fa-solid fa-address-book text-success"></i>
                        </div>
                        <div class="text-white">
                            <h5 class="fw-bold mb-0">Informasi Dasar & Alamat</h5>
                            <small class="opacity-75">Data ini digunakan untuk keperluan pengiriman produk KWT.</small>
                        </div>
                    </div>
                </div>
                <div class="profile-body">
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email (Akun)</label>
                                <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}" placeholder="08xxx">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Provinsi</label>
                                <input type="text" name="province" class="form-control" value="{{ old('province', $user->province) }}" placeholder="Jawa Barat">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kota / Kabupaten</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city', $user->city) }}" placeholder="Bandung">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kecamatan</label>
                                <input type="text" name="district" class="form-control" value="{{ old('district', $user->district) }}" placeholder="Cicendo">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="address" class="form-control" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW">{{ old('address', $user->address) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4 d-flex align-items-center gap-3">
                            <button type="submit" class="btn-save">Simpan Perubahan</button>
                            @if (session('status') === 'profile-updated')
                            <span class="text-success fw-bold animate__animated animate__fadeIn">✓ Data Berhasil Diperbarui</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="profile-card shadow-sm">
                <div class="profile-header d-flex align-items-center px-4" style="background: #1e5e19;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="fa-solid fa-lock text-success"></i>
                        </div>
                        <div class="text-white">
                            <h5 class="fw-bold mb-0">Keamanan Akun</h5>
                            <small class="opacity-75">Ganti password secara berkala untuk menjaga akun tetap aman.</small>
                        </div>
                    </div>
                </div>
                <div class="profile-body">
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="row g-3">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Password Saat Ini</label>
                                <input type="text" class="form-control" value="efo****" readonly>
                            </div>

                            <hr class="my-4">

                            <div class="col-md-12">
                                <label class="form-label">Verifikasi Password Lama</label>
                                <input type="password" name="current_password" class="form-control" placeholder="Masukkan password lama">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn-save mt-4">Update Password</button>
                    </form>
                </div>
            </div>

            <div class="text-center mt-5 mb-5">
                <button class="btn btn-outline-danger btn-sm border-0" data-bs-toggle="modal" data-bs-target="#confirm-user-deletion">
                    Hapus Akun Permanen
                </button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="confirm-user-deletion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg" style="border-radius: 20px; border: none;">
            <div class="modal-body p-5 text-center">
                <div class="mb-3">
                    <i class="fa-solid fa-triangle-exclamation text-danger fs-1"></i>
                </div>
                <h4 class="fw-bold text-dark mb-3">Hapus Akun Anda?</h4>
                <p class="text-muted mb-4">Aksi ini tidak bisa dibatalkan. Masukkan password Anda untuk mengonfirmasi penghapusan akun.</p>

                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <input type="password" name="password" class="form-control mb-4 text-center" placeholder="Password Anda" required>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger py-3 fw-bold" style="border-radius: 12px;">Hapus Akun Sekarang</button>
                        <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection