@extends('layouts.admin')

@section('content')
<style>
    /* Menggunakan CSS yang sudah Anda miliki sebelumnya */
    .admin-card {
        border: none;
        border-radius: 20px;
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .admin-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
    }

    .icon-shape {
        width: 55px;
        height: 55px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
    }

    .gradient-green {
        background: linear-gradient(135deg, #166534 0%, #22c55e 100%);
        color: white;
    }

    .gradient-blue {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
    }

    .gradient-orange {
        background: linear-gradient(135deg, #9a3412 0%, #f97316 100%);
        color: white;
    }

    .gradient-purple {
        background: linear-gradient(135deg, #6b21a8 0%, #a855f7 100%);
        color: white;
    }

    .kwt-table {
        background: white;
        border-radius: 24px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .badge-status {
        background: #dcfce7;
        color: #166534;
        padding: 6px 16px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.75rem;
        border: 1px solid #bbf7d0;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
    }
</style>

@php
$kwtPending = \App\Models\OrderDetail::whereHas('order', function ($q) { $q->where('status', 'selesai'); })->where('is_cair_kwt', false)->sum(DB::raw('harga_saat_ini * jumlah'));
$kurirPending = \App\Models\Order::where('status', 'selesai')->where('is_paid_out', false)->sum('ongkir');
$totalOmzetKwt = collect($penjualanPerKwt)->sum('omzet');
$totalOngkir = collect($penjualanPerKurir)->sum('total_ongkir');
$totalBiayaAdmin = max(0, $totalPendapatan - ($totalOmzetKwt + $totalOngkir));
@endphp

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold text-dark mb-1">Dashboard Admin</h2>
            <p class="text-muted">Selamat datang kembali, Admin. Berikut ringkasan operasional dan finansial.</p>
        </div>
        <span class="badge-status"><i class="bi bi-shield-check me-1"></i> Sistem Aktif</span>
    </div>

    {{-- STATISTIK UMUM --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card admin-card gradient-green p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div><small class="opacity-75">Total KWT</small>
                        <h2 class="fw-bold mb-0">{{ $totalKwt }}</h2>
                    </div>
                    <div class="icon-shape"><i class="bi bi-people"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card admin-card gradient-blue p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div><small class="opacity-75">Produk</small>
                        <h2 class="fw-bold mb-0">{{ $totalProduk }}</h2>
                    </div>
                    <div class="icon-shape"><i class="bi bi-box-seam"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card admin-card gradient-orange p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div><small class="opacity-75">Pesanan</small>
                        <h2 class="fw-bold mb-0">{{ $totalPesanan }}</h2>
                    </div>
                    <div class="icon-shape"><i class="bi bi-cart3"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card admin-card gradient-purple p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div><small class="opacity-75">Total Omzet</small>
                        <h5 class="fw-bold mb-0">Rp {{ number_format($totalPendapatan,0,',','.') }}</h5>
                    </div>
                    <div class="icon-shape"><i class="bi bi-wallet2"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- RINCIAN DISTRIBUSI KEUANGAN (DENGAN INFO DANA SUDAH DICAIRKAN) --}}
    <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-pie-chart-fill me-2 text-success"></i>Buku Kas & Kewajiban Finansial</h5>
    <div class="row g-4 mb-5">

        {{-- KARTU SALDO KWT --}}
        <div class="col-md-4">
            <div class="card admin-card bg-white p-4 border-bottom border-success border-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem;">Saldo Mitra KWT (Siap Cair)</small>
                        <h3 class="fw-extrabold mb-0 text-success mt-1">Rp {{ number_format($kwtPending, 0, ',', '.') }}</h3>
                    </div>
                    <div class="icon-shape bg-success bg-opacity-10 text-success"><i class="bi bi-shop"></i></div>
                </div>
                <div class="pt-3 border-top mt-auto">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Sudah Disalurkan:</small>
                        <small class="fw-bold text-dark">Rp {{ number_format($totalOmzetKwt - $kwtPending, 0, ',', '.') }}</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- KARTU SALDO KURIR --}}
        <div class="col-md-4">
            <div class="card admin-card bg-white p-4 border-bottom border-primary border-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem;">Saldo Kurir (Siap Cair)</small>
                        <h3 class="fw-extrabold mb-0 text-primary mt-1">Rp {{ number_format($kurirPending, 0, ',', '.') }}</h3>
                    </div>
                    <div class="icon-shape bg-primary bg-opacity-10 text-primary"><i class="bi bi-truck"></i></div>
                </div>
                <div class="pt-3 border-top mt-auto">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Sudah Disalurkan:</small>
                        <small class="fw-bold text-dark">Rp {{ number_format($totalOngkir - $kurirPending, 0, ',', '.') }}</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- KARTU PENDAPATAN ADMIN --}}
        <div class="col-md-4">
            <div class="card admin-card bg-white p-4 border-bottom border-warning border-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem;">Pendapatan Admin</small>
                        <h3 class="fw-extrabold mb-0 text-warning mt-1">Rp {{ number_format($totalBiayaAdmin, 0, ',', '.') }}</h3>
                    </div>
                    <div class="icon-shape bg-warning bg-opacity-10 text-warning"><i class="bi bi-laptop"></i></div>
                </div>
                <div class="pt-3 border-top mt-auto">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Status:</small>
                        <small class="fw-bold text-success">Hak Milik Platform</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL LEADERBOARD --}}
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="kwt-table p-3">
                <h6 class="p-3 section-title"><i class="bi bi-shop me-2 text-green"></i>KWT Terdaftar</h6>
                <table class="table mb-0">
                    <tbody>@foreach($kwts as $kwt)<tr>
                            <td class="fw-semibold">{{ $kwt->name }}</td>
                            <td class="text-center"><span class="badge bg-success text-white">{{ $kwt->products->count() }} Produk</span></td>
                        </tr>@endforeach</tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="kwt-table p-3 h-100">
                <h6 class="p-3 section-title"><i class="bi bi-trophy me-2 text-warning"></i>Performa KWT</h6>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>KWT</th>
                                <th class="text-end">Omzet</th>
                                <th class="text-end">Sudah Cair</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penjualanPerKwt as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item['nama'] }}</td>
                                <td class="text-end fw-bold text-success">Rp {{ number_format($item['omzet'], 0, ',', '.') }}</td>
                                <td class="text-end text-muted small">Rp {{ number_format($item['sudah_dicairkan'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="kwt-table p-3 h-100">
                <h6 class="p-3 section-title"><i class="bi bi-truck me-2 text-primary"></i>Performa Kurir</h6>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Kurir</th>
                                <th class="text-end">Total Ongkir</th>
                                <th class="text-end">Sudah Cair</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penjualanPerKurir as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item['nama'] }}</td>
                                <td class="text-end fw-bold text-primary">Rp {{ number_format($item['total_ongkir'], 0, ',', '.') }}</td>
                                <td class="text-end text-muted small">Rp {{ number_format($item['sudah_dicairkan'], 0, ',', '.') }}</td>
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