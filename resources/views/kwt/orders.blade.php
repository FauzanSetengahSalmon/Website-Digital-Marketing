@extends('layouts.kwt')

@section('content')
<div class="container-fluid py-4 bg-light min-vh-100">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">Daftar Pesanan Masuk</h4>
            <p class="text-muted small mb-0">Verifikasi ketersediaan stok produk Anda sebelum Admin memanggil kurir.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-semibold fs-7 d-flex align-items-center">
                <i class="bi bi-cart-check-fill me-1"></i> {{ $orders->count() }} Total Pesanan
            </span>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
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
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">{{ $countMenunggu }} <span class="fs-7 text-muted fw-normal">Pesanan</span></h5>
                        <small class="text-muted text-uppercase tracking-wider fs-8 fw-semibold">Perlu Diverifikasi</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-box-seam fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">{{ $countDiproses }} <span class="fs-7 text-muted fw-normal">Pesanan</span></h5>
                        <small class="text-muted text-uppercase tracking-wider fs-8 fw-semibold">Sedang Diproses</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-x-circle fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">{{ $countBatal }} <span class="fs-7 text-muted fw-normal">Pesanan</span></h5>
                        <small class="text-muted text-uppercase tracking-wider fs-8 fw-semibold">Batal / Ditolak</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-gradient-success text-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-wallet2 fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-extrabold mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h5>
                        <small class="text-white opacity-75 text-uppercase tracking-wider fs-8 fw-semibold">Pemasukan Selesai</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover" id="interactive-table">
                <thead class="bg-light border-bottom text-uppercase tracking-wider fs-7 fw-bold text-secondary">
                    <tr>
                        <th class="ps-4 py-3 sortable" data-sort="id" style="cursor: pointer;">
                            Order ID <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i>
                        </th>
                        <th class="py-3">Customer</th>
                        <th class="py-3">Produk Dipesan</th>
                        <th class="py-3 text-end sortable" data-sort="subtotal" style="cursor: pointer;">
                            Subtotal KWT <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i>
                        </th>
                        <th class="py-3 text-center sortable" data-sort="status" style="cursor: pointer;">
                            Status <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i>
                        </th>
                        <th class="py-3">Jadwal Kirim</th>
                        <th class="py-3 sortable" data-sort="date" style="cursor: pointer;">
                            Tanggal Transaksi <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i>
                        </th>
                        <th class="py-3 text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $sale)
                    @php
                    // Filter HANYA produk milik KWT yang sedang login
                    $kwtDetails = $sale->details->filter(function($d) {
                    return $d->product && $d->product->user_id == Auth::id();
                    });

                    // Hitung total harga hanya untuk produk KWT ini
                    $subtotalKwt = $kwtDetails->sum(function($d) {
                    return $d->harga_saat_ini * $d->jumlah;
                    });
                    @endphp

                    {{-- Tampilkan baris jika ada produk milik KWT ini --}}
                    @if($kwtDetails->isNotEmpty())
                    <tr class="align-middle border-bottom border-light data-row" data-id="{{ $sale->id }}" data-date="{{ $sale->created_at->timestamp }}" data-status-text="{{ $sale->status }}" data-subtotal="{{ $subtotalKwt }}">
                        <td class="ps-4 py-3.5">
                            <span class="fw-bold text-success font-monospace">#ORD-{{ $sale->id }}</span>
                        </td>
                        <td class="py-3.5">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center fw-bold text-uppercase" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                    {{ substr($sale->user->name ?? 'M', 0, 1) }}
                                </div>
                                <div>
                                    <span class="fw-semibold text-dark d-block lh-sm">{{ $sale->user->name ?? 'Masyarakat' }}</span>
                                    <small class="text-muted fs-8 font-monospace">{{ $sale->nomor_hp ?? $sale->user->phone_number ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5">
                            <div class="d-flex flex-wrap gap-1">
                                @php $count = 0; @endphp
                                @foreach($kwtDetails as $d)
                                @if($count < 2)
                                    <span class="badge bg-light text-dark border px-2 py-1 rounded small">
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
                        <td class="py-3.5 text-end">
                            <span class="fw-bold text-dark">Rp {{ number_format($subtotalKwt, 0, ',', '.') }}</span>
                        </td>
                        <td class="py-3.5 text-center">
                            @if($sale->status == 'menunggu')
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">⚡ Menunggu</span>
                            @elseif($sale->status == 'diproses')
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">📦 Diproses</span>
                            @elseif($sale->status == 'diantar')
                            <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">🚚 Diantar</span>
                            @elseif($sale->status == 'batal')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">❌ Batal</span>
                            @else
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">✅ {{ $sale->status }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 fs-7 fw-medium text-dark">
                            @if($sale->jadwal_pengiriman)
                            <span class="text-success bg-success bg-opacity-10 px-2.5 py-1 rounded-pill fw-bold"><i class="bi bi-calendar-check me-1"></i>{{ \Carbon\Carbon::parse($sale->jadwal_pengiriman)->format('d M Y') }}</span>
                            @else
                            <span class="text-muted small italic"><i class="bi bi-hourglass-split me-1"></i>Belum Dijadwalkan Admin</span>
                            @endif
                        </td>
                        <td class="py-3.5 text-secondary fs-7">
                            <i class="bi bi-calendar3 me-1 text-muted"></i> {{ $sale->created_at->format('d M Y, H:i') }} WIB
                        </td>
                        <td class="py-3.5 text-center pe-4">
                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 fw-medium text-dark transition-all shadow-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalProsesKwt{{ $sale->id }}">
                                    <i class="bi bi-clipboard-check me-1 text-success"></i> Verifikasi
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr class="empty-row">
                        <td colspan="8" class="text-center py-5 text-muted">
                            <div class="py-3">
                                <i class="bi bi-inbox fs-2 mb-2 d-block opacity-50"></i>
                                <span class="d-block fw-medium">Belum ada pesanan masuk.</span>
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
@endphp
@if($kwtDetails->isNotEmpty())
<div class="modal fade" id="modalProsesKwt{{ $sale->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            {{-- PERBAIKAN: ACTION MENGGUNAKAN ROUTE KWT --}}
            <form action="{{ route('kwt.orders.accept', $sale->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header border-0 py-3 px-4 bg-light align-items-center">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Verifikasi Stok Pesanan</h5>
                        <small class="text-muted fs-7">Order ID: <span class="text-success fw-bold font-monospace">#ORD-{{ $sale->id }}</span></small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 py-3">

                    <div class="alert alert-success bg-success bg-opacity-10 border-success border-opacity-25 d-flex align-items-center mb-4 rounded-3 p-3">
                        <i class="bi bi-info-circle-fill text-success fs-4 me-3"></i>
                        <div>
                            <p class="mb-0 small text-dark fw-medium">
                                Tugas Anda hanya mengonfirmasi ketersediaan stok. <strong>Penjadwalan dan pemanggilan kurir akan diurus sepenuhnya oleh Admin.</strong>
                            </p>
                        </div>
                    </div>

                    <div class="p-3 border rounded-4 bg-light bg-opacity-50 mb-3">
                        <div class="row g-3">
                            <div class="col-6 col-md-4">
                                <small class="text-muted d-block text-uppercase fs-8 fw-semibold mb-0.5">Customer</small>
                                <span class="fw-bold text-dark">{{ $sale->user->name ?? 'Masyarakat' }}</span>
                            </div>
                            <div class="col-6 col-md-4">
                                <small class="text-muted d-block text-uppercase fs-8 fw-semibold mb-0.5">Status Pesanan</small>
                                <span class="fw-semibold text-dark text-uppercase">{{ $sale->status }}</span>
                            </div>
                            <div class="col-12 col-md-4 text-md-end">
                                <small class="text-muted d-block text-uppercase fs-8 fw-semibold mb-0.5">Hak Pemasukan KWT</small>
                                <span class="fw-bold text-success fs-5">Rp {{ number_format($subtotalKwt, 0, ',', '.') }}</span>
                            </div>

                            @if($sale->jadwal_pengiriman)
                            <div class="col-12 border-top pt-2 mt-2">
                                <small class="text-success d-block text-uppercase fs-8 fw-bold mb-0.5"><i class="bi bi-calendar-check-fill me-1"></i>Jadwal Logistik Admin</small>
                                <span class="small text-success fw-bold bg-success bg-opacity-10 px-2 py-1.5 rounded d-inline-block">
                                    <i class="bi bi-clock-history me-1"></i>Pesanan akan dijemput kurir pada: {{ \Carbon\Carbon::parse($sale->jadwal_pengiriman)->format('d F Y') }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-2">
                        <h6 class="fw-bold text-dark mb-2 fs-7 text-uppercase tracking-wider text-secondary"><i class="bi bi-basket3-fill text-success me-2"></i>Daftar Produk yang Harus Disiapkan</h6>
                        <div class="table-responsive rounded-3 border bg-white">
                            <table class="table align-middle mb-0 sm-table">
                                <thead class="table-light text-secondary fs-8 fw-bold text-uppercase">
                                    <tr>
                                        <th class="ps-3 py-2">Nama Produk KWT</th>
                                        <th class="py-2 text-center">Kuantitas</th>
                                        <th class="py-2 text-end pe-3">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kwtDetails as $detail)
                                    <tr>
                                        <td class="ps-3 py-2.5 fw-semibold text-dark">
                                            {{ $detail->product->nama_produk ?? 'Produk Terhapus' }}
                                        </td>
                                        <td class="text-center py-2.5 text-muted small fw-medium">
                                            {{ $detail->jumlah }} {{ $detail->product->satuan ?? 'Ikat/Kg' }}
                                        </td>
                                        <td class="text-end py-2.5 pe-3 fw-bold text-dark">
                                            Rp {{ number_format($detail->harga_saat_ini * $detail->jumlah, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 px-4 pb-4 bg-light bg-opacity-25 d-flex justify-content-between gap-2">
                    {{-- SISI KIRI: TOMBOL TOLAK --}}
                    <div>
                        @if($sale->status == 'menunggu' || $sale->status == 'diproses')
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold shadow-sm"
                            data-bs-toggle="modal" data-bs-target="#modalTolakPesananKwt{{ $sale->id }}">
                            <i class="bi bi-x-circle me-1"></i> Tolak (Stok Kosong)
                        </button>
                        @endif
                    </div>

                    {{-- SISI KANAN: TOMBOL AKSI --}}
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>

                        @if($sale->status === 'menunggu')
                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-4 fw-bold shadow-sm">
                            <i class="bi bi-check2-all me-1"></i> Stok Tersedia, Terima Pesanan!
                        </button>

                        @elseif($sale->status === 'diproses')
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 fs-8">
                            <i class="bi bi-hourglass-split me-1"></i> Menunggu Penjadwalan Admin
                        </span>

                        @elseif($sale->status === 'diantar')
                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-2 fs-8">
                            <i class="bi bi-truck me-1"></i> Sedang dalam perjalanan Kurir
                        </span>

                        @elseif($sale->status === 'selesai')
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fs-8">
                            <i class="bi bi-check-circle-fill me-1"></i> Pesanan Selesai
                        </span>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL FORM ALASAN PENOLAKAN PESANAN KWT --}}
<div class="modal fade" id="modalTolakPesananKwt{{ $sale->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0 p-4 pb-0">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Tolak Pesanan Ini</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{-- PERBAIKAN: ACTION MENGGUNAKAN ROUTE KWT --}}
            <form action="{{ route('kwt.orders.reject', $sale->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4 pt-2">
                    <p class="text-muted small mb-3">Jika produk gagal panen atau stok tidak memadai, tolak pesanan agar Admin dapat memproses pengembalian dana pembeli.</p>
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-secondary mb-1">Alasan Penolakan KWT</label>
                        <textarea name="alasan_tolak" class="form-control text-dark p-2.5 small bg-light" rows="3" placeholder="Contoh: Maaf, panen sawi minggu ini gagal dikarenakan cuaca..." required style="border-radius: 10px; resize: none;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 p-4 pt-0 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light border rounded-pill px-4 fw-bold small py-2" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalProsesKwt{{ $sale->id }}">Kembali</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold py-2" style="background: #dc2626; border:none;">
                        <i class="bi bi-trash3-fill me-1"></i> Tolak Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach

<style>
    .bg-gradient-success {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .fs-7 {
        font-size: 0.85rem !important;
    }

    .fs-8 {
        font-size: 0.75rem !important;
    }

    .tracking-wider {
        letter-spacing: 0.05em;
    }

    .transition-all {
        transition: all 0.3s ease;
    }

    .table-hover tbody tr:hover {
        background-color: #fcfdfe !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // LOGIKA SORTING TABEL
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

                rows.forEach(row => {
                    tbody.appendChild(row);
                });
            });
        });
    });
</script>
@endsection