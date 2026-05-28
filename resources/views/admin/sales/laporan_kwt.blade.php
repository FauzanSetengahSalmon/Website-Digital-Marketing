@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4 no-print-container">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h3 class="fw-bold">Laporan Keuangan: {{ $kwt->name }}</h3>
    </div>

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4 no-print">
        <div class="col-md-4"><div class="card p-4 border-0 shadow-sm bg-success text-white rounded-4"><small class="opacity-75 text-uppercase fw-bold">Total Omzet</small><h4 class="fw-extrabold mt-1">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</h4></div></div>
        <div class="col-md-4"><div class="card p-4 border-0 shadow-sm bg-white rounded-4"><small class="text-muted text-uppercase fw-bold">Produk Terjual</small><h4 class="text-dark fw-extrabold mt-1">{{ $orders->where('status', 'selesai')->sum(fn($o) => $o->details->where('product.user_id', $kwt->id)->sum('jumlah')) }} <span class="fs-6 fw-normal">pcs</span></h4></div></div>
        <div class="col-md-4"><div class="card p-4 border-0 shadow-sm bg-white rounded-4"><small class="text-muted text-uppercase fw-bold">Total Order</small><h4 class="text-dark fw-extrabold mt-1">{{ $orders->count() }} <span class="fs-6 fw-normal">pesanan</span></h4></div></div>
    </div>

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 no-print p-3">
        <form action="{{ route('admin.kwt.laporan', $kwt->id) }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted ps-1">Bulan</label>
                <select name="month" class="form-select border-0 bg-light">
                    @for($m=1; $m<=12; $m++)
                        @php $mVal = str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                        <option value="{{ $mVal }}" {{ request('month', date('m')) == $mVal ? 'selected' : '' }}>{{ $bulanIndo[$mVal] }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted ps-1">Tahun</label>
                <select name="year" class="form-select border-0 bg-light">
                    @foreach($tersediaTahun as $y)
                        <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4"><button type="submit" class="btn btn-success w-100 rounded-3">Terapkan Filter</button></div>
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
        <div class="modal fade" id="modalCairkan" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Konfirmasi Pencairan</h5></div>
                    <div class="modal-body">
                        <label class="form-label">Nama Penerima Uang:</label>
                        <input type="text" name="nama_penerima" class="form-control" required placeholder="Contoh: Budi Santoso">
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-warning">Proses & Cetak</button></div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden no-print">
            <table class="table align-middle mb-0 table-hover">
                <thead class="table-light"><tr><th class="ps-4">Pilih</th><th>Order ID</th><th>Subtotal</th><th>Status</th><th>Penerima</th></tr></thead>
                <tbody>
                    @foreach($orders->sortBy('is_paid_out') as $order)
                    @php $sub = $order->details->where('product.user_id', $kwt->id)->sum(fn($d) => $d->harga_saat_ini * $d->jumlah); @endphp
                    <tr>
                        <td class="ps-4">
                            @if($order->is_paid_out)
                                <input type="checkbox" checked disabled class="form-check-input" style="pointer-events: none;">
                            @else
                                <input type="checkbox" name="order_ids[]" value="{{ $order->id }}" class="cair-checkbox form-check-input">
                            @endif
                        </td>
                        <td class="fw-bold text-success font-monospace">#ORD-{{ $order->id }}</td>
                        <td>Rp {{ number_format($sub, 0, ',', '.') }}</td>
                        <td><span class="badge {{ $order->is_paid_out ? 'bg-success' : 'bg-secondary' }}">{{ $order->is_paid_out ? 'Dicairkan' : 'Pending' }}</span></td>
                        <td><small class="text-muted">{{ $order->is_paid_out ? $order->nama_penerima : '-' }}</small></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>
</div>

<script>
    document.getElementById('btnBatal').addEventListener('click', function() {
        document.querySelectorAll('.cair-checkbox').forEach(cb => { cb.checked = false; });
    });
</script>

{{-- AREA INVOICE --}}
@if(session('printed_ids'))
<div class="print-only p-4">
    <div class="text-center mb-4"><h3 class="fw-bold">INVOICE PEMBAYARAN KWT</h3><p>Penerima: {{ session('penerima') }} | Periode: {{ $bulanIndo[$month] ?? '' }} {{ $year ?? '' }}</p></div>
    <table class="print-table w-100 mb-5">
        <thead><tr><th>Order ID</th><th>Subtotal</th></tr></thead>
        <tbody>
            @php $grand = 0; @endphp
            @foreach($orders->whereIn('id', session('printed_ids')) as $order)
            @php $sub = $order->details->where('product.user_id', $kwt->id)->sum(fn($d) => $d->harga_saat_ini * $d->jumlah); $grand += $sub; @endphp
            <tr><td>#ORD-{{ $order->id }}</td><td>Rp {{ number_format($sub, 0, ',', '.') }}</td></tr>
            @endforeach
        </tbody>
        <tfoot><tr class="fw-bold"><td class="text-end">TOTAL:</td><td>Rp {{ number_format($grand, 0, ',', '.') }}</td></tr></tfoot>
    </table>
    <div class="row mt-5"><div class="col-6 text-center"><p>Penerima (KWT)</p><br><br><br><p>( {{ session('penerima') }} )</p></div><div class="col-6 text-center"><p>Admin Keuangan</p><br><br><br><p>( ........................... )</p></div></div>
</div>
<script>window.addEventListener('load', function() { window.print(); });</script>
@endif

<style>.print-only { display: none; } @media print { .no-print { display: none !important; } .print-only { display: block !important; } .print-table { border-collapse: collapse; width: 100%; } .print-table td, .print-table th { border: 1px solid #000; padding: 10px; } }</style>
@endsection