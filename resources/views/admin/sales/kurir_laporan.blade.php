@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4 no-print-container">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 no-print">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.kurir.index') }}" class="btn btn-sm btn-light rounded-circle shadow-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h3 class="fw-bold text-dark mb-0">Laporan Penghasilan Kurir</h3>
            </div>
            <p class="text-muted small mb-0">
                Pantau rincian pengiriman dan pendapatan bersih dari kurir <strong>{{ $kurir->nama }}</strong>.
            </p>
        </div>
    </div>

    @php
    $bulanIndo = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];
    $namaBulan = $bulanIndo[$month] ?? date('F', mktime(0, 0, 0, $month, 1));
    $jumlahSelesai = $orders->where('status', 'selesai')->count();
    @endphp

    {{-- WIDGET STATISTIK --}}
    <div class="row g-3 mb-4 no-print">

        {{-- Total Pendapatan Kurir (100%) --}}
        <div class="col-12 col-md-6">
            <div class="card stat-card border-0 shadow-sm h-100 bg-gradient-success text-white">
                <div class="card-body p-4">
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
                <div class="card-body p-4">
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
        <div class="card-body p-4">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-funnel text-success me-2"></i>Filter Laporan</h5>
            <form action="{{ route('admin.kurir.laporan', $kurir->id) }}" method="GET">
                <div class="row g-3 align-items-end">

                    {{-- BULAN --}}
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted mb-2">Bulan</label>
                        <select name="month" class="form-select rounded-3 border-secondary-subtle py-2">
                            @for($m=5; $m<=12; $m++)
                                @php $mVal=str_pad($m, 2, '0' , STR_PAD_LEFT); @endphp
                                <option value="{{ $mVal }}" {{ $month == $mVal ? 'selected' : '' }}>
                                {{ $bulanIndo[$mVal] }}
                                </option>
                                @endfor
                        </select>
                    </div>

                    {{-- TAHUN --}}
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted mb-2">Tahun</label>
                        <select name="year" class="form-select rounded-3 border-secondary-subtle py-2">
                            @for($y=2026; $y<=max(2026, date('Y')); $y++)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                                </option>
                                @endfor
                        </select>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="col-md-4 d-flex gap-2">
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

    {{-- CARD TABEL INTERAKTIF --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden no-print bg-white">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-clock-history text-success me-2"></i>Riwayat Pengiriman
            </h5>

            {{-- KELOMPOK TOMBOL CETAK & BADGE --}}
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="badge bg-light text-success border px-3 py-2 rounded-pill fw-semibold">
                    {{ $orders->count() }} Data Ditemukan
                </span>

                <button id="btn-cetak-terpilih" onclick="triggerPrint('terpilih')"
                    class="btn btn-success btn-sm rounded-pill px-3 py-2 shadow-sm d-flex align-items-center gap-2">
                    <i class="bi bi-printer-fill"></i>
                    Cetak Terpilih
                </button>

                <button id="btn-cetak-semua" onclick="triggerPrint('semua')"
                    class="btn btn-outline-success btn-sm rounded-pill px-3 py-2 shadow-sm d-flex align-items-center gap-2"
                    {{ $jumlahSelesai == 0 ? 'disabled' : '' }}>
                    <i class="bi bi-printer"></i>
                    Cetak Semua
                </button>
            </div>
        </div>

        <div class="card-body p-0 mt-3">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover" id="interactive-table">
                    <thead class="table-light text-uppercase tracking-wider fs-7">
                        <tr>
                            <th class="ps-4 py-3" style="width: 40px;"><input type="checkbox" id="check-all" class="form-check-input" {{ $jumlahSelesai == 0 ? 'disabled' : 'checked' }}></th>
                            <th class="py-3 sortable" data-sort="id" style="cursor: pointer;" title="Klik untuk mengurutkan">
                                Order ID <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i>
                            </th>
                            <th class="py-3">Customer</th>
                            <th class="py-3">Alamat Tujuan</th>
                            <th class="py-3 sortable" data-sort="date" style="cursor: pointer;" title="Klik untuk mengurutkan">
                                Tanggal Transaksi <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i>
                            </th>
                            <th class="py-3 text-end">Tarif Ongkir</th>
                            <th class="py-3 text-center pe-4 sortable" data-sort="status" style="cursor: pointer;" title="Klik untuk mengurutkan">
                                Status <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="data-row" data-id="{{ $order->id }}" data-date="{{ $order->created_at->timestamp }}" data-status-text="{{ $order->status }}">
                            <td class="ps-4 py-3">
                                {{-- MATIKAN CHECKBOX JIKA STATUS BUKAN SELESAI --}}
                                <input type="checkbox" class="form-check-input order-checkbox" value="{{ $order->id }}" data-status="{{ $order->status }}" data-ongkir="{{ $order->ongkir }}" {{ $order->status == 'selesai' ? 'checked' : 'disabled' }}>
                            </td>
                            <td class="py-3">
                                <span class="fw-bold text-success font-monospace">#{{ $order->id }}</span>
                            </td>
                            <td class="py-3">
                                <div class="fw-semibold text-dark">{{ $order->user->name ?? 'Masyarakat' }}</div>
                                <small class="text-muted font-monospace">{{ $order->nomor_hp ?? $order->user->phone_number ?? '-' }}</small>
                            </td>
                            <td class="py-3" style="max-width: 250px;">
                                <div class="text-truncate" title="{{ $order->alamat }}">
                                    {{ $order->alamat }}
                                </div>
                            </td>
                            <td class="py-3 text-secondary small">
                                {{ $order->created_at->format('d M Y, H:i') }} WIB
                            </td>
                            <td class="py-3 text-end fw-bold text-success">
                                Rp {{ number_format($order->ongkir, 0, ',', '.') }}
                            </td>
                            <td class="py-3 text-center pe-4">
                                @if($order->status == 'selesai')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-bold text-uppercase fs-8">Selesai</span>
                                @elseif($order->status == 'diproses')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold text-uppercase fs-8">Diproses</span>
                                @elseif($order->status == 'diantar')
                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-1 fw-bold text-uppercase fs-8">Diantar</span>
                                @elseif($order->status == 'batal')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1 fw-bold text-uppercase fs-8">Batal</span>
                                @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1 fw-bold text-uppercase fs-8">{{ $order->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr class="empty-row">
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="py-3">
                                    <i class="bi bi-inbox fs-2 mb-2 d-block opacity-50 text-success"></i>
                                    <span>Tidak ada data pengiriman untuk bulan ini.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- AREA INVOICE CETAKAN (Hanya Muncul saat Print) --}}
<div class="print-only">

    {{-- KOP SURAT / HEADER DOKUMEN --}}
    <div class="print-header d-flex justify-content-between align-items-center pb-4 mb-4 border-bottom border-2">
        <div>
            <h2 class="fw-extrabold text-success mb-1">KWT DIGITAL CIBIRU</h2>
            <p class="small text-muted mb-0">Website Digital Marketing - Kelompok Wanita Tani Cibiru</p>
            <p class="small text-muted mb-0">Kec. Cibiru, Kota Bandung, Jawa Barat</p>
        </div>
        <div class="text-end">
            <h4 class="fw-bold text-dark mb-1">INVOICE PENGHASILAN</h4>
            <span class="badge bg-success text-uppercase py-1.5 px-3 rounded-pill text-white fw-bold">KURIR INTERNAL</span>
        </div>
    </div>

    {{-- DETAIL MITRA --}}
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
            <p class="mb-1"><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</p>
            <p class="mb-0"><strong>Dicetak Oleh:</strong> Administrator</p>
        </div>
    </div>

    {{-- KELOLA TOTAL BIAYA --}}
    <div class="print-summary-box p-4 rounded-4 mb-4" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
        <h6 class="fw-bold text-uppercase small text-muted mb-3 border-bottom pb-2">Rangkuman Keuangan Periode Ini</h6>
        <div class="row text-center">
            <div class="col-6 border-end">
                <small class="text-muted d-block mb-1">Total Trip / Pengiriman</small>
                <h4 class="fw-bold text-dark" id="print-jumlah-trip">0 Pesanan</h4>
            </div>
            <div class="col-6">
                <small class="text-muted d-block mb-1 fw-bold text-success">Pendapatan Bersih Kurir (100%)</small>
                <h3 class="fw-extrabold text-success" id="print-total-ongkir">Rp 0</h3>
            </div>
        </div>
    </div>

    {{-- TABEL DATA TRANSAKSI --}}
    <h6 class="fw-bold text-uppercase small text-muted mb-2"><i class="bi bi-list-check me-2"></i>Daftar Pengiriman Tercetak</h6>
    <table class="print-table w-100 mb-5" id="print-data-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Nama Customer</th>
                <th>Tujuan Pengiriman</th>
                <th>Tanggal Order</th>
                <th>Status</th>
                <th class="text-end">Tarif Ongkir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr data-print-order-id="{{ $order->id }}" class="print-order-row" style="display: none;">
                <td class="font-monospace fw-bold">#{{ $order->id }}</td>
                <td>{{ $order->user->name ?? 'Masyarakat' }}</td>
                <td class="small">{{ $order->alamat }}</td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td class="text-uppercase">{{ $order->status }}</td>
                <td class="text-end text-success fw-bold">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr id="print-empty-row">
                <td colspan="6" class="text-center py-4 text-muted">Belum ada pengiriman untuk periode ini.</td>
            </tr>
            @endforelse

            @if($orders->isNotEmpty())
            <tr id="print-empty-row" style="display: none;">
                <td colspan="6" class="text-center py-4 text-muted">Belum ada data pengiriman yang dipilih.</td>
            </tr>
            @endif
        </tbody>
        <tfoot>
            <tr class="fw-bold bg-light">
                <td colspan="5" class="text-end">Total Kumulatif Pendapatan:</td>
                <td class="text-end text-success fs-6" id="print-foot-ongkir">Rp 0</td>
            </tr>
        </tfoot>
    </table>

    {{-- TANDA TANGAN --}}
    <div class="row mt-5 pt-3">
        <div class="col-4 text-center">
            <p class="mb-5 small text-muted">Kurir Penerima,</p>
            <div class="mt-4 border-bottom w-75 mx-auto" style="height: 40px;"></div>
            <p class="fw-bold text-dark mt-1">{{ $kurir->nama }}</p>
        </div>
        <div class="col-4"></div>
        <div class="col-4 text-center">
            <p class="mb-5 small text-muted">Mengetahui, Admin KWT</p>
            <div class="mt-4 border-bottom w-75 mx-auto" style="height: 40px;"></div>
            <p class="fw-bold text-dark mt-1">{{ Auth::user()->name }}</p>
        </div>
    </div>

</div>

<style>
    /* Gradient stats card style */
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

        /* Sembunyikan seluruh sidebar admin & UI */
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

    .bg-info-subtle {
        background-color: #e0f7fa !important;
        color: #00838f !important;
    }

    .border-info-subtle {
        border-color: #b2ebf2 !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkAll = document.getElementById('check-all');
        const checkboxes = document.querySelectorAll('.order-checkbox');
        const btnCetakTerpilih = document.getElementById('btn-cetak-terpilih');

        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0
            }).format(Math.round(number));
        }

        // Fungsi khusus untuk Update Layout Cetakan
        function updatePrintLayout(totalOngkir, totalTripCount) {
            const printTotalOngkir = document.getElementById('print-total-ongkir');
            const printFootOngkir = document.getElementById('print-foot-ongkir');
            const printJumlahTrip = document.getElementById('print-jumlah-trip');

            if (printTotalOngkir) printTotalOngkir.innerHTML = formatRupiah(totalOngkir);
            if (printFootOngkir) printFootOngkir.innerHTML = formatRupiah(totalOngkir);
            if (printJumlahTrip) printJumlahTrip.innerHTML = totalTripCount + ' Pesanan';

            const printEmptyRow = document.getElementById('print-empty-row');
            if (printEmptyRow) {
                printEmptyRow.style.display = (totalTripCount === 0) ? 'table-row' : 'none';
            }
        }

        // Hitung total dari checkbox yang sedang dicentang saat ini
        function updateTotals() {
            let totalOngkir = 0;
            let totalTripCount = 0;

            checkboxes.forEach(cb => {
                const ongkir = parseFloat(cb.getAttribute('data-ongkir')) || 0;
                const orderId = cb.value;

                const printRow = document.querySelector(`.print-order-row[data-print-order-id="${orderId}"]`);

                if (cb.checked && !cb.disabled) {
                    totalTripCount++;
                    totalOngkir += ongkir;
                    if (printRow) printRow.style.display = 'table-row';
                } else {
                    if (printRow) printRow.style.display = 'none';
                }
            });

            // Aktifkan / Nonaktifkan tombol cetak terpilih
            if (btnCetakTerpilih) {
                btnCetakTerpilih.disabled = (totalTripCount === 0);
            }

            // Widget di Layar (Kalkulasi Berdasarkan yang dicentang)
            const widgetOngkir = document.getElementById('widget-ongkir');
            const widgetJumlahTrip = document.getElementById('widget-jumlah-trip');

            if (widgetOngkir) widgetOngkir.innerHTML = formatRupiah(totalOngkir);
            if (widgetJumlahTrip) {
                widgetJumlahTrip.innerHTML = `${totalTripCount} <span class="fs-6 fw-normal text-muted">Terpilih</span>`;
            }

            // Update Total pada Layout Print sesuai dengan checkbox
            updatePrintLayout(totalOngkir, totalTripCount);
        }

        // TRIGGER FUNGSI CETAK DENGAN DUA MODE
        window.triggerPrint = function(mode) {
            if (mode === 'semua') {
                // Tampilkan semua baris pada print layout YANG STATUSNYA SELESAI
                let totalOngkirSemua = 0;
                let totalTripSemua = 0;

                checkboxes.forEach(cb => {
                    const status = cb.getAttribute('data-status');
                    const ongkir = parseFloat(cb.getAttribute('data-ongkir')) || 0;
                    const orderId = cb.value;
                    const printRow = document.querySelector(`.print-order-row[data-print-order-id="${orderId}"]`);

                    // Hanya print yang selesai (meskipun tak di-centang)
                    if (status === 'selesai') {
                        totalTripSemua++;
                        totalOngkirSemua += ongkir;
                        if (printRow) printRow.style.display = 'table-row';
                    } else {
                        // Pastikan status non-selesai tersembunyi
                        if (printRow) printRow.style.display = 'none';
                    }
                });

                updatePrintLayout(totalOngkirSemua, totalTripSemua);

                window.print();

                // Kembalikan ke state awal (hanya berdasarkan checkbox) setelah window print ditutup
                setTimeout(() => {
                    updateTotals();
                }, 500);

            } else {
                // Cetak terpilih (Sudah di-handle otomatis oleh updateTotals setiap klik)
                window.print();
            }
        };

        // Event listener saat window print selesai/batal (Jaga-jaga agar data kembali normal)
        window.addEventListener('afterprint', () => {
            updateTotals();
        });

        // Event listener CheckAll dan Checkbox
        if (checkAll) {
            checkAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    // Jangan toggle checkbox yang disabled (non-selesai)
                    if (!cb.disabled) {
                        cb.checked = checkAll.checked;
                    }
                });
                updateTotals();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                if (checkAll) {
                    // Cek apakah semua checkbox yang "TIDAK DISABLED" sudah tercentang
                    const enabledCheckboxes = Array.from(checkboxes).filter(chk => !chk.disabled);
                    const checkedCount = enabledCheckboxes.filter(chk => chk.checked).length;

                    checkAll.checked = (checkedCount === enabledCheckboxes.length && enabledCheckboxes.length > 0);
                }
                updateTotals();
            });
        });

        // Inisialisasi awal
        updateTotals();

        // LOGIKA SORTING
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
                    if (printRow) {
                        printTbody.appendChild(printRow);
                    }
                });
            });
        });
    });
</script>
@endsection