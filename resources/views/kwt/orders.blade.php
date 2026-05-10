@extends('layouts.kwt')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-success">Daftar Pesanan Masuk</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">ID Pesanan</th>
                            <th>Nama Pembeli</th>
                            <th>Total Pembayaran</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 fw-bold">#{{ $order->id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $badgeColor = match($order->status) {
                                        'menunggu' => 'warning',
                                        'diterima', 'diproses' => 'info',
                                        'selesai' => 'success',
                                        'ditolak', 'dibatalkan' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge rounded-pill bg-{{ $badgeColor }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($order->status == 'menunggu')
                                    <form action="{{ route('kwt.order.status', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="diterima">
                                        <button type="submit" class="btn btn-sm btn-success px-3">Terima</button>
                                    </form>
                                    <form action="{{ route('kwt.order.status', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="ditolak">
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-3">Tolak</button>
                                    </form>
                                @elseif($order->status == 'diterima')
                                    <a href="{{ route('kwt.orders.process', $order->id) }}" class="btn btn-sm btn-primary px-3">Proses Kirim</a>
                                @elseif($order->status == 'selesai')
                                    <a href="{{ route('kwt.orders.detail', $order->id) }}" class="btn btn-sm btn-outline-success px-3">Lihat Laporan</a>
                                @else
                                    <span class="text-muted small">Tidak ada aksi</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <p class="mb-0">Belum ada pesanan masuk.</p>
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