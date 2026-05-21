@extends('layouts.admin')

@section('content')

<style>
    .admin-card {
        transition: .2s ease;
        border: none;
        overflow: hidden;
    }

    .admin-card:hover {
        transform: translateY(-5px);
    }

    .icon-shape {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        background: rgba(255,255,255,.15);
    }

    .bg-green {
        background: linear-gradient(135deg,#2d7a22,#43a047);
        color: white;
    }

    .bg-blue {
        background: linear-gradient(135deg,#1976d2,#42a5f5);
        color: white;
    }

    .bg-orange {
        background: linear-gradient(135deg,#ef6c00,#ffa726);
        color: white;
    }

    .bg-purple {
        background: linear-gradient(135deg,#7b1fa2,#ba68c8);
        color: white;
    }

    .welcome-box {
        background: white;
        border-radius: 24px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,.05);
        border-top: 5px solid #2d7a22;
    }

    .kwt-table {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,.05);
        margin-bottom: 30px;
    }

    .table thead {
        background: #f0f9eb;
    }

    .table thead th {
        color: #2d7a22;
        font-weight: 700;
        border: none;
        padding: 15px;
    }

    .table td {
        vertical-align: middle;
        padding: 15px;
    }

    .badge-status {
        background: #e8f5e9;
        color: #2d7a22;
        padding: 7px 14px;
        border-radius: 999px;
        font-size: .8rem;
        font-weight: 600;
    }

    .admin-title {
        font-weight: 800;
        color: #1e293b;
    }

    .admin-subtitle {
        color: #64748b;
    }
</style>

<div class="container-fluid py-2">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="admin-title mb-1">Dashboard Admin</h2>
            <p class="admin-subtitle mb-0">Monitoring seluruh aktivitas KWT Cibiru</p>
        </div>
        <div>
            <span class="badge-status">
                <i class="bi bi-check-circle-fill me-1"></i> Sistem Aktif
            </span>
        </div>
    </div>

    {{-- CARD STATISTIK --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card admin-card bg-green rounded-4 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-uppercase opacity-75 fw-semibold">Total KWT</small>
                        <h2 class="fw-bold mt-2 mb-0">{{ $totalKwt }}</h2>
                    </div>
                    <div class="icon-shape"><i class="bi bi-people-fill"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card admin-card bg-blue rounded-4 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-uppercase opacity-75 fw-semibold">Total Produk</small>
                        <h2 class="fw-bold mt-2 mb-0">{{ $totalProduk }}</h2>
                    </div>
                    <div class="icon-shape"><i class="bi bi-box-seam"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card admin-card bg-orange rounded-4 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-uppercase opacity-75 fw-semibold">Total Pesanan</small>
                        <h2 class="fw-bold mt-2 mb-0">{{ $totalPesanan }}</h2>
                    </div>
                    <div class="icon-shape"><i class="bi bi-cart-check-fill"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card admin-card bg-purple rounded-4 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-uppercase opacity-75 fw-semibold">Total Omzet Global</small>
                        <h4 class="fw-bold mt-2 mb-0">Rp {{ number_format($totalPendapatan,0,',','.') }}</h4>
                    </div>
                    <div class="icon-shape"><i class="bi bi-wallet2"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- WELCOME BOX --}}
    <div class="welcome-box mb-5">
        <div class="row align-items-center">
            <div class="col-md-2 text-center d-none d-md-block">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" width="120" class="img-fluid">
            </div>
            <div class="col-md-10">
                <h4 class="fw-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h4>
                <p class="text-muted mb-3">
                    Anda memiliki akses penuh untuk mengelola data User, Akun KWT, dan memantau seluruh transaksi yang terjadi di sistem.
                </p>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.kwt') }}" class="btn btn-success btn-sm rounded-pill px-4">
                        <i class="bi bi-plus-circle me-1"></i> Tambah KWT
                    </a>
                    <a href="{{ route('admin.sales.index') }}" class="btn btn-outline-success btn-sm rounded-pill px-4">
                        <i class="bi bi-graph-up me-1"></i> Lihat Penjualan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- TABEL DATA KWT --}}
        <div class="col-lg-7">
            <div class="kwt-table">
                <div class="p-4 border-bottom">
                    <h5 class="fw-bold mb-0"><i class="bi bi-shop me-2"></i>Daftar KWT Terdaftar</h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nama KWT</th>
                                <th>Produk</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kwts as $kwt)
                            <tr>
                                <td class="fw-semibold">{{ $kwt->name }}</td>
                                {{-- KODE YANG SUDAH DIPERBAIKI DENGAN MENGGUNAKAN FUNGSI COUNT KANONIKAL --}}
                                <td><span class="badge bg-light text-success border">{{ $kwt->products->count() }} Item</span></td>
                                <td>
                                    <a href="{{ route('admin.kwt') }}" class="btn btn-sm btn-light text-success fw-bold">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Belum ada KWT.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TABEL PERINGKAT OMZET KWT --}}
        <div class="col-lg-5">
            <div class="kwt-table border-top border-success border-4">
                <div class="p-4 border-bottom">
                    <h5 class="fw-bold mb-0"><i class="bi bi-trophy me-2 text-warning"></i>Omzet per KWT</h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>KWT</th>
                                <th class="text-end">Omzet Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penjualanPerKwt as $item)
                            <tr>
                                <td class="fw-bold text-secondary">{{ $item['nama'] }}</td>
                                <td class="text-end fw-bold text-success">
                                    Rp {{ number_format($item['omzet'], 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection