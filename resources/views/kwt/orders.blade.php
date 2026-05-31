@extends('layouts.kwt')

@section('content')
<div class="container-fluid py-4 bg-light min-vh-100">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">Daftar Pesanan Masuk</h4>
            <p class="text-muted mb-0">Halaman khusus Ibu-ibu KWT untuk mengecek dan memastikan barang pesanan tersedia.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-4 py-2 fw-semibold fs-6 d-flex align-items-center shadow-sm">
                <i class="bi bi-cart-check-fill me-2 fs-5"></i> {{ $orders->count() }} Total Pesanan
            </span>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @php
    // Kalkulasi Statistik Khusus KWT yang sedang login
    $totalPendapatan = 0;
    $countMenunggu = 0;
    $countDiproses = 0;
    $countBatal = 0;

    foreach($orders as $sale) {
    $kwtDetails = $sale->details->filter(function($d) {
    return $d->product && $d->product->user_id == Auth::id();
    });

    if($kwtDetails->isNotEmpty()) {
    if($sale->status == 'selesai') {
    $totalPendapatan += $kwtDetails->sum(function($d) { return $d->harga_saat_ini * $d->jumlah; });
    } elseif($sale->status == 'menunggu') {
    $countMenunggu++;
    } elseif($sale->status == 'diproses' || $sale->status == 'diantar') {
    $countDiproses++;
    } elseif($sale->status == 'batal') {
    $countBatal++;
    }
    }
    }
    @endphp

    {{-- WIDGET STATISTIK KWT --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white hover-elevate">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 55px; height: 55px;">
                        <i class="bi bi-hourglass-split fs-3"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $countMenunggu }} <span class="fs-6 text-muted fw-normal">Pesanan</span></h4>
                        <small class="text-muted text-uppercase tracking-wider fs-8 fw-bold">Perlu Dicek Ibu</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white hover-elevate">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 55px; height: 55px;">
                        <i class="bi bi-box-seam fs-3"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $countDiproses }} <span class="fs-6 text-muted fw-normal">Pesanan</span></h4>
                        <small class="text-muted text-uppercase tracking-wider fs-8 fw-bold">Sedang Diproses</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white hover-elevate">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 55px; height: 55px;">
                        <i class="bi bi-x-circle fs-3"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $countBatal }} <span class="fs-6 text-muted fw-normal">Pesanan</span></h4>
                        <small class="text-muted text-uppercase tracking-wider fs-8 fw-bold">Batal / Ditolak</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-gradient-success text-white hover-elevate">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 55px; height: 55px;">
                        <i class="bi bi-wallet2 fs-3"></i>
                    </div>
                    <div>
                        <h4 class="fw-extrabold mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                        <small class="text-white opacity-75 text-uppercase tracking-wider fs-8 fw-bold">Pemasukan Selesai</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-5">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-list-task me-2 text-success"></i>Daftar Pesanan yang Masuk ke Ibu</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0 table-hover" id="interactive-table">
                <thead class="bg-light text-uppercase tracking-wider fs-7 fw-bold text-secondary">
                    <tr>
                        <th class="ps-4 py-3 sortable" data-sort="id" style="cursor: pointer;">
                            Order ID <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i>
                        </th>
                        <th class="py-3 text-center">Pembeli</th>
                        <th class="py-3 text-center">Produk Dipesan</th>
                        <th class="py-3 text-end sortable" data-sort="subtotal" style="cursor: pointer;">
                            Pemasukan Ibu <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i>
                        </th>
                        <th class="py-3 text-center sortable" data-sort="status" style="cursor: pointer;">
                            Status <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i>
                        </th>
                        <th class="py-3 text-center">Tgl Masuk</th>
                        <th class="py-3 text-center pe-4">Aksi / Tombol</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $sale)
                    @php
                    $kwtDetails = $sale->details->filter(function($d) {
                    return $d->product && $d->product->user_id == Auth::id();
                    });
                    $subtotalKwt = $kwtDetails->sum(function($d) {
                    return $d->harga_saat_ini * $d->jumlah;
                    });
                    @endphp

                    @if($kwtDetails->isNotEmpty())
                    <tr class="align-middle data-row" data-id="{{ $sale->id }}" data-date="{{ $sale->created_at->timestamp }}" data-status-text="{{ $sale->status }}" data-subtotal="{{ $subtotalKwt }}">
                        <td class="ps-4 py-3 text-center">
                            <span class="fw-bold text-success font-monospace bg-success bg-opacity-10 px-2 py-1 rounded">#ORD-{{ $sale->id }}</span>
                        </td>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm bg-secondary bg-opacity-10 text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold text-uppercase" style="width: 38px; height: 38px;">
                                    {{ substr($sale->user->name ?? 'M', 0, 1) }}
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block lh-sm">{{ $sale->user->name ?? 'Masyarakat' }}</span>
                                    <small class="text-muted fs-8"><i class="bi bi-telephone-fill me-1"></i>{{ $sale->nomor_hp ?? $sale->user->phone_number ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 text-center">
                            <div class="d-flex flex-wrap gap-1 text-center justify-content-center">
                                @php $count = 0; @endphp
                                @foreach($kwtDetails as $d)
                                @if($count < 2)
                                    <span class="badge bg-light text-dark border px-2 py-1 rounded small fw-medium shadow-sm">
                                    {{ $d->product->nama_produk ?? 'Produk' }} (x{{ $d->jumlah }})
                                    </span>
                                    @endif
                                    @php $count++; @endphp
                                    @endforeach

                                    @if($count > 2)
                                    <span class="badge bg-light text-primary border px-2 py-1 rounded small">
                                        +{{ $count - 2 }} lainnya
                                    </span>
                                    @endif
                            </div>
                        </td>
                        <td class="py-3 text-center text-success fw-bold">
                            <span class="fw-bold text-dark fs-6">Rp {{ number_format($subtotalKwt, 0, ',', '.') }}</span>
                        </td>
                        <td class="py-3 text-center">
                            @if($sale->status == 'menunggu')
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-2 fw-bold text-uppercase fs-8 shadow-sm">⚡ Menunggu Dicek</span>
                            @elseif($sale->status == 'diproses')
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 fw-bold text-uppercase fs-8 shadow-sm">📦 Diproses Admin</span>
                            @elseif($sale->status == 'diantar')
                            <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-2 fw-bold text-uppercase fs-8 shadow-sm">🚚 Diantar Kurir</span>
                            @elseif($sale->status == 'batal')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2 fw-bold text-uppercase fs-8 shadow-sm">❌ Batal</span>
                            @else
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-bold text-uppercase fs-8 shadow-sm">✅ {{ $sale->status }}</span>
                            @endif
                        </td>
                        <td class="py-3 text-secondary fs-7 text-center">
                            <div class="fw-medium text-dark">{{ $sale->created_at->format('d M Y') }}</div>
                            <small class="text-muted">{{ $sale->created_at->format('H:i') }} WIB</small>
                        </td>
                        <td class="py-3 text-center pe-4">
                            <div class="d-flex gap-2 justify-content-center">
                                @if($sale->status == 'menunggu')
                                <button type="button" class="btn btn-success border-0 rounded-pill px-4 fw-bold shadow-sm hover-elevate"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalProsesKwt{{ $sale->id }}">
                                    <i class="bi bi-hand-index-thumb me-1"></i> Cek Pesanan
                                </button>
                                @else
                                <button type="button" class="btn btn-light border rounded-pill px-3 fw-medium text-dark shadow-sm hover-elevate"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalProsesKwt{{ $sale->id }}">
                                    <i class="bi bi-lock-fill text-muted me-1"></i> Lihat Data
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr class="empty-row">
                        <td colspan="7" class="text-center py-5 text-muted">
                            <div class="py-4">
                                <i class="bi bi-basket2-fill fs-1 mb-3 d-block opacity-25 text-success"></i>
                                <span class="d-block fw-bold fs-5 text-dark mb-1">Belum Ada Pesanan Masuk</span>
                                <span class="d-block small">Pesanan dari masyarakat akan muncul di sini.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL VERIFIKASI PESANAN KHUSUS KWT --}}
@foreach($orders as $sale)
@php
$kwtDetails = $sale->details->filter(function($d) {
return $d->product && $d->product->user_id == Auth::id();
});
$subtotalKwt = $kwtDetails->sum(function($d) {
return $d->harga_saat_ini * $d->jumlah;
});

// Cek apakah ada barang yang sudah dikonfirmasi ready
$hasConfirmed = $kwtDetails->contains(function($d) {
return $d->stok_ready == true;
});
@endphp

@if($kwtDetails->isNotEmpty())
<div class="modal fade" id="modalProsesKwt{{ $sale->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('kwt.orders.accept', $sale->id) }}" method="POST" class="form-auto-submit">
                @csrf
                @method('PUT')

                <div class="modal-header border-bottom py-3 px-4 bg-white align-items-center rounded-top-4">
                    <div>
                        <h4 class="fw-bold text-dark mb-0">
                            {{ $sale->status === 'menunggu' ? 'Cek Ketersediaan Barang' : 'Data Pesanan (Terkunci)' }}
                        </h4>
                        <small class="text-muted fs-7">Nomor Resi: <span class="text-success fw-bold font-monospace">#ORD-{{ $sale->id }}</span></small>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 py-4" style="background-color: #f8fafc;">

                    {{-- INSTRUKSI UNTUK IBU-IBU --}}
                    @if($sale->status === 'menunggu')
                    <div class="alert alert-info border-0 shadow-sm d-flex align-items-start mb-4 rounded-4 p-3 bg-white">
                        <i class="bi bi-info-circle-fill text-info fs-3 me-3 mt-1"></i>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Tugas Ibu:</h6>
                            <p class="mb-0 small text-dark lh-base">
                                Mohon cek kebun atau keranjang Ibu. Apakah barang di bawah ini <strong>ada dan siap dikirim?</strong><br>
                                Jika ada, silakan <strong>geser tombol ke warna hijau</strong>. Data akan otomatis terkunci & tersimpan.
                            </p>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-secondary border-0 shadow-sm d-flex align-items-center mb-4 rounded-4 p-3 bg-white">
                        <i class="bi bi-lock-fill text-secondary fs-3 me-3"></i>
                        <div>
                            <p class="mb-0 small text-dark lh-base">
                                Pesanan ini sudah berstatus <strong>{{ strtoupper($sale->status) }}</strong>. Form pengecekan barang sudah <strong>dikunci otomatis</strong>.
                            </p>
                        </div>
                    </div>
                    @endif

                    {{-- INFO PEMBELI & PEMASUKAN --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <div class="col-12 col-md-6 border-md-end">
                                    <small class="text-muted d-block text-uppercase fs-8 fw-bold mb-1">Dipesan Oleh:</small>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-person-circle fs-4 text-secondary"></i>
                                        <span class="fw-bold text-dark fs-5">{{ $sale->user->name ?? 'Masyarakat' }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 text-md-end">
                                    <small class="text-muted d-block text-uppercase fs-8 fw-bold mb-1">Total Pemasukan Ibu (Jika Selesai):</small>
                                    <span class="fw-bold text-success fs-4">Rp {{ number_format($subtotalKwt, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DAFTAR PRODUK & TOGGLE / CHECKBOX --}}
                    <h6 class="fw-bold text-dark mb-3 fs-6"><i class="bi bi-basket3-fill text-success me-2"></i>Daftar Barang Pesanan:</h6>

                    <div class="d-flex flex-column gap-3 mb-2">
                        @foreach($kwtDetails as $detail)
                        @php
                        // Kunci visual jika produk SUDAH ready atau status pesanan bukan lagi menunggu
                        $isLocked = $detail->stok_ready || $sale->status !== 'menunggu';
                        @endphp
                        <div class="card product-card transition-all border shadow-sm rounded-4 overflow-hidden {{ $isLocked ? 'bg-light opacity-75 card-checked' : 'bg-white' }}">
                            <div class="card-body p-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                {{-- Kiri: Info Barang --}}
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border" style="width: 60px; height: 60px;">
                                        <i class="bi bi-box2-heart text-success fs-3"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-1">{{ $detail->product->nama_produk }}</h5>
                                        <span class="badge bg-secondary bg-opacity-10 text-dark border px-2 py-1">
                                            Jumlah: <strong>{{ $detail->jumlah }} {{ $detail->product->satuan }}</strong>
                                        </span>
                                        <div class="text-muted small mt-1 fw-semibold">Harga: Rp {{ number_format($detail->harga_saat_ini * $detail->jumlah, 0, ',', '.') }}</div>
                                    </div>
                                </div>

                                {{-- Kanan: Aksi (Toggle Besar) --}}
                                <div class="bg-white px-4 py-3 rounded-4 border shadow-sm flex-shrink-0">
                                    <div class="form-check form-switch d-flex align-items-center gap-2 mb-0 custom-switch-lg">

                                        @if(!$isLocked)
                                        <input type="hidden" class="fallback-input" name="stok_ready[{{ $detail->id }}]" value="0">
                                        @endif

                                        <input class="form-check-input ms-0 mt-0 kwt-toggle-stok {{ $isLocked ? 'cursor-not-allowed' : 'cursor-pointer' }}"
                                            type="checkbox"
                                            name="stok_ready[{{ $detail->id }}]"
                                            value="1"
                                            id="stok_{{ $detail->id }}"
                                            {{ $detail->stok_ready ? 'checked' : '' }}
                                            {{ $isLocked ? 'disabled' : '' }}>

                                        <label class="form-check-label fw-bold ms-3 user-select-none {{ $isLocked ? 'cursor-not-allowed' : 'cursor-pointer' }}"
                                            for="stok_{{ $detail->id }}">

                                            <span class="d-block text-dark fs-6">Barang ADA (Ready)</span>

                                            @if($detail->stok_ready)
                                            <span class="text-success fw-bold"><i class="bi bi-check2-all me-1"></i>Tersedia (Terkunci)</span>
                                            @elseif($sale->status !== 'menunggu')
                                            <small class="text-danger fw-bold"><i class="bi bi-lock-fill me-1"></i>Dikunci</small>
                                            @else
                                            <small class="text-muted fw-normal toggle-subtitle">Geser tombol di samping</small>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- FOOTER HANYA MENYISAKAN TOMBOL TOLAK --}}
                @if($sale->status == 'menunggu')
                <div class="modal-footer border-top py-3 px-4 bg-white d-flex justify-content-between flex-wrap gap-3 rounded-bottom-4">
                    <button type="button"
                        class="btn btn-outline-danger btn-tolak-pesanan rounded-pill px-4 fw-bold hover-elevate transition-all m-0 {{ $hasConfirmed ? 'disabled opacity-50' : '' }}"
                        {{ $hasConfirmed ? 'disabled' : '' }}
                        data-bs-toggle="modal"
                        data-bs-target="#modalTolakPesananKwt{{ $sale->id }}">
                        @if($hasConfirmed)
                        <i class="bi bi-lock-fill me-1"></i> Tolak Dikunci
                        @else
                        <i class="bi bi-x-octagon-fill me-1"></i> Tolak (Semua Kosong)
                        @endif
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>

{{-- MODAL FORM ALASAN PENOLAKAN --}}
@if($sale->status == 'menunggu')
<div class="modal fade" id="modalTolakPesananKwt{{ $sale->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom py-3 px-4 bg-white rounded-top-4">
                <h5 class="fw-bold text-danger mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>Tolak Pesanan</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('kwt.orders.reject', $sale->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-light">
                    <div class="alert alert-warning border-0 shadow-sm rounded-3 small text-dark mb-4">
                        Jika panen gagal atau barang benar-benar kosong, Ibu bisa menolak pesanan ini agar Admin dapat mengembalikan uang pembeli.
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold text-dark fs-6 mb-2">Kenapa pesanan ini ditolak, Bu?</label>
                        <textarea name="alasan_tolak" class="form-control text-dark p-3 bg-white border shadow-sm" rows="4" placeholder="Contoh: Maaf ya Bu, sawinya gagal panen karena hujan terus..." required style="border-radius: 12px; resize: none;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top p-3 px-4 d-flex justify-content-end gap-2 bg-white rounded-bottom-4">
                    <button type="button" class="btn btn-light border rounded-pill px-4 fw-bold" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalProsesKwt{{ $sale->id }}">Kembali</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm hover-elevate">
                        <i class="bi bi-trash3-fill me-1"></i> Yakin Tolak Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endif
@endforeach

<style>
    /* Styling Dasar Kustom */
    .bg-gradient-success {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .fs-7 {
        font-size: 0.9rem !important;
    }

    .fs-8 {
        font-size: 0.8rem !important;
    }

    .tracking-wider {
        letter-spacing: 0.05em;
    }

    .transition-all {
        transition: all 0.3s ease;
    }

    .hover-elevate {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-elevate:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05) !important;
    }

    .table-hover tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6 !important;
    }

    /* Membesarkan ukuran Toggle Switch Bootstrap */
    .custom-switch-lg .form-check-input {
        width: 3.5em;
        height: 1.75em;
    }

    .custom-switch-lg .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }

    .custom-switch-lg .form-check-input:disabled {
        opacity: 0.6;
        cursor: not-allowed !important;
    }

    /* Highlight kartu saat dicentang */
    .product-card.card-checked {
        border-color: #198754 !important;
        background-color: #f0fdf4 !important;
    }

    /* Tambahkan ini di dalam <style> Anda */
    .cursor-pointer {
        cursor: pointer !important;
    }

    .cursor-not-allowed {
        cursor: not-allowed !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Buat Container Khusus untuk Toast (Notifikasi Pojok)
        const toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-4';
        toastContainer.style.zIndex = '1055'; // Berada di atas modal
        document.body.appendChild(toastContainer);

        function showNotification(message) {
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-white bg-success border-0 show shadow-lg mb-2 rounded-4';
            toast.innerHTML = `
                <div class="d-flex p-2">
                    <div class="toast-body fw-bold fs-6">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-3 m-auto shadow-none" onclick="this.closest('.toast').remove()"></button>
                </div>
            `;
            toastContainer.appendChild(toast);

            // Hapus otomatis setelah 2.5 detik
            setTimeout(() => {
                if (toast) toast.remove();
            }, 2500);
        }

        // 1. LOGIKA INTERAKTIF CHECKBOX DAN AUTO-SUBMIT
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            const form = modal.querySelector('form.form-auto-submit');
            const toggles = modal.querySelectorAll('.kwt-toggle-stok');
            const btnTolak = modal.querySelector('.btn-tolak-pesanan');

            if (toggles.length > 0) {
                const checkTogglesState = () => {
                    let anyChecked = false;

                    toggles.forEach(toggle => {
                        const card = toggle.closest('.product-card');
                        const subtitle = toggle.closest('.custom-switch-lg').querySelector('.toggle-subtitle');

                        if (toggle.checked) {
                            anyChecked = true;
                            card.classList.add('card-checked');
                            card.classList.add('opacity-75');
                            card.classList.add('bg-light');
                            card.classList.remove('bg-white');
                            if (subtitle && !toggle.disabled) {
                                subtitle.innerHTML = '<span class="text-success fw-bold"><i class="bi bi-check2-all me-1"></i>Tersedia (Menyimpan...)</span>';
                            }
                        } else {
                            card.classList.remove('card-checked');
                            card.classList.remove('opacity-75');
                            card.classList.remove('bg-light');
                            card.classList.add('bg-white');
                            if (subtitle && !toggle.disabled) subtitle.innerHTML = 'Geser tombol di samping';
                        }
                    });

                    // Mengatur tombol tolak
                    if (btnTolak) {
                        if (anyChecked) {
                            btnTolak.setAttribute('disabled', 'disabled');
                            btnTolak.classList.add('opacity-50', 'disabled');
                            btnTolak.innerHTML = '<i class="bi bi-lock-fill me-1"></i> Tolak Dikunci';
                        } else {
                            btnTolak.removeAttribute('disabled');
                            btnTolak.classList.remove('opacity-50', 'disabled');
                            btnTolak.innerHTML = '<i class="bi bi-x-octagon-fill me-1"></i> Tolak (Semua Kosong)';
                        }
                    }
                };

                // Panggil saat modal pertama kali terbuka
                checkTogglesState();

                toggles.forEach(toggle => {
                    toggle.addEventListener('change', async function() {
                        checkTogglesState();

                        if (form) {
                            // Kirim form di latar belakang tanpa refresh
                            const formData = new FormData(form);
                            try {
                                const response = await fetch(form.action, {
                                    method: 'POST', // Blade sudah menggunakan @method('PUT') 
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                });

                                if (response.ok) {
                                    showNotification('Ketersediaan barang berhasil diperbarui!');

                                    // KUNCI LANGSUNG SECARA VISUAL JIKA BERHASIL (MENCEGAH TOGGLE ULANG)
                                    if (toggle.checked) {
                                        toggle.disabled = true;
                                        toggle.style.cursor = 'not-allowed';
                                        const label = toggle.nextElementSibling;
                                        if (label) {
                                            label.style.cursor = 'not-allowed';
                                            const subtitle = label.querySelector('.toggle-subtitle');
                                            if (subtitle) {
                                                // Ubah tulisan menjadi terkunci
                                                subtitle.outerHTML = '<span class="text-success fw-bold"><i class="bi bi-check2-all me-1"></i>Tersedia (Terkunci)</span>';
                                            }
                                        }

                                        // Hapus input hidden miliknya agar tidak conflict
                                        const hiddenFallback = toggle.closest('.custom-switch-lg').querySelector('.fallback-input');
                                        if (hiddenFallback) hiddenFallback.remove();
                                    }

                                } else {
                                    showNotification('Terjadi kesalahan. Silakan refresh halaman.');
                                }
                            } catch (error) {
                                console.error('Error saat menyimpan data:', error);
                            }
                        }
                    });
                });
            }
        });

        // 2. LOGIKA SORTING TABEL
        let sortDirection = {
            id: false,
            date: false,
            status: false,
            subtotal: false
        };
        const headers = document.querySelectorAll('th.sortable');

        headers.forEach(th => {
            th.addEventListener('click', () => {
                const sortType = th.getAttribute('data-sort');
                const tbody = document.querySelector('#interactive-table tbody');
                const rows = Array.from(tbody.querySelectorAll('tr.data-row'));

                sortDirection[sortType] = !sortDirection[sortType];
                const isAsc = sortDirection[sortType];

                headers.forEach(header => {
                    const icon = header.querySelector('.sort-icon');
                    if (icon) icon.className = 'bi bi-arrow-down-up ms-1 text-muted sort-icon';
                });
                const activeIcon = th.querySelector('.sort-icon');
                if (activeIcon) {
                    activeIcon.className = isAsc ? 'bi bi-arrow-up ms-1 text-success sort-icon' : 'bi bi-arrow-down ms-1 text-success sort-icon';
                }

                rows.sort((a, b) => {
                    let valA, valB;
                    if (sortType === 'id') {
                        valA = parseInt(a.getAttribute('data-id'));
                        valB = parseInt(b.getAttribute('data-id'));
                    } else if (sortType === 'date') {
                        valA = parseInt(a.getAttribute('data-date'));
                        valB = parseInt(b.getAttribute('data-date'));
                    } else if (sortType === 'subtotal') {
                        valA = parseFloat(a.getAttribute('data-subtotal'));
                        valB = parseFloat(b.getAttribute('data-subtotal'));
                    } else if (sortType === 'status') {
                        valA = a.getAttribute('data-status-text').toLowerCase();
                        valB = b.getAttribute('data-status-text').toLowerCase();
                    }

                    if (valA < valB) return isAsc ? -1 : 1;
                    if (valA > valB) return isAsc ? 1 : -1;
                    return 0;
                });
                rows.forEach(row => tbody.appendChild(row));
            });
        });
    });
</script>
@endsection