@extends('layouts.kwt')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="fw-bold mb-1 text-success">Laporan Transaksi</h4>
            <p class="text-muted mb-0">Seluruh riwayat penjualan produk Anda</p>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-success text-white">
                <div class="card-body p-3">
                    <small class="opacity-75">Total Pendapatan (Selesai)</small>
                    <h3 class="fw-bold mb-0">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <form action="{{ route('kwt.laporan.reset') }}" method="POST" onsubmit="return confirm('Yakin reset semua riwayat transaksi Anda?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger rounded-pill px-4 btn-sm">
                <i class="bi bi-trash me-1"></i> Reset Data
            </button>
        </form>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Order ID</th>
                        <th>Tanggal</th>
                        <th>Pembeli</th>
                        <th>Total (Milik Anda)</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $totalKwt = 0;
                            foreach($order->details as $detail){
                                if($detail->product && $detail->product->user_id == Auth::id()){
                                    // Menggunakan harga_saat_ini agar sesuai database
                                    $totalKwt += $detail->harga_saat_ini * $detail->jumlah;
                                }
                            }
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark">#ORD-{{ $order->id }}</span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="fw-semibold">{{ $order->user->name }}</div>
                                <small class="text-muted" style="font-size: 0.7rem;">{{ $order->user->email }}</small>
                            </td>
                            <td class="fw-bold text-success">
                                Rp {{ number_format($totalKwt, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($order->status == 'menunggu')
                                    <span class="badge rounded-pill bg-warning text-dark">Menunggu</span>
                                @elseif($order->status == 'selesai')
                                    <span class="badge rounded-pill bg-success">Selesai</span>
                                @elseif($order->status == 'diproses')
                                    <span class="badge rounded-pill bg-primary">Diproses</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('kwt.orders.detail', $order->id) }}" class="btn btn-light border btn-sm rounded-pill px-3">
                                    <i class="bi bi-search me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                                Belum ada transaksi yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection