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

    .form-control:focus {
        border-color: var(--green-primary);
        box-shadow: none;
        background-color: white;
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
        <h3 class="fw-bold text-dark">Profil <span class="text-success">Pelanggan</span></h3>
        <p class="text-muted">Halo, {{ Auth::user()->name }}! Kelola alamat pengiriman Anda untuk memudahkan belanja produk KWT.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="profile-card shadow-sm">
                <div class="profile-header d-flex align-items-center px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="bi bi-person-badge text-success fs-5"></i>
                        </div>
                        <div class="text-white">
                            <h5 class="fw-bold mb-0">Detail Profil & Pengiriman</h5>
                            <small class="opacity-75">Pastikan alamat Anda sudah benar agar sayur sampai dengan segar.</small>
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
                                <label class="form-label">Email Akun</label>
                                <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. Telepon (WhatsApp)</label>
                                <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}" placeholder="08xxx">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Provinsi</label>
                                <input type="text" name="province" class="form-control" value="{{ old('province', $user->province) }}" placeholder="Contoh: Jawa Barat">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kota / Kabupaten</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city', $user->city) }}" placeholder="Contoh: Bandung">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kecamatan</label>
                                <input type="text" name="district" class="form-control" value="{{ old('district', $user->district) }}" placeholder="Contoh: Cicendo">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="address" class="form-control" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW">{{ old('address', $user->address) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn-save">Simpan Alamat</button>
                            @if (session('status') === 'profile-updated')
                            <span class="ms-3 text-success fw-bold">✓ Profil Berhasil Diperbarui</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection