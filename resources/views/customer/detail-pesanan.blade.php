@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    body {
        font-family: 'Inter', sans-serif;
        background: #f8fafc;
        color: #0f172a;
    }

    /* HEADER */
    .page-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #16a34a;
    }

    .status-pill {
        padding: 10px 18px;
        border-radius: 999px;
        font-weight: 700;
        letter-spacing: .4px;
    }

    /* CARD */
    .card-modern {
        background: #fff;
        border: 1px solid #edf2f7;
        border-radius: 22px;
        padding: 26px;
        box-shadow: 0 10px 30px rgba(2, 6, 23, .04);
    }

    /* ICON BOX */
    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #dcfce7;
        color: #16a34a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    /* PRODUCT */
    .product-row {
        border: 1px solid #edf2f7;
        border-radius: 18px;
        padding: 16px;
        transition: .2s;
        background: white;
    }

    .product-row:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, .05);
    }

    .product-img {
        width: 75px;
        height: 75px;
        object-fit: cover;
        border-radius: 16px;
    }

    /* RIGHT SUMMARY */
    .summary-card {
        position: sticky;
        top: 20px;
    }

    /* TIMELINE */
    .timeline {
        border-left: 3px solid #22c55e;
        padding-left: 20px;
    }

    .timeline-step {
        margin-bottom: 18px;
    }

    .timeline-dot {
        width: 12px;
        height: 12px;
        background: #22c55e;
        border-radius: 50%;
        position: absolute;
        margin-left: -27px;
        margin-top: 6px;
    }

    .total-price {
        font-size: 1.7rem;
        font-weight: 800;
        color: #16a34a;
    }
</style>

<div class="container py-5">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="page-title">Detail Pesanan</div>
            <small class="text-muted">Invoice #{{ $order->id }}</small>
        </div>

        <span class="badge bg-{{ $order->status_color }} status-pill">
            {{ strtoupper($order->status) }}
        </span>
    </div>

    <div class="row g-4">

        {{-- LEFT --}}
        <div class="col-lg-7">

            {{-- TIMELINE STATUS --}}
            <div class="card-modern mb-4">
                <h6 class="fw-bold mb-3">Status Pesanan</h6>

                <div class="timeline position-relative">
                    <div class="timeline-step position-relative">
                        <div class="timeline-dot"></div>
                        <strong>Pesanan Dibuat</strong>
                        <div class="text-muted small">
                            {{ $order->created_at->format('d M Y H:i') }}
                        </div>
                    </div>

                    @if($order->status != 'menunggu')
                    <div class="timeline-step position-relative">
                        <div class="timeline-dot"></div>
                        <strong>Pesanan Diproses KWT</strong>
                    </div>
                    @endif

                    @if($order->kurir)
                    <div class="timeline-step position-relative">
                        <div class="timeline-dot"></div>
                        <strong>Dikirim Kurir</strong>
                    </div>
                    @endif

                    @if($order->status == 'selesai')
                    <div class="timeline-step position-relative">
                        <div class="timeline-dot"></div>
                        <strong>Pesanan Selesai</strong>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ALAMAT --}}
            <div class="card-modern mb-4">
                <div class="d-flex gap-3">
                    <div class="icon-box">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Alamat Pengiriman</h6>
                        <div class="fw-semibold">{{ $order->user->name }}</div>
                        <div class="text-muted">{{ $order->alamat }}</div>
                        <div class="text-muted">HP: {{ $order->nomor_hp ?? '-' }}</div>

                        @if($order->catatan)
                        <div class="mt-2 small">
                            <strong>Catatan:</strong> {{ $order->catatan }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- KURIR --}}
            <div class="card-modern mb-4">
                <div class="d-flex gap-3">
                    <div class="icon-box">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Informasi Kurir</h6>

                        @if($order->kurir)
                        <div class="fw-semibold">{{ $order->kurir }}</div>
                        <div class="text-muted">
                            Hubungi kurir: <strong>{{ $order->no_hp_kurir }}</strong>
                        </div>
                        @else
                        <div class="text-muted">Kurir belum ditentukan oleh KWT</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- PRODUK --}}
            <h5 class="fw-bold mb-3">Produk Dibeli</h5>

            @foreach($order->details as $detail)
            <div class="product-row d-flex gap-3 align-items-center mb-3">
                <img src="{{ $detail->product->image_url }}" class="product-img">

                <div class="flex-grow-1">
                    <div class="fw-bold">{{ $detail->product->nama_produk }}</div>
                    <small class="text-muted">
                        {{ $detail->jumlah }} × Rp {{ number_format($detail->harga_saat_ini,0,',','.') }}
                    </small>
                </div>

                <div class="fw-bold text-success">
                    Rp {{ number_format($detail->subtotal,0,',','.') }}
                </div>
            </div>
            @endforeach

        </div>

        {{-- RIGHT --}}
        <div class="col-lg-5">
            <div class="card-modern summary-card">

                <h5 class="fw-bold mb-4">Ringkasan Pembayaran</h5>

                <div class="d-flex justify-content-between mb-3">
                    <span>Subtotal Produk</span>
                    <strong>Rp {{ number_format($order->grand_total - $order->ongkir,0,',','.') }}</strong>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span>Ongkos Kirim</span>
                    <strong class="text-success">+ Rp {{ number_format($order->ongkir,0,',','.') }}</strong>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Total Bayar</span>
                    <div class="total-price">
                        Rp {{ number_format($order->grand_total,0,',','.') }}
                    </div>
                </div>

                <a href="/riwayat-pesanan" class="btn btn-outline-secondary w-100 mt-4">
                    ← Kembali ke Riwayat Pesanan
                </a>

            </div>
        </div>

    </div>
</div>
@endsection