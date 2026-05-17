@extends('layouts.app')

@section('content')
<!-- Google Fonts & Font Icon Minimalis -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
    body {
        font-family: 'Inter', sans-serif;
        background: #f8fafc;
        color: #334155;
    }

    .checkout-title-main {
        font-weight: 700;
        color: #0f172a;
        font-size: 1.25rem;
    }

    .checkout-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .section-heading {
        font-size: 0.9rem;
        font-weight: 700;
        color: #1e293b;
    }

    .summary-side {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
        position: sticky;
        top: 20px;
    }

    .btn-confirm {
        background: #10b981;
        color: white;
        padding: 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.88rem;
        width: 100%;
        border: none;
        transition: all 0.2s;
    }

    .btn-confirm:hover:not(:disabled) {
        background: #059669;
    }

    .btn-confirm:disabled {
        background: #cbd5e1;
        color: #94a3b8;
        cursor: not-allowed;
    }

    .address-icon-box {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #f0fdf4;
        color: #10b981;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .info-label {
        font-size: 0.7rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
        font-weight: 700;
    }

    .info-value {
        font-size: 0.88rem;
        color: #0f172a;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .address-badge-box {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.8rem;
    }

    .alert-incomplete {
        background: #fff5f5;
        border: 1px solid #fee2e2;
        border-radius: 10px;
        padding: 14px;
    }

    .btn-to-profile {
        background: #ef4444;
        color: white;
        border: none;
        padding: 6px 14px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .product-item {
        padding: 10px 14px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
    }

    .form-control-custom:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.1);
    }
</style>

<div class="container py-4">
    <div class="row g-3">
        {{-- KIRI: DATA TOKO & DETAIL ITEM --}}
        <div class="col-lg-7">
            <div class="mb-3">
                <h2 class="checkout-title-main m-0">Konfirmasi Pesanan</h2>
            </div>

            {{-- BLOK LOKASI TOKO --}}
            <div class="checkout-card mb-3">
                <div class="d-flex gap-2">
                    <div class="address-icon-box">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="section-heading mb-2">Lokasi Pengiriman</h6>

                        @php
                        $user = Auth::user();
                        // Validasi kecukupan data utama profil toko
                        $isDataComplete = !empty($user->name) && !empty($user->phone_number) && !empty($user->address);
                        @endphp

                        @if($isDataComplete)
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <div class="info-label">Penerima</div>
                                <div class="info-value">{{ $user->name }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="info-label">WhatsApp</div>
                                <div class="info-value text-success">{{ $user->phone_number }}</div>
                            </div>
                            <div class="col-12">
                                <div class="info-label">Alamat Lengkap</div>
                                <div class="info-value mb-2" style="line-height: 1.4;">{{ $user->address }}</div>

                                <div class="address-badge-box">
                                    <div class="text-secondary">
                                        <strong>RT/RW:</strong> {{ $user->rt ?? '-' }}/{{ $user->rw ?? '-' }}
                                        <span class="mx-2 text-muted">|</span>
                                        <strong>Patokan:</strong> <span class="text-dark">{{ $user->address_detail ?? '-' }}</span>
                                    </div>
                                    @if($user->district || $user->city)
                                    <div class="text-muted mt-1" style="font-size: 0.75rem;">
                                        <i class="bi bi-map me-1"></i>{{ implode(', ', array_filter([$user->district, $user->city, $user->province])) }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="alert-incomplete">
                            <div class="d-flex align-items-center gap-2 text-danger mb-1" style="font-size: 0.85rem;">
                                <i class="bi bi-exclamation-octagon-fill"></i>
                                <span class="fw-bold">Alamat Utama Belum Lengkap!</span>
                            </div>
                            <p class="small text-muted mb-2" style="font-size: 0.78rem; line-height: 1.4;">Lengkapi data Nama, Kontak WhatsApp, dan Alamat Utama Toko Anda terlebih dahulu.</p>
                            <a href="{{ route('profile.edit') }}" class="btn-to-profile">
                                Lengkapi Profil <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- DAFTAR PRODUK --}}
            <div class="d-flex align-items-center justify-content-between mb-2 mt-3">
                <h6 class="section-heading m-0">Item Pesanan ({{ count($cartItems) }})</h6>
            </div>

            @foreach($cartItems as $item)
            <div class="product-item d-flex align-items-center gap-3 mb-2">
                <img src="{{ asset('storage/'.$item->product->foto_produk) }}" style="width: 44px; height: 44px; object-fit: cover;" class="rounded border">
                <div class="flex-grow-1">
                    <div class="fw-bold text-dark small">{{ $item->product->nama_produk }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">{{ $item->jumlah }} {{ $item->product->satuan }}</div>
                </div>
                <div class="fw-bold text-dark small">Rp {{ number_format($item->jumlah * $item->product->harga, 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>

        {{-- KANAN: RINGKASAN & ONGKIR --}}
        <div class="col-lg-5">
            <div class="summary-side">
                <h6 class="fw-bold mb-3 text-dark">Ringkasan Pembayaran</h6>

                <div class="d-flex justify-content-between mb-2 text-secondary small">
                    <span>Subtotal Produk</span>
                    <span class="text-dark fw-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between mb-2 text-secondary small">
                    <span>Ongkos Kirim ({{ $jarak ?? 0 }} km)</span>
                    <span class="fw-bold text-dark">
                        {{-- FIX: Jika data profil lengkap, tampilkan nominal ongkir (meskipun nilai 0 / gratis) --}}
                        @if($isDataComplete)
                        Rp {{ number_format($ongkir ?? 0, 0, ',', '.') }}
                        @else
                        <span class="text-danger fw-normal" style="font-size: 0.8rem;">Lengkapi alamat dahulu</span>
                        @endif
                    </span>
                </div>

                <hr class="my-2 text-muted opacity-25">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold text-dark small">Total Tagihan</span>
                    <h5 class="fw-bold text-success mb-0">Rp {{ number_format(($subtotal + ($ongkir ?? 0)), 0, ',', '.') }}</h5>
                </div>

                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="item_ids" value="{{ implode(',', $cartItems->pluck('id')->toArray()) }}">
                    {{-- Ganti ke input dinamis form --}}
                    <input type="hidden" name="ongkir" value="{{ $ongkir ?? 0 }}">
                    <input type="hidden" name="jarak" value="{{ $jarak ?? 0 }}">

                    {{-- Menyediakan default payload jika alamat di-generate dari profile --}}
                    <input type="hidden" name="kota_kab" value="{{ Auth::user()->city ?? '-' }}">
                    <input type="hidden" name="kecamatan" value="{{ Auth::user()->district ?? '-' }}">
                    <input type="hidden" name="kelurahan" value="-">
                    <input type="hidden" name="rtrw" value="{{ (Auth::user()->rt ?? '0') . '/' . (Auth::user()->rw ?? '0') }}">
                    <input type="hidden" name="detail_alamat" value="{{ Auth::user()->address ?? '-' }}">

                    <div class="mb-3">
                        <label class="info-label">Catatan (Opsional)</label>
                        <textarea name="catatan" class="form-control form-control-custom border bg-light bg-opacity-50 rounded-3 p-2 small" rows="1.5" placeholder="Contoh: Titip di satpam..."></textarea>
                    </div>

                    <button type="submit" class="btn-confirm py-2" {{ $isDataComplete ? '' : 'disabled' }}>
                        @if($isDataComplete)
                        KONFIRMASI & BAYAR
                        @else
                        ALAMAT BELUM LENGKAP
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection