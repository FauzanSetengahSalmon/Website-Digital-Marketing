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

    /* HEADER KE TENGAH */
    .order-history-header {
        margin: 40px 0;
        text-align: center;
        /* Teks ke tengah */
    }

    .order-history-header h2 {
        font-weight: 800;
        color: var(--green-dark);
        margin-bottom: 10px;
    }

    /* Garis dekorasi bawah judul */
    .header-line {
        width: 60px;
        height: 4px;
        background: var(--green-primary);
        margin: 0 auto 15px auto;
        border-radius: 10px;
    }

    .order-card {
        border: none;
        border-radius: 16px;
        background: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .05);
        margin-bottom: 20px;
        transition: .25s;
        overflow: hidden;
    }

    .order-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, .1);
    }

    .order-header {
        background: var(--green-light);
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .order-date {
        font-size: 0.9rem;
        color: var(--green-dark);
        font-weight: 600;
    }

    .order-status {
        font-size: 0.8rem;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-dibayar {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-menunggu {
        background: #fff8e1;
        color: #f57f17;
    }

    .status-dikirim {
        background: #e3f2fd;
        color: #1565c0;
    }

    .order-body {
        padding: 20px;
    }

    .item-list {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }

    .item-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 10px;
    }

    .item-info h6 {
        color: var(--green-dark);
        margin-bottom: 5px;
        font-weight: 700;
    }

    .order-footer {
        padding: 15px 20px;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .total-label {
        font-size: 0.85rem;
        color: #777;
    }

    .total-amount {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--green-dark);
    }

    .btn-detail {
        background-color: var(--green-primary);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-detail:hover {
        background-color: var(--green-dark);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 100px 0;
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--green-light);
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="order-history-header">
        <h2 class="section-title">Riwayat Pesanan</h2>
        <div class="header-line"></div>
        <p class="text-muted">Pantau status belanja dan dokumentasi transaksi Anda</p>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">

            <div class="order-card">
                <div class="order-header">
                    <div class="order-date">
                        <i class="bi bi-calendar3 me-2"></i> 15 April 2026 | ID: #ORD-99210
                    </div>
                    <span class="order-status status-dikirim">Dalam Pengiriman</span>
                </div>
                <div class="order-body">
                    <div class="item-list">
                        <img src="{{ asset('image/Screenshot 2026-04-16 233530.png') }}" class="item-img" alt="Produk">
                        <div class="item-info">
                            <h6>Bayam Organik Segar</h6>
                            <p class="text-muted mb-0 small">2 x Rp 15.000</p>
                            <small class="text-muted">+1 produk lainnya</small>
                        </div>
                    </div>
                </div>
                <div class="order-footer">
                    <div>
                        <div class="total-label">Total Belanja</div>
                        <div class="total-amount">Rp 45.000</div>
                    </div>
                    <a href="#" class="btn btn-detail">Lihat Detail</a>
                </div>
            </div>

            <div class="order-card">
                <div class="order-header">
                    <div class="order-date">
                        <i class="bi bi-calendar3 me-2"></i> 10 April 2026 | ID: #ORD-99185
                    </div>
                    <span class="order-status status-dibayar">Selesai</span>
                </div>
                <div class="order-body">
                    <div class="item-list">
                        <img src="{{ asset('image/Screenshot 2026-04-16 233530.png') }}" class="item-img" alt="Produk">
                        <div class="item-info">
                            <h6>Keripik Bayam Gurih (KWT Melati)</h6>
                            <p class="text-muted mb-0 small">3 x Rp 20.000</p>
                        </div>
                    </div>
                </div>
                <div class="order-footer">
                    <div>
                        <div class="total-label">Total Belanja</div>
                        <div class="total-amount">Rp 60.000</div>
                    </div>
                    <a href="#" class="btn btn-detail">Lihat Detail</a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection