@extends('layouts.app')

@section('title','Riwayat Pesanan - EFood')

@push('styles')
<style>
    :root {
        --green-dark: #2d7a22;
        --green-primary: #4caf50;
        --green-light: #d6f0c2;
    }

    body { background-color: #fcfdfc; }
    .order-history-header { margin: 40px 0; text-align: center; }
    .order-history-header h2 { font-weight: 800; color: var(--green-dark); margin-bottom: 10px; }
    .header-line { width: 60px; height: 4px; background: var(--green-primary); margin: 0 auto 15px auto; border-radius: 10px; }

    .nav-status { border-bottom: 2px solid #f1f1f1; margin-bottom: 30px; gap: 20px; }
    .nav-status .nav-link { color: #888; font-weight: 600; border: none; padding: 10px 5px; position: relative; text-decoration: none; }
    .nav-status .nav-link.active { color: var(--green-primary); background: none; }
    .nav-status .nav-link.active::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 100%; height: 3px; background: var(--green-primary); border-radius: 10px; }

    .order-card { border: 1px solid #eee; border-radius: 16px; background: white; margin-bottom: 20px; transition: .3s ease; overflow: hidden; }
    .order-card:hover { border-color: var(--green-primary); box-shadow: 0 10px 25px rgba(0, 0, 0, .05); }
    .order-header { background: #fcfdfc; border-bottom: 1px solid #f5f5f5; padding: 15px 25px; display: flex; justify-content: space-between; align-items: center; }
    .order-status { font-size: 0.75rem; padding: 6px 14px; border-radius: 8px; font-weight: 700; text-transform: capitalize; }
    
    .status-menunggu { background: #fff8e1; color: #f57f17; }
    .status-diproses { background: #e3f2fd; color: #1565c0; }
    .status-selesai { background: #e8f5e9; color: #2e7d32; }
    .status-batal { background: #ffebee; color: #c62828; }

    .order-body { padding: 25px; }
    .item-list { display: flex; gap: 20px; align-items: center; }
    .item-img { width: 80px; height: 80px; object-fit: cover; border-radius: 12px; border: 1px solid #f0f0f0; }
    .item-info h6 { color: var(--green-dark); margin-bottom: 5px; font-weight: 700; font-size: 1rem; }
    .order-footer { padding: 15px 25px; background: #fcfdfc; border-top: 1px solid #f5f5f5; display: flex; justify-content: space-between; align-items: center; }
    .total-amount { font-size: 1.2rem; font-weight: 800; color: var(--green-dark); }
    .btn-detail { background-color: var(--green-primary); color: white; border: none; padding: 10px 24px; border-radius: 12px; font-weight: 700; font-size: 0.9rem; transition: 0.3s; text-decoration: none; }
    .btn-detail:hover { background-color: var(--green-dark); color: white; }
</style>
@endpush

@section('content')
<div class="container pb-5">
    <div class="order-history-header">
        <h2>Riwayat Pesanan</h2>
        <div class="header-line"></div>
        <p class="text-muted">Pantau status belanjaan segar Anda</p>
    </div>

    <div class="row">
        <div class="col-lg-3 d-none d-lg-block">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; background: #f8faf8;">
                <h6 class="fw-bold text-success mb-3">Informasi</h6>
                <p class="small text-muted">Pesanan Anda diproses langsung oleh Kelompok Wanita Tani (KWT) terkait.</p>
            </div>
        </div>

        <div class="col-lg-9">
            <ul class="nav nav-status px-2">
                <li class="nav-item"><a class="nav-link active" href="#">Semua Pesanan</a></li>
            </ul>

            @forelse($orders as $order)
            <div class="order-card shadow-sm">
                <div class="order-header">
                    <div class="order-date">
                        <strong>{{ $order->created_at->format('d M Y') }}</strong> 
                        <span class="mx-2 text-muted">|</span> #ORD-{{ $order->id }}
                    </div>
                    <span class="order-status status-{{ $order->status }}">
                        @if($order->status == 'menunggu') Menunggu Konfirmasi
                        @elseif($order->status == 'diproses') Sedang Diproses
                        @else {{ ucfirst($order->status) }}
                        @endif
                    </span>
                </div>
                <div class="order-body">
                    @foreach($order->details as $detail)
                    <div class="item-list {{ !$loop->last ? 'mb-3 border-bottom pb-3' : '' }}">
                        <img src="{{ $detail->product && $detail->product->foto_produk ? asset('storage/' . $detail->product->foto_produk) : asset('image/default_product.jpg') }}" class="item-img">
                        <div class="item-info">
                            <h6>{{ $detail->product->nama_produk ?? 'Produk Tidak Tersedia' }}</h6>
                            <p class="text-muted mb-0 small">
                                {{ $detail->jumlah }} x Rp {{ number_format($detail->harga_saat_ini, 0, ',', '.') }}
                                <strong class="ms-2 text-dark">(Rp {{ number_format($detail->jumlah * $detail->harga_saat_ini, 0, ',', '.') }})</strong>
                            </p>
                            <span class="badge bg-light text-success mt-1">KWT {{ $detail->product->user->name ?? 'Lokal' }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="order-footer">
                    <div>
                        <div class="total-label text-muted small">Total Pembayaran</div>
                        <div class="total-amount">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                    </div>
                    <div class="d-flex gap-2">
                        @if($order->status == 'selesai')
                            <a href="{{ route('customer.katalog') }}" class="btn btn-outline-success border-2 fw-bold" style="border-radius: 12px; font-size: 0.85rem; text-decoration:none;">Beli Lagi</a>
                        @endif
                        <a href="{{ route('orders.detail', $order->id) }}" class="btn btn-detail">Lihat Detail</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <img src="https://illustrations.popsy.co/green/delivery.svg" style="height: 150px;" alt="empty">
                <p class="mt-3 text-muted">Belum ada pesanan.</p>
                <a href="{{ route('customer.katalog') }}" class="btn btn-success px-4 rounded-pill">Mulai Belanja</a>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection