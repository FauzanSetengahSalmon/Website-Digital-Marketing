@extends('layouts.kwt')

@section('content')
<style>
    /* Container Utama */
    .order-container {
        background: white;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        min-height: 400px;
        /* Biar gak terlalu ceper saat kosong */
        display: flex;
        flex-direction: column;
    }

    /* Tabel Styling */
    .table thead th {
        background-color: #fcfdfd;
        font-weight: 600;
        font-size: 0.8rem;
        color: #64748b;
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
    }

    /* Empty State Styling (Ini yang kita bagusin) */
    .empty-state {
        padding: 80px 20px;
        text-align: center;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: #f0fdf4;
        color: #10b981;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin-bottom: 20px;
        border: 4px solid #fcfdfd;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.1);
    }

    .empty-state h6 {
        color: #334155;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #94a3b8;
        font-size: 0.85rem;
        max-width: 300px;
        margin: 0 auto;
    }
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Pesanan Masuk</h5>
            <p class="text-muted small mb-0">Update status pengiriman pelanggan Anda.</p>
        </div>
        <span class="badge bg-white text-success border border-success-subtle px-3 py-2 rounded-pill fw-bold">
            {{ $orders->count() }} Order Baru
        </span>
    </div>

    <div class="order-container shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th width="120">ID Order</th>
                        <th>Produk</th>
                        <th class="text-center">Qty</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-right" style="text-align: right; padding-right: 25px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $o)
                    <tr>
                        <td class="ps-3 fw-bold text-secondary">#ORD-{{ $o->id }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $o->nama_produk }}</div>
                            <small class="text-muted" style="font-size: 0.7rem;">Katalog KWT</small>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border px-2">{{ $o->jumlah }}</span>
                        </td>
                        <td>
                            <span class="fw-bold text-success">Rp {{ number_format($o->total_harga, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3">
                                Menunggu
                            </span>
                        </td>
                        <td style="text-align: right; padding-right: 25px;">
                            <form action="{{ route('kwt.orders.done', $o->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 fw-bold shadow-sm">
                                    Selesai <i class="bi bi-check2-all ms-1"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    {{-- Tampilan saat pesanan kosong (Empty State Baru) --}}
                    <tr>
                        <td colspan="6" class="p-0">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-mailbox"></i>
                                </div>
                                <h6>Belum Ada Pesanan</h6>
                                <p>Santai dulu, Bu! Belum ada pesanan masuk hari ini. Pastikan produk Ibu sudah aktif di katalog.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection