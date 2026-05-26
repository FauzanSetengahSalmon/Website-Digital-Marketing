@extends('layouts.kwt')

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Laporan Transaksi</h3>
            <p class="text-muted small mb-0">
                Riwayat penjualan produk Kelompok Wanita Tani (KWT) Anda.
            </p>
        </div>

        <button onclick="bukaKonfirmasiCetak()"
            class="btn btn-outline-primary rounded-pill px-4 py-2 shadow-sm"
            id="btnCetakMasal">
            <i class="bi bi-printer me-1"></i>
            Cetak Semua Invoice Selesai
        </button>
    </div>

    {{-- WIDGET --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon success me-3">
                            <i class="bi bi-cash-stack"></i>
                        </div>

                        <div>
                            <span class="text-muted small fw-semibold d-block">
                                Total Pendapatan
                            </span>

                            <h4 class="fw-bold mb-0 text-dark">
                                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon primary me-3">
                            <i class="bi bi-receipt"></i>
                        </div>

                        <div>
                            <span class="text-muted small fw-semibold d-block">
                                Jumlah Order
                            </span>

                            <h4 class="fw-bold mb-0 text-dark">
                                {{ $orders->count() }}
                                <span class="fs-6 fw-normal text-muted">
                                    Pesanan
                                </span>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon warning me-3">
                            <i class="bi bi-truck"></i>
                        </div>

                        <div>
                            <span class="text-muted small fw-semibold d-block">
                                Order Selesai
                            </span>

                            <h4 class="fw-bold mb-0 text-dark">
                                {{ $orders->where('status', 'selesai')->count() }}
                                <span class="fs-6 fw-normal text-muted">
                                    Selesai
                                </span>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- CARD TABEL --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        {{-- HEADER --}}
        <div class="card-header bg-white border-0 px-4 pt-4 pb-0">
            <h5 class="fw-bold text-dark mb-4">
                Riwayat Penjualan
            </h5>

            {{-- FILTER --}}
            <div class="filter-wrapper mb-4">

                <form action="{{ route('kwt.laporan') }}" method="GET">

                    <div class="row g-3 align-items-end">

                        {{-- BULAN --}}
                        <div class="col-md-4 col-lg-3">

                            <label class="form-label small fw-semibold text-muted mb-2">
                                Bulan
                            </label>

                            <div class="input-group filter-input">

                                <span class="input-group-text border-0 bg-transparent">
                                    <i class="bi bi-calendar-event text-success"></i>
                                </span>

                                <select name="month"
                                    class="form-select border-0 shadow-none">

                                    @for($m=5; $m<=12; $m++)
                                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}"
                                        {{ request('month', date('m')) == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                        @endfor

                                </select>
                            </div>
                        </div>

                        {{-- TAHUN --}}
                        <div class="col-md-4 col-lg-3">

                            <label class="form-label small fw-semibold text-muted mb-2">
                                Tahun
                            </label>

                            <div class="input-group filter-input">

                                <span class="input-group-text border-0 bg-transparent">
                                    <i class="bi bi-calendar3 text-primary"></i>
                                </span>

                                <select name="year"
                                    class="form-select border-0 shadow-none">

                                    @for($y=2026; $y<=max(2026, date('Y')); $y++)
                                    <option value="{{ $y }}"
                                        {{ request('year', date('Y')) == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                    @endfor

                                </select>
                            </div>
                        </div>

                        {{-- BUTTON --}}
                        <div class="col-md-auto">
                            <button type="submit"
                                class="btn btn-success filter-btn px-4">
                                <i class="bi bi-funnel-fill me-1"></i>
                                Terapkan
                            </button>
                        </div>

                        {{-- TOTAL --}}
                        <div class="col text-md-end">
                            <div class="total-badge">
                                <i class="bi bi-receipt-cutoff me-2"></i>
                                {{ $orders->count() }} Data Transaksi
                            </div>
                        </div>

                    </div>

                </form>

            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">

            <table class="table align-middle mb-0 table-hover">

                <thead class="table-light">

                    <tr>
                        <th class="ps-4 py-3">Order ID</th>
                        <th class="py-3">Pembeli</th>
                        <th class="py-3">Total Barang</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Jadwal Kirim</th>
                        <th class="py-3">Tanggal Order</th>
                        <th class="py-3 text-center pe-4">Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($orders as $order)

                    @php
                    $subtotalKwt = $order->details->sum(function($detail) {
                    return $detail->harga_saat_ini * $detail->jumlah;
                    });
                    @endphp

                    <tr data-status="{{ $order->status }}">

                        <td class="ps-4 py-3">

                            <span class="fw-bold text-primary">
                                #ORD-{{ $order->id }}
                            </span>

                        </td>

                        <td class="py-3">

                            <div class="d-flex align-items-center">

                                <div class="avatar-info me-3">
                                    {{ strtoupper(substr($order->user->name ?? 'M', 0, 1)) }}
                                </div>

                                <div>
                                    <div class="fw-semibold">
                                        {{ $order->user->name ?? 'Masyarakat' }}
                                    </div>

                                    <small class="text-muted d-block">
                                        {{ $order->user->email ?? '-' }}
                                    </small>
                                </div>

                            </div>

                        </td>

                        <td class="py-3">

                            <span class="fw-bold text-success">
                                Rp {{ number_format($subtotalKwt, 0, ',', '.') }}
                            </span>

                            <small class="text-muted d-block">
                                {{ $order->details->count() }} Produk
                            </small>

                        </td>

                        <td class="py-3">

                            @if($order->status == 'selesai')

                            <span class="badge-status success">
                                SELESAI
                            </span>

                            @elseif($order->status == 'diproses')

                            <span class="badge-status primary">
                                DIPROSES
                            </span>

                            @else

                            <span class="badge-status warning">
                                {{ strtoupper($order->status) }}
                            </span>

                            @endif

                        </td>

                        <td class="py-3">

                            @if($order->jadwal_pengiriman)

                            <span class="small fw-semibold text-dark">
                                {{ \Carbon\Carbon::parse($order->jadwal_pengiriman)->format('d M Y') }}
                            </span>

                            @else

                            <span class="text-muted small">
                                Belum Diatur
                            </span>

                            @endif

                        </td>

                        <td class="py-3 text-muted small">
                            {{ $order->created_at->format('d M Y') }}
                        </td>

                        <td class="py-3 text-center pe-4">

                            <a href="{{ route('kwt.orders.detail', $order->id) }}?from=laporan"
                                class="btn btn-outline-primary btn-sm rounded-pill px-3 link-invoice-item">

                                Detail

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="7"
                            class="text-center py-5 text-muted">

                            Belum terdapat data transaksi.

                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- MODAL --}}
<div class="modal fade"
    id="notifCetakModal"
    data-bs-backdrop="static"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-sm">

        <div class="modal-content border-0 shadow rounded-4 text-center p-4">

            <div class="modal-body p-0">

                <div id="modalIcon" class="mb-3">
                    <i class="bi bi-printer text-primary fs-1"></i>
                </div>

                <h5 class="fw-bold text-dark mb-2"
                    id="modalTitle">

                    Konfirmasi Cetak

                </h5>

                <p class="text-muted small mb-4"
                    id="modalDesc">

                    Apakah Anda ingin mencetak seluruh invoice selesai?

                </p>

                <div class="progress mb-4 d-none"
                    id="modalProgress"
                    style="height: 8px;">

                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                        style="width: 0%"></div>

                </div>

                <div class="d-flex gap-2 justify-content-center"
                    id="modalActionGroup">

                    <button type="button"
                        class="btn btn-light btn-sm rounded-pill px-3"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button type="button"
                        onclick="mulaiCetakMasal()"
                        class="btn btn-primary btn-sm rounded-pill px-3">

                        Ya, Cetak

                    </button>

                </div>

                <div class="d-none"
                    id="modalCloseGroup">

                    <button type="button"
                        class="btn btn-dark btn-sm rounded-pill px-4"
                        data-bs-dismiss="modal">

                        Selesai

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

<iframe id="printFrame" style="display:none;"></iframe>

<style>
    body {
        background: #f6f8fb;
    }

    .stat-card {
        border-radius: 24px;
        background: #fff;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon.success {
        background: #e8f5e9;
        color: #198754;
    }

    .stat-icon.primary {
        background: #e3f2fd;
        color: #0d6efd;
    }

    .stat-icon.warning {
        background: #fff8e1;
        color: #ffc107;
    }

    .filter-wrapper {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border: 1px solid #edf2f7;
        border-radius: 22px;
        padding: 1.25rem;
    }

    .filter-input {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.25s ease;
    }

    .filter-input:focus-within {
        border-color: #198754;
        box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.08);
    }

    .filter-input .form-select {
        background-color: transparent;
        font-weight: 600;
    }

    .filter-btn {
        height: 48px;
        border-radius: 14px;
        font-weight: 600;
        box-shadow: 0 8px 20px rgba(25, 135, 84, 0.15);
    }

    .total-badge {
        display: inline-flex;
        align-items: center;
        background: #fff;
        border: 1px solid #e9ecef;
        padding: 0.8rem 1rem;
        border-radius: 14px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #495057;
    }

    .avatar-info {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #f1f3f5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #6c757d;
    }

    .badge-status {
        display: inline-block;
        padding: 0.5rem 0.9rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .badge-status.success {
        background: #e8f5e9;
        color: #198754;
    }

    .badge-status.primary {
        background: #e3f2fd;
        color: #0d6efd;
    }

    .badge-status.warning {
        background: #fff8e1;
        color: #ff9800;
    }

    .table thead th {
        font-size: 0.78rem;
        text-transform: uppercase;
        color: #6c757d;
        border-bottom: 1px solid #edf2f7;
        white-space: nowrap;
    }

    .table tbody tr {
        transition: 0.2s ease;
    }

    .table tbody tr:hover {
        background: #fafcff;
    }

    @media (max-width: 768px) {

        .filter-btn {
            width: 100%;
        }

        .total-badge {
            width: 100%;
            justify-content: center;
            margin-top: .5rem;
        }

    }
</style>

<script>
    let rowsToPrint = [];
    let cetakModal;

    document.addEventListener("DOMContentLoaded", function() {

        cetakModal = new bootstrap.Modal(
            document.getElementById('notifCetakModal')
        );

    });

    function bukaKonfirmasiCetak() {

        rowsToPrint = document.querySelectorAll(
            'tbody tr[data-status="selesai"]'
        );

        if (rowsToPrint.length === 0) {
            alert('Tidak ada data selesai.');
            return;
        }

        cetakModal.show();
    }

    async function mulaiCetakMasal() {

        document.getElementById('modalActionGroup')
            .classList.add('d-none');

        document.getElementById('modalProgress')
            .classList.remove('d-none');

        const progressBar = document.querySelector(
            '#modalProgress .progress-bar'
        );

        const iframe = document.getElementById('printFrame');

        for (let i = 0; i < rowsToPrint.length; i++) {

            let percent = Math.round(
                ((i + 1) / rowsToPrint.length) * 100
            );

            progressBar.style.width = percent + '%';

            const linkElement = rowsToPrint[i]
                .querySelector('.link-invoice-item');

            const url = linkElement.getAttribute('href') +
                '&print=true';

            await new Promise((resolve) => {

                iframe.src = url;

                iframe.onload = function() {

                    setTimeout(() => {

                        iframe.contentWindow.print();

                        resolve();

                    }, 1000);

                };

            });

        }

        document.getElementById('modalTitle').innerText = 'Selesai';

        document.getElementById('modalProgress')
            .classList.add('d-none');

        document.getElementById('modalCloseGroup')
            .classList.remove('d-none');

    }
</script>
@endsection