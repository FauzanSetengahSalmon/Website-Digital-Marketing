@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
    body {
        font-family: 'Inter', sans-serif;
        background: #f4f4f4;
        color: #334155;
    }

    .checkout-title-main {
        font-weight: 700;
        color: #0f172a;
        font-size: 1.25rem;
    }

    .checkout-card {
        background: #ffffff;
        border-radius: 4px;
        padding: 20px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        border-top: 4px solid #10b981;
        /* Aksen hijau KWT */
    }

    /* Styling ala Shopee Web Grouping */
    .shop-group-card {
        background: #ffffff;
        border-radius: 3px;
        padding: 20px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    }

    .shop-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 12px;
    }

    .shop-badge {
        background: #10b981;
        color: white;
        font-size: 0.68rem;
        padding: 2px 6px;
        border-radius: 2px;
        font-weight: 700;
    }

    .shop-name {
        font-size: 0.93rem;
        font-weight: 600;
        color: #222;
    }

    /* Tabel Produk ala Shopee */
    .checkout-table-header {
        font-size: 0.85rem;
        color: black;
        padding: 10px 0;
        border-bottom: 1px solid #f4f4f4;
    }

    .product-row {
        padding: 15px 0;
        border-bottom: 1px solid #f4f4f4;
    }

    .product-img {
        width: 55px;
        height: 55px;
        object-fit: cover;
        border: 1px solid #e8e8e8;
    }

    /* Sektor Pengiriman per Toko */
    .shipping-section {
        background: #fafdff;
        border-top: 1px dashed #cae2f5;
        border-bottom: 1px dashed #cae2f5;
        padding: 15px 20px;
        margin-top: 10px;
    }

    .shipping-title {
        font-size: 0.88rem;
        font-weight: 600;
        color: #10b981;
    }

    /* Ringkasan Kanan */
    .summary-side {
        background: #ffffff;
        border-radius: 3px;
        padding: 20px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        position: sticky;
        top: 20px;
    }

    /* Style Kolom Catatan Minimalis KWT */
    .input-catatan-kwt {
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
        border-radius: 6px;
        padding: 8px 10px;
        font-size: 0.8rem;
        color: #334155;
        transition: all 0.2s ease-in-out;
        resize: none;
    }

    .input-catatan-kwt::placeholder {
        color: #94a3b8;
        font-size: 0.78rem;
    }

    .input-catatan-kwt:focus {
        background-color: #ffffff;
        border-color: #10b981;
        /* Hijau KWT */
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.1);
        outline: none;
    }

    /* Style Tombol KWT - Diperkecil & Warna Serasi */
    .btn-confirm-kwt {
        background: #10b981;
        /* Hijau Segar KWT */
        color: white;
        padding: 10px 14px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.88rem;
        /* Ukuran teks lebih proposional */
        width: 100%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.15);
        transition: all 0.2s ease;
    }

    .btn-confirm-kwt:hover:not(:disabled) {
        background: #059669;
        /* Hijau agak gelap saat di-hover */
        box-shadow: 0 4px 6px rgba(16, 185, 129, 0.25);
    }

    .btn-confirm-kwt:active:not(:disabled) {
        transform: scale(0.985);
    }

    .btn-confirm-kwt:disabled {
        background: #cbd5e1;
        color: #94a3b8;
        box-shadow: none;
        cursor: not-allowed;
    }

    .btn-confirm {
        background: #ee4d2d;
        /* Orange Shopee style untuk CTA Utama */
        color: white;
        padding: 12px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.95rem;
        width: 100%;
        border: none;
        transition: all 0.2s;
    }

    .btn-confirm:hover:not(:disabled) {
        background: #d73c1f;
    }

    .btn-confirm:disabled {
        background: #cbd5e1;
        color: #94a3b8;
        cursor: not-allowed;
    }

    .info-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
    }

    .info-value {
        font-size: 0.88rem;
        color: #222;
        font-weight: 600;
    }
</style>

<div class="container py-4">
    <div class="row g-3">

        {{-- SEKTOR KIRI: DETAIL ALAMAT & ITEM PER TOKO --}}
        <div class="col-lg-8">
            <div class="mb-3">
                <h2 class="checkout-title-main m-0"><i class="bi bi-cart-check text-success me-2"></i>Checkout</h2>
            </div>

            {{-- 1. BLOK ALAMAT PENGIRIMAN (ALAMAT SHOPEE STYLE) --}}
            <div class="checkout-card mb-3">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-geo-alt-fill text-success fs-5"></i>
                    <h6 class="section-heading m-0" style="color: #10b981; font-size: 1rem;">Alamat Pengiriman</h6>
                </div>

                @php
                $user = Auth::user();
                $isDataComplete = !empty($user->name) && !empty($user->phone_number) && !empty($user->address);

                // Grouping produk berdasarkan ID Toko Penjual
                $groupedItems = $cartItems->groupBy(function($item) {
                return $item->product->user->id;
                });
                $jumlahToko = count($groupedItems);

                // Variabel akumulator untuk ringkasan kasir
                $totalSemuaProduk = 0;
                $totalSemuaOngkirSetelahDiskon = 0;
                $totalSemuaPotonganOngkir = 0;
                @endphp

                @if($isDataComplete)
                <div class="row g-1 small">
                    <div class="col-md-3 fw-bold text-dark">
                        {{ $user->name }} <br>
                        <span class="text-secondary fw-normal">{{ $user->phone_number }}</span>
                    </div>
                    <div class="col-md-9 text-secondary">
                        <span class="text-dark">{{ $user->address }}</span> <br>
                        Kec. {{ $user->district ?? '-' }}, {{ $user->city ?? '-' }}, {{ $user->province ?? '-' }}
                        <div class="mt-1">
                            <span class="badge bg-light text-dark border">RT/RW: {{ $user->rt ?? '0' }}/{{ $user->rw ?? '0' }}</span>
                            <span class="badge bg-light text-dark border">Patokan: {{ $user->address_detail ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                @else
                <div class="alert bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-3 p-3">
                    <div class="d-flex align-items-center gap-2 text-danger mb-2">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span class="fw-bold">Alamat Utama Belum Lengkap</span>
                    </div>
                    <p class="small text-muted mb-2">Harap lengkapi Nama, WhatsApp, dan detail alamat pengiriman Anda pada halaman profil.</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-danger btn-sm fw-bold">Lengkapi Profil <i class="bi bi-arrow-right"></i></a>
                </div>
                @endif
            </div>

            {{-- 2. DAFTAR ITEM PER TOKO DENGAN DETAIL HARGA & ONGKIR --}}
            @foreach($groupedItems as $shopId => $items)
            @php
            $shop = $items->first()->product->user;
            $subtotalShop = $items->sum(fn($i) => $i->jumlah * $i->product->harga);
            $totalSemuaProduk += $subtotalShop;

            /* |--------------------------------------------------------------------------
            | HITUNGAN ONGKIR PER TOKO (SINKRONISASI LOGIKA SHOPEE)
            |--------------------------------------------------------------------------
            */
            $ongkirAsliPerToko = $ongkir; // Nilai asli dari controller (misal Rp 48.000)

            if($jumlahToko > 1) {
            // Jika beli di lebih dari 1 toko, ongkir dibagi rata ke setiap toko (Diskon Gabungan)
            $ongkirFinalPerToko = $ongkirAsliPerToko / $jumlahToko;
            } else {
            // Jika hanya beli di 1 toko, ongkir dibebankan full tanpa pembagian
            $ongkirFinalPerToko = $ongkirAsliPerToko;
            }

            $potonganOngkirPerToko = $ongkirAsliPerToko - $ongkirFinalPerToko;

            // Masukkan ke akumulator total ringkasan kanan
            $totalSemuaOngkirSetelahDiskon += $ongkirFinalPerToko;
            $totalSemuaPotonganOngkir += $potonganOngkirPerToko;
            @endphp

            <div class="shop-group-card mb-3">
                {{-- Header Identitas Toko --}}
                <div class="shop-header">
                    <span class="shop-badge">KWT</span>
                    <span class="shop-name">{{ $shop->name ?? 'Kelompok Wanita Tani' }}</span>
                </div>

                {{-- Header Kolom ala Tabel Shopee Web --}}
                <div class="row checkout-table-header d-none d-md-flex">
                    <div class="col-md-6 fw-bold">Produk Dipesan</div>
                    <div class="col-md-2 text-center fw-bold">Harga Satuan</div>
                    <div class="col-md-2 text-center fw-bold">Jumlah</div>
                    <div class="col-md-2 text-end fw-bold">Subtotal Produk</div>
                </div>

                {{-- Loop Item di Toko Terkait --}}
                @foreach($items as $item)
                <div class="row product-row align-items-center">
                    <div class="col-md-6 col-12 mb-2 mb-md-0">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ asset('storage/'.$item->product->foto_produk) }}" class="product-img rounded">
                            <div>
                                <div class="fw-bold text-dark small">{{ $item->product->nama_produk }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">Satuan: {{ $item->product->satuan }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-4 text-start text-md-center small text-secondary">
                        <span class="d-inline d-md-none">Harga: </span>Rp {{ number_format($item->product->harga, 0, ',', '.') }}
                    </div>
                    <div class="col-md-2 col-4 text-start text-md-center small text-secondary">
                        <span class="d-inline d-md-none">Jumlah: </span>{{ $item->jumlah }}
                    </div>
                    <div class="col-md-2 col-4 text-end small fw-bold text-dark">
                        Rp {{ number_format($item->jumlah * $item->product->harga, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach

                {{-- Rincian Ongkos Kirim Spesifik untuk Toko Ini (Shopee Style) --}}
                <div class="shipping-section rounded">
                    <div class="row align-items-center g-2">
                        <div class="col-md-4 col-12">
                            <span class="shipping-title"><i class="bi bi-truck me-2"></i>Opsi Pengiriman:</span>
                            <div class="small text-dark fw-semibold mt-1">Kurir Lokal KWT</div>
                            <div class="text-muted xsmall" style="font-size: 0.75rem;">Jarak Toko ke Rumah: {{ $jarak ?? 0 }} km</div>
                        </div>
                        <div class="col-md-8 col-12 text-md-end text-start">
                            <div class="small">
                                <span class="text-secondary">Ongkos Kirim Toko:</span>

                                @if($potonganOngkirPerToko > 0)
                                {{-- Tampilkan harga asli dicoret jika ada promo potongan multi-toko --}}
                                <span class="text-decoration-line-through text-muted me-1 small">
                                    Rp {{ number_format($ongkirAsliPerToko, 0, ',', '.') }}
                                </span>
                                <span class="fw-bold text-success">
                                    Rp {{ number_format($ongkirFinalPerToko, 0, ',', '.') }}
                                </span>
                                <div class="text-success fw-medium" style="font-size: 0.75rem;">
                                    <i class="bi bi-tags-fill me-1"></i> Diskon Multi-Toko (Bagi {{ $jumlahToko }}) -Rp {{ number_format($potonganOngkirPerToko, 0, ',', '.') }}
                                </div>
                                @else
                                {{-- Tampilkan normal jika hanya 1 toko --}}
                                <span class="fw-bold text-dark">
                                    Rp {{ number_format($ongkirFinalPerToko, 0, ',', '.') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Akhir per Toko --}}
                <div class="text-end mt-3">
                    <span class="text-secondary small">Total Pesanan ({{ count($items) }} Produk): </span>
                    <span class="fw-bold text-danger fs-6">Rp {{ number_format(($subtotalShop + $ongkirFinalPerToko), 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- SEKTOR KANAN: RINGKASAN PEMBAYARAN KASIR --}}
        <div class="col-lg-4">
            <div class="summary-side">
                <div class="d-flex align-items-center gap-2 mb-3 border-bottom pb-2">
                    <i class="bi bi-receipt text-secondary fs-5"></i>
                    <h6 class="fw-bold m-0 text-dark">Ringkasan Pembayaran</h6>
                </div>

                <div class="d-flex justify-content-between mb-2 text-secondary small">
                    <span>Subtotal untuk Produk</span>
                    <span class="text-dark fw-medium">Rp {{ number_format($totalSemuaProduk, 0, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between mb-2 text-secondary small">
                    <span>Total Ongkos Kirim</span>
                    <span class="text-dark fw-medium">
                        @if($isDataComplete)
                        Rp {{ number_format(($totalSemuaOngkirSetelahDiskon + $totalSemuaPotonganOngkir), 0, ',', '.') }}
                        @else
                        <span class="text-danger">Alamat Kosong</span>
                        @endif
                    </span>
                </div>

                @if($totalSemuaPotonganOngkir > 0)
                <div class="d-flex justify-content-between mb-2 text-success small fw-medium">
                    <span><i class="bi bi-patch-check-fill me-1"></i> Total Diskon Ongkir</span>
                    <span>-Rp {{ number_format($totalSemuaPotonganOngkir, 0, ',', '.') }}</span>
                </div>
                @endif

                <hr class="my-2 text-muted opacity-25">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold text-dark small">Total Pembayaran</span>
                    <h5 class="fw-bold text-danger mb-0">Rp {{ number_format(($totalSemuaProduk + $totalSemuaOngkirSetelahDiskon), 0, ',', '.') }}</h5>
                </div>

                {{-- FORM SUBMIT DATA KE CONTROLLER --}}
                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="item_ids" value="{{ implode(',', $cartItems->pluck('id')->toArray()) }}">
                    {{-- Ongkir yang dikirim adalah total akhir setelah dikurangi diskon gabungan --}}
                    <input type="hidden" name="ongkir" value="{{ $totalSemuaOngkirSetelahDiskon }}">
                    <input type="hidden" name="jarak" value="{{ $jarak ?? 0 }}">

                    <input type="hidden" name="kota_kab" value="{{ Auth::user()->city ?? '-' }}">
                    <input type="hidden" name="kecamatan" value="{{ Auth::user()->district ?? '-' }}">
                    <input type="hidden" name="kelurahan" value="-">
                    <input type="hidden" name="rtrw" value="{{ (Auth::user()->rt ?? '0') . '/' . (Auth::user()->rw ?? '0') }}">
                    <input type="hidden" name="detail_alamat" value="{{ Auth::user()->address ?? '-' }}">

                    <div class="mb-3">
                        <label class="info-label mb-1 text-secondary d-flex align-items-center gap-1" style="font-size: 0.72rem; letter-spacing: 0.3px;">
                            <i class="bi bi-chat-left-text-fill text-success" style="font-size: 0.8rem;"></i> Catatan Pesanan <span class="text-muted fw-normal lowercase">(opsional)</span>
                        </label>
                        <textarea name="catatan"
                            class="form-control input-catatan-kwt"
                            rows="2"
                            placeholder="Titip di satpam, rumah pagar hitam, dll..."></textarea>
                    </div>

                    <button type="submit" class="btn-confirm-kwt" {{ $isDataComplete ? '' : 'disabled' }}>
                        @if($isDataComplete)
                        <i class="bi bi-check2-circle" style="font-size: 1.05rem;"></i> Konfirmasi & Bayar
                        @else
                        <i class="bi bi-exclamation-circle"></i> Alamat Belum Lengkap
                        @endif
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection