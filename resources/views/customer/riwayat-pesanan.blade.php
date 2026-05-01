@extends('layouts.app')

@section('title','Riwayat Pesanan - EFood')

@push('styles')
<style>
    :root {
        --green-dark: #2d7a22;
        --green-primary: #4caf50;
        --green-light: #d6f0c2;
        --status-pending: #ffc107;
        --status-success: #28a745;
        --status-shipping: #17a2b8;
    }

    body {
        background-color: #fcfdfc;
    }

    .order-history-header {
        margin: 40px 0;
        text-align: center;
    }

    .order-history-header h2 {
        font-weight: 800;
        color: var(--green-dark);
        margin-bottom: 10px;
    }

    .header-line {
        width: 60px;
        height: 4px;
        background: var(--green-primary);
        margin: 0 auto 15px auto;
        border-radius: 10px;
    }

    /* Tab Status ala Desain Premium */
    .nav-status {
        border-bottom: 2px solid #f1f1f1;
        margin-bottom: 30px;
        gap: 20px;
    }

    .nav-status .nav-link {
        color: #888;
        font-weight: 600;
        border: none;
        padding: 10px 5px;
        position: relative;
    }

    .nav-status .nav-link.active {
        color: var(--green-primary);
        background: none;
    }

    .nav-status .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--green-primary);
        border-radius: 10px;
    }

    /* Sidebar Filter */
    .filter-card {
        border: none;
        border-radius: 20px;
        background: #f8faf8;
        padding: 25px;
        position: sticky;
        top: 100px;
    }

    .order-card {
        border: 1px solid #eee;
        border-radius: 16px;
        background: white;
        margin-bottom: 20px;
        transition: .3s ease;
        overflow: hidden;
    }

    .order-card:hover {
        border-color: var(--green-primary);
        box-shadow: 0 10px 25px rgba(0, 0, 0, .05);
    }

    .order-header {
        background: #fcfdfc;
        border-bottom: 1px solid #f5f5f5;
        padding: 15px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .order-date {
        font-size: 0.85rem;
        color: #666;
        font-weight: 500;
    }

    .order-status {
        font-size: 0.75rem;
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: 700;
    }

    .status-selesai {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-dikirim {
        background: #e3f2fd;
        color: #1565c0;
    }

    .status-proses {
        background: #fff8e1;
        color: #f57f17;
    }

    .order-body {
        padding: 25px;
    }

    .item-list {
        display: flex;
        gap: 20px;
    }

    .item-img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid #f0f0f0;
    }

    .item-info h6 {
        color: var(--green-dark);
        margin-bottom: 5px;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .order-footer {
        padding: 15px 25px;
        background: #fcfdfc;
        border-top: 1px solid #f5f5f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .total-amount {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--green-dark);
    }

    .btn-detail {
        background-color: var(--green-primary);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: 0.3s;
    }

    .btn-detail:hover {
        background-color: var(--green-dark);
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')
<div class="container pb-5">
    <div class="order-history-header">
        <h2 class="section-title">Riwayat Pesanan</h2>
        <div class="header-line"></div>
        <p class="text-muted">Lihat kembali belanjaan sehat Anda dari kelompok tani lokal</p>
    </div>

    <div class="row">
        <!-- Filter Samping (Sesuai Gambar) -->
        <div class="col-lg-3 d-none d-lg-block">
            <div class="filter-card shadow-sm">
                <h6 class="fw-bold mb-4 text-success"><i class="bi bi-funnel me-2"></i> Filter Pesanan</h6>

                <div class="mb-4">
                    <label class="small fw-bold mb-2">Pilih Periode</label>
                    <select class="form-select border-0 shadow-sm py-2" style="border-radius: 10px; font-size: 0.9rem;">
                        <option>Semua Waktu</option>
                        <option>30 Hari Terakhir</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="small fw-bold mb-2">Status Pesanan</label>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" checked id="f1">
                        <label class="form-check-label small" for="f1">Semua Status</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="f2">
                        <label class="form-check-label small" for="f2">Diproses</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="f3">
                        <label class="form-check-label small" for="f3">Selesai</label>
                    </div>
                </div>

                <button class="btn btn-success w-100 py-2 fw-bold" style="border-radius: 12px;">Terapkan Filter</button>
            </div>
        </div>

        <!-- Daftar Pesanan -->
        <div class="col-lg-9">
            <!-- Nav Status -->
            <ul class="nav nav-status px-2">
                <li class="nav-item"><a class="nav-link active" href="#">Semua Pesanan</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Diproses</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Dikirim</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Selesai</a></li>
            </ul>

            <!-- Order Card 1 -->
            <div class="order-card shadow-sm">
                <div class="order-header">
                    <div class="order-date">
                        <i class="bi bi-bag-check me-2 text-success"></i> <strong>15 April 2026</strong> <span class="mx-2 text-light">|</span> ID: #ORD-99210
                    </div>
                    <span class="order-status status-dikirim">Dalam Pengiriman</span>
                </div>
                <div class="order-body">
                    <div class="item-list">
                        <img src="{{ asset('image/image_ba0f78.jpg') }}" class="item-img" alt="Produk">
                        <div class="item-info">
                            <h6>Bayam Organik Segar</h6>
                            <p class="text-muted mb-0 small">2 ikat x Rp 15.000</p>
                            <span class="badge bg-light text-success mt-2" style="font-size: 0.7rem;">KWT Desa Cibiru Wetan</span>
                        </div>
                    </div>
                </div>
                <div class="order-footer">
                    <div>
                        <div class="total-label text-muted small">Total Belanja</div>
                        <div class="total-amount">Rp 30.000</div>
                    </div>
                    <a href="#" class="btn btn-detail">Lihat Detail</a>
                </div>
            </div>

            <!-- Order Card 2 -->
            <div class="order-card shadow-sm">
                <div class="order-header">
                    <div class="order-date">
                        <i class="bi bi-bag-check me-2 text-success"></i> <strong>10 April 2026</strong> <span class="mx-2 text-light">|</span> ID: #ORD-99185
                    </div>
                    <span class="order-status status-selesai">Selesai</span>
                </div>
                <div class="order-body">
                    <div class="item-list">
                        <img src="{{ asset('image/image_ba0f78.jpg') }}" class="item-img" alt="Produk">
                        <div class="item-info">
                            <h6>Keripik Bayam Gurih</h6>
                            <p class="text-muted mb-0 small">3 bungkus x Rp 20.000</p>
                            <span class="badge bg-light text-success mt-2" style="font-size: 0.7rem;">KWT Melati</span>
                        </div>
                    </div>
                </div>
                <div class="order-footer">
                    <div>
                        <div class="total-label text-muted small">Total Belanja</div>
                        <div class="total-amount">Rp 60.000</div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-success border-2 fw-bold" style="border-radius: 12px; font-size: 0.85rem;">Beli Lagi</button>
                        <a href="#" class="btn btn-detail">Detail</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection