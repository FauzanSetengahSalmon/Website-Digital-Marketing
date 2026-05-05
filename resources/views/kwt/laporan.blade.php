@extends('layouts.kwt')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h5 class="fw-bold mb-0">Laporan Transaksi KWT</h5>
            <p class="text-muted small">Rekap pesanan masuk dari pembeli.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="bg-success text-white p-3 rounded-4 shadow-sm">
                <small class="d-block opacity-75">Total Pendapatan</small>
                <h4 class="fw-bold mb-0">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3" width="150">ID Order</th>
                            <th class="py-3">Pembeli</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3">Total Bayar</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 fw-bold">#{{ $order->id }}</td>
                            <td>
                                <div class="fw-bold">{{ $order->user->name ?? 'Guest' }}</div>
                                <small class="text-muted">{{ $order->user->email ?? '-' }}</small>
                            </td>
                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td class="fw-bold text-success">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning text-dark rounded-pill px-3">Menunggu</span>
                                @elseif($order->status == 'success')
                                    <span class="badge bg-success rounded-pill px-3">Selesai</span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3">Batal</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <button class="btn btn-sm btn-light border rounded-pill px-3">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                                Belum ada transaksi masuk.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection