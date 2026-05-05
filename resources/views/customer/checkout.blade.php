@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    body { font-family: 'Inter', sans-serif; background-color: #fcfcfc; color: #333; }
    .checkout-card { background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 24px; }
    .text-success-kwt { color: #28a745 !important; font-weight: 900; }
    .img-checkout { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
    .summary-side { background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 24px; position: sticky; top: 20px; }
    .btn-confirm { background: #28a745; color: white; padding: 12px; border-radius: 8px; font-weight: 600; width: 100%; border: none; }
</style>

<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-7">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                <h5 class="text-success-kwt">Detail Pembayaran</h5>
                <span class="text-muted small">Konfirmasi Pesanan</span>
            </div>

            <div class="checkout-card shadow-sm mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2 text-success"></i>Alamat Pengiriman</h6>
                <p class="text-muted small mb-1">{{ Auth::user()->name }}</p>
                <p class="small mb-0 text-dark">Alamat terdaftar di sistem KWT (Pastikan data profil benar)</p>
            </div>

            <h6 class="fw-bold mb-3">Produk yang Dibeli</h6>
            @foreach($cartItems as $item)
            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                <img src="{{ asset('storage/'.$item->product->foto_produk) }}" class="img-checkout">
                <div class="flex-grow-1">
                    <p class="mb-0 fw-bold" style="font-size: 14px;">{{ $item->product->nama_produk }}</p>
                    <p class="mb-0 text-muted small">{{ $item->jumlah }} {{ $item->product->satuan }} x Rp {{ number_format($item->product->harga, 0, ',', '.') }}</p>
                </div>
                <div class="text-end">
                    <p class="mb-0 fw-bold text-success">Rp {{ number_format($item->jumlah * $item->product->harga, 0, ',', '.') }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="col-lg-5">
            <div class="summary-side shadow-sm">
                <h6 class="fw-bold mb-4">Ringkasan Transaksi</h6>
                
                <div class="d-flex justify-content-between mb-2 small">
                    <span class="text-muted">Total Harga Produk</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                
                <div class="d-flex justify-content-between mb-2 small">
                    <span class="text-muted">Ongkos Kirim ({{ $jarak }}km)</span>
                    <span class="text-success">+ Rp {{ number_format($ongkir, 0, ',', '.') }}</span>
                </div>

                <hr style="border-top: 1px dashed #eee;">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold small">Total Bayar</span>
                    <h5 class="fw-bold text-success m-0">Rp {{ number_format($totalBayar, 0, ',', '.') }}</h5>
                </div>

                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="item_ids" value="{{ request('items') }}">
                    <button type="submit" class="btn-confirm shadow-sm">
                        BUAT PESANAN
                    </button>
                </form>

                <div class="mt-4 p-3 bg-light rounded-3" style="font-size: 11px; line-height: 1.6;">
                    <i class="bi bi-info-circle text-success"></i> 
                    Pesanan akan diproses setelah mendapatkan <strong>persetujuan dari pihak KWT</strong>. Silakan cek status di menu Riwayat.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection