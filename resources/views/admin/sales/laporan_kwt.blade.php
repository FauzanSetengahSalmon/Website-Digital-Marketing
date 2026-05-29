@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4 no-print-container">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h3 class="fw-bold">Laporan Keuangan: {{ $kwt->name }}</h3>
    </div>

    @php
    $bulanIndo = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];
    $namaBulan = $bulanIndo[request('month', date('m'))] ?? date('F');

    // Hitung produk terjual (Aman dari error karena pakai logic filter PHP standar)
    $totalProdukTerjual = 0;
    foreach($orders->where('status', 'selesai') as $order) {
    $totalProdukTerjual += $order->details->filter(function($d) use ($kwt) {
    return $d->product && $d->product->user_id == $kwt->id;
    })->sum('jumlah');
    }
    @endphp

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4 no-print">
        <div class="col-md-4">
            <div class="card p-4 border-0 shadow-sm bg-success text-white rounded-4">
                <small class="opacity-75 text-uppercase fw-bold">Total Omzet</small>
                <h4 class="fw-extrabold mt-1">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 border-0 shadow-sm bg-white rounded-4">
                <small class="text-muted text-uppercase fw-bold">Produk Terjual</small>
                <h4 class="text-dark fw-extrabold mt-1">
                    {{-- Panggil variabel langsung, tidak usah ngoding rumit di dalam sini biar editor nggak merah --}}
                    {{ $totalProdukTerjual }}
                    <span class="fs-6 fw-normal">pcs</span>
                </h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 border-0 shadow-sm bg-white rounded-4">
                <small class="text-muted text-uppercase fw-bold">Total Order</small>
                <h4 class="text-dark fw-extrabold mt-1">
                    {{ $orders->count() }} <span class="fs-6 fw-normal">pesanan</span>
                </h4>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 no-print p-3 bg-white">
        <form action="{{ route('admin.kwt.laporan', $kwt->id) }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted ps-1">Bulan</label>
                <select name="month" class="form-select border-0 bg-light">
                    @for($m=1; $m<=12; $m++)
                        @php $mVal=str_pad($m, 2, '0' , STR_PAD_LEFT); @endphp
                        <option value="{{ $mVal }}" {{ request('month', date('m')) == $mVal ? 'selected' : '' }}>
                        {{ $bulanIndo[$mVal] }}
                        </option>
                        @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted ps-1">Tahun</label>
                <select name="year" class="form-select border-0 bg-light">
                    {{-- Beri fallback array jika $tersediaTahun dari controller kosong --}}
                    @foreach($tersediaTahun ?? range(2023, date('Y')) as $y)
                    <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-success w-100 rounded-3">Terapkan Filter</button>
            </div>
        </form>
    </div>

    {{-- TABEL --}}
    <form id="formCairkan" action="{{ route('admin.kwt.cairkan', $kwt->id) }}" method="POST">
        @csrf
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <div>
                <h5 class="fw-bold m-0">Riwayat Transaksi</h5>
                <button type="button" id="btnBatal" class="btn btn-sm btn-link text-danger p-0 border-0">Batal Pilih Semua</button>
            </div>
            <button type="button" class="btn btn-warning rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCairkan">
                <i class="bi bi-wallet2 me-1"></i> Cairkan & Cetak
            </button>
        </div>

        {{-- MODAL --}}
        <div class="modal fade no-print" id="modalCairkan" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-warning border-0">
                        <h5 class="modal-title fw-bold text-dark">Konfirmasi Pencairan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <label class="form-label fw-semibold text-muted">Nama Penerima Uang:</label>
                        <input type="text" name="nama_penerima" class="form-control form-control-lg bg-light" required placeholder="Contoh: Budi Santoso">
                        <small class="text-danger mt-2 d-block">*Pastikan anda mencentang data yang ingin dicairkan pada tabel.</small>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning fw-bold px-4">Proses & Cetak</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden no-print bg-white">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 50px;">Pilih</th>
                            <th>Order ID</th>
                            <th>Subtotal</th>
                            <th>Status</th>
                            <th>Penerima</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders->sortBy('is_paid_out') as $order)
                        @php
                        // Perbaikan logika sum agar aman dari relation bernilai null
                        $sub = $order->details->filter(function($d) use ($kwt) {
                        return $d->product && $d->product->user_id == $kwt->id;
                        })->sum(function($d) {
                        return $d->harga_saat_ini * $d->jumlah;
                        });
                        @endphp
                        <tr>
                            <td class="ps-4">
                                @if(isset($order->is_paid_out) && $order->is_paid_out)
                                <input type="checkbox" checked disabled class="form-check-input bg-secondary border-secondary">
                                @else
                                <input type="checkbox" name="order_ids[]" value="{{ $order->id }}" class="cair-checkbox form-check-input">
                                @endif
                            </td>
                            <td class="fw-bold text-success font-monospace">#ORD-{{ $order->id }}</td>
                            <td class="fw-semibold">Rp {{ number_format($sub, 0, ',', '.') }}</td>
                            <td>
                                @if(isset($order->is_paid_out) && $order->is_paid_out)
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Dicairkan</span>
                                @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3">Pending</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted fw-semibold">
                                    {{ (isset($order->is_paid_out) && $order->is_paid_out && $order->nama_penerima) ? $order->nama_penerima : '-' }}
                                </small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada transaksi untuk periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnBatal = document.getElementById('btnBatal');
        if (btnBatal) {
            btnBatal.addEventListener('click', function() {
                document.querySelectorAll('.cair-checkbox').forEach(cb => {
                    if (!cb.disabled) cb.checked = false;
                });
            });
        }
    });
</script>

{{-- AREA INVOICE (Hanya Muncul saat session memiliki data) --}}
@if(session()->has('printed_ids') && is_array(session('printed_ids')))
<div class="print-only p-4">
    <div class="text-center mb-4 border-bottom pb-3">
        <h2 class="fw-bold text-success mb-1">INVOICE PENCAIRAN KWT</h2>
        <p class="text-muted mb-0">Nama KWT: <strong>{{ $kwt->name }}</strong></p>
        <p class="text-muted mb-0">
            Nama Penerima Dana: <strong>{{ session('penerima') }}</strong> |
            Periode Laporan: <strong>{{ $namaBulan }} {{ request('year', date('Y')) }}</strong>
        </p>
    </div>

    <table class="print-table w-100 mb-5">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th class="text-start">Order ID</th>
                <th class="text-end">Subtotal Penghasilan KWT</th>
            </tr>
        </thead>
        <tbody>
            @php $grand = 0; @endphp
            @foreach($orders->whereIn('id', session('printed_ids')) as $order)
            @php
            $sub = $order->details->filter(function($d) use ($kwt) {
            return $d->product && $d->product->user_id == $kwt->id;
            })->sum(function($d) {
            return $d->harga_saat_ini * $d->jumlah;
            });
            $grand += $sub;
            @endphp
            <tr>
                <td class="font-monospace fw-bold">#ORD-{{ $order->id }}</td>
                <td class="text-end">Rp {{ number_format($sub, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="fw-bold" style="background-color: #f8f9fa;">
                <td class="text-end py-2">TOTAL PENCAIRAN DANA:</td>
                <td class="text-end text-success fs-5 py-2">Rp {{ number_format($grand, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="row mt-5 pt-4">
        <div class="col-6 text-center">
            <p class="text-muted small mb-5">Penerima Dana (KWT),</p>
            <p class="fw-bold text-dark border-bottom d-inline-block pb-1" style="width: 200px;">
                {{ session('penerima') }}
            </p>
        </div>
        <div class="col-6 text-center">
            <p class="text-muted small mb-5">Admin Keuangan Cibiru,</p>
            <p class="fw-bold text-dark border-bottom d-inline-block pb-1" style="width: 200px;">
                {{ Auth::user()->name ?? 'Administrator' }}
            </p>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        // Menggunakan timeout sedikit untuk memastikan DOM render terlebih dahulu
        setTimeout(() => {
            window.print();
        }, 300);
    });
</script>
@endif

<style>
    .print-only {
        display: none;
    }

    @media print {
        body {
            background-color: #fff !important;
        }

        .sidebar,
        .navbar,
        .no-print {
            display: none !important;
        }

        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
            background: none !important;
        }

        .print-only {
            display: block !important;
        }

        .print-table {
            border-collapse: collapse;
            width: 100%;
        }

        .print-table td,
        .print-table th {
            border: 1px solid #dee2e6;
            padding: 12px;
        }
    }
</style>
@endsection