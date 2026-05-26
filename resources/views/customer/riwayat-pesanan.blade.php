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

    .status-diantar {
        background: #e0f7fa;
        color: #00838f;
    }

    .status-batal {
        background: #ffebee;
        color: #b71c1c;
    }

    /* Body Items */
    .order-body {
        padding: 25px 25px 15px 25px;
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

    /* Tracking Timeline Layout */
    .tracking-timeline {
        background: #fafafa;
        border-radius: 14px;
        padding: 20px;
        margin: 15px 25px 25px 25px;
        border: 1px solid #f0f0f0;
    }

    .timeline-wrapper {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 10px;
    }

    .timeline-wrapper::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 5%;
        width: 90%;
        height: 3px;
        background: #e0e0e0;
        z-index: 1;
    }

    .timeline-step {
        text-align: center;
        position: relative;
        z-index: 2;
        flex: 1;
    }

    .timeline-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #ffffff;
        border: 3px solid #e0e0e0;
        color: #9e9e9e;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px auto;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .timeline-step.active .timeline-icon {
        background: var(--green-light);
        border-color: var(--green-primary);
        color: var(--green-primary);
        box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.15);
    }

    .timeline-step.completed .timeline-icon {
        background: var(--green-primary);
        border-color: var(--green-primary);
        color: #ffffff;
    }

    .timeline-text {
        font-size: 0.82rem;
        font-weight: 600;
        color: #9e9e9e;
        margin-bottom: 2px;
    }

    .timeline-step.active .timeline-text,
    .timeline-step.completed .timeline-text {
        color: #212121;
    }

    .timeline-time {
        font-size: 0.72rem;
        color: var(--text-muted);
        display: block;
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
        <div class="col-lg-3 d-none d-lg-block">
            <div class="info-sidebar shadow-sm p-4 sticky-top" style="top: 30px;">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px;">
                        <i class="bi bi-info-circle-fill small" style="font-size: 0.85rem;"></i>
                    </div>
                    <h6 class="fw-bold text-success mb-0">Informasi Pesanan</h6>
                </div>
                <p class="small text-muted lh-base mb-0">Pesanan Anda diproses langsung oleh para Kelompok Wanita Tani (KWT) lokal untuk menjamin kesegaran produk hingga ke tangan Anda.</p>
            </div>
        </div>

        <div class="col-lg-9">
            <ul class="nav nav-status px-1">
                <li class="nav-item"><a class="nav-link active" href="#">Semua Pesanan</a></li>
            </ul>

            @forelse($orders as $order)
            <div class="order-card shadow-sm">
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
                        @elseif($order->status == 'diantar') Pesanan Diantar
                        @else {{ ucfirst($order->status) }}
                        @endif
                    </span>
                </div>

                <div class="order-body">
                    @php $totalKwt = 0; @endphp
                    @foreach($order->details as $detail)
                    @php $totalKwt += $detail->jumlah * $detail->harga_saat_ini; @endphp
                    <div class="item-list {{ !$loop->last ? 'mb-4 border-bottom pb-4' : '' }}">
                        <img src="{{ $detail->product && $detail->product->foto_produk ? asset('storage/' . $detail->product->foto_produk) : 'https://placehold.co/150?text=Sayur+Segar' }}"
                            class="item-img"
                            alt="Produk"
                            onerror="this.onerror=null;this.src='https://placehold.co/150?text=Gambar+Pecah';">

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

                <div class="tracking-timeline">
                    <div class="timeline-wrapper">

                        {{-- STEP 1: Pesanan Dibuat --}}
                        <div class="timeline-step completed">
                            <div class="timeline-icon">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                            <div class="timeline-text">Pesanan Dibuat</div>
                            <small class="timeline-time">{{ $order->created_at->format('d M Y, H:i') }} WIB</small>
                        </div>

                        {{-- STEP 2: Diverifikasi & Diproses --}}
                        <div class="timeline-step {{ in_array($order->status, ['diproses', 'diantar', 'selesai']) ? 'completed' : ($order->status == 'menunggu' ? 'active' : '') }}">
                            <div class="timeline-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="timeline-text">Diverifikasi & Diproses</div>
                            <small class="timeline-time">
                                @if(in_array($order->status, ['diproses', 'diantar', 'selesai']))
                                Pesanan siap dikirim
                                @else
                                Menunggu konfirmasi...
                                @endif
                            </small>
                        </div>

                        {{-- STEP 3: Sedang Diantar --}}
                        <div class="timeline-step {{ in_array($order->status, ['diantar', 'selesai']) ? 'completed' : ($order->status == 'diproses' ? 'active' : '') }}">
                            <div class="timeline-icon">
                                <i class="bi bi-truck"></i>
                            </div>
                            <div class="timeline-text">Sedang Diantar</div>
                            <small class="timeline-time">
                                @if($order->status == 'diantar')
                                Kurir dalam perjalanan
                                @elseif($order->status == 'selesai')
                                Telah sampai tujuan
                                @else
                                Menunggu kurir lepas...
                                @endif
                            </small>
                        </div>

                        {{-- STEP 4: Pesanan Selesai --}}
                        <div class="timeline-step {{ $order->status == 'selesai' ? 'completed' : ($order->status == 'diantar' ? 'active' : '') }}">
                            <div class="timeline-icon">
                                <i class="bi bi-house-check"></i>
                            </div>
                            <div class="timeline-text">Pesanan Selesai</div>
                            <small class="timeline-time">
                                @if($order->status == 'selesai')
                                {{ $order->updated_at->format('d M Y, H:i') }} WIB
                                @else
                                Menunggu konfirmasimu
                                @endif
                            </small>
                        </div>

                    </div>
                </div>

                {{-- DAFTAR KOMPLAIN DI RIWAYAT PESANAN --}}
                @if($order->reports && $order->reports->isNotEmpty())
                <div class="mx-4 mb-4 p-4 bg-light rounded-4 border border-light-subtle">
                    <h6 class="fw-bold mb-3 text-success text-start" style="font-size: 0.95rem; letter-spacing: -0.2px;"><i class="bi bi-chat-left-text-fill me-2"></i>Komplain & Tanggapan KWT</h6>
                    @foreach($order->reports as $rep)
                        <div class="p-3.5 bg-white rounded-4 mb-3 border border-light-subtle border-start border-4 border-danger shadow-sm small text-start">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-danger-subtle text-danger px-2.5 py-1 rounded-pill fw-bold text-uppercase fs-8" style="font-size: 0.68rem; letter-spacing: 0.2px;">{{ $rep->tipe_pengaduan }}</span>
                                <small class="text-muted fs-8 font-monospace">{{ $rep->created_at->format('d M Y') }}</small>
                            </div>
                            <div class="text-dark mb-3 lh-base" style="font-size: 0.88rem;">
                                <strong>Keluhan Anda:</strong> 
                                <span class="text-secondary">"{{ $rep->pesan }}"</span>
                            </div>
                            @if($rep->tanggapan_kwt)
                            <div class="p-3 bg-success bg-opacity-10 rounded-3 border-start border-3 border-success small lh-base">
                                <strong class="text-success small d-block mb-1.5"><i class="bi bi-chat-right-quote-fill me-1"></i> Tanggapan KWT:</strong>
                                <span class="text-dark font-medium">"{!! nl2br(e($rep->tanggapan_kwt)) !!}"</span>
                            </div>
                            @else
                            <div class="p-3 bg-warning bg-opacity-10 rounded-3 border-start border-3 border-warning small text-warning lh-base">
                                <i class="bi bi-clock-history me-1"></i> Menunggu tanggapan dari Kelompok Wanita Tani (KWT)...
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                @endif

                <div class="order-footer">
                    <div>
                        <div class="total-label">Total Pembayaran Toko</div>
                        <div class="total-amount">Rp {{ number_format($totalKwt, 0, ',', '.') }}</div>
                    </div>
                    <div class="d-flex gap-2">
                        @if($order->status == 'selesai')
                        <a href="{{ route('customer.katalog') }}" class="btn btn-buy-again">Beli Lagi</a>
                        @endif

                        @if($order->status == 'diantar')
                        <button type="button" class="btn btn-buy-again bg-success border-0 px-4" data-bs-toggle="modal" data-bs-target="#modalSelesaiCustomer{{ $order->id }}">
                            <i class="bi bi-check2-circle me-1"></i> Selesaikan Pesanan
                        </button>
                        @endif

                        <a href="{{ route('orders.history.detail', $order->id) }}" class="btn btn-detail">Lihat Detail</a>
                    </div>
                </div>
            </div>

            {{-- MODAL UPLOAD FOTO SAMPAI UNTUK CUSTOMER --}}
            @if($order->status == 'diantar')
            <div class="modal fade" id="modalSelesaiCustomer{{ $order->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        {{-- 🌟 FIX UTAMA: Method diarahkan ke POST dengan spoofing @method('PATCH') di bawahnya 🌟 --}}
                        <form action="{{ route('orders.complete', $order->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="modal-header border-0 bg-light py-3">
                                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-camera text-success me-2"></i>Konfirmasi Barang Sampai</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <p class="small text-muted mb-3">Harap ambil foto komoditas hasil tani KWT yang telah Anda terima sebagai bukti validasi penyelesaian pesanan.</p>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-dark">Unggah Foto Produk Diterima</label>
                                    <input type="file" name="bukti_sampai" class="form-control rounded-3" accept="image/*" required>
                                </div>
                            </div>
                            <div class="modal-footer border-0 bg-light bg-opacity-50">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-4 fw-bold">Konfirmasi Selesai</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

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