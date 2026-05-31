@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4 no-print-container">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 no-print">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.kwt') }}" class="btn btn-sm btn-light rounded-circle shadow-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h3 class="fw-bold text-dark mb-0">Laporan Keuangan KWT</h3>
            </div>
            <p class="text-muted small mb-0">
                Laporan penjualan hasil panen dan komoditas tani untuk kelompok <strong>{{ $kwt->name }}</strong>.
            </p>
        </div>
    </div>

    @php
    $bulanIndo = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];
    $namaBulan = $bulanIndo[request('month', date('m'))] ?? date('F', mktime(0, 0, 0, date('m'), 1));

    $totalProdukTerjual = 0;
    $totalPendapatan = 0;
    $pendingCount = 0;

    foreach($orders->where('status', 'selesai') as $order) {
    $kwtDetails = $order->details->filter(function($d) use ($kwt) {
    return $d->product && $d->product->user_id == $kwt->id;
    });
    $totalProdukTerjual += $kwtDetails->sum('jumlah');
    $totalPendapatan += $kwtDetails->sum(function($d) {
    return $d->harga_saat_ini * $d->jumlah;
    });

    $isCair = $kwtDetails->isNotEmpty() && $kwtDetails->every(fn($d) => $d->is_cair_kwt == true);
    if (!$isCair && $kwtDetails->isNotEmpty()) {
    $pendingCount++;
    }
    }

    $jumlahSelesai = $orders->where('status', 'selesai')->count();
    $printedIdsString = session()->has('printed_ids') && is_array(session('printed_ids'))
    ? implode(',', session('printed_ids'))
    : '';
    @endphp

    {{-- WIDGET STATISTIK --}}
    <div class="row g-3 mb-4 no-print">
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card stat-card border-0 shadow-sm h-100 bg-gradient-emerald text-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="small fw-bold text-uppercase tracking-wider opacity-75">Total Omzet Panen (Selesai)</span>
                        <div class="stat-icon bg-white text-success rounded-3 shadow-sm"><i class="bi bi-cash-coin fs-4 text-success"></i></div>
                    </div>
                    <h3 class="fw-extrabold mb-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                    <p class="small mb-0 opacity-75">Pendapatan kotor dari order selesai</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card stat-card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="small fw-semibold text-muted text-uppercase tracking-wider">Produk Panen Terjual</span>
                        <div class="stat-icon bg-success bg-opacity-10 text-success rounded-3"><i class="bi bi-box-seam fs-4"></i></div>
                    </div>
                    <h3 class="fw-extrabold mb-1 text-dark">{{ $totalProdukTerjual }} <span class="fs-6 fw-normal text-muted">Item / Pack</span></h3>
                    <p class="small mb-0 text-muted">Berdasarkan pesanan berstatus selesai</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card stat-card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="small fw-semibold text-muted text-uppercase tracking-wider">Jumlah Order (Semua Status)</span>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary rounded-3"><i class="bi bi-receipt-cutoff fs-4"></i></div>
                    </div>
                    <h3 class="fw-extrabold mb-1 text-dark">{{ $orders->count() }} <span class="fs-6 fw-normal text-muted">Pesanan</span></h3>
                    <p class="small mb-0 text-muted">{{ $jumlahSelesai }} Selesai, {{ $orders->count() - $jumlahSelesai }} Lainnya</p>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER DATA --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 no-print bg-white">
        <div class="card-body p-4">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-funnel text-success me-2"></i>Filter Laporan KWT</h5>
            <form action="{{ route('admin.kwt.laporan', $kwt->id) }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold text-muted mb-2">Bulan</label>
                        <select name="month" class="form-select rounded-3 border-secondary-subtle py-2">
                            @for($m=1; $m<=12; $m++)
                                @php $mVal=str_pad($m, 2, '0' , STR_PAD_LEFT); @endphp
                                <option value="{{ $mVal }}" {{ request('month', date('m')) == $mVal ? 'selected' : '' }}>{{ $bulanIndo[$mVal] }}</option>
                                @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted mb-2">Tahun</label>
                        <select name="year" class="form-select rounded-3 border-secondary-subtle py-2">
                            @for($y=2024; $y<=max(2024, date('Y')); $y++)
                                <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-success rounded-3 w-100 py-2 fw-semibold shadow-sm"><i class="bi bi-filter me-1"></i> Terapkan</button>
                        <a href="{{ route('admin.kwt.laporan', $kwt->id) }}" class="btn btn-outline-secondary rounded-3 py-2 px-3"><i class="bi bi-arrow-clockwise"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div id="print-trigger-data" data-ids="{{ $printedIdsString }}" style="display: none;"></div>

    {{-- FORM & TABEL UTAMA --}}
    <form id="formCairkan" action="{{ route('admin.kwt.cairkan', $kwt->id) }}" method="POST">
        @csrf
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden no-print bg-white">
            <div class="card-header bg-white border-0 px-4 pt-4 pb-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-shop text-success me-2"></i>Riwayat Transaksi Hasil Tani
                </h5>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" id="btn-cetak-terpilih" class="btn btn-warning btn-sm rounded-pill px-3 py-2 shadow-sm d-flex align-items-center gap-2 fw-bold" data-bs-toggle="modal" data-bs-target="#modalCairkan" disabled>
                        <i class="bi bi-wallet2"></i> Cairkan & Cetak Pilihan
                    </button>
                    <button type="button" id="btn-cetak-semua" class="btn btn-success btn-sm rounded-pill px-3 py-2 shadow-sm d-flex align-items-center gap-2 fw-bold" {{ $pendingCount == 0 ? 'disabled' : '' }}>
                        <i class="bi bi-cash-coin"></i> Cairkan & Cetak Semua
                    </button>
                </div>
            </div>

            {{-- MODAL KONFIRMASI --}}
            <div class="modal fade" id="modalCairkan" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header bg-success border-0 p-4">
                            <h5 class="modal-title fw-bold text-white"><i class="bi bi-check-circle me-2"></i>Konfirmasi Pencairan Dana</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <label class="form-label fw-semibold text-dark">Nama Perwakilan Penerima Dana:</label>
                            <input type="text" name="nama_penerima" class="form-control form-control-lg bg-light rounded-3" required placeholder="Contoh: Ibu Ani / Pengurus KWT">
                            <div class="alert alert-success bg-success-subtle mt-3 border-0 rounded-3 small mb-0 text-dark">
                                <i class="bi bi-info-circle-fill text-success me-1"></i>
                                Data akan ditandai <strong>Sudah Cair</strong> di database dan Bukti Pencairan akan otomatis dicetak.
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success rounded-pill fw-bold px-4 shadow-sm">Simpan & Cetak PDF</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0 mt-3">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover" id="interactive-table">
                        <thead class="table-light text-uppercase tracking-wider fs-7">
                            <tr>
                                <th class="ps-4 py-3" style="width: 40px;" title="Pilih Semua yang Belum Cair">
                                    <input type="checkbox" id="check-all" class="form-check-input" {{ $pendingCount == 0 ? 'disabled' : '' }}>
                                </th>
                                <th class="py-3 sortable" data-sort="id" style="cursor: pointer;">Order ID <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i></th>
                                <th class="py-3">Customer</th>
                                <th class="py-3">Detail Hasil Panen</th>
                                <th class="py-3 text-end sortable" data-sort="subtotal" style="cursor: pointer;">Subtotal <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i></th>
                                <th class="py-3 text-center sortable" data-sort="status" style="cursor: pointer;">Status / Penerima <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i></th>
                                <th class="py-3 text-center pe-4 sortable" data-sort="date" style="cursor: pointer;">Tanggal <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            @php
                            $kwtDetails = $order->details->filter(function($detail) use ($kwt) {
                            return $detail->product && $detail->product->user_id == $kwt->id;
                            });
                            $subtotalKwt = $kwtDetails->sum(function($detail) {
                            return $detail->harga_saat_ini * $detail->jumlah;
                            });

                            $isCair = $kwtDetails->isNotEmpty() && $kwtDetails->every(fn($d) => $d->is_cair_kwt == true);
                            $rowClass = $isCair ? 'bg-light opacity-75' : '';

                            $namaPenerimaDb = $isCair ? $kwtDetails->first()->nama_penerima_cair : null;
                            @endphp

                            <tr class="data-row {{ $rowClass }}" data-id="{{ $order->id }}" data-date="{{ $order->created_at->timestamp }}" data-status-text="{{ $order->status }}" data-subtotal="{{ $subtotalKwt }}">
                                <td class="ps-4 py-3">
                                    @if($isCair)
                                    {{-- PERBAIKAN: Menambahkan value dan data attributes agar Javascript tetap bisa membaca data ini saat dicetak otomatis --}}
                                    <input type="checkbox" class="form-check-input bg-secondary border-secondary shadow-none order-checkbox-disabled" value="{{ $order->id }}" data-subtotal-kwt="{{ $subtotalKwt }}" data-items-count="{{ $kwtDetails->sum('jumlah') }}" disabled checked style="cursor: not-allowed;">
                                    @else
                                    <input type="checkbox" name="order_ids[]" class="form-check-input order-checkbox shadow-sm border-secondary" value="{{ $order->id }}" data-subtotal-kwt="{{ $subtotalKwt }}" data-items-count="{{ $kwtDetails->sum('jumlah') }}" {{ $order->status == 'selesai' ? '' : 'disabled' }} style="cursor: pointer;">
                                    @endif
                                </td>
                                <td class="py-3">
                                    <span class="fw-bold {{ $isCair ? 'text-secondary text-decoration-line-through' : 'text-success' }} font-monospace">#ORD-{{ $order->id }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="fw-semibold {{ $isCair ? 'text-muted' : 'text-dark' }}">{{ $order->user->name ?? 'Masyarakat' }}</div>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($kwtDetails as $d)
                                        <span class="badge bg-white {{ $isCair ? 'text-muted border-secondary' : 'text-dark border-secondary-subtle' }} border px-2 py-1 rounded small">
                                            {{ $d->product->nama_produk ?? 'Produk' }} (x{{ $d->jumlah }})
                                        </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-3 text-end fw-extrabold {{ $isCair ? 'text-muted' : 'text-success' }}">
                                    Rp {{ number_format($subtotalKwt, 0, ',', '.') }}
                                </td>
                                <td class="py-3 text-center">
                                    @if($isCair)
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="badge bg-secondary text-white rounded-pill px-3 py-1 fw-medium fs-8 shadow-sm mb-1"><i class="bi bi-check2-all me-1"></i>Sudah Cair</span>
                                        <small class="text-muted fw-bold" style="font-size: 0.70rem;"><i class="bi bi-person-fill"></i> {{ $namaPenerimaDb ?? '-' }}</small>
                                    </div>
                                    @elseif($order->status == 'selesai')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">Siap Cair</span>
                                    @elseif($order->status == 'batal')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">Batal</span>
                                    @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">{{ $order->status }}</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center pe-4 text-secondary small">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>
                            </tr>
                            @empty
                            <tr class="empty-row">
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <div class="py-3">
                                        <i class="bi bi-inbox fs-2 mb-2 d-block opacity-50 text-success"></i>
                                        <span>Belum ada transaksi untuk kelompok tani ini.</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- AREA CETAKAN --}}
<div class="print-only">
    <div class="print-header d-flex justify-content-between align-items-center pb-4 mb-4 border-bottom border-2 border-dark">
        <div>
            <h2 class="fw-extrabold text-success mb-1">KWT DIGITAL CIBIRU</h2>
            <p class="small text-muted mb-0">Website Digital Marketing - Kelompok Wanita Tani Cibiru</p>
            <p class="small text-muted mb-0">Kec. Cibiru, Kota Bandung, Jawa Barat</p>
        </div>
        <div class="text-end">
            <h4 class="fw-bold text-dark mb-1">BUKTI PENCAIRAN DANA</h4>
            <span class="badge bg-success text-uppercase py-1.5 px-3 rounded-pill text-white fw-bold">MITRA PRODUSEN TANI</span>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-6">
            <h6 class="text-muted text-uppercase small fw-bold mb-2">Kelompok Wanita Tani (KWT):</h6>
            <h5 class="fw-bold text-dark mb-1">{{ $kwt->name }}</h5>
            <p class="mb-1"><strong>Email KWT:</strong> {{ $kwt->email }}</p>
            <p class="mb-0"><strong>No. Telepon Pengurus:</strong> {{ $kwt->phone_number ?? '-' }}</p>
        </div>
        <div class="col-6 text-end">
            <h6 class="text-muted text-uppercase small fw-bold mb-2">Rincian Dokumen:</h6>
            <p class="mb-1"><strong>Periode Laporan:</strong> {{ $namaBulan }} {{ $year }}</p>
            <p class="mb-1"><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d F Y, H:i') }} WIB</p>
            <p class="mb-0"><strong>Dicetak Oleh:</strong> {{ Auth::user()->name ?? 'Administrator' }}</p>
        </div>
    </div>

    <div class="print-summary-box p-4 rounded-4 mb-4" style="background-color: #f0fdf4; border: 1px solid #c6f6d5;">
        <h6 class="fw-bold text-uppercase small text-success mb-3 border-bottom pb-2" style="border-bottom-color: #c6f6d5 !important;">Ringkasan Pencairan KWT</h6>
        <div class="row text-center">
            <div class="col-4 border-end" style="border-end-color: #c6f6d5 !important;">
                <small class="text-muted d-block mb-1">Total Hasil Panen Terjual</small>
                <h4 class="fw-bold text-dark" id="print-total-terjual">0 Item / Pack</h4>
            </div>
            <div class="col-4 border-end" style="border-end-color: #c6f6d5 !important;">
                <small class="text-muted d-block mb-1">Transaksi Sukses Dicairkan</small>
                <h4 class="fw-bold text-dark" id="print-order-count">0 Order</h4>
            </div>
            <div class="col-4">
                <small class="text-muted d-block mb-1 fw-bold text-success">Total Dana Cair Bersih</small>
                <h3 class="fw-extrabold text-success" id="print-total-omzet">Rp 0</h3>
            </div>
        </div>
    </div>

    <h6 class="fw-bold text-uppercase small text-muted mb-2"><i class="bi bi-list-check me-2"></i>Rincian Transaksi Dicairkan</h6>
    <table class="print-table w-100 mb-5" id="print-data-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Pembeli</th>
                <th>Komoditas Hasil Panen</th>
                <th>Tanggal Order</th>
                <th class="text-end">Jumlah</th>
                <th class="text-end">Subtotal Cair</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            @php
            $kwtDetails = $order->details->filter(function($detail) use ($kwt) {
            return $detail->product && $detail->product->user_id == $kwt->id;
            });
            $subtotalKwt = $kwtDetails->sum(function($detail) {
            return $detail->harga_saat_ini * $detail->jumlah;
            });
            @endphp
            <tr data-print-order-id="{{ $order->id }}" class="print-order-row" style="display: none;">
                <td class="font-monospace fw-bold">#ORD-{{ $order->id }}</td>
                <td>{{ $order->user->name ?? 'Masyarakat' }}</td>
                <td>
                    @foreach($kwtDetails as $d)
                    <div>- {{ $d->product->nama_produk ?? 'Produk' }} (x{{ $d->jumlah }})</div>
                    @endforeach
                </td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td class="text-end">{{ $kwtDetails->sum('jumlah') }}</td>
                <td class="text-end fw-bold text-success">Rp {{ number_format($subtotalKwt, 0, ',', '.') }}</td>
            </tr>
            @empty
            @endforelse
            <tr id="print-empty-row" style="display: none;">
                <td colspan="6" class="text-center py-4 text-muted">Belum ada data pencairan yang dicetak.</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="fw-bold bg-light">
                <td colspan="5" class="text-end py-2">TOTAL AKUMULASI DANA CAIR:</td>
                <td class="text-end text-success fs-5 py-2" id="print-foot-omzet">Rp 0</td>
            </tr>
        </tfoot>
    </table>

    <div class="row mt-5 pt-3">
        <div class="col-4 text-center">
            <p class="mb-5 small text-muted">Penerima Dana (KWT),</p>
            <div class="mt-4 border-bottom border-dark w-75 mx-auto" style="height: 40px;"></div>
            <p class="fw-bold text-dark mt-1" id="signature-penerima-name">{{ session('penerima') ?? $kwt->name }}</p>
        </div>
        <div class="col-4"></div>
        <div class="col-4 text-center">
            <p class="mb-5 small text-muted">Mengetahui, Admin Keuangan</p>
            <div class="mt-4 border-bottom border-dark w-75 mx-auto" style="height: 40px;"></div>
            <p class="fw-bold text-dark mt-1">{{ Auth::user()->name }}</p>
        </div>
    </div>
</div>

<style>
    .bg-gradient-emerald {
        background: linear-gradient(135deg, #059669, #047857);
    }

    .stat-card {
        border-radius: 20px;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .fw-extrabold {
        font-weight: 800;
    }

    .fs-7 {
        font-size: 0.78rem !important;
    }

    .fs-8 {
        font-size: 0.72rem !important;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(5, 150, 105, 0.02) !important;
    }

    .print-only {
        display: none;
    }

    @media print {
        body {
            background: #ffffff !important;
            color: #000000 !important;
            font-size: 11px !important;
        }

        .sidebar,
        .logout-section,
        .mobile-header,
        .no-print,
        .no-print-container {
            display: none !important;
        }

        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
            background: none !important;
        }

        .print-only {
            display: block !important;
            padding: 20px !important;
        }

        .print-table {
            border-collapse: collapse !important;
            width: 100% !important;
            margin-top: 15px;
        }

        .print-table th,
        .print-table td {
            border: 1px solid #dee2e6 !important;
            padding: 8px !important;
            text-align: left;
        }

        .print-table th {
            background-color: #f8f9fa !important;
            color: #333 !important;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }

        .print-table tfoot td {
            border-top: 2px solid #333 !important;
            font-weight: bold;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkAll = document.getElementById('check-all');
        const checkboxes = document.querySelectorAll('.order-checkbox');
        const btnCetakTerpilih = document.getElementById('btn-cetak-terpilih');
        const btnCetakSemua = document.getElementById('btn-cetak-semua');

        function toggleProsesButton() {
            if (!btnCetakTerpilih) return;
            const hasChecked = Array.from(checkboxes).some(cb => cb.checked && !cb.disabled);
            btnCetakTerpilih.disabled = !hasChecked;
        }

        if (checkAll) {
            checkAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    if (!cb.disabled) cb.checked = checkAll.checked;
                });
                toggleProsesButton();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                if (checkAll) {
                    const enabledCheckboxes = Array.from(checkboxes).filter(chk => !chk.disabled);
                    const checkedCount = enabledCheckboxes.filter(chk => chk.checked).length;
                    checkAll.checked = (checkedCount === enabledCheckboxes.length && enabledCheckboxes.length > 0);
                }
                toggleProsesButton();
            });
        });

        if (btnCetakSemua) {
            btnCetakSemua.addEventListener('click', function() {
                checkboxes.forEach(cb => {
                    if (!cb.disabled) cb.checked = true;
                });
                if (checkAll) checkAll.checked = true;
                toggleProsesButton();

                var modal = new bootstrap.Modal(document.getElementById('modalCairkan'));
                modal.show();
            });
        }

        const triggerDiv = document.getElementById('print-trigger-data');
        if (triggerDiv) {
            const idsString = triggerDiv.getAttribute('data-ids');

            if (idsString && idsString.trim() !== '') {
                const printedIds = idsString.split(',').map(id => parseInt(id.trim(), 10));

                let totalOmzet = 0;
                let totalTerjualCount = 0;
                let totalTripCount = 0;

                // Membaca SEMUA checkbox termasuk yang sudah disabled
                document.querySelectorAll('.order-checkbox, .order-checkbox-disabled').forEach(cb => {
                    const orderId = parseInt(cb.value, 10);
                    const printRow = document.querySelector(`.print-order-row[data-print-order-id="${orderId}"]`);

                    if (printedIds.includes(orderId)) {
                        const subtotalKwt = parseFloat(cb.getAttribute('data-subtotal-kwt')) || 0;
                        const itemsCount = parseInt(cb.getAttribute('data-items-count')) || 0;

                        totalTripCount++;
                        totalOmzet += subtotalKwt;
                        totalTerjualCount += itemsCount;
                        if (printRow) printRow.style.display = 'table-row';
                    } else {
                        if (printRow) printRow.style.display = 'none';
                    }
                });

                const pTerjual = document.getElementById('print-total-terjual');
                const pCount = document.getElementById('print-order-count');
                const pOmzet = document.getElementById('print-total-omzet');
                const pFoot = document.getElementById('print-foot-omzet');
                const pEmpty = document.getElementById('print-empty-row');

                if (pTerjual) pTerjual.innerHTML = `${totalTerjualCount} Item / Pack`;
                if (pCount) pCount.innerHTML = `${totalTripCount} Order`;
                if (pOmzet) pOmzet.innerHTML = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalOmzet);
                if (pFoot) pFoot.innerHTML = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalOmzet);
                if (pEmpty) pEmpty.style.display = totalTripCount === 0 ? 'table-row' : 'none';

                setTimeout(() => {
                    window.print();
                }, 800);
            }
        }

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
                const printTbody = document.querySelector('#print-data-table tbody');
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
                    const orderId = row.getAttribute('data-id');
                    const printRow = printTbody.querySelector(`.print-order-row[data-print-order-id="${orderId}"]`);
                    if (printRow) printTbody.appendChild(printRow);
                });
            });
        });
    });
</script>
@endsection