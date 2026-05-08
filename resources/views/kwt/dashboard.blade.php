@extends('layouts.kwt')

@section('content')

<style>
    .kwt-card {
        transition: .2s;
        border: none;
        overflow: hidden;
    }

    .kwt-card:hover {
        transform: translateY(-5px);
    }

    .icon-shape {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: rgba(255,255,255,.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .bg-gradient-green {
        background: linear-gradient(135deg,#2d6a4f,#52b788);
        color: white;
    }

    .bg-gradient-blue {
        background: linear-gradient(135deg,#0077b6,#48cae4);
        color: white;
    }

    .bg-gradient-orange {
        background: linear-gradient(135deg,#e67e22,#f39c12);
        color: white;
    }

    .bg-gradient-purple {
        background: linear-gradient(135deg,#6d597a,#b56576);
        color: white;
    }

    .welcome-box {
        background: white;
        border-radius: 24px;
        padding: 35px;
        box-shadow: 0 10px 30px rgba(0,0,0,.05);
    }
</style>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <div>
            <h2 class="fw-bold mb-1">
                Halo, {{ Auth::user()->name }} 👋
            </h2>

            <p class="text-muted mb-0">
                Selamat datang di dashboard KWT Cibiru
            </p>
        </div>

        <span class="badge bg-success px-3 py-2 rounded-pill">
            {{ date('d F Y') }}
        </span>

    </div>

    <div class="row g-4 mb-5">

        <div class="col-md-3">

            <div class="card kwt-card bg-gradient-green p-3 shadow-sm rounded-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <small>Total Pendapatan</small>

                        <h3 class="fw-bold mt-2">
                            Rp {{ number_format($stats['total_received'],0,',','.') }}
                        </h3>
                    </div>

                    <div class="icon-shape">
                        <i class="bi bi-wallet2"></i>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card kwt-card bg-gradient-blue p-3 shadow-sm rounded-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <small>Produk Terjual</small>

                        <h3 class="fw-bold mt-2">
                            {{ $stats['sold_count'] }}
                        </h3>
                    </div>

                    <div class="icon-shape">
                        <i class="bi bi-cart-check"></i>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card kwt-card bg-gradient-purple p-3 shadow-sm rounded-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <small>Total Produk</small>

                        <h3 class="fw-bold mt-2">
                            {{ $stats['total_products'] }}
                        </h3>
                    </div>

                    <div class="icon-shape">
                        <i class="bi bi-box-seam"></i>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card kwt-card bg-gradient-orange p-3 shadow-sm rounded-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <small>Pesanan Baru</small>

                        <h3 class="fw-bold mt-2">
                            {{ $stats['pending_orders'] }}
                        </h3>
                    </div>

                    <div class="icon-shape">
                        <i class="bi bi-bell-fill"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="welcome-box">

        <div class="row align-items-center">

            <div class="col-md-4 text-center mb-4 mb-md-0">

                <img src="https://cdn-icons-png.flaticon.com/512/3079/3079177.png"
                    class="img-fluid"
                    style="max-height:220px;">

            </div>

            <div class="col-md-8">

                <h3 class="fw-bold mb-3">
                    Dashboard Kelompok Wanita Tani
                </h3>

                <p class="text-muted">
                    Kelola produk, pesanan, dan laporan penjualan dengan lebih mudah
                    menggunakan sistem digital KWT Cibiru.
                </p>

                <a href="{{ route('kwt.orders') }}"
                    class="btn btn-success rounded-pill px-4 mt-2">

                    <i class="bi bi-receipt me-2"></i>
                    Lihat Pesanan

                </a>

            </div>

        </div>

    </div>

</div>

@endsection