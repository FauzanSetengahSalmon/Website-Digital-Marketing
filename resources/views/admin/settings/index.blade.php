@extends('layouts.admin')

@section('content')
<style>
    :root {
        --primary-green: #064e3b;
        --light-green: #10b981;
        --soft-green: #ecfdf5;
    }

    .form-label-bold {
        font-size: 0.88rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
    }

    .input-clean {
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.25s ease;
        font-weight: 600;
        color: #1e293b;
    }

    .input-clean:focus {
        border-color: var(--light-green);
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.12);
    }

    .input-group-text-custom {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-right: none;
        border-radius: 12px 0 0 12px;
        color: #64748b;
        font-weight: 700;
    }

    .input-clean-group {
        border-radius: 0 12px 12px 0 !important;
    }

    .petunjuk-admin {
        font-size: 0.78rem;
        color: #64748b;
        margin-top: 6px;
        display: block;
        line-height: 1.4;
    }

    .page-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .icon-box-lg {
        width: 60px;
        height: 60px;
        background: var(--soft-green);
        color: var(--light-green);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .btn-success {
        background: var(--primary-green);
        border-color: var(--primary-green);
    }

    .btn-success:hover {
        background: #053b2c;
        border-color: #053b2c;
    }
</style>

<div class="container-fluid py-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Pengaturan Aplikasi</h2>
            <p class="text-muted mb-0">Kelola tarif pengiriman, batas jarak, dan biaya layanan sistem.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="page-card shadow-sm p-4 p-md-5">

                <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-4">
                    <div class="icon-box-lg"><i class="bi bi-gear-fill"></i></div>
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">Konfigurasi Tarif & Jarak</h5>
                        <p class="text-muted small mb-0">Perubahan akan langsung berlaku pada transaksi terbaru.</p>
                    </div>
                </div>

                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label-bold">Biaya Layanan Sistem (Platform Fee)</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-custom">Rp</span>
                                <input type="number" name="biaya_layanan" class="form-control input-clean input-clean-group" value="{{ $setting->biaya_layanan }}" required>
                            </div>
                            <small class="petunjuk-admin"><i class="bi bi-info-circle me-1"></i>Biaya ini dibebankan kepada pelanggan. Masuk 100% ke Kas Admin.</small>
                        </div>

                        <div class="col-12">
                            <label class="form-label-bold">Tarif Ongkir per Kilometer (KM)</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-custom">Rp</span>
                                <input type="number" name="tarif_per_km" class="form-control input-clean input-clean-group" value="{{ $setting->tarif_per_km }}" required>
                                <span class="input-group-text" style="background:#f8fafc; border:1px solid #cbd5e1; border-radius: 0 12px 12px 0;">/ KM</span>
                            </div>
                            <small class="petunjuk-admin"><i class="bi bi-truck me-1"></i>Dikalikan dengan jarak pelanggan. <strong class="text-success">100% masuk ke kurir</strong>.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-bold">Batas Minimal Jarak (KM)</label>
                            <div class="input-group">
                                <input type="number" name="minimal_km" class="form-control input-clean" value="{{ $setting->minimal_km }}" required style="border-radius: 12px 0 0 12px;">
                                <span class="input-group-text" style="background:#f8fafc; border:1px solid #cbd5e1; border-radius: 0 12px 12px 0;">KM</span>
                            </div>
                            <small class="petunjuk-admin">Jarak terdekat yang dihitung.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-bold">Batas Maksimal Jarak (KM)</label>
                            <div class="input-group">
                                <input type="number" name="maksimal_km" class="form-control input-clean" value="{{ $setting->maksimal_km }}" required style="border-radius: 12px 0 0 12px;">
                                <span class="input-group-text" style="background:#f8fafc; border:1px solid #cbd5e1; border-radius: 0 12px 12px 0;">KM</span>
                            </div>
                            <small class="petunjuk-admin text-danger">Pelanggan di atas jarak ini ditolak.</small>
                        </div>
                    </div>

                    <hr class="my-5" style="border-color: #e2e8f0;">

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success rounded-pill px-5 py-2.5 fw-bold shadow-sm" style="font-size: 1.05rem;">
                            <i class="bi bi-save2-fill me-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="page-card shadow-sm p-4 bg-success bg-opacity-10 border-success border-opacity-25">
                <h6 class="fw-bold text-success mb-3"><i class="bi bi-lightbulb-fill me-2"></i>Simulasi Sistem Tarif</h6>
                <div class="small text-dark lh-lg">
                    <p class="mb-2">Jika pelanggan berjarak <strong>4 KM</strong>:</p>
                    <ul class="list-unstyled mb-0">
                        <li><i class="bi bi-dot"></i> Ongkir = 4 x Tarif per KM</li>
                        <li><i class="bi bi-dot"></i> Total Belanja = Subtotal Produk + Ongkir + Biaya Layanan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection