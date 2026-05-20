@extends('layouts.kwt')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    body {
        font-family: 'Inter', sans-serif;
        background: #f6f7fb;
    }

    .page-title {
        font-weight: 800;
        font-size: 26px;
        color: #111827;
    }

    .sub-title {
        color: #6b7280;
        font-size: 13px;
    }

    .stat-card {
        background: white;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .04);
        height: 100%;
        border: 1px solid #f1f5f9;
    }

    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .icon-green {
        background: #ecfdf5;
        color: #16a34a;
    }

    .icon-blue {
        background: #eef4ff;
        color: #2563eb;
    }

    .icon-orange {
        background: #fff7ed;
        color: #f97316;
    }

    .table-card {
        background: white;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .04);
        border: 1px solid #f1f5f9;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        border-bottom: none;
        padding: 16px 20px;
        font-size: 11px;
        color: #6b7280;
        font-weight: 700;
        white-space: nowrap;
        background: #fafafa;
    }

    .table tbody td {
        padding: 18px 20px;
        vertical-align: middle;
        border-top: 1px solid #f1f5f9;
        font-size: 13px;
    }

    .table tbody tr:hover {
        background: #fcfcfc;
    }

    .order-id {
        font-weight: 800;
        color: #111827;
    }

    .product-name {
        font-size: 13px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 3px;
    }

    .product-extra {
        font-size: 11px;
        color: #9ca3af;
    }

    .qty-badge {
        background: #f3f4f6;
        color: #111827;
        border-radius: 999px;
        padding: 5px 10px;
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
    }

    .total-price {
        font-size: 14px;
        font-weight: 800;
        color: #16a34a;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .status-menunggu {
        background: #fff7ed;
        color: #ea580c;
    }

    .status-diproses {
        background: #eff6ff;
        color: #2563eb;
    }

    .status-selesai {
        background: #ecfdf5;
        color: #16a34a;
    }

    .empty-box {
        padding: 70px 20px;
        text-align: center;
    }

    .empty-box i {
        font-size: 50px;
        color: #cbd5e1;
    }

    .empty-box h6 {
        margin-top: 15px;
        font-weight: 700;
        color: #111827;
    }

    .empty-box p {
        color: #9ca3af;
        font-size: 13px;
    }

    .product-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 10px 12px;
        margin-top: 8px;
    }

    .product-box-name {
        font-size: 12px;
        font-weight: 700;
        color: #111827;
    }

    .product-box-detail {
        font-size: 11px;
        color: #6b7280;
    }
</style>

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="page-title">Pesanan Masuk</div>
            <div class="sub-title">Kelola pesanan pelanggan khusus produk milik KWT Anda</div>
        </div>
    </div>

    {{-- STATS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Total Pesanan</small>
                        <h4 class="fw-bold mt-1 mb-0">{{ $orders->count() }}</h4>
                    </div>
                    <div class="stat-icon icon-blue">
                        <i class="bi bi-bag-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Menunggu Diproses</small>
                        <h4 class="fw-bold mt-1 mb-0">{{ $orders->where('status','menunggu')->count() }}</h4>
                    </div>
                    <div class="stat-icon icon-orange">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Sedang Dikirim</small>
                        <h4 class="fw-bold mt-1 mb-0">{{ $orders->where('status','diproses')->count() }}</h4>
                    </div>
                    <div class="stat-icon icon-green">
                        <i class="bi bi-truck"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-card">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Invoice</th>
                        <th>Produk KWT</th>
                        <th>Jumlah</th>
                        <th>Total KWT</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $o)
                    @php
                    // Saring item: Hanya ambil produk milik KWT yang sedang login
                    $kwtDetails = $o->details->filter(function($d) {
                    return $d->product->user_id == Auth::id();
                    });

                    // Hitung total harga eksklusif untuk barang KWT ini saja
                    $totalKwt = $kwtDetails->sum(function($d){
                    return $d->harga_saat_ini * $d->jumlah;
                    });
                    @endphp

                    {{-- Hanya tampilkan baris invoice jika ada produk milik KWT ini --}}
                    @if($kwtDetails->count() > 0)
                    <tr>
                        {{-- INVOICE ID --}}
                        <td class="ps-4">
                            <div class="order-id">#ORD-{{ $o->id }}</div>
                            <div class="small text-muted mt-1">
                                {{ $o->created_at->format('d M Y H:i') }}
                            </div>
                        </td>

                        {{-- LIST PRODUK YANG DIFILTER --}}
                        <td>
                            <div class="product-name">
                                {{ $kwtDetails->first()->product->nama_produk ?? 'Produk' }}
                            </div>

                            @if($kwtDetails->count() > 1)
                            <div class="product-extra">
                                +{{ $kwtDetails->count() - 1 }} produk lainnya
                            </div>
                            @endif

                            <div class="mt-2">
                                @foreach($kwtDetails as $detail)
                                <div class="product-box">
                                    <div class="product-box-name">
                                        {{ $detail->product->nama_produk }}
                                    </div>
                                    <div class="product-box-detail">
                                        {{ $detail->jumlah }} x Rp {{ number_format($detail->harga_saat_ini,0,',','.') }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </td>

                        {{-- TOTAL QUANTITY ITEM KHUSUS KWT --}}
                        <td>
                            <span class="qty-badge">
                                {{ $kwtDetails->sum('jumlah') }} Item
                            </span>
                        </td>

                        {{-- TOTAL PEMBAYARAN KHUSUS KWT --}}
                        <td class="total-price">
                            Rp {{ number_format($totalKwt,0,',','.') }}
                        </td>

                        {{-- STATUS PESANAN GLOBAL --}}
                        <td>
                            @if($o->status == 'menunggu')
                            <span class="status-badge status-menunggu">
                                <i class="bi bi-clock-history"></i> Menunggu
                            </span>
                            @elseif($o->status == 'diproses')
                            <span class="status-badge status-diproses">
                                <i class="bi bi-truck"></i> Diproses
                            </span>
                            @else
                            <span class="status-badge status-selesai">
                                <i class="bi bi-check-circle"></i> {{ ucfirst($o->status) }}
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endif

                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-box">
                                <i class="bi bi-bag-x"></i>
                                <h6>Belum Ada Pesanan</h6>
                                <p class="mb-0">Pesanan pelanggan akan muncul di sini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection