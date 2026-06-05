@extends('layouts.app')

@section('title','Riwayat Pesanan - Tani Cibiru')

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
        border-radius: 18px 18px 0 0;
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

    /* PREMIUM HORIZONTAL TIMELINE 6 STEPS STYLING */
    .tracking-timeline {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px 10px 10px 10px;
        border: 1px solid #edf2f7;
        margin: 15px 25px 25px 25px;
    }

    .timeline-wrapper {
        display: flex;
        justify-content: space-between;
        position: relative;
    }

    .timeline-wrapper::before {
        content: '';
        position: absolute;
        top: 22px;
        left: 8%;
        width: 84%;
        height: 3px;
        background: #e2e8f0;
        z-index: 1;
    }

    .timeline-step {
        text-align: center;
        position: relative;
        z-index: 2;
        flex: 1;
    }

    .timeline-icon {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: #ffffff;
        border: 3px solid #e2e8f0;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px auto;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    .timeline-step.active .timeline-icon {
        background: #e8f5e9;
        border-color: #22c55e;
        color: #22c55e;
        animation: pulseTimeline 1.8s infinite;
    }

    .timeline-step.completed .timeline-icon {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border-color: transparent;
        color: #ffffff;
        box-shadow: 0 6px 15px rgba(34, 197, 94, 0.25);
    }

    .timeline-text {
        font-size: 0.82rem;
        font-weight: 700;
        color: #94a3b8;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .timeline-step.active .timeline-text,
    .timeline-step.completed .timeline-text {
        color: #0f172a;
    }

    .timeline-time {
        font-size: 0.7rem;
        color: #64748b;
        display: block;
        font-weight: 500;
        line-height: 1.3;
    }

    @keyframes pulseTimeline {
        0% {
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
        }
    }

    @media (max-width: 768px) {
        .timeline-icon {
            width: 34px;
            height: 34px;
            font-size: 0.95rem;
        }

        .timeline-wrapper::before {
            top: 16px;
            left: 8%;
            width: 84%;
        }

        .timeline-text {
            font-size: 0.65rem;
        }

        .timeline-time {
            font-size: 0.6rem;
        }
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

    /* 🌟 TOMBOL SELESAIKAN DENGAN EFEK NYALA (PULSE) 🌟 */
    .btn-selesai-pulse {
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: white;
        border: none;
        animation: pulseBtn 1.5s infinite;
        transition: all 0.2s ease;
    }

    .btn-selesai-pulse:hover {
        background: linear-gradient(135deg, #15803d, #16a34a);
        transform: translateY(-2px);
    }

    @keyframes pulseBtn {
        0% {
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
        }

        70% {
            box-shadow: 0 0 0 12px rgba(34, 197, 94, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
        }
    }

    .info-sidebar {
        position: sticky;
        top: 100px;
        z-index: 100;
        background: white;
        border-radius: 16px;
    }

    /* Styling Komplain & Tanggapan */
    .complaint-container {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 20px;
        margin: 15px 25px 25px 25px;
    }

    .complaint-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .msg-box {
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 12px;
        position: relative;
    }

    .msg-user {
        background: #f1f5f9;
        color: #475569;
    }

    .msg-kwt {
        background: #f0fdf4;
        border-left: 4px solid #22c55e;
        color: #166534;
    }

    .msg-header {
        font-size: 0.75rem;
        font-weight: 700;
        margin-bottom: 4px;
        display: flex;
        justify-content: space-between;
    }

    @media (max-width:768px) {

        .order-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .order-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .order-footer .btn,
        .order-footer button,
        .order-footer a {
            width: 100%;
        }

        .item-list {
            flex-direction: column;
            align-items: flex-start;
        }

        .item-img {
            width: 100%;
            max-width: 220px;
            height: auto;
        }

        .tracking-timeline {
            overflow-x: auto;
        }

        .timeline-wrapper {
            min-width: 700px;
        }

        .order-body,
        .order-header,
        .order-footer {
            padding: 16px;
        }

        .tracking-timeline {
            margin: 10px 16px 16px;
        }

        .modal-dialog {
            margin: 10px;
        }
    }
</style>
@endpush

@php
$noAdminSistem = '';

if(isset($admin) && $admin->phone_number){
$noAdminSistem = preg_replace('/[^0-9]/', '', $admin->phone_number);

if(substr($noAdminSistem, 0, 1) === '0'){
$noAdminSistem = '62' . substr($noAdminSistem, 1);
}
}
@endphp

@section('content')
<div class="container pb-5">
    <div class="order-history-header">
        <h2>Riwayat Pesanan</h2>
        <div class="header-line"></div>
        <p class="text-muted">Pantau status belanjaan segar langsung dari petani</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-3 d-none d-lg-block">
            <div class="info-sidebar shadow-sm p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px;">
                        <i class="bi bi-info-circle-fill small" style="font-size: 0.85rem;"></i>
                    </div>
                    <h6 class="fw-bold text-success mb-0">Informasi Penting</h6>
                </div>
                <p class="small text-muted lh-base mb-2">Dana pembayaran Anda saat ini ditahan secara aman oleh sistem (Escrow).</p>
                <p class="small text-muted lh-base mb-0">Uang baru akan diteruskan ke Admin, Kurir, dan Ibu KWT <strong>setelah Anda menekan tombol "Selesaikan Pesanan".</strong></p>
            </div>
        </div>

        <div class="col-lg-9">
            <ul class="nav nav-status px-1">
                <li class="nav-item"><a class="nav-link active" href="#">Semua Pesanan</a></li>
            </ul>

            @forelse($orders as $order)
            @php
            $pesanAdminSistem = rawurlencode("Halo Admin E-Food, saya ingin bertanya terkait pesanan saya dengan ID #ORD-{$order->id}.");
            @endphp
            <div class="order-card shadow-sm">
                <div class="order-header">
                    <div class="order-date">
                        <span class="text-muted small me-1">Tanggal Keluar:</span>
                        <strong>{{ $order->created_at->format('d M Y') }}</strong>
                        <span class="mx-2 text-muted" style="opacity: 0.5;">•</span>
                        <span class="text-muted small">ID:</span> <strong class="text-dark">#ORD-{{ $order->id }}</strong>
                    </div>
                    @php
                    $statusClass = 'status-menunggu';
                    $statusText = 'Menunggu Konfirmasi';

                    if ($order->status == 'diproses') {
                    $statusClass = 'status-diproses';
                    $statusText = $order->jadwal_pengiriman ? 'Sudah Dijadwalkan' : 'Sedang Diproses';
                    }
                    if ($order->status == 'diantar') {
                    $statusClass = 'status-diantar';
                    $statusText = $order->bukti_sampai ? 'Barang Telah Tiba' : 'Pesanan Diantar';
                    }
                    if ($order->status == 'selesai') {
                    $statusClass = 'status-selesai';
                    $statusText = 'Pesanan Selesai';
                    }
                    if (in_array($order->status, ['batal', 'ditolak', 'dibatalkan'])) {
                    $statusClass = 'status-batal';
                    $statusText = 'Pesanan Dibatalkan';
                    }
                    @endphp

                    <span class="order-status {{ $statusClass }}">
                        {{ $statusText }}
                    </span>
                </div>

                <div class="order-body">
                    @foreach($order->details as $detail)
                    <div class="item-list {{ !$loop->last ? 'mb-4 border-bottom pb-4' : '' }}">
                        <img src="{{ $detail->product && $detail->product->foto_produk ? asset('storage/' . $detail->product->foto_produk) : 'https://placehold.co/150?text=Sayur+Segar' }}"
                            class="item-img" alt="Produk" onerror="this.onerror=null;this.src='https://placehold.co/150?text=Gambar+Pecah';">

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

                {{-- ==========================================
                     TRACKING TIMELINE (6 STEPS - MENGGUNAKAN bukti_sampai)
                     ========================================== --}}
                <div class="tracking-timeline shadow-sm">
                    <div class="timeline-wrapper">

                        {{-- STEP 1: Dipesan --}}
                        <div class="timeline-step completed">
                            <div class="timeline-icon"><i class="bi bi-file-earmark-text"></i></div>
                            <div class="timeline-text">Dipesan</div>
                            <small class="timeline-time">{{ $order->created_at->timezone('Asia/Jakarta')->format('d M') }}</small>
                        </div>

                        {{-- STEP 2: Verifikasi KWT --}}
                        <div class="timeline-step {{ in_array($order->status, ['diproses', 'diantar', 'selesai']) ? 'completed' : ($order->status == 'menunggu' ? 'active' : '') }}">
                            <div class="timeline-icon"><i class="bi bi-box-seam"></i></div>
                            <div class="timeline-text">Verifikasi</div>
                            <small class="timeline-time">
                                @if($order->status == 'menunggu')
                                Cek Stok...
                                @else
                                Tersedia
                                @endif
                            </small>
                        </div>

                        {{-- STEP 3: Dijadwalkan Admin --}}
                        <div class="timeline-step {{ in_array($order->status, ['diantar', 'selesai']) ? 'completed' : ($order->status == 'diproses' ? 'active' : '') }}">
                            <div class="timeline-icon"><i class="bi bi-calendar2-check"></i></div>
                            <div class="timeline-text">Dijadwal</div>
                            <small class="timeline-time">
                                @if($order->jadwal_pengiriman)
                                <span class="text-success fw-bold">{{ \Carbon\Carbon::parse($order->jadwal_pengiriman)->format('d M') }}</span>
                                @else
                                @if($order->status == 'menunggu') Menunggu @else Mengatur jadwal @endif
                                @endif
                            </small>
                        </div>

                        {{-- STEP 4: Perjalanan --}}
                        <div class="timeline-step {{ in_array($order->status, ['selesai']) || ($order->status == 'diantar' && $order->bukti_sampai) ? 'completed' : ($order->status == 'diantar' && !$order->bukti_sampai ? 'active' : '') }}">
                            <div class="timeline-icon"><i class="bi bi-truck"></i></div>
                            <div class="timeline-text">Perjalanan</div>
                            <small class="timeline-time">
                                @if(in_array($order->status, ['menunggu', 'diproses']))
                                Menunggu kurir
                                @elseif($order->status == 'diantar' && !$order->bukti_sampai)
                                Menuju lokasi
                                @else
                                Telah sampai
                                @endif
                            </small>
                        </div>

                        {{-- STEP 5: Tiba Tujuan --}}
                        <div class="timeline-step {{ $order->status == 'selesai' ? 'completed' : ($order->status == 'diantar' && $order->bukti_sampai ? 'active' : '') }}">
                            <div class="timeline-icon"><i class="bi bi-geo-alt-fill"></i></div>
                            <div class="timeline-text">Tiba Tujuan</div>
                            <small class="timeline-time">
                                @if($order->status == 'selesai' || ($order->status == 'diantar' && $order->bukti_sampai))
                                <span class="text-success fw-bold">Paket Tiba</span>
                                @else
                                Belum sampai
                                @endif
                            </small>
                        </div>

                        {{-- STEP 6: Selesai --}}
                        <div class="timeline-step {{ $order->status == 'selesai' ? 'completed' : '' }}">
                            <div class="timeline-icon"><i class="bi bi-house-check"></i></div>
                            <div class="timeline-text">Selesai</div>
                            <small class="timeline-time">
                                @if($order->status == 'selesai')
                                <span class="text-success fw-bold">{{ $order->updated_at->timezone('Asia/Jakarta')->format('d M') }}</span>
                                @else
                                Menunggu Acc
                                @endif
                            </small>
                        </div>

                    </div>
                </div>

                {{-- DAFTAR KOMPLAIN / PENGADUAN --}}
                @if($order->reports && $order->reports->isNotEmpty())
                <div class="complaint-container shadow-sm">
                    <div class="complaint-title">
                        <i class="bi bi-shield-exclamation text-danger me-2"></i> Riwayat Pengaduan
                    </div>

                    @foreach($order->reports as $rep)
                    <div class="mb-4 pb-3 border-bottom">
                        {{-- Pesan Customer --}}
                        <div class="msg-box msg-user">
                            <div class="msg-header">
                                <span>Anda - {{ $rep->tipe_pengaduan }}</span>
                                <span>{{ $rep->created_at->format('d M, H:i') }}</span>
                            </div>
                            <div style="font-size: 0.8rem; font-weight: 500;">Pesan : {{ $rep->pesan }}</div>
                        </div>

                        {{-- Tanggapan KWT --}}
                        @if($rep->tanggapan_kwt)
                        <div class="msg-box msg-kwt">
                            <div class="msg-header text-success">
                                <span>Tanggapan KWT</span>
                            </div>
                            <div style="font-size: 0.9rem; line-height: 1.5;">{!! nl2br(e($rep->tanggapan_kwt)) !!}</div>
                        </div>
                        @else
                        <div class="p-3 bg-light rounded-3 border text-center text-muted" style="font-size: 0.8rem;">
                            <i class="bi bi-hourglass-split me-1"></i> Menunggu balasan dari pihak KWT...
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- FOOTER CARD --}}
                <div class="order-footer">
                    <div>
                        <div class="total-label">Total Pembayaran (Termasuk Ongkir)</div>
                        <div class="total-amount">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>

                        {{-- 🌟 NOTIFIKASI PENTING TENTANG ESCROW / PENAHANAN DANA 🌟 --}}
                        @if(in_array($order->status, ['menunggu', 'diproses', 'diantar']))
                        <div class="d-flex align-items-center mt-2 text-warning" style="font-size: 0.75rem; font-weight: 600;">
                            <i class="bi bi-lock-fill me-1"></i> Dana aman ditahan sistem. Belum diteruskan ke penjual.
                        </div>
                        @elseif($order->status == 'selesai')
                        <div class="d-flex align-items-center mt-2 text-success" style="font-size: 0.75rem; font-weight: 600;">
                            <i class="bi bi-check-circle-fill me-1"></i> Dana telah berhasil dicairkan ke penjual & kurir.
                        </div>
                        @endif
                    </div>

                    <div class="d-flex flex-column flex-md-row gap-2 align-items-md-center">
                        {{-- TOMBOL REFUND --}}
                        @if(in_array($order->status, ['diantar', 'selesai']) && (!isset($order->status_refund) || $order->status_refund == 'tidak_ada'))
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill fw-bold px-3 py-2" data-bs-toggle="modal" data-bs-target="#modalRefund{{ $order->id }}">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Ajukan Refund
                        </button>
                        @elseif(isset($order->status_refund) && $order->status_refund == 'diajukan')
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill fw-bold">
                            <i class="bi bi-hourglass-split me-1"></i> Refund Diproses Admin
                        </span>
                        @elseif(isset($order->status_refund) && $order->status_refund == 'disetujui')
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-bold">
                            <i class="bi bi-check-circle-fill me-1"></i> Refund Berhasil
                        </span>
                        @elseif(isset($order->status_refund) && $order->status_refund == 'ditolak')
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill fw-bold">
                            <i class="bi bi-x-circle-fill me-1"></i> Refund Ditolak Admin
                        </span>
                        @endif

                        {{-- 🌟 TOMBOL SELESAIKAN PESANAN (EFEK DENYUT) - MENGGUNAKAN bukti_sampai 🌟 --}}
                        @if(in_array($order->status, ['menunggu', 'diproses', 'diantar']))
                        @if($order->status == 'diantar' && $order->bukti_sampai)
                        <button type="button" class="btn btn-sm rounded-pill fw-bold px-4 py-2 btn-selesai-pulse" data-bs-toggle="modal" data-bs-target="#modalSelesaiCustomer{{ $order->id }}">
                            <i class="bi bi-check-circle-fill me-1"></i> Selesaikan Pesanan
                        </button>
                        @elseif($order->status == 'diantar' && !$order->bukti_sampai)
                        <button type="button" class="btn btn-secondary btn-sm rounded-pill fw-bold px-3 py-2" disabled style="opacity: 0.6; cursor: not-allowed;" title="Menunggu kurir upload bukti foto">
                            <i class="bi bi-camera-fill me-1"></i> Menunggu Foto Kurir
                        </button>
                        @else
                        <button type="button" class="btn btn-secondary btn-sm rounded-pill fw-bold px-3 py-2" disabled style="opacity: 0.5; cursor: not-allowed;" title="Menunggu paket diantar kurir">
                            <i class="bi bi-lock-fill me-1"></i> Selesaikan Pesanan
                        </button>
                        @endif
                        @endif

                        @if($order->status == 'selesai')
                        <a href="{{ route('customer.katalog') }}" class="btn btn-buy-again">Beli Lagi</a>
                        @endif

                        <a href="{{ route('orders.history.detail', $order->id) }}" class="btn btn-detail">Lihat Detail</a>
                    </div>
                </div>
            </div>

            @if($order->status == 'diantar' && $order->bukti_sampai)
            <div class="modal fade" id="modalSelesaiCustomer{{ $order->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <form action="{{ route('orders.complete', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="modal-header border-0 bg-light py-3">
                                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-check-circle-fill text-success me-2"></i>Konfirmasi Pesanan Selesai</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">

                                <div class="mb-3 text-center">
                                    <p class="small text-muted mb-2 text-start">Bukti barang telah diantar kurir:</p>
                                    <img src="{{ asset('storage/' . $order->bukti_sampai) }}" class="img-fluid rounded-4 border shadow-sm w-100" style="max-height: 250px; object-fit: cover;">
                                </div>

                                {{-- Pesan Edukasi Untuk Ibu-ibu --}}
                                <div class="p-3 bg-success bg-opacity-10 rounded-4 mb-4 border border-success border-opacity-25">
                                    <h6 class="fw-bold text-success mb-2"><i class="bi bi-basket-fill me-1"></i> Periksa Belanjaan Bunda!</h6>
                                    <p class="text-dark small mb-0" style="line-height: 1.5;">
                                        Uang belanja Bunda <strong>masih aman ditahan oleh sistem kami</strong> dan belum diberikan ke penjual.
                                        <br><br>
                                        Jika sayur/buah yang tiba sudah sesuai, <strong>mohon klik tombol "Selesaikan Pesanan"</strong> di bawah ini agar dana bisa segera kami teruskan kepada ibu-ibu KWT dan bapak kurir yang bertugas ya Bunda. 😊
                                    </p>

                                    <hr class="border-success border-opacity-25 my-2">

                                    <div class="d-flex align-items-start gap-2">
                                        <i class="bi bi-info-circle-fill text-warning mt-1"></i>
                                        <span class="text-secondary small" style="line-height: 1.4;">
                                            <strong>Sistem Otomatis:</strong> Apabila Bunda lupa mengklik tombol, sistem akan otomatis mencairkan dana ke KWT besok pada <strong>{{ \Carbon\Carbon::parse($order->updated_at)->addDay()->format('d M Y, H:i') }} WIB</strong>.
                                        </span>
                                    </div>
                                </div>

                                <p class="small text-muted mb-3 text-center">Jika pesanan belum diterima atau ada kerusakan, klik <span class="fw-bold">Ajukan Refund</span> pada halaman riwayat, atau hubungi
                                    <a href="https://wa.me/{{ $noAdminSistem }}?text={{ $pesanAdminSistem }}" target="_blank" class="fw-bold text-success text-decoration-none"><i class="bi bi-whatsapp"></i> Admin E-Food</a>.
                                </p>
                            </div>

                            <div class="modal-footer border-0 bg-light bg-opacity-50 justify-content-center p-3">
                                <button type="submit" class="btn w-100 py-3 d-flex flex-column align-items-center justify-content-center shadow" style="background: linear-gradient(135deg, #16a34a, #22c55e); color: white; border-radius: 16px;" onclick="return confirm('Yakin pesanan ini sudah Anda terima dengan baik? Dana akan diteruskan ke KWT.')">
                                    <span class="fw-bold fs-6"><i class="bi bi-check-circle-fill me-2"></i>Selesaikan Pesanan & Cairkan Dana</span>
                                </button>
                                <button type="button" class="btn btn-link text-muted fw-semibold small mt-2 w-100 text-decoration-none" data-bs-dismiss="modal">
                                    Nanti saja, mau periksa barang dulu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            {{-- MODAL FORM PENGAJUAN REFUND CUSTOMER --}}
            @if(in_array($order->status, ['diantar', 'selesai']) && (!isset($order->status_refund) || $order->status_refund == 'tidak_ada'))
            <div class="modal fade" id="modalRefund{{ $order->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 border-0 shadow-lg">
                        <form action="{{ route('orders.refund', $order->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header border-bottom-0 p-4 pb-0">
                                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-arrow-counterclockwise text-danger me-2"></i>Ajukan Refund</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4 pt-2">
                                <div class="alert alert-warning bg-warning bg-opacity-10 border-warning border-opacity-25 rounded-3 p-3 mb-3 small text-dark">
                                    <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i> <strong>Perhatian:</strong> Pastikan Bunda memberikan alasan yang jelas beserta bukti foto.
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary mb-1">Alasan Pengembalian</label>
                                    <textarea name="alasan_refund" class="form-control text-dark p-2.5 small bg-light" rows="3" required style="border-radius: 10px; resize: none;"></textarea>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold text-secondary mb-1">Upload Bukti Foto</label>
                                    <input type="file" name="bukti_refund" class="form-control bg-light" accept="image/*" required style="border-radius: 10px;">
                                </div>
                            </div>
                            <div class="modal-footer border-top-0 p-4 pt-0 d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-light border rounded-pill px-4 fw-bold small py-2" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold py-2 shadow-sm" style="background: #dc2626; border:none;">
                                    <i class="bi bi-send-fill me-1"></i> Kirim Pengajuan
                                </button>
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
                <p class="text-muted small px-3">Sepertinya Anda belum memesan apapun. Yuk, temukan sayur segar pilihan!</p>
                <a href="{{ route('customer.katalog') }}" class="btn btn-buy-again px-4 py-2 mt-2 rounded-pill">Mulai Belanja</a>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection