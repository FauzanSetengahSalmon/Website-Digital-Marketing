@extends('layouts.kwt')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Laporan Transaksi</h3>
            <p class="text-muted small mb-0">Riwayat penjualan produk Kelompok Wanita Tani (KWT).</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('kwt.export.excel') }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>

            <form action="#" method="POST" onsubmit="return confirm('Tarik semua pendapatan sekarang?')">
                @csrf
                <button class="btn btn-outline-dark btn-sm rounded-pill px-3">
                    <i class="bi bi-wallet2 me-1"></i> Tarik Pendapatan
                </button>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="bg-success-subtle text-success p-3 rounded-4 me-3">
                        <i class="bi bi-cash-stack fs-3"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block fw-semibold">Total Pendapatan</span>
                        <h4 class="fw-bold text-dark mb-0">
                            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary p-3 rounded-4 me-3">
                        <i class="bi bi-receipt fs-3"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block fw-semibold">Jumlah Order</span>
                        <h4 class="fw-bold text-dark mb-0">
                            {{ $orders->count() }} <span class="fs-6 fw-normal text-muted">Pesanan</span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="bg-warning-subtle text-warning p-3 rounded-4 me-3">
                        <i class="bi bi-truck fs-3"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block fw-semibold">Order Selesai</span>
                        <h4 class="fw-bold text-dark mb-0">
                            {{ $orders->where('status', 'selesai')->count() }} <span class="fs-6 fw-normal text-muted">Selesai</span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="fw-bold text-dark mb-0">Riwayat Penjualan</h5>
            <span class="badge bg-light text-dark rounded-pill px-3 py-2 fw-semibold">Total Data: {{ $orders->count() }}</span>
        </div>
        
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead class="bg-light text-secondary text-uppercase fs-7 fw-bold border-bottom">
                    <tr>
                        <th class="ps-4 py-3">Order ID</th>
                        <th class="py-3">Pembeli</th>
                        <th class="py-3">Total Anda</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Tanggal</th>
                        <th class="py-3 text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-dark">
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
                            <td class="ps-4 py-3">
                                <span class="fw-bold text-primary">#ORD-{{ $order->id }}</span>
                            </td>
                            
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-info me-2 bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-weight: 600; font-size: 0.85rem;">
                                        {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $order->user->name }}</div>
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">{{ $order->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="py-3">
                                <span class="fw-bold text-success">
                                    Rp {{ number_format($totalKwt, 0, ',', '.') }}
                                </span>
                            </td>
                            
                            <td class="py-3">
                                @if($order->status == 'selesai')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                        <i class="bi bi-check-circle-fill me-1"></i> SELESAI
                                    </span>
                                @elseif($order->status == 'diproses')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                        <i class="bi bi-arrow-repeat me-1"></i> DIPROSES
                                    </span>
                                @elseif($order->status == 'menunggu' || $order->status == 'pending')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                        <i class="bi bi-hourglass-split me-1"></i> MENUNGGU
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                        <i class="bi bi-x-circle-fill me-1"></i> {{ strtoupper($order->status) }}
                                    </span>
                                @endif
                            </td>
                            
                            <td class="py-3 text-secondary small">
                                <i class="bi bi-calendar3 me-1"></i> {{ $order->created_at->format('d M Y') }}
                            </td>

                            <td class="py-3 text-center pe-4">
                                <a href="{{ route('kwt.orders.detail', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="py-3">
                                    <i class="bi bi-cart-x fs-1 text-muted mb-2 d-block"></i>
                                    <span>Belum terdapat data transaksi penjualan yang tercatat.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .fs-7 { font-size: 0.78rem !important; letter-spacing: 0.5px; }
    .bg-success-subtle { background-color: #e8f5e9 !important; }
    .bg-primary-subtle { background-color: #e3f2fd !important; }
    .bg-warning-subtle { background-color: #fff8e1 !important; }
    .bg-danger-subtle { background-color: #ffebee !important; }
</style>
@endsection