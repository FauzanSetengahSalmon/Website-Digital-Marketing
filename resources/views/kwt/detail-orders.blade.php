@extends('layouts.kwt')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    body {
        background: #f5f7fb;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #111827;
    }

    /* CONTAINER */
    .invoice-wrapper {
        max-width: 700px;
        margin: 28px auto;
        padding: 0 16px;
    }

    /* ACTION */
    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }

    .back-link {
        text-decoration: none;
        color: #166534;
        font-size: 11px;
        font-weight: 700;
    }

    .print-btn {
        border: none;
        background: #111827;
        color: white;
        border-radius: 999px;
        padding: 9px 16px;
        font-size: 11px;
        font-weight: 600;
        transition: .2s;
    }

    .print-btn:hover {
        background: #000;
    }

    /* CSS Tambahan untuk tombol disabled */
    .print-btn:disabled {
        background: #9ca3af !important;
        color: #f3f4f6 !important;
        cursor: not-allowed;
        opacity: 0.7;
    }

    /* CARD */
    .invoice-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, .04);
    }

    /* HEADER */
    .invoice-header {
        padding: 26px 28px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 14px;
    }

    .invoice-title {
        font-size: 22px;
        font-weight: 800;
        margin-bottom: 3px;
    }

    .invoice-sub {
        font-size: 11px;
        color: #6b7280;
    }

    .invoice-badge {
        background: #f0fdf4;
        color: #15803d;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
    }

    /* BODY */
    .invoice-body {
        padding: 24px 28px;
    }

    /* INFO */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 22px;
    }

    .info-card {
        background: #f9fafb;
        border-radius: 18px;
        padding: 14px 16px;
    }

    .info-label {
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #9ca3af;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 12px;
        font-weight: 700;
    }

    /* SECTION */
    .section-title {
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 12px;
    }

    /* PRODUCT */
    .product-item {
        border: 1px solid #f1f5f9;
        border-radius: 18px;
        padding: 14px 16px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
    }

    .product-name {
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .product-detail {
        font-size: 10px;
        color: #6b7280;
    }

    .product-price {
        text-align: right;
    }

    .product-price small {
        display: block;
        font-size: 9px;
        color: #9ca3af;
        margin-bottom: 2px;
    }

    .product-price strong {
        font-size: 12px;
        color: #16a34a;
    }

    /* SUMMARY */
    .summary-card {
        margin-top: 22px;
        border: 1px solid #f1f5f9;
        border-radius: 20px;
        padding: 18px;
        background: #fafafa;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        margin-bottom: 10px;
        color: #374151;
    }

    .summary-total {
        margin-top: 12px;
        padding-top: 14px;
        border-top: 1px dashed #d1d5db;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .summary-total span {
        font-size: 12px;
        font-weight: 700;
    }

    .summary-total strong {
        font-size: 22px;
        font-weight: 800;
        color: #111827;
    }

    /* DELIVERY */
    .delivery-card {
        margin-top: 20px;
        border: 1px solid #f1f5f9;
        border-radius: 18px;
        padding: 16px;
        background: white;
    }

    .delivery-top {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .delivery-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #16a34a;
        font-size: 16px;
    }

    .delivery-label {
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #9ca3af;
        margin-bottom: 3px;
    }

    .delivery-name {
        font-size: 12px;
        font-weight: 700;
    }

    .delivery-phone {
        font-size: 10px;
        color: #6b7280;
        margin-top: 2px;
    }

    /* NOTE */
    .note-box {
        margin-top: 12px;
        background: #f9fafb;
        border-radius: 14px;
        padding: 12px;
        font-size: 10px;
        color: #6b7280;
        line-height: 1.6;
    }

    /* FOOTER */
    .footer {
        margin-top: 24px;
        text-align: center;
    }

    .footer small {
        display: block;
        font-size: 9px;
        color: #9ca3af;
        margin-bottom: 4px;
    }

    .footer strong {
        font-size: 12px;
        color: #166534;
    }

    @media(max-width:576px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .product-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .product-price {
            text-align: left;
        }

        .top-bar {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }

    /* 🌟 PERBAIKAN STRUKTUR CSS KHUSUS PRINT AGAR PAS SATU HALAMAN PDF 🌟 */
    @media print {
        @page {
            size: A4;
            margin: 10mm 12mm 10mm 12mm;
            /* Persempit margin kertas */
        }

        .no-print {
            display: none !important;
        }

        body {
            background: white;
            font-size: 10px;
            /* Perkecil ukuran font dasar global */
        }

        .invoice-wrapper {
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        .invoice-card {
            box-shadow: none;
            border: none;
        }

        /* Padatkan padding setiap section card */
        .invoice-header {
            padding: 12px 15px !important;
        }

        .invoice-body {
            padding: 15px 15px !important;
        }

        .invoice-title {
            font-size: 18px !important;
        }

        .info-grid {
            margin-bottom: 12px !important;
            gap: 10px !important;
        }

        .info-card {
            padding: 8px 12px !important;
            border-radius: 12px !important;
        }

        .product-item {
            padding: 8px 12px !important;
            margin-bottom: 6px !important;
            border-radius: 12px !important;
        }

        .summary-card {
            margin-top: 12px !important;
            padding: 10px 15px !important;
            border-radius: 14px !important;
        }

        .summary-total strong {
            font-size: 16px !important;
        }

        .delivery-card {
            margin-top: 12px !important;
            padding: 10px 15px !important;
            border-radius: 14px !important;
        }

        /* Batasi tinggi gambar agar tidak memakan sisa space halaman */
        .img-print-target {
            max-height: 160px !important;
        }

        .footer {
            margin-top: 120px !important;
        }
    }
</style>

<div class="invoice-wrapper">

    {{-- Tombol kembali pintar otomatis --}}
    <div class="top-bar no-print">
        <a href="{{ url()->previous() }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="invoice-card">

        <div class="invoice-header">
            <div>
                <div class="invoice-title">Invoice Pesanan</div>
                <div class="invoice-sub">Order #{{ $order->id }}</div>
            </div>
            <div class="invoice-badge">
                {{ strtoupper($order->status) }}
            </div>
        </div>

        <div class="invoice-body">

            <div class="info-grid">
                <div class="info-card">
                    <div class="info-label">Customer</div>
                    <div class="info-value">
                        {{ $order->user->name ?? 'Pelanggan' }}
                    </div>
                    <div class="text-muted mt-1" style="font-size: 10px;">
                        <i class="bi bi-telephone"></i> {{ $order->nomor_hp ?? '-' }}
                    </div>
                </div>

                <div class="info-card text-end">
                    <div class="info-label">Tanggal</div>
                    <div class="info-value">
                        {{ $order->created_at->format('d F Y') }}
                    </div>
                </div>
            </div>

            <div class="section-title">Produk Pesanan</div>

            @foreach($order->details as $detail)
            <div class="product-item">
                <div>
                    <div class="product-name">
                        {{ $detail->product->nama_produk }}
                    </div>
                    <div class="product-detail">
                        {{ $detail->jumlah }} {{ $detail->product->satuan ?? 'Pcs' }}
                        ×
                        Rp {{ number_format($detail->harga_saat_ini,0,',','.') }}
                    </div>
                </div>
                <div class="product-item-price text-end">
                    <small class="text-muted d-block" style="font-size: 9px;">Subtotal</small>
                    <strong style="font-size: 12px; color: #16a34a;">
                        Rp {{ number_format($detail->harga_saat_ini * $detail->jumlah,0,',','.') }}
                    </strong>
                </div>
            </div>
            @endforeach

            {{-- Ringkasan Pembayaran Khusus KWT Bersih --}}
            <div class="summary-card">
                <div class="summary-row">
                    <span>Subtotal Produk Anda</span>
                    <span>Rp {{ number_format($order->total_kwt,0,',','.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Ongkir</span>
                    <span class="text-muted"><em>Dikelola Admin</em></span>
                </div>
                <div class="summary-total">
                    <span>Total Piutang Toko</span>
                    <strong>Rp {{ number_format($order->total_kwt,0,',','.') }}</strong>
                </div>
            </div>

            <div class="delivery-card">
                <div class="delivery-top">
                    <div class="delivery-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div>
                        <div class="delivery-label">Pengiriman</div>
                        <div class="delivery-name">
                            {{ $order->kurir ?? 'Kurir Belum Ditunjuk' }}
                        </div>
                        <div class="delivery-phone">
                            {{ $order->no_hp_kurir ?? '-' }}
                        </div>
                    </div>
                </div>
                <div class="note-box">
                    <strong>Alamat Kirim:</strong> {{ $order->alamat }}
                    @if($order->catatan)
                    <br><br><strong>Catatan Pembeli:</strong> "{{ $order->catatan }}"
                    @endif
                </div>
            </div>

            {{-- 📸 FOTO BUKTI TERIMA CUSTOMER (KOTAK TUNGGAL FULL-WIDTH) 📸 --}}
            <div class="mt-4 p-3 border rounded-4 text-center bg-light">
                <div class="info-label text-start mb-2" style="color: #15803d; font-weight: 800;">
                    <i class="bi bi-image-fill"></i> Foto Bukti Terima Customer:
                </div>
                @if($order->bukti_sampai)
                <div class="d-flex justify-content-center bg-white p-2 border rounded-3">
                    <img src="{{ asset('storage/' . $order->bukti_sampai) }}"
                        class="img-fluid rounded-2 img-print-target"
                        style="max-height: 240px; width: auto; object-fit: contain;"
                        alt="Bukti Terima Customer"
                        onerror="this.onerror=null;this.src='https://placehold.co/600x400?text=Gambar+Tidak+Ditemukan';">
                </div>
                @else
                <div class="text-muted py-4 small">
                    <i class="bi bi-clock me-1"></i> Menunggu pelanggan konfirmasi penerimaan barang...
                </div>
                @endif
            </div>

            <div class="footer">
                <small>Tanda Tangan Pengurus</small>
                <strong>
                    {{ Auth::user()->name }}
                </strong>
            </div>

        </div>
    </div>
</div>
@endsection