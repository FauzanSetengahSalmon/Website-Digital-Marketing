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
    
    .btn-selesai {
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: white;
        font-weight: 700;
        border: none;
        border-radius: 12px;
        transition: 0.2s;
    }
    .btn-selesai:hover {
        background: linear-gradient(135deg, #15803d, #16a34a);
        color: white;
    }
    .wa-link {
        color: #25D366 !important;
        transition: all 0.25s ease-in-out;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 8px;
        background-color: transparent;
    }

    .wa-link:hover {
        color: #128C7E !important;
        transform: scale(1.03); 
        text-decoration: underline !important;
        background-color: #f1f5f9;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
</style>

<div class="container py-5">

    {{-- Alert Success Flash Message --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="page-title">Detail Pesanan Anda</div>
            <small class="text-muted fw-bold">Invoice #INV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</small>
        </div>

        @php
            $statusColor = 'warning';
            if($order->status == 'diterima' || $order->status == 'diproses') $statusColor = 'info';
            if($order->status == 'selesai') $statusColor = 'success';
            if($order->status == 'ditolak' || $order->status == 'dibatalkan') $statusColor = 'danger';
        @endphp
        <span class="badge bg-{{ $statusColor }} status-pill">
            {{ strtoupper($order->status) }}
        </span>
    </div>

    <div class="row g-4">

        {{-- LEFT SIDE --}}
        <div class="col-lg-7">

            {{-- TIMELINE STATUS --}}
            <div class="card-modern mb-4">
                <h6 class="fw-bold mb-3">Status Lacak Pesanan</h6>

                <div class="timeline position-relative">
                    <div class="timeline-step position-relative">
                        <div class="timeline-dot"></div>
                        <strong>Pesanan Dibuat</strong>
                        <div class="text-muted small">
                            {{ $order->created_at->format('d M Y H:i') }} WIB
                        </div>
                    </div>

                    @if($order->status != 'menunggu' && $order->status != 'ditolak' && $order->status != 'dibatalkan')
                    <div class="timeline-step position-relative">
                        <div class="timeline-dot"></div>
                        <strong>Pesanan Diterima oleh KWT</strong>
                        <div class="text-muted small">Sedang dipersiapkan dan dikemas</div>
                    </div>
                    @endif

                    @if($order->status == 'diproses' || $order->status == 'selesai')
                    <div class="timeline-step position-relative">
                        <div class="timeline-dot"></div>
                        <strong>Dalam Pengiriman / Kurir Jalan</strong>
                        <div class="text-muted small">Pesanan sedang dibawa oleh kurir menuju rumahmu</div>
                    </div>
                    @endif

                    @if($order->status == 'selesai')
                    <div class="timeline-step position-relative">
                        <div class="timeline-dot"></div>
                        <strong class="text-success">Pesanan Selesai</strong>
                        <div class="text-muted small">Paket telah sampai dan dikonfirmasi pembeli</div>
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
                        <div class="fw-semibold">{{ $order->user->name ?? 'Pelanggan' }}</div>
                        <div class="text-muted small mt-1" style="line-height: 1.4;">{{ $order->alamat }}</div>
                        <div class="text-muted small mt-1">No. HP: {{ $order->nomor_hp ?? '-' }}</div>

                        @if($order->catatan)
                        <div class="mt-2 small p-2 bg-light rounded" style="border-left: 3px solid #16a34a;">
                            <strong>Catatan untuk KWT:</strong> {{ $order->catatan }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- INFORMASI KURIR --}}
            <div class="card-modern mb-4">
                <div class="d-flex gap-3">
                    <div class="icon-box">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Informasi Pengantar</h6>

                        @if($order->kurir)
                        <div class="fw-semibold text-dark">{{ $order->kurir }}</div>
                        <div class="text-muted small mt-1">
                            Hubungi Kurir: 
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->no_hp_kurir) }}" 
                            target="_blank" 
                            class="fw-bold text-success text-decoration-none wa-link"
                            title="Klik untuk chat WhatsApp Kurir">
                                <i class="bi bi-whatsapp"></i> {{ $order->no_hp_kurir }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- PRODUK DIBELI --}}
            <h5 class="fw-bold mb-3">Produk yang Dibeli</h5>

            @foreach($order->details as $detail)
            <div class="product-row d-flex gap-3 align-items-center mb-3">
                @if($detail->product->foto_produk)
                    <img src="{{ asset('storage/' . $detail->product->foto_produk) }}" class="product-img" alt="Produk">
                @else
                    <div class="product-img bg-secondary d-flex align-items-center justify-content-center text-white rounded-3"><i class="bi bi-box"></i></div>
                @endif

                <div class="flex-grow-1">
                    <div class="fw-bold" style="font-size: 0.95rem;">{{ $detail->product->nama_produk }}</div>
                    <small class="text-muted">
                        {{ $detail->jumlah }} {{ $detail->product->satuan ?? 'Pcs' }} × Rp {{ number_format($detail->harga_saat_ini, 0, ',', '.') }}
                    </small>
                </div>

                <div class="fw-bold text-success">
                    Rp {{ number_format($detail->jumlah * $detail->harga_saat_ini, 0, ',', '.') }}
                </div>
            </div>
            @endforeach

        </div>

        {{-- RIGHT SIDE --}}
        <div class="col-lg-5">
            <div class="summary-card">
                
                {{-- TOMBOL SELESAI & FORM UPLOAD BUKTI (Hanya muncul jika status 'diproses') --}}
                @if($order->status == 'diproses')
                <div class="card-modern mb-4 border border-success bg-white shadow-sm">
                    <h5 class="fw-bold text-success mb-2"><i class="bi bi-box-seam-fill me-1"></i> Pesanan Sudah Sampai?</h5>
                    <p class="text-muted small mb-3">Silakan upload foto bukti paket telah kamu terima untuk menyelesaikan pesanan ini.</p>
                    
                    <form action="{{ route('orders.complete', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Foto Bukti Penerimaan:</label>
                            <input type="file" name="bukti_sampai" class="form-control" accept="image/*" required style="border-radius: 10px;">
                        </div>
                        <button type="submit" class="btn btn-selesai w-100 py-2-5" onclick="return confirm('Apakah kamu yakin barang sudah diterima dengan benar?')">
                            Klik Pesanan Selesai
                        </button>
                    </form>
                </div>
                @endif

                {{-- TAMPILKAN FOTO JIKA PESANAN SUDAH SELESAI --}}
                @if($order->status == 'selesai')
                <div class="card-modern mb-4 bg-light">
                    <h6 class="fw-bold mb-2 text-success"><i class="bi bi-patch-check-fill me-1"></i> Pesanan Selesai</h6>
                    @if($order->bukti_sampai)
                        <small class="text-muted d-block mb-2">Foto bukti penerimaan paket:</small>
                        <img src="{{ asset('storage/' . $order->bukti_sampai) }}" class="img-fluid rounded-4 w-100 border shadow-sm" style="max-height: 250px; object-fit: contain; background: #fff;">
                    @else
                        <div class="alert alert-warning small rounded-3 p-2 mb-0">
                            <i class="bi bi-exclamation-circle-fill"></i> Selesai tanpa berkas lampiran foto.
                        </div>
                    @endif
                </div>
                @endif

                {{-- RINGKASAN PEMBAYARAN --}}
                <div class="card-modern">
                    <h5 class="fw-bold mb-4">Ringkasan Pembayaran</h5>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Subtotal Produk</span>
                        <strong class="text-dark">Rp {{ number_format($order->total_harga - $order->ongkir, 0, ',', '.') }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Ongkos Kirim</span>
                        <strong class="text-success">+ Rp {{ number_format($order->ongkir, 0, ',', '.') }}</strong>
                    </div>

                    <hr class="my-3" style="border-top: 1px dashed #cbd5e1;">

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark">Total Transaksi</span>
                        <div class="total-price">
                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                        </div>
                    </div>

                    <a href="{{ route('orders.history') }}" class="btn btn-outline-secondary w-100 mt-4" style="border-radius: 12px; font-weight: 600;">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection