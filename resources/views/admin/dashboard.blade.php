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
    }

    .table thead {
        background: #f0f9eb;
    }

    .table thead th {
        color: #2d7a22;
        font-weight: 700;
        border: none;
    }

    .table td {
        vertical-align: middle;
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
            <h2 class="admin-title mb-1">
                Dashboard Admin
            </h2>

            <p class="admin-subtitle mb-0">
                Monitoring seluruh aktivitas KWT Cibiru
            </p>
        </div>

        <div>
            <span class="badge-status">
                <i class="bi bi-check-circle-fill me-1"></i>
                Sistem Aktif
            </span>
        </div>

    </div>

    {{-- CARD --}}
    <div class="row g-4 mb-5">

        <div class="col-md-3">

            <div class="card admin-card bg-green rounded-4 shadow-sm p-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <small class="text-uppercase opacity-75 fw-semibold">
                            Total KWT
                        </small>

                        <h2 class="fw-bold mt-2 mb-0">
                            {{ $totalKwt }}
                        </h2>
                    </div>

                    <div class="icon-shape">
                        <i class="bi bi-people-fill"></i>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card admin-card bg-blue rounded-4 shadow-sm p-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <small class="text-uppercase opacity-75 fw-semibold">
                            Total Produk
                        </small>

                        <h2 class="fw-bold mt-2 mb-0">
                            {{ $totalProduk }}
                        </h2>
                    </div>

                    <div class="icon-shape">
                        <i class="bi bi-box-seam"></i>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card admin-card bg-orange rounded-4 shadow-sm p-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <small class="text-uppercase opacity-75 fw-semibold">
                            Total Pesanan
                        </small>

                        <h2 class="fw-bold mt-2 mb-0">
                            {{ $totalPesanan }}
                        </h2>
                    </div>

                    <div class="icon-shape">
                        <i class="bi bi-cart-check-fill"></i>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card admin-card bg-purple rounded-4 shadow-sm p-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <small class="text-uppercase opacity-75 fw-semibold">
                            Total Pendapatan
                        </small>

                        <h4 class="fw-bold mt-2 mb-0">
                            Rp {{ number_format($totalPendapatan,0,',','.') }}
                        </h4>
                    </div>

                    <div class="icon-shape">
                        <i class="bi bi-wallet2"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- INFO BOX --}}
    <div class="welcome-box mb-5">

        <div class="row align-items-center">

            <div class="col-md-3 text-center">

                <img
                    src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                    width="170"
                    class="img-fluid">

            </div>

            <div class="col-md-9">

                <h3 class="fw-bold mb-3">
                    Selamat Datang Admin 👋
                </h3>

                <p class="text-muted mb-4">
                    Dashboard ini digunakan untuk memantau seluruh aktivitas penjualan,
                    akun KWT, produk, dan perkembangan UMKM digital KWT Cibiru.
                </p>

                <a href="{{ route('admin.users') }}"
                   class="btn btn-success rounded-pill px-4">

                    <i class="bi bi-people me-2"></i>
                    Kelola Akun KWT

                </a>

            </div>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="kwt-table">

        <div class="p-4 border-bottom">

            <h5 class="fw-bold mb-0">
                Data KWT Terdaftar
            </h5>

        </div>

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>

                    <tr>
                        <th class="ps-4">Nama KWT</th>
                        <th>Email</th>
                        <th>Total Produk</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($kwts as $kwt)

                    <tr>

                        <td class="ps-4 fw-semibold">
                            {{ $kwt->name }}
                        </td>

                        <td>
                            {{ $kwt->email }}
                        </td>

                        <td>
                            {{ $kwt->products->count() }} Produk
                        </td>

                        <td>
                            <span class="badge bg-success">
                                Aktif
                            </span>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">
                            Belum ada akun KWT
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection