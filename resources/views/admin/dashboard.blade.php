@extends('layouts.admin')

@section('content')
<style>
    /* Modern Dashboard Styling */
    .admin-card { border: none; border-radius: 20px; transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
    .admin-card:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.15); }
    
    .icon-shape { width: 55px; height: 55px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; background: rgba(255,255,255,0.2); backdrop-filter: blur(5px); }
    
    .gradient-green { background: linear-gradient(135deg, #166534 0%, #22c55e 100%); color: white; }
    .gradient-blue { background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white; }
    .gradient-orange { background: linear-gradient(135deg, #9a3412 0%, #f97316 100%); color: white; }
    .gradient-purple { background: linear-gradient(135deg, #6b21a8 0%, #a855f7 100%); color: white; }

    .kwt-table { background: white; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .table thead th { background: #f8fafc !important; color: #64748b !important; text-transform: uppercase; font-size: 0.75rem !important; letter-spacing: 0.05em; border-bottom: 1px solid #f1f5f9 !important; }
    .table td { padding: 18px !important; font-size: 0.9rem; color: #334155; }
    
    .badge-status { background: #dcfce7; color: #166534; padding: 6px 16px; border-radius: 50px; font-weight: 700; font-size: 0.75rem; border: 1px solid #bbf7d0; }
    .section-title { font-size: 1.25rem; font-weight: 800; color: #0f172a; }
</style>

<div class="container-fluid py-4">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="admin-title mb-1">Dashboard Admin</h2>
            <p class="admin-subtitle text-muted">Selamat datang kembali, Admin. Berikut ringkasan operasional.</p>
        </div>
        <span class="badge-status"><i class="bi bi-shield-check me-1"></i> Sistem Aktif</span>
    </div>

    {{-- STATISTIK --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3"><div class="card admin-card gradient-green p-4"><div class="d-flex justify-content-between align-items-center"><div><small class="opacity-75">Total KWT</small><h2 class="fw-bold mb-0">{{ $totalKwt }}</h2></div><div class="icon-shape"><i class="bi bi-people"></i></div></div></div></div>
        <div class="col-md-3"><div class="card admin-card gradient-blue p-4"><div class="d-flex justify-content-between align-items-center"><div><small class="opacity-75">Produk</small><h2 class="fw-bold mb-0">{{ $totalProduk }}</h2></div><div class="icon-shape"><i class="bi bi-box-seam"></i></div></div></div></div>
        <div class="col-md-3"><div class="card admin-card gradient-orange p-4"><div class="d-flex justify-content-between align-items-center"><div><small class="opacity-75">Pesanan</small><h2 class="fw-bold mb-0">{{ $totalPesanan }}</h2></div><div class="icon-shape"><i class="bi bi-cart3"></i></div></div></div></div>
        <div class="col-md-3"><div class="card admin-card gradient-purple p-4"><div class="d-flex justify-content-between align-items-center"><div><small class="opacity-75">Total Omzet</small><h5 class="fw-bold mb-0">Rp {{ number_format($totalPendapatan,0,',','.') }}</h5></div><div class="icon-shape"><i class="bi bi-wallet2"></i></div></div></div></div>
    </div>

    {{-- TABEL --}}
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="kwt-table p-3">
                <h6 class="p-3 section-title"><i class="bi bi-shop me-2 text-green"></i>KWT Terdaftar</h6>
                <table class="table mb-0">
                    <thead><tr><th>Nama</th><th class="text-center">Produk</th></tr></thead>
                    <tbody>
                        @foreach($kwts as $kwt)
                        <tr><td class="fw-semibold">{{ $kwt->name }}</td><td class="text-center"><span class="badge bg-green text-white">{{ $kwt->products->count() }}</span></td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="kwt-table p-3">
                <h6 class="p-3 section-title"><i class="bi bi-trophy me-2 text-warning"></i>Omzet KWT</h6>
                <table class="table mb-0">
                    <thead><tr><th>Nama KWT</th><th class="text-end">Omzet</th></tr></thead>
                    <tbody>
                        @foreach($penjualanPerKwt as $item)
                        <tr><td class="fw-semibold">{{ $item['nama'] }}</td><td class="text-end fw-bold text-success">Rp {{ number_format($item['omzet'], 0, ',', '.') }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="kwt-table p-3">
                <h6 class="p-3 section-title"><i class="bi bi-truck me-2 text-primary"></i>Omzet Kurir</h6>
                <table class="table mb-0">
                    <thead><tr><th>Kurir</th><th class="text-end">Ongkir</th></tr></thead>
                    <tbody>
                        @foreach($penjualanPerKurir as $item)
                        <tr><td class="fw-semibold">{{ $item['nama'] }}</td><td class="text-end fw-bold text-primary">Rp {{ number_format($item['total_ongkir'], 0, ',', '.') }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection