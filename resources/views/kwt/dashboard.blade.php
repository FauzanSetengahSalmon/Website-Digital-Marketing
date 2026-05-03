@extends('layouts.kwt')

@section('content')
<!-- Tambahkan link Google Font & Bootstrap Icons di layout utama jika belum ada -->
<style>
    .kwt-card {
        transition: transform 0.2s;
        border: none;
        overflow: hidden;
    }

    .kwt-card:hover {
        transform: translateY(-5px);
    }

    .icon-shape {
        width: 48px;
        height: 48px;
        background-image: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.5rem;
    }

    .text-sm-custom {
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .bg-gradient-green {
        background: linear-gradient(45deg, #2d6a4f, #52b788);
        color: white;
    }

    .bg-gradient-blue {
        background: linear-gradient(45deg, #0077b6, #48cae4);
        color: white;
    }

    .bg-gradient-orange {
        background: linear-gradient(45deg, #e67e22, #f39c12);
        color: white;
    }

    .bg-gradient-purple {
        background: linear-gradient(45deg, #6d597a, #b56576);
        color: white;
    }

    /* Tambahan style untuk nama KWT */
    .kwt-name-badge {
        background-color: #e8f5e9;
        color: #2e7d32;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 5px;
    }
</style>

<div class="container-fluid py-4">
    <!-- Header Bagian Atas -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <div class="kwt-name-badge shadow-sm text-uppercase">
                <i class="bi bi-house-heart me-1"></i> {{ Auth::user()->name }}
            </div>
            <h2 class="fw-bold text-dark mb-0">Selamat Pagi, Ibu-ibu Pengurus!</h2>
            <p class="text-muted mb-0">Berikut adalah laporan usaha kita hari ini.</p>
        </div>
        <div class="text-md-end">
            <span class="badge bg-white text-success border border-success px-3 py-2 rounded-pill shadow-sm">
                <i class="bi bi-check-circle-fill me-1"></i> Sistem Aktif
            </span>
            <div class="small text-muted mt-1"><i class="bi bi-calendar3 me-1"></i> {{ date('d F Y') }}</div>
        </div>
    </div>

    <!-- Statistik Utama -->
    <div class="row g-4 mb-5">
        <!-- Pendapatan -->
        <div class="col-md-3">
            <div class="card kwt-card shadow-sm rounded-4 bg-gradient-green p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-sm-custom fw-bold text-uppercase opacity-75">Hasil Uang Penualan</small>
                        <h3 class="fw-bold mb-0 mt-1">Rp {{ number_format($stats['total_received'] ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <div class="icon-shape shadow-sm">
                        <i class="bi bi-wallet2 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Terjual -->
        <div class="col-md-3">
            <div class="card kwt-card shadow-sm rounded-4 bg-gradient-blue p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-sm-custom fw-bold text-uppercase opacity-75">Produk Terjual</small>
                        <h3 class="fw-bold mb-0 mt-1">{{ $stats['sold_count'] ?? 0 }} <span class="fs-6 fw-normal">Item</span></h3>
                    </div>
                    <div class="icon-shape shadow-sm">
                        <i class="bi bi-cart-check text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stok Produk -->
        <div class="col-md-3">
            <div class="card kwt-card shadow-sm rounded-4 bg-gradient-purple p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-sm-custom fw-bold text-uppercase opacity-75">Total Produk</small>
                        <h3 class="fw-bold mb-0 mt-1">{{ $stats['total_products'] ?? 0 }} <span class="fs-6 fw-normal">Jenis</span></h3>
                    </div>
                    <div class="icon-shape shadow-sm">
                        <i class="bi bi-box-seam text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pesanan Baru -->
        <div class="col-md-3">
            <div class="card kwt-card shadow-sm rounded-4 bg-gradient-orange p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-sm-custom fw-bold text-uppercase opacity-75">Pesanan Masuk</small>
                        <h3 class="fw-bold mb-0 mt-1">{{ $stats['pending_orders'] ?? 0 }} <span class="fs-6 fw-normal">Baru</span></h3>
                    </div>
                    <div class="icon-shape shadow-sm">
                        <i class="bi bi-bell-fill text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Bawah: Motivasi & Ilustrasi -->
    <div class="row align-items-center bg-white mx-1 rounded-4 shadow-sm p-4 border-top border-success border-4">
        <div class="col-md-3 text-center">
            <img src="https://illustrations.popsy.co/green/data-analysis.svg" style="height: 160px;" alt="illustration" class="img-fluid">
        </div>
        <div class="col-md-9 text-center text-md-start">
            <h4 class="fw-bold text-dark">Laporan Bisnis {{ Auth::user()->name }}</h4>
            <p class="text-muted small">Semua data di atas adalah hasil kerja keras kelompok kita. Mari terus jaga kualitas produk dan layani pelanggan dengan ramah agar KWT kita semakin maju dan barokah.</p>
            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start mt-3">
                <a href="#" class="btn btn-success rounded-pill px-4 shadow-sm">
                    <i class="bi bi-receipt me-2"></i> Periksa Pesanan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection