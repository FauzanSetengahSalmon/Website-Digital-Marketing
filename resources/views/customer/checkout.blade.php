@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    body {
        font-family: 'Inter', sans-serif;
        background: #f8fafc;
        color: #1e293b;
    }

    .checkout-card {
        background: #fff;
        border: 1px solid #edf2f7;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
    }

    .checkout-title {
        color: #16a34a;
        font-weight: 800;
        font-size: 1.3rem;
    }

    .img-checkout {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 14px;
        border: 1px solid #edf2f7;
    }

    .summary-side {
        background: #fff;
        border: 1px solid #edf2f7;
        border-radius: 20px;
        padding: 24px;
        position: sticky;
        top: 20px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
    }

    .btn-confirm {
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: white;
        padding: 13px;
        border-radius: 12px;
        font-weight: 700;
        width: 100%;
        border: none;
        transition: .2s;
    }

    .btn-confirm:hover {
        transform: translateY(-1px);
        background: linear-gradient(135deg, #15803d, #16a34a);
    }

    .btn-confirm:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
    }

    /* ADDRESS */
    .address-box {
        background: #fff;
        border: 1px solid #edf2f7;
        border-radius: 18px;
        padding: 22px;
    }

    .address-user {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: #dcfce7;
        color: #16a34a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .address-name {
        font-size: .95rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 3px;
    }

    .address-detail {
        font-size: .88rem;
        color: #475569;
        line-height: 1.7;
    }

    .btn-edit-address {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        border: 1px solid #dbe3ee;
        background: white;
        color: #16a34a;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: .2s;
        text-decoration: none;
    }

    .btn-edit-address:hover {
        background: #16a34a;
        color: white;
        border-color: #16a34a;
    }

    .warning-address {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        color: #c2410c;
        border-radius: 14px;
        padding: 14px;
        font-size: .85rem;
        margin-top: 14px;
    }

    /* PRODUCT */
    .product-item {
        background: white;
        border: 1px solid #edf2f7;
        border-radius: 18px;
        padding: 16px;
        transition: .2s;
    }

    .product-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(15, 23, 42, 0.05);
    }

    .product-name {
        font-size: .95rem;
        font-weight: 700;
        color: #0f172a;
    }

    .product-desc {
        font-size: .82rem;
        color: #64748b;
    }

    .product-price {
        font-weight: 700;
        color: #16a34a;
    }

    .summary-row {
        font-size: .9rem;
    }

    .summary-total {
        font-size: 1.4rem;
        font-weight: 800;
        color: #16a34a;
    }

    .info-box {
        background: #f8fafc;
        border-radius: 14px;
        padding: 14px;
        font-size: .78rem;
        color: #64748b;
        line-height: 1.7;
    }

    @media(max-width: 992px) {
        .summary-side {
            position: static;
        }
    }
</style>

<div class="container py-5">

    @php
    $alamatKosong =
    empty(Auth::user()->address) ||
    trim(Auth::user()->address) == '';
    @endphp

    <div class="row g-4">

        {{-- LEFT --}}
        <div class="col-lg-7">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>
                    <div class="checkout-title">
                        Detail Pembayaran
                    </div>

                    <div class="text-muted small">
                        Konfirmasi pesanan Anda
                    </div>
                </div>

                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                    Checkout
                </span>

            </div>

            {{-- ADDRESS --}}
            <div class="checkout-card mb-4">

                <div class="d-flex justify-content-between align-items-start gap-3">

                    <div class="d-flex gap-3">

                        <div class="address-user">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>

                        <div>

                            <div class="address-name">
                                {{ Auth::user()->name }}
                            </div>

                            @if(!$alamatKosong)

                            <div class="address-detail">

                                {{ Auth::user()->address }}

                                @if(Auth::user()->district)
                                , {{ Auth::user()->district }}
                                @endif

                                @if(Auth::user()->city)
                                , {{ Auth::user()->city }}
                                @endif

                                @if(Auth::user()->province)
                                , {{ Auth::user()->province }}
                                @endif

                            </div>

                            @else

                            <div class="warning-address">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                Alamat belum diisi. Silakan lengkapi alamat terlebih dahulu sebelum membuat pesanan.
                            </div>

                            @endif

                        </div>

                    </div>

                    <a href="{{ route('profile.edit') }}"
                        class="btn-edit-address">

                        <i class="bi bi-pencil-square"></i>

                    </a>

                </div>

            </div>

            {{-- PRODUK --}}
            <div class="mb-3">
                <h5 class="fw-bold">
                    Produk yang Dibeli
                </h5>
            </div>

            @foreach($cartItems as $item)

            <div class="product-item d-flex align-items-center gap-3 mb-3">

                <img
                    src="{{ asset('storage/'.$item->product->foto_produk) }}"
                    class="img-checkout">

                <div class="flex-grow-1">

                    <div class="product-name">
                        {{ $item->product->nama_produk }}
                    </div>

                    <div class="product-desc">
                        {{ $item->jumlah }}
                        {{ $item->product->satuan }}
                        ×
                        Rp {{ number_format($item->product->harga, 0, ',', '.') }}
                    </div>

                </div>

                <div class="product-price">
                    Rp {{ number_format($item->jumlah * $item->product->harga, 0, ',', '.') }}
                </div>

            </div>

            @endforeach

        </div>

        {{-- RIGHT --}}
        <div class="col-lg-5">

            <div class="summary-side">

                <h5 class="fw-bold mb-4">
                    Ringkasan Transaksi
                </h5>

                <div class="d-flex justify-content-between mb-3 summary-row">

                    <span class="text-muted">
                        Total Harga Produk
                    </span>

                    <span class="fw-semibold">
                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </span>

                </div>

                <div class="d-flex justify-content-between mb-3 summary-row">

                    <span class="text-muted">
                        Ongkos Kirim ({{ $jarak }}km)
                    </span>

                    <span class="text-success fw-semibold">
                        + Rp {{ number_format($ongkir, 0, ',', '.') }}
                    </span>

                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <span class="fw-bold">
                        Total Bayar
                    </span>

                    <div class="summary-total">
                        Rp {{ number_format($totalBayar, 0, ',', '.') }}
                    </div>

                </div>

                {{-- FORM --}}
                <form action="{{ route('checkout.process') }}" method="POST">

                    @csrf

                    <input
                        type="hidden"
                        name="item_ids"
                        value="{{ implode(',', $cartItems->pluck('id')->toArray()) }}">

                    <div class="mb-3">

                        <label class="small text-muted mb-2">
                            Catatan Pesanan (Opsional)
                        </label>

                        <textarea
                            name="catatan"
                            class="form-control"
                            rows="3"
                            placeholder="Contoh: Titip depan pagar"></textarea>

                    </div>

                    @if($alamatKosong)

                    <button
                        type="button"
                        class="btn-confirm"
                        disabled>

                        Lengkapi Alamat Terlebih Dahulu

                    </button>

                    @else

                    <button
                        type="submit"
                        class="btn-confirm">

                        BUAT PESANAN

                    </button>

                    @endif

                </form>

                {{-- INFO --}}
                <div class="info-box mt-4">

                    <i class="bi bi-info-circle-fill text-success me-1"></i>

                    Pesanan akan diproses setelah mendapatkan
                    <strong>persetujuan dari pihak KWT</strong>.
                    Silakan cek status pesanan Anda pada menu riwayat transaksi.

                </div>

            </div>

        </div>

    </div>

</div>
@endsection