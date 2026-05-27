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

    /* PREMIUM HORIZONTAL TIMELINE TRACKER STYLING */
    .tracking-timeline {
        background: #ffffff;
        padding: 10px 5px;
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
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #ffffff;
        border: 2px solid #cbd5e1;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px auto;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .timeline-step.active .timeline-icon {
        background: #e8f5e9;
        border-color: #4caf50;
        color: #4caf50;
        box-shadow: 0 0 0 5px rgba(76, 175, 80, 0.15);
    }

    .timeline-step.completed .timeline-icon {
        background: #4caf50;
        border-color: #4caf50;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(76, 175, 80, 0.15);
    }

    .timeline-text {
        font-size: 0.85rem;
        font-weight: 700;
        color: #94a3b8;
        margin-bottom: 3px;
    }

    .timeline-step.active .timeline-text,
    .timeline-step.completed .timeline-text {
        color: #0f172a;
    }

    .timeline-time {
        font-size: 0.75rem;
        color: #64748b;
        display: block;
        font-weight: 500;
        line-height: 1.3;
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
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
</style>

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
        if($order->status == 'ditolak' || $order->status == 'dibatalkan' || $order->status == 'batal') $statusColor = 'danger';
        @endphp
        <span class="badge bg-{{ $statusColor }} status-pill">
            {{ strtoupper($order->status == 'diproses' ? 'Sedang Diproses' : ($order->status == 'diantar' ? 'Pesanan Diantar' : ($order->status == 'menunggu' ? 'Menunggu Konfirmasi' : $order->status))) }}
        </span>
    </div>

    <div class="row g-4">

        {{-- LEFT SIDE --}}
        <div class="col-lg-7">

            {{-- TRACKING TIMELINE STATUS --}}
            <div class="card-modern mb-4">
                <h6 class="fw-bold mb-4"><i class="bi bi-truck-flatbed text-success me-2"></i>Status Lacak Pesanan</h6>

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
                                @if($order->jadwal_pengiriman)
                                Dijadwalkan: <span class="text-success fw-bold">{{ $order->jadwal_pengiriman }}</span>
                                @else
                                Pesanan siap dikirim
                                @endif
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
                            <small class="timeline-time text-success fw-bold">
                                @if($order->status == 'diantar')
                                Kurir dalam perjalanan
                                @if($order->kurir)
                                <span class="d-block text-muted fw-normal" style="font-size: 0.7rem;">({{ $order->kurir }})</span>
                                @endif
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
                                Menunggu fotomu
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

                {{-- 🌟 INFO DANA MASUK & TOMBOL KLAIM REFUND JIKA STATUSNYA BATAL/DITOLAK 🌟 --}}
                @if($order->status == 'batal' || $order->status == 'ditolak' || $order->status == 'dibatalkan')
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

                    @php
                    $noAdminKWT = '0822222222'; // Sesuaikan nomor HP Admin KWT Anda
                    $pesanWA = rawurlencode("Halo Pengurus KWT, pesanan saya dengan ID #ORD-".$order->id." ditolak dengan alasan '".$order->alasan_tolak."'. Saya ingin mengajukan klaim pengembalian dana (refund) sebesar Rp ".number_format($order->total_harga, 0, ',', '.')." atas nama " . Auth::user()->name . ".");
                    @endphp

                    <a href="https://wa.me/{{ $noAdminKWT }}?text={{ $pesanWA }}" target="_blank" class="btn btn-danger w-100 py-2.5 fw-bold text-white d-flex align-items-center justify-content-center gap-2" style="border-radius: 12px; background: #dc2626; border: none; text-decoration: none;">
                        <i class="bi bi-whatsapp"></i> Klaim Pengembalian Dana (Refund)
                    </a>
                </div>
                @endif

                {{-- TOMBOL SELESAI & UPLOAD FOTO BUKTI --}}
                @if($order->status == 'diantar')
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

                        <div class="d-flex justify-content-end gap-2 mt-2">
                            <a href="{{ route('orders.history') }}" class="btn btn-light border rounded-pill px-3 fw-bold small py-2">
                                <i class="bi bi-x-lg me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-selesai rounded-pill px-4 fw-bold py-2" onclick="return confirm('Apakah kamu yakin barang sudah diterima dengan benar?')">
                                <i class="bi bi-check-lg me-1"></i> Selesai
                            </button>
                        </div>
                    </form>
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
                    <p class="text-muted small mb-2 ms-1">Foto bukti penerimaan paket:</p>
                    <div class="rounded-4 overflow-hidden border border-light p-1 bg-light">
                        <img src="{{ asset('storage/' . $order->bukti_sampai) }}" class="img-fluid rounded-3 w-100" style="max-height: 280px; object-fit: cover; background: #fff;">
                    </div>
                    @else
                    <div class="alert alert-warning small rounded-3 p-2 mb-0">
                        <i class="bi bi-exclamation-circle-fill me-1"></i> Selesai tanpa berkas lampiran foto.
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