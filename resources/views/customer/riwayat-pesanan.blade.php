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

    body { background-color: #fcfdfc; }
    .order-history-header { margin: 40px 0; text-align: center; }
    .order-history-header h2 { font-weight: 800; color: var(--green-dark); margin-bottom: 10px; }
    .header-line { width: 60px; height: 4px; background: var(--green-primary); margin: 0 auto 15px auto; border-radius: 10px; }

    .nav-status { border-bottom: 2px solid #f1f1f1; margin-bottom: 30px; gap: 20px; }
    .nav-status .nav-link { color: #888; font-weight: 600; border: none; padding: 10px 5px; position: relative; }
    .nav-status .nav-link.active { color: var(--green-primary); background: none; }
    .nav-status .nav-link.active::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 100%; height: 3px; background: var(--green-primary); border-radius: 10px; }

    .filter-card { border: none; border-radius: 20px; background: #f8faf8; padding: 25px; position: sticky; top: 100px; }
    .order-card { border: 1px solid #eee; border-radius: 16px; background: white; margin-bottom: 20px; transition: .3s ease; overflow: hidden; }
    .order-card:hover { border-color: var(--green-primary); box-shadow: 0 10px 25px rgba(0, 0, 0, .05); }
    .order-header { background: #fcfdfc; border-bottom: 1px solid #f5f5f5; padding: 15px 25px; display: flex; justify-content: space-between; align-items: center; }
    .order-date { font-size: 0.85rem; color: #666; font-weight: 500; }
    .order-status { font-size: 0.75rem; padding: 6px 14px; border-radius: 8px; font-weight: 700; text-transform: capitalize; }
    
    /* Warna Status Dinamis */
    .status-menunggu { background: #fff8e1; color: #f57f17; }
    .status-disetujui { background: #e3f2fd; color: #1565c0; }
    .status-selesai { background: #e8f5e9; color: #2e7d32; }
    .status-ditolak { background: #ffebee; color: #c62828; }

    .order-body { padding: 25px; }
    .item-list { display: flex; gap: 20px; }
    .item-img { width: 90px; height: 90px; object-fit: cover; border-radius: 12px; border: 1px solid #f0f0f0; }
    .item-info h6 { color: var(--green-dark); margin-bottom: 5px; font-weight: 700; font-size: 1.1rem; }
    .order-footer { padding: 15px 25px; background: #fcfdfc; border-top: 1px solid #f5f5f5; display: flex; justify-content: space-between; align-items: center; }
    .total-amount { font-size: 1.2rem; font-weight: 800; color: var(--green-dark); }
    .btn-detail { background-color: var(--green-primary); color: white; border: none; padding: 10px 24px; border-radius: 12px; font-weight: 700; font-size: 0.9rem; transition: 0.3s; text-decoration: none; }
    .btn-detail:hover { background-color: var(--green-dark); color: white; transform: scale(1.05); }
</style>
@endpush

@section('content')
<div class="container pb-5">
    <div class="order-history-header">
        <h2 class="section-title">Riwayat Pesanan</h2>
        <div class="header-line"></div>
        <p class="text-muted">Lihat kembali belanjaan sehat Anda dari kelompok tani lokal</p>
    </div>

    <div class="row">
        <div class="col-lg-3 d-none d-lg-block">
            <div class="filter-card shadow-sm">
                <h6 class="fw-bold mb-4 text-success"><i class="bi bi-funnel me-2"></i> Filter Pesanan</h6>
                <div class="mb-4">
                    <label class="small fw-bold mb-2">Pilih Periode</label>
                    <select class="form-select border-0 shadow-sm py-2" style="border-radius: 10px; font-size: 0.9rem;">
                        <option>Semua Waktu</option>
                        <option>30 Hari Terakhir</option>
                    </select>
                </div>
                <button class="btn btn-success w-100 py-2 fw-bold" style="border-radius: 12px;">Terapkan Filter</button>
            </div>
        </div>

        <div class="col-lg-9">
            <ul class="nav nav-status px-2">
                <li class="nav-item"><a class="nav-link active" href="#">Semua Pesanan</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Diproses</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Selesai</a></li>
            </ul>

            @forelse($orders as $order)
            <div class="order-card shadow-sm">
                <div class="order-header">
                    <div class="order-date">
                        <i class="bi bi-bag-check me-2 text-success"></i> 
                        <strong>{{ $order->created_at->format('d M Y') }}</strong> 
                        <span class="mx-2 text-light">|</span> ID: #ORD-{{ $order->id }}
                    </div>
                    <span class="order-status status-{{ $order->status }}">
                        {{ $order->status == 'menunggu' ? 'Menunggu Konfirmasi' : ($order->status == 'disetujui' ? 'Sedang Diproses' : $order->status) }}
                    </span>
                </div>
                <div class="order-body">
                    @foreach($order->details as $detail)
                    <div class="item-list {{ !$loop->last ? 'mb-3' : '' }}">
                        <img src="{{ $detail->product->image ? asset('storage/' . $detail->product->image) : asset('image/default_product.jpg') }}" class="item-img" alt="Produk">
                        <div class="item-info">
                            <h6>{{ $detail->product->nama_produk }}</h6>
                            <p class="text-muted mb-0 small">{{ $detail->jumlah }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</p>
                            <span class="badge bg-light text-success mt-2" style="font-size: 0.7rem;">KWT {{ $detail->product->user->name ?? 'Lokal' }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="order-footer">
                    <div>
                        <div class="total-label text-muted small">Total Belanja</div>
                        <div class="total-amount">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                    </div>
                    <div class="d-flex gap-2">
                        @if($order->status == 'selesai')
                            <a href="{{ route('customer.katalog') }}" class="btn btn-outline-success border-2 fw-bold" style="border-radius: 12px; font-size: 0.85rem; text-decoration:none;">Beli Lagi</a>
                        @endif
                        <a href="#" class="btn btn-detail">Lihat Detail</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <img src="https://illustrations.popsy.co/green/delivery.svg" style="height: 200px;" alt="empty">
                <p class="mt-3 text-muted">Belum ada riwayat pesanan.</p>
                <a href="{{ route('customer.katalog') }}" class="btn btn-success px-4 rounded-pill">Mulai Belanja</a>
            </div>
            @endforelse

        </div>
    </div>
</div>
@endsection