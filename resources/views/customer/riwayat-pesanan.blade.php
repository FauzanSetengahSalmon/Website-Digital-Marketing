@extends('layouts.app')

@section('title','Riwayat Pesanan - EFood')

@push('styles')
<style>
    :root {
        --green-dark: #1e5217;
        --green-primary: #4caf50;
        --green-light: #e8f5e9;
        --bg-main: #f8faf9;
        --text-muted: #757575;
    }

    body {
        background-color: var(--bg-main);
        font-family: 'Inter', sans-serif;
    }

    .order-history-header {
        margin: 50px 0 35px 0;
        text-align: center;
    }

    .order-history-header h2 {
        font-weight: 800;
        color: var(--green-dark);
        letter-spacing: -0.5px;
        margin-bottom: 8px;
    }

    .header-line {
        width: 50px;
        height: 4px;
        background: var(--green-primary);
        margin: 0 auto 12px auto;
        border-radius: 10px;
    }

    /* Navigation Status */
    .nav-status {
        border-bottom: 2px solid #eaeaea;
        margin-bottom: 30px;
        gap: 25px;
    }

    .nav-status .nav-link {
        color: var(--text-muted);
        font-weight: 600;
        border: none;
        padding: 12px 4px;
        position: relative;
        text-decoration: none;
        font-size: 0.95rem;
        transition: color 0.3s;
    }

    .nav-status .nav-link:hover {
        color: var(--green-primary);
    }

    .nav-status .nav-link.active {
        color: var(--green-primary);
        background: none;
        font-weight: 700;
    }

    .nav-status .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--green-primary);
        border-radius: 10px;
    }

    /* Card Styling */
    .order-card {
        border: 1px solid rgba(0, 0, 0, 0.04);
        border-radius: 18px;
        background: white;
        margin-bottom: 25px;
        transition: all .3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .order-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(76, 175, 80, 0.08) !important;
        border-color: rgba(76, 175, 80, 0.2);
    }

    .order-header {
        background: #ffffff;
        border-bottom: 1px dashed #ededed;
        padding: 18px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .order-date {
        color: #424242;
        font-size: 0.9rem;
    }

    .order-status {
        font-size: 0.75rem;
        padding: 6px 14px;
        border-radius: 30px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Modernized Status Badges */
    .status-menunggu {
        background: #fff3e0;
        color: #e65100;
    }

    .status-diproses {
        background: #e3f2fd;
        color: #0d47a1;
    }

    .status-selesai {
        background: #e8f5e9;
        color: #1b5e20;
    }

    .status-batal {
        background: #ffebee;
        color: #b71c1c;
    }

    /* Body Items */
    .order-body {
        padding: 25px;
    }

    .item-list {
        display: flex;
        gap: 20px;
        align-items: center;
    }

    .item-img {
        width: 85px;
        height: 85px;
        object-fit: cover;
        border-radius: 14px;
        border: 1px solid #f0f0f0;
        background-color: #f9f9f9;
    }

    .item-info h6 {
        color: #212121;
        margin-bottom: 6px;
        font-weight: 700;
        font-size: 1.05rem;
    }

    /* Footer Layout */
    .order-footer {
        padding: 18px 25px;
        background: #fafbfa;
        border-top: 1px solid #f3f4f3;
        border-radius: 0 0 18px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .total-label {
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-bottom: 2px;
    }

    .total-amount {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--green-dark);
    }

    /* Buttons Customization */
    .btn-detail {
        background-color: #ffffff;
        color: var(--green-dark);
        border: 1.5px solid #e0e0e0;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.88rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-detail:hover {
        background-color: #f5f5f5;
        border-color: #bdbdbd;
        color: var(--green-dark);
    }

    .btn-buy-again {
        background-color: var(--green-primary);
        color: white !important;
        border: none;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.88rem;
        transition: all 0.2s ease;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.2);
    }

    .btn-buy-again:hover {
        background-color: var(--green-dark);
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(76, 175, 80, 0.3);
    }

    /* Sidebar info */
    .info-sidebar {
        border-radius: 22px;
        background: linear-gradient(145deg, #ffffff, #f4fbf4);
        border: 1px solid rgba(76, 175, 80, 0.08);
    }
</style>
@endpush

@section('content')
<div class="container pb-5">
    <div class="order-history-header">
        <h2>Riwayat Pesanan</h2>
        <div class="header-line"></div>
        <p class="text-muted">Pantau status belanjaan segar langsung dari petani</p>
    </div>

    <div class="row g-4">
        <!-- Sidebar Info -->
        <div class="col-lg-3 d-none d-lg-block">
            <div class="info-sidebar shadow-sm p-4 sticky-top" style="top: 30px;">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px;">
                        <i class="bi bi-info-circle-fill small" style="font-size: 0.85rem;">i</i>
                    </div>
                    <h6 class="fw-bold text-success mb-0">Informasi Pesanan</h6>
                </div>
                <p class="small text-muted lh-base mb-0">Pesanan Anda diproses langsung oleh para Kelompok Wanita Tani (KWT) lokal untuk menjamin kesegaran produk hingga ke tangan Anda.</p>
            </div>
        </div>

        <!-- Orders List -->
        <div class="col-lg-9">
            <ul class="nav nav-status px-1">
                <li class="nav-item"><a class="nav-link active" href="#">Semua Pesanan</a></li>
                <!-- Anda bisa menambah tab lain di sini nanti jika diperlukan -->
            </ul>

            @forelse($orders as $order)
            <div class="order-card shadow-sm">
                <!-- Header Kartu -->
                <div class="order-header">
                    <div class="order-date">
                        <span class="text-muted small me-1">Tanggal Keluar:</span>
                        <strong>{{ $order->created_at->format('d M Y') }}</strong>
                        <span class="mx-2 text-muted" style="opacity: 0.5;">•</span>
                        <span class="text-muted small">ID:</span> <strong class="text-dark">#ORD-{{ $order->id }}</strong>
                    </div>
                    <span class="order-status status-{{ $order->status }}">
                        @if($order->status == 'menunggu') Menunggu Konfirmasi
                        @elseif($order->status == 'diproses') Sedang Diproses
                        @else {{ ucfirst($order->status) }}
                        @endif
                    </span>
                </div>

                <!-- Isi Produk -->
                <div class="order-body">
                    @foreach($order->details as $detail)
                    <div class="item-list {{ !$loop->last ? 'mb-4 border-bottom pb-4' : '' }}">
                        <img src="{{ $detail->product && $detail->product->foto_produk ? asset('storage/' . $detail->product->foto_produk) : asset('image/default_product.jpg') }}" class="item-img" alt="Produk">
                        <div class="item-info flex-grow-1">
                            <h6 class="mb-1 text-truncate" style="max-width: 400px;">{{ $detail->product->nama_produk ?? 'Produk Tidak Tersedia' }}</h6>
                            <p class="text-muted mb-2 small">
                                {{ $detail->jumlah }} x Rp {{ number_format($detail->harga_saat_ini, 0, ',', '.') }}
                                <strong class="ms-2 text-dark font-monospace">(Rp {{ number_format($detail->jumlah * $detail->harga_saat_ini, 0, ',', '.') }})</strong>
                            </p>
                            <span class="badge rounded-pill bg-light text-success border border-success-subtle px-2.5 py-1" style="font-size: 0.75rem;">
                                <i class="bi bi-shop me-1"></i>KWT {{ $detail->product->user->name ?? 'Lokal' }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Footer Kartu (Harga & Tombol Aksi) -->
                <div class="order-footer">
                    <div>
                        <div class="total-label">Total Pembayaran</div>
                        <div class="total-amount">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                    </div>
                    <div class="d-flex gap-2">
                        @if($order->status == 'selesai')
                        <a href="{{ route('customer.katalog') }}" class="btn btn-buy-again">Beli Lagi</a>
                        @endif
                        <a href="{{ route('orders.detail', $order->id) }}" class="btn btn-detail">Lihat Detail</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5 my-4 bg-white rounded-4 shadow-sm border border-light">
                <div class="mb-4 text-success opacity-75">
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-bag-x" viewBox="0 0 16 16" style="filter: drop-shadow(0px 8px 16px rgba(76, 175, 80, 0.15));">
                        <path fill-rule="evenodd" d="M6.146 8.146a.5.5 0 0 1 .708 0L8 9.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 10l1.147 1.146a.5.5 0 0 1-.708.708L8 10.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 10 6.146 8.854a.5.5 0 0 1 0-.708z" />
                        <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z" />
                    </svg>
                </div>
                <h5 class="fw-bold mt-2 text-dark">Belum Ada Pesanan</h5>
                <p class="text-muted small px-3">Sepertinya Anda belum memesan apapun. Yuk, temukan sayur dan buah segar pilihan!</p>
                <a href="{{ route('customer.katalog') }}" class="btn btn-buy-again px-4 py-2 mt-2 rounded-pill">Mulai Belanja</a>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection