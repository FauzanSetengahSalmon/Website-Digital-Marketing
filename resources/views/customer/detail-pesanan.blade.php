@extends('layouts.app')

@section('title','Detail Pesanan - EFood')

@push('styles')
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

    .summary-card {
        position: sticky;
        top: 20px;
    }

    /* PREMIUM HORIZONTAL TIMELINE */
    .tracking-timeline {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px 10px 10px 10px;
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
        left: 10%;
        width: 80%;
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
            width: 38px;
            height: 38px;
            font-size: 1rem;
        }

        .timeline-wrapper::before {
            top: 18px;
        }

        .timeline-text {
            font-size: 0.72rem;
        }

        .timeline-time {
            font-size: 0.65rem;
        }
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
        text-decoration: underline !important;
        background-color: #f1f5f9;
        transform: translateY(-1px);
    }
</style>
@endpush

@php
// GANTI DENGAN NOMOR WA ADMIN SISTEM ANDA
$noAdminSistem = '0822222222';
$pesanAdminSistem = rawurlencode("Halo Admin E-Food, saya ingin bertanya terkait pesanan saya dengan ID #ORD-{$order->id}.");
@endphp

@section('content')
<div class="container py-5">

    {{-- Alert Flash Message --}}
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
        if($order->status == 'diproses') $statusColor = 'primary';
        if($order->status == 'diantar') $statusColor = 'info';
        if($order->status == 'selesai') $statusColor = 'success';
        if(in_array($order->status, ['ditolak', 'dibatalkan', 'batal'])) $statusColor = 'danger';
        @endphp
        <span class="badge bg-{{ $statusColor }} status-pill">
            {{ strtoupper($order->status == 'menunggu' ? 'MENUNGGU VERIFIKASI KWT' : 
              ($order->status == 'diproses' ? 'SEDANG DIPROSES ADMIN' : 
              ($order->status == 'diantar' ? 'PESANAN DIANTAR' : $order->status))) }}
        </span>
    </div>

    <div class="row g-4">

        <div class="row g-4">

            {{-- LEFT SIDE --}}
            <div class="col-lg-7">

                {{-- TRACKING TIMELINE (6 STEPS) --}}
                <div class="card-modern mb-4">
                    <h6 class="fw-bold mb-4"><i class="bi bi-truck-flatbed text-success me-2"></i>Status Lacak Pesanan</h6>

                    <div class="tracking-timeline">
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
                                    Pesanan dijadwalkan pada:
                                    @if($order->jadwal_pengiriman)
                                    <span class="text-success fw-bold">{{ \Carbon\Carbon::parse($order->jadwal_pengiriman)->format('d M') }}</span>
                                    @else
                                    @if(in_array($order->status, ['menunggu'])) Menunggu @else Mengatur jadwal @endif
                                    @endif
                                </small>
                            </div>

                            {{-- STEP 4: Perjalanan --}}
                            <div class="timeline-step {{ in_array($order->status, ['selesai']) || ($order->status == 'diantar' && $order->bukti_sampai) ? 'completed' : ($order->status == 'diantar' && !$order->bukti_sampai ? 'active' : '') }}">
                                <div class="timeline-icon"><i class="bi bi-truck"></i></div>
                                <div class="timeline-text">Perjalanan</div>
                                <small class="timeline-time">
                                    Pesanan:
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
                                    Pesanan:
                                    @if($order->status == 'selesai' || ($order->status == 'diantar' && $order->bukti_sampai))
                                    <span class="text-success fw-bold">Telah Sampai</span>
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
                                    Pesanan selesai pada:
                                    @if($order->status == 'selesai')
                                    <span class="text-success fw-bold">{{ $order->updated_at->timezone('Asia/Jakarta')->format('d M') }}</span>
                                    @else
                                    Menunggu Acc
                                    @endif
                                </small>
                            </div>

                        </div>
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

                            @php
                            $cleanedAddress = str_replace(['Kel/Desa -,', 'Kel/Desa -'], '', $order->alamat);
                            $cleanedAddress = trim(preg_replace('/,\s*,/', ',', $cleanedAddress));
                            @endphp
                            <div class="text-muted small mt-1" style="line-height: 1.4;">{{ $cleanedAddress }}</div>
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
                            <i class="bi bi-person-bounding-box"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Informasi Pengantar</h6>

                            @if($order->kurir)
                            @php
                            $kurirParts = explode(' - ', $order->kurir);
                            $namaKurir = $kurirParts[0] ?? 'Kurir Internal';

                            $detailKendaraan = count($kurirParts) > 1 ? implode(' - ', array_slice($kurirParts, 1)) : '';

                            if(!$detailKendaraan) {
                            $dataKurir = \App\Models\Kurir::where('nama', $order->kurir)->first();
                            $detailKendaraan = $dataKurir ? $dataKurir->kendaraan : '';
                            }

                            $isMobil = $detailKendaraan ? (str_contains(strtolower($detailKendaraan), 'mobil') || str_contains(strtolower($detailKendaraan), 'carry')) : false;
                            @endphp

                            <div class="fw-semibold text-dark">{{ $namaKurir }}</div>

                            @if($detailKendaraan)
                            <span class="badge bg-secondary bg-opacity-10 text-dark border px-2 py-1 mt-1 mb-1 d-inline-block">
                                <i class="bi {{ $isMobil ? 'bi-car-front-fill' : 'bi-bicycle' }} me-1 text-success"></i> {{ $detailKendaraan }}
                            </span>
                            @endif

                            <div class="text-muted small mt-1">
                                Hubungi Kurir:
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->no_hp_kurir) }}" target="_blank" class="fw-bold text-success text-decoration-none wa-link">
                                    <i class="bi bi-whatsapp"></i> {{ $order->no_hp_kurir }}
                                </a>
                            </div>
                            @else
                            <div class="text-muted small">Kurir belum ditugaskan oleh admin.</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- PRODUK DIBELI --}}
                <h5 class="fw-bold mb-3">Produk yang Dibeli</h5>

                @foreach($order->details as $detail)
                <div class="product-row d-flex gap-3 align-items-center mb-3">
                    @if($detail->product && $detail->product->foto_produk)
                    <img src="{{ asset('storage/' . $detail->product->foto_produk) }}" class="product-img" alt="Produk">
                    @else
                    <div class="product-img bg-secondary d-flex align-items-center justify-content-center text-white rounded-3"><i class="bi bi-box"></i></div>
                    @endif

                    <div class="flex-grow-1">
                        <div class="fw-bold" style="font-size: 0.95rem;">{{ $detail->product->nama_produk ?? 'Produk Terhapus' }}</div>
                        <div class="my-1">
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2 py-1" style="font-size: 0.72rem; font-weight: 600;">
                                <i class="bi bi-shop me-1"></i>{{ $detail->product->user->name ?? 'KWT Mandiri' }}
                            </span>
                        </div>
                        <small class="text-muted">
                            {{ $detail->jumlah }} {{ $detail->product->satuan ?? 'Pcs' }} × Rp {{ number_format($detail->harga_saat_ini, 0, ',', '.') }}
                        </small>
                    </div>

                    <div class="fw-bold text-success">
                        Rp {{ number_format($detail->jumlah * $detail->harga_saat_ini, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach

                {{-- RIWAYAT PENGADUAN --}}
                @if($order->reports && $order->reports->isNotEmpty())
                <div class="card border-0 shadow-sm p-4 bg-white mt-4" style="border-radius: 18px;">
                    <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-chat-left-text-fill text-success me-2"></i>Riwayat Pengaduan & Tanggapan KWT</h6>
                    @foreach($order->reports as $rep)
                    <div class="p-3 bg-light rounded-4 mb-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-danger bg-opacity-10 text-danger px-2.5 py-1 rounded-pill fw-bold text-uppercase fs-8" style="font-size: 0.72rem;">{{ $rep->tipe_pengaduan }}</span>
                            <small class="text-muted fs-8 font-monospace">{{ $rep->created_at->format('d M Y, H:i') }} WIB</small>
                        </div>
                        <div class="small text-dark mb-2"><strong>Keluhan Anda:</strong> "{{ $rep->pesan }}"</div>

                        @if($rep->tanggapan_kwt)
                        <div class="p-3 bg-success bg-opacity-10 rounded-3 border-start border-4 border-success mt-2">
                            <strong class="text-success small d-block mb-1"><i class="bi bi-chat-right-quote-fill me-1"></i> Tanggapan dari KWT:</strong>
                            <p class="small text-dark mb-0 lh-base">"{!! nl2br(e($rep->tanggapan_kwt)) !!}"</p>
                        </div>
                        @else
                        <div class="p-3 bg-warning bg-opacity-10 rounded-3 border-start border-4 border-warning mt-2 small text-warning">
                            <i class="bi bi-clock-history me-1"></i> Menunggu tanggapan dari Kelompok Wanita Tani (KWT)...
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- FORM PENGADUAN KENDALA --}}
                <div class="card border-0 shadow-sm p-4 bg-white mt-4" style="border-radius: 18px;">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-circle p-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="bi bi-shield-exclamation fs-6"></i>
                        </span>
                        <h6 class="fw-bold m-0 text-dark" style="font-size: 1.05rem;">Ada Kendala dengan Pesanan Ini?</h6>
                    </div>
                    <p class="text-muted small mb-4 ms-1">
                        Pesanan belum sampai, kurir bermasalah, atau barang bermasalah? Kirim pengaduan Anda di bawah ini.
                    </p>

                    <form action="{{ route('orders.report.store', $order->id) }}" method="POST" enctype="multipart/form-data" class="ms-1">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary mb-1">Pilih Produk Kendala</label>
                                <select name="product_id" class="form-select border-light-subtle bg-light bg-opacity-50 py-2" style="border-radius: 10px; font-size: 0.9rem;">
                                    <option value="">Umum / Seluruh Pesanan</option>
                                    @foreach($order->details as $detail)
                                    <option value="{{ $detail->product_id }}">
                                        {{ Str::limit($detail->product->nama_produk ?? 'Produk Terhapus', 35) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary mb-1">Foto Bukti (Opsional)</label>
                                <input type="file" name="foto_bukti" class="form-control border-light-subtle bg-light bg-opacity-50" accept="image/*" style="border-radius: 10px; font-size: 0.9rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary mb-1">Kategori Keluhan</label>
                                <select name="tipe_pengaduan" class="form-select border-light-subtle bg-light bg-opacity-50 py-2" required style="border-radius: 10px;">
                                    <option value="" disabled selected>Pilih jenis keluhan...</option>
                                    <option value="Produk Rusak">Produk Rusak</option>
                                    <option value="Produk Kurang">Produk Kurang</option>
                                    <option value="Pengiriman Terlambat">Pengiriman Terlambat</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-secondary mb-1">Isi Laporan / Keluhan</label>
                                <textarea name="pesan" class="form-control border-light-subtle bg-light bg-opacity-50" rows="3" placeholder="Ceritakan detail kendala yang dialami secara singkat..." required style="border-radius: 10px; font-size: 0.9rem; resize: none;"></textarea>
                            </div>
                            <div class="col-12 d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-danger px-4 py-2" style="border-radius: 10px; font-weight: 600; font-size: 0.88rem; background: #dc2626; border: none;">
                                    Kirim Pengaduan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

            {{-- RIGHT SIDE --}}
            <div class="col-lg-5">
                <div class="summary-card">

                    {{-- INFO DANA MASUK & TOMBOL KLAIM REFUND JIKA BATAL/DITOLAK --}}
                    @if(in_array($order->status, ['batal', 'ditolak', 'dibatalkan']))
                    <div class="card-modern mb-4 border border-danger bg-white shadow-sm">
                        <h5 class="fw-bold text-danger mb-2"><i class="bi bi-exclamation-octagon-fill me-1"></i> Pesanan Dibatalkan KWT</h5>
                        <p class="text-muted small mb-2"><strong>Alasan Pembatalan:</strong> <span class="text-dark">"{{ $order->alasan_tolak ?? 'Komoditas sayur/hasil tani tidak memenuhi standar kelayakan pengiriman.' }}"</span></p>

                        <div class="p-3 bg-light rounded-3 border mb-3 small text-dark">
                            <div class="d-flex justify-content-between mb-1.5 pb-1.5 border-bottom">
                                <span class="text-muted"><i class="bi bi-arrow-down-left-circle text-success me-1"></i> Nominal Dana</span>
                                <strong>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1.5 pb-1.5 border-bottom">
                                <span class="text-muted"><i class="bi bi-shield-check text-success me-1"></i> Status Pembayaran</span>
                                <span class="text-success fw-bold">Dana Masuk di KWT</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted"><i class="bi bi-arrow-counterclockwise text-danger me-1"></i> Status Pengembalian</span>
                                <span class="text-danger fw-bold">Diproses Refund</span>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">Dana transaksi Anda dijamin aman. Harap klik tombol di bawah untuk menyetorkan nomor rekening pribadi tujuan pencairan melalui WhatsApp resmi Pengurus KWT.</p>

                        <a href="https://wa.me/{{ $noAdminSistem }}?text={{ $pesanAdminSistem }}" target="_blank" class="btn btn-danger w-100 py-2.5 fw-bold text-white d-flex align-items-center justify-content-center gap-2" style="border-radius: 12px; background: #dc2626; border: none;">
                            <i class="bi bi-whatsapp"></i> Klaim Pengembalian Dana (Refund)
                        </a>
                    </div>
                    @endif

                    {{-- KARTU KONFIRMASI SELESAI --}}
                    @if($order->status == 'diantar')
                    <div class="card-modern mb-4 border {{ $order->bukti_sampai ? 'border-success' : 'border-warning' }} bg-white shadow-sm">
                        <h5 class="fw-bold {{ $order->bukti_sampai ? 'text-success' : 'text-warning' }} mb-2">
                            <i class="bi {{ $order->bukti_sampai ? 'bi-check-circle-fill' : 'bi-truck' }} me-1"></i>
                            {{ $order->bukti_sampai ? 'Paket Tiba di Tujuan' : 'Pesanan Sedang Diantar' }}
                        </h5>

                        <div class="p-3 {{ $order->bukti_sampai ? 'bg-success border-success' : 'bg-warning border-warning' }} bg-opacity-10 rounded-3 mb-3 border border-opacity-25 d-flex align-items-center gap-3">
                            <div class="{{ $order->bukti_sampai ? 'bg-success' : 'bg-warning' }} text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px;">
                                <i class="bi {{ $order->bukti_sampai ? 'bi-camera-fill' : 'bi-truck' }} fs-5"></i>
                            </div>
                            <div>
                                <span class="d-block fw-bold {{ $order->bukti_sampai ? 'text-success' : 'text-dark' }}" style="font-size: 0.95rem;">
                                    Status: {{ $order->bukti_sampai ? 'Menunggu Konfirmasi Anda' : 'Dalam Perjalanan' }}
                                </span>
                                <span class="small {{ $order->bukti_sampai ? 'text-success' : 'text-dark' }}" style="line-height: 1.2; display: block; margin-top: 4px;">
                                    {{ $order->bukti_sampai ? 'Kurir telah mengunggah bukti pengiriman. Silakan periksa barang belanjaan Anda.' : 'Kurir sedang menuju alamat Anda. Tombol konfirmasi akan aktif setelah kurir mengunggah bukti foto pesanan sampai.' }}
                                </span>
                            </div>
                        </div>

                        @if($order->bukti_sampai)
                        <div class="text-center mb-3">
                            <p class="text-muted small mb-2 text-start">Pratinjau Bukti Pengiriman:</p>
                            <img src="{{ asset('storage/' . $order->bukti_sampai) }}" class="img-fluid rounded-4 border shadow-sm w-100" style="max-height: 250px; object-fit: cover;">
                        </div>
                        <p class="text-muted small mb-3">Jika pesanan Anda sudah sesuai dan diterima dengan baik, klik <strong>"Pesanan Diterima"</strong> di bawah. <br><br>Jika paket belum diterima atau ada kendala, segera hubungi <a href="https://wa.me/{{ $noAdminSistem }}?text={{ $pesanAdminSistem }}" target="_blank" class="fw-bold text-success text-decoration-none wa-link"><i class="bi bi-whatsapp"></i> Admin E-Food</a>.</p>

                        <form action="{{ route('orders.complete', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="d-flex justify-content-end mt-2">
                                <button type="submit" class="btn btn-selesai rounded-pill w-100 fw-bold py-2.5 shadow-sm" onclick="return confirm('Apakah Anda yakin pesanan sudah diterima dengan lengkap dan sesuai?')">
                                    <i class="bi bi-check-circle-fill me-1"></i> Pesanan Diterima (Selesai)
                                </button>
                            </div>
                        </form>
                        @else
                        <div class="d-flex justify-content-end mt-2">
                            <button type="button" class="btn btn-secondary rounded-pill w-100 fw-bold py-2.5 shadow-sm" disabled style="opacity: 0.6; cursor: not-allowed;">
                                <i class="bi bi-hourglass-split me-1"></i> Menunggu Foto Bukti Kurir
                            </button>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- BUKTI FOTO JIKA STATUS SUDAH SELESAI --}}
                    @if($order->status == 'selesai')
                    <div class="card-modern mb-4 border-0 shadow-sm bg-white" style="border-radius: 22px;">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="badge bg-success bg-opacity-10 text-success rounded-circle p-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="bi bi-patch-check-fill fs-6"></i>
                            </span>
                            <h6 class="fw-bold m-0 text-success" style="font-size: 1.1rem;">Pesanan Selesai</h6>
                        </div>

                        @if($order->bukti_sampai)
                        <p class="text-muted small mb-2 ms-1">Foto bukti pengiriman dari kurir:</p>
                        <div class="rounded-4 overflow-hidden border border-light p-1 bg-light">
                            <img src="{{ asset('storage/' . $order->bukti_sampai) }}" class="img-fluid rounded-3 w-100" style="max-height: 280px; object-fit: cover; background: #fff;">
                        </div>
                        @else
                        <div class="alert alert-warning small rounded-3 p-2 mb-0">
                            <i class="bi bi-check-circle-fill me-1"></i> Pesanan telah diselesaikan.
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- =====================================
                     RINGKASAN PEMBAYARAN (DIPERBAIKI)
                     ===================================== --}}
                    <div class="card-modern">
                        <h5 class="fw-bold mb-4">Ringkasan Pembayaran</h5>

                        @php
                        // Hitung harga asli produk untuk mengatasi pesanan lama yang biaya layanannya 0 di DB
                        $subtotalKWT = $order->details->sum(fn($d) => $d->jumlah * $d->harga_saat_ini);
                        $biayaPlatformAsli = $order->total_harga - $order->ongkir - $subtotalKWT;
                        @endphp

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-secondary">Subtotal Produk</span>
                            <strong class="text-dark">Rp {{ number_format($subtotalKWT, 0, ',', '.') }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-secondary">Ongkos Kirim <i class="bi bi-info-circle text-muted" title="Sudah termasuk jarak dan volume barang"></i></span>
                            <strong class="text-success">+ Rp {{ number_format($order->ongkir, 0, ',', '.') }}</strong>
                        </div>

                        {{-- BIAYA LAYANAN HANYA MUNCUL JIKA LEBIH DARI 0 --}}
                        @if($biayaPlatformAsli > 0)
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-secondary">Biaya Layanan</span>
                            <strong class="text-success">+ Rp {{ number_format($biayaPlatformAsli, 0, ',', '.') }}</strong>
                        </div>
                        @endif

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