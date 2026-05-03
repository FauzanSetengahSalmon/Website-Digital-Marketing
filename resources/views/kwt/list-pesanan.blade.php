@extends('layouts.kwt')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-0">Pesanan Masuk (Belum Terkirim)</h5>
                            <p class="text-muted small mb-0">Kelola pesanan pelanggan dan update status pengiriman di sini.</p>
                        </div>
                        <span class="badge bg-primary rounded-pill px-3">Total: {{ $orders->count() }} Pesanan</span>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    {{-- Alert jika ada notifikasi sukses --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">ID Order</th>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $o)
                                <tr>
                                    <td class="ps-3 fw-bold text-dark">#{{ $o->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-3 p-2 me-3">
                                                <i class="bi bi-box-seam text-success"></i>
                                            </div>
                                            <span class="fw-semibold">{{ $o->nama_produk }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-3">{{ $o->jumlah }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">Rp {{ number_format($o->total_harga, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        @if($o->status == 'pending')
                                            <span class="badge bg-warning text-dark px-3 rounded-pill">Menunggu</span>
                                        @else
                                            <span class="badge bg-info text-white px-3 rounded-pill">{{ ucfirst($o->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{-- Form untuk tombol Selesai --}}
                                        <form action="{{ route('kwt.orders.done', $o->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 shadow-sm">
                                                <i class="bi bi-check2-circle me-1"></i> Proses Selesai
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="py-4">
                                            <img src="https://illustrations.popsy.co/green/delivery-boy.svg" style="height: 150px;" class="mb-3">
                                            <h6 class="text-muted">Belum ada pesanan masuk saat ini.</h6>
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
    </div>
</div>
@endsection