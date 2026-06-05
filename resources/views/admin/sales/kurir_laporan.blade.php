@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4 no-print-container">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 no-print">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.kurir.pencairan') }}" class="btn btn-sm btn-light rounded-circle shadow-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h3 class="fw-bold text-dark mb-0">Laporan Penghasilan Kurir <strong>{{ $kurir->nama }}</strong>.</h3>
            </div>
            <p class="text-muted small mb-0">
                Pantau rincian pengiriman dan pendapatan bersih
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

    $jumlahSelesai = $orders->where('status', 'selesai')->count();

    // Cek berapa order selesai yang belum dicairkan (is_paid_out == false/0)
    $pendingCount = $orders->where('status', 'selesai')->where('is_paid_out', false)->count();

    // Variabel untuk trigger print (Anti Error)
    $printedIdsString = session()->has('printed_ids') && is_array(session('printed_ids'))
    ? implode(',', session('printed_ids'))
    : '';
    @endphp

    {{-- WIDGET STATISTIK --}}
    <div class="row g-3 mb-4 no-print">
        {{-- Total Pendapatan Kurir (100%) --}}
        <div class="col-12 col-md-6">
            <div class="card stat-card border-0 shadow-sm h-100 bg-gradient-success text-white">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="small fw-bold text-uppercase tracking-wider opacity-75">
                            Total Pendapatan (100% Hak Kurir)
                        </span>
                        <div class="stat-icon bg-white text-success rounded-3 shadow-sm">
                            <i class="bi bi-cash-stack fs-4 text-success"></i>
                        </div>
                    </div>
                    <h3 class="fw-extrabold mb-1" id="widget-ongkir">
                        Rp {{ number_format($totalOngkir, 0, ',', '.') }}
                    </h3>
                    <p class="small mb-0 opacity-75">Seluruh tarif ongkir tanpa potongan admin.</p>
                </div>
            </div>
        </div>

        {{-- Jumlah Pengiriman --}}
        <div class="col-12 col-md-6">
            <div class="card stat-card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="small fw-semibold text-muted text-uppercase tracking-wider">
                            Jumlah Trip / Order
                        </span>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary rounded-3">
                            <i class="bi bi-truck fs-4"></i>
                        </div>
                    </div>
                    <h3 class="fw-extrabold mb-1 text-dark" id="widget-jumlah-trip">
                        {{ $orders->count() }} <span class="fs-6 fw-normal text-muted">Pesanan</span>
                    </h3>
                    <p class="small mb-0 text-muted">
                        {{ $jumlahSelesai }} Selesai,
                        {{ $orders->count() - $jumlahSelesai }} Lainnya
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER DATA --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 no-print bg-white">
        <div class="card-body p-3 p-md-4">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-funnel text-success me-2"></i>Filter Laporan</h5>
            <form action="{{ route('admin.kurir.laporan', $kurir->id) }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold text-muted mb-2">Bulan</label>
                        <select name="month" class="form-select rounded-3 border-secondary-subtle py-2">
                            @for($m=1; $m<=12; $m++)
                                @php $mVal=str_pad($m, 2, '0' , STR_PAD_LEFT); @endphp
                                <option value="{{ $mVal }}" {{ request('month', date('m')) == $mVal ? 'selected' : '' }}>
                                {{ $bulanIndo[$mVal] }}
                                </option>
                                @endfor
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold text-muted mb-2">Tahun</label>
                        <select name="year" class="form-select rounded-3 border-secondary-subtle py-2">
                            @for($y=2024; $y<=max(2024, date('Y')); $y++)
                                <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>
                                {{ $y }}
                                </option>
                                @endfor
                        </select>
                    </div>
                    <div class="col-12 col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-success rounded-3 w-100 py-2 fw-semibold shadow-sm">
                            <i class="bi bi-filter me-1"></i> Terapkan
                        </button>
                        <a href="{{ route('admin.kurir.laporan', $kurir->id) }}" class="btn btn-outline-secondary rounded-3 py-2 px-3">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- TRIGGER PRINT --}}
    <div id="print-trigger-data" data-ids="{{ $printedIdsString }}" style="display: none;"></div>

    {{-- FORM & TABEL UTAMA --}}
    <form id="formCairkan" action="{{ route('admin.kurir.cairkan', $kurir->id) }}" method="POST">
        @csrf
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden no-print bg-white">
            <div class="card-header bg-white border-0 px-3 px-md-4 pt-4 pb-0 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-clock-history text-success me-2"></i>Riwayat Pengiriman
                </h5>

                {{-- 2 TOMBOL PROFESIONAL - Responsive --}}
                <div class="d-flex align-items-center gap-2 flex-wrap w-100 w-md-auto">
                    <button type="button" id="btn-cetak-terpilih" class="btn btn-warning btn-sm rounded-pill px-3 py-2 shadow-sm d-flex align-items-center gap-2 fw-bold flex-grow-1 flex-md-grow-0 justify-content-center" data-bs-toggle="modal" data-bs-target="#modalCairkan" disabled>
                        <i class="bi bi-wallet2"></i> Cetak Pilihan
                    </button>

                    <button type="button" id="btn-cetak-semua" class="btn btn-success btn-sm rounded-pill px-3 py-2 shadow-sm d-flex align-items-center gap-2 fw-bold flex-grow-1 flex-md-grow-0 justify-content-center" {{ $pendingCount == 0 ? 'disabled' : '' }}>
                        <i class="bi bi-cash-coin"></i> Cetak Semua
                    </button>
                </div>
            </div>

            {{-- MODAL KONFIRMASI --}}
            <div class="modal fade" id="modalCairkan" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header bg-success border-0 p-4">
                            <h5 class="modal-title fw-bold text-white"><i class="bi bi-check-circle me-2"></i>Konfirmasi Pencairan</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4 text-center">
                            <i class="bi bi-person-bounding-box text-success mb-3" style="font-size: 3rem;"></i>
                            <h4 class="fw-bold text-dark">{{ $kurir->nama }}</h4>
                            <p class="text-muted mb-0">Dana ongkos kirim akan dicairkan dan diberikan kepada kurir yang bersangkutan.</p>

                            <div class="alert alert-success bg-success-subtle mt-4 border-0 rounded-3 small mb-0 text-dark text-start">
                                <i class="bi bi-info-circle-fill text-success me-1"></i>
                                Data transaksi akan ditandai <strong>Sudah Cair</strong> secara permanen di database dan bukti penyerahan dana akan otomatis dicetak.
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0 justify-content-center flex-column flex-sm-row gap-2">
                            <button type="button" class="btn btn-light rounded-pill px-4 w-100 w-sm-auto" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success rounded-pill fw-bold px-4 px-sm-5 shadow-sm w-100 w-sm-auto">Proses & Cetak</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0 mt-3">
                {{-- Penambahan custom-scrollbar --}}
                <div class="table-responsive custom-scrollbar">
                    {{-- Penambahan text-nowrap --}}
                    <table class="table align-middle mb-0 table-hover text-nowrap" id="interactive-table">
                        <thead class="table-light text-uppercase tracking-wider fs-7">
                            <tr>
                                <th class="ps-4 py-3" style="width: 40px;" title="Pilih Semua yang Belum Cair">
                                    <input type="checkbox" id="check-all" class="form-check-input" {{ $pendingCount == 0 ? 'disabled' : '' }}>
                                </th>
                                <th class="py-3 sortable" data-sort="id" style="cursor: pointer;">
                                    Order ID <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i>
                                </th>
                                <th class="py-3">Customer</th>
                                <th class="py-3">Alamat Tujuan</th>
                                <th class="py-3 sortable" data-sort="date" style="cursor: pointer;">
                                    Tanggal Order <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i>
                                </th>
                                <th class="py-3 text-end">Tarif Ongkir</th>
                                <th class="py-3 text-center pe-4 sortable" data-sort="status" style="cursor: pointer;">
                                    Status / Pencairan <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            @php
                            $isCair = $order->is_paid_out == true;
                            $rowClass = $isCair ? 'bg-light opacity-75' : '';
                            @endphp
                            <tr class="data-row {{ $rowClass }}" data-id="{{ $order->id }}" data-date="{{ $order->created_at->timestamp }}" data-status-text="{{ $order->status }}">
                                <td class="ps-4 py-3">
                                    @if($isCair)
                                    <input type="checkbox" class="form-check-input bg-secondary border-secondary shadow-none order-checkbox-disabled" value="{{ $order->id }}" data-ongkir="{{ $order->ongkir }}" disabled checked style="cursor: not-allowed;">
                                    @else
                                    <input type="checkbox" name="order_ids[]" class="form-check-input order-checkbox shadow-sm border-secondary" value="{{ $order->id }}" data-ongkir="{{ $order->ongkir }}" {{ $order->status == 'selesai' ? '' : 'disabled' }} style="cursor: pointer;">
                                    @endif
                                </td>
                                <td class="py-3">
                                    <span class="fw-bold {{ $isCair ? 'text-secondary text-decoration-line-through' : 'text-success' }} font-monospace">#ORD-{{ $order->id }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="fw-semibold {{ $isCair ? 'text-muted' : 'text-dark' }}">{{ $order->user->name ?? 'Masyarakat' }}</div>
                                    <small class="{{ $isCair ? 'text-muted' : 'text-secondary' }} font-monospace">{{ $order->nomor_hp ?? $order->user->phone_number ?? '-' }}</small>
                                </td>
                                {{-- Penambahan min-width dan white-space normal agar alamat panjang bisa wrap ke bawah --}}
                                <td class="py-3" style="min-width: 200px; max-width: 250px; white-space: normal;">
                                    <div class="{{ $isCair ? 'text-muted' : 'text-dark' }} lh-sm" title="{{ $order->alamat }}">
                                        {{ Str::limit($order->alamat, 60) }}
                                    </div>
                                </td>
                                <td class="py-3 text-secondary small">
                                    {{ $order->created_at->format('d M Y, H:i') }} WIB
                                </td>
                                <td class="py-3 text-end fw-extrabold {{ $isCair ? 'text-muted' : 'text-success' }}">
                                    Rp {{ number_format($order->ongkir, 0, ',', '.') }}
                                </td>
                                <td class="py-3 text-center pe-4">
                                    @if($isCair)
                                    <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 fw-medium fs-8 shadow-sm"><i class="bi bi-check2-all me-1"></i>Sudah Cair</span>
                                    @elseif($order->status == 'selesai')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">Siap Cair</span>
                                    @elseif($order->status == 'diproses')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">Diproses</span>
                                    @elseif($order->status == 'diantar')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">Diantar</span>
                                    @elseif($order->status == 'batal')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">Batal</span>
                                    @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">{{ $order->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr class="empty-row">
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <div class="py-3">
                                        <i class="bi bi-inbox fs-2 mb-2 d-block opacity-50 text-success"></i>
                                        <span>Tidak ada data pengiriman.</span>
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

{{-- AREA INVOICE CETAKAN (Murni Hasil Generate Server) --}}
<div class="print-only">
    <div class="print-header d-flex justify-content-between align-items-center pb-4 mb-4 border-bottom border-2 border-dark">
        <div>
            <h2 class="fw-extrabold text-success mb-1">KWT DIGITAL CIBIRU</h2>
            <p class="small text-muted mb-0">Website Digital Marketing - Kelompok Wanita Tani Cibiru</p>
            <p class="small text-muted mb-0">Kec. Cibiru, Kota Bandung, Jawa Barat</p>
        </div>
        <div class="text-end">
            <h4 class="fw-bold text-dark mb-1">INVOICE PENCAIRAN KURIR</h4>
            <span class="badge bg-success text-uppercase py-1.5 px-3 rounded-pill text-white fw-bold">ARMADA INTERNAL</span>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-6">
            <h6 class="text-muted text-uppercase small fw-bold mb-2">Informasi Penerima / Kurir:</h6>
            <h5 class="fw-bold text-dark mb-1">{{ $kurir->nama }}</h5>
            <p class="mb-1"><strong>No. Handphone:</strong> {{ $kurir->no_hp }}</p>
            <p class="mb-0"><strong>Status Kurir:</strong> Aktif</p>
        </div>
        <div class="col-6 text-end">
            <h6 class="text-muted text-uppercase small fw-bold mb-2">Rincian Dokumen:</h6>
            <p class="mb-1"><strong>Periode Laporan:</strong> {{ $namaBulan }} {{ $year }}</p>
            <p class="mb-1"><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d F Y, H:i') }} WIB</p>
            <p class="mb-0"><strong>Dicetak Oleh:</strong> {{ Auth::user()->name ?? 'Administrator' }}</p>
        </div>
    </div>

    <div class="print-summary-box p-4 rounded-4 mb-4" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
        <h6 class="fw-bold text-uppercase small text-muted mb-3 border-bottom pb-2">Rangkuman Pencairan Dana</h6>
        <div class="row text-center">
            <div class="col-6 border-end">
                <small class="text-muted d-block mb-1">Total Trip / Order Dicairkan</small>
                <h4 class="fw-bold text-dark" id="print-jumlah-trip">0 Pesanan</h4>
            </div>
            <div class="col-6">
                <small class="text-muted d-block mb-1 fw-bold text-success">Total Dana Cair Bersih</small>
                <h3 class="fw-extrabold text-success" id="print-total-ongkir">Rp 0</h3>
            </div>
        </div>
    </div>

    <h6 class="fw-bold text-uppercase small text-muted mb-2"><i class="bi bi-list-check me-2"></i>Daftar Pengiriman Dicairkan</h6>
    <table class="print-table w-100 mb-5" id="print-data-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Nama Customer</th>
                <th>Tujuan Pengiriman</th>
                <th>Tanggal Order</th>
                <th class="text-end">Tarif Ongkir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr data-print-order-id="{{ $order->id }}" class="print-order-row" style="display: none;">
                <td class="font-monospace fw-bold">#ORD-{{ $order->id }}</td>
                <td>{{ $order->user->name ?? 'Masyarakat' }}</td>
                <td class="small">{{ $order->alamat }}</td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td class="text-end text-success fw-bold">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</td>
            </tr>
            @empty
            @endforelse
            <tr id="print-empty-row" style="display: none;">
                <td colspan="5" class="text-center py-4 text-muted">Belum ada data pencairan yang dicetak.</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="fw-bold bg-light">
                <td colspan="4" class="text-end py-2">TOTAL AKUMULASI DANA CAIR:</td>
                <td class="text-end text-success fs-6 py-2" id="print-foot-ongkir">Rp 0</td>
            </tr>
        </tfoot>
    </table>

    <div class="row mt-5 pt-3">
        <div class="col-4 text-center">
            <p class="mb-5 small text-muted">Kurir Penerima Dana,</p>
            <div class="mt-4 border-bottom border-dark w-75 mx-auto" style="height: 40px;"></div>
            <p class="fw-bold text-dark mt-1">{{ $kurir->nama }}</p>
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
    .bg-gradient-success {
        background: linear-gradient(135deg, #10b981, #059669);
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
        background-color: rgba(16, 185, 129, 0.02) !important;
    }

    /* CUSTOM SCROLLBAR UNTUK MOBILE */
    .custom-scrollbar::-webkit-scrollbar {
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    /* PRINT STYLING */
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

        // Event Tombol Cetak Semua (Otomatis centang yang belum cair & panggil modal)
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

        // FUNGSI RENDER CETAKAN OTOMATIS SETELAH SUBMIT
        const triggerDiv = document.getElementById('print-trigger-data');
        if (triggerDiv) {
            const idsString = triggerDiv.getAttribute('data-ids');

            // Jika ada string ID, berarti baru saja sukses mencairkan
            if (idsString && idsString.trim() !== '') {
                const printedIds = idsString.split(',').map(id => parseInt(id.trim(), 10));

                let totalOngkir = 0;
                let totalTripCount = 0;

                // Loop Semua checkbox di tabel (termasuk yang abu-abu agar terbaca valuenya)
                document.querySelectorAll('.order-checkbox, .order-checkbox-disabled').forEach(cb => {
                    const orderId = parseInt(cb.value, 10);
                    const printRow = document.querySelector(`.print-order-row[data-print-order-id="${orderId}"]`);

                    if (printedIds.includes(orderId)) {
                        const ongkir = parseFloat(cb.getAttribute('data-ongkir')) || 0;

                        totalTripCount++;
                        totalOngkir += ongkir;
                        if (printRow) printRow.style.display = 'table-row';
                    } else {
                        if (printRow) printRow.style.display = 'none';
                    }
                });

                // Terapkan hasil kalkulasi ke struk cetakan
                const pTrip = document.getElementById('print-jumlah-trip');
                const pOmzet = document.getElementById('print-total-ongkir');
                const pFoot = document.getElementById('print-foot-ongkir');
                const pEmpty = document.getElementById('print-empty-row');

                if (pTrip) pTrip.innerHTML = `${totalTripCount} Pesanan`;
                if (pOmzet) pOmzet.innerHTML = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalOngkir);
                if (pFoot) pFoot.innerHTML = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalOngkir);
                if (pEmpty) pEmpty.style.display = totalTripCount === 0 ? 'table-row' : 'none';

                // Trigger pop-up print otomatis
                setTimeout(() => {
                    window.print();
                }, 800);
            }
        }

        // LOGIKA SORTING TABEL
        let sortDirection = {
            id: false,
            date: false,
            status: false
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