@extends('layouts.kwt')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Inter', sans-serif;
        background: #f6f7fb;
    }

    /* header */
    .page-title {
        font-weight: 800;
        font-size: 26px
    }

    .sub-title {
        color: #6b7280
    }

    /* stat cards */
    .stat-card {
        background: white;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, .04);
        height: 100%;
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .icon-green {
        background: #e8f7ee;
        color: #16a34a
    }

    .icon-blue {
        background: #eef4ff;
        color: #2563eb
    }

    .icon-orange {
        background: #fff4e6;
        color: #f97316
    }

    /* action buttons */
    .btn-soft {
        border-radius: 999px;
        padding: 10px 22px;
        font-weight: 600;
    }

    .btn-export {
        background: #16a34a;
        color: white
    }

    .btn-export:hover {
        background: #15803d;
        color: white
    }

    .btn-withdraw {
        background: #111827;
        color: white
    }

    .btn-withdraw:hover {
        background: #000
    }

    /* table */
    .table-card {
        background: white;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .04);
    }

    .table thead th {
        font-size: 13px;
        color: #6b7280;
        font-weight: 600;
        border-bottom: none;
    }

    .table tbody tr:hover {
        background: #f9fafb;
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 999px;
        font-weight: 600;
        font-size: 12px;
    }

    .badge-success {
        background: #e8f7ee;
        color: #16a34a
    }

    .badge-wait {
        background: #fff7ed;
        color: #ea580c
    }

    .empty-box {
        padding: 70px 20px;
        text-align: center;
        color: #9ca3af;
    }
</style>

<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="page-title">Laporan Transaksi</div>
            <div class="sub-title">Riwayat penjualan produk KWT</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('kwt.export.excel') }}" class="btn btn-soft btn-export">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>

            <form action="#" method="POST"
                onsubmit="return confirm('Tarik semua pendapatan sekarang?')">
                @csrf
                <button class="btn btn-soft btn-withdraw">
                    <i class="bi bi-wallet2 me-1"></i> Tarik Pendapatan
                </button>
            </form>
        </div>
    </div>

    <!-- STATS -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <small class="text-muted">Total Pendapatan</small>
                        <h3 class="fw-bold">Rp {{ number_format($totalPendapatan,0,',','.') }}</h3>
                    </div>
                    <div class="stat-icon icon-green"><i class="bi bi-cash-stack"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <small class="text-muted">Jumlah Order</small>
                        <h3 class="fw-bold">{{ $orders->count() }}</h3>
                    </div>
                    <div class="stat-icon icon-blue"><i class="bi bi-receipt"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <small class="text-muted">Order Selesai</small>
                        <h3 class="fw-bold">{{ $orders->where('status','selesai')->count() }}</h3>
                    </div>
                    <div class="stat-icon icon-orange"><i class="bi bi-truck"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Order ID</th>
                        <th>Tanggal</th>
                        <th>Pembeli</th>
                        <th>Total Anda</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse($orders as $order)
                    @php
                    $totalKwt = 0;
                    foreach($order->details as $detail){
                    if($detail->product && $detail->product->user_id == Auth::id()){
                    $totalKwt += $detail->harga_saat_ini * $detail->jumlah;
                    }
                    }
                    @endphp

                    <tr>
                        <td class="ps-4 fw-bold">#ORD-{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>

                        <td>
                            <div class="fw-semibold">{{ $order->user->name }}</div>
                            <small class="text-muted">{{ $order->user->email }}</small>
                        </td>

                        <td class="fw-bold text-success">
                            Rp {{ number_format($totalKwt,0,',','.') }}
                        </td>

                        <td>
                            @if($order->status=='selesai')
                            <span class="badge-status badge-success">Selesai</span>
                            @else
                            <span class="badge-status badge-wait">{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <a href="{{ route('kwt.orders.detail',$order->id) }}"
                                class="btn btn-sm btn-light border rounded-pill px-3">
                                Detail
                            </a>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-box">
                                <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                                Belum ada transaksi penjualan
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