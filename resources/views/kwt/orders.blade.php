@extends('layouts.kwt')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    body {
        font-family: 'Inter', sans-serif;
        background: #f6f7fb;
    }

    .page-title {
        font-weight: 800;
        font-size: 26px;
        color: #111827;
    }

    .table-card {
        background: white;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .04);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }

    .table thead th {
        background: #fafafa;
        font-size: 11px;
        text-transform: uppercase;
        padding: 16px 20px;
        color: #6b7280;
    }

    .table tbody td {
        padding: 18px 20px;
        vertical-align: middle;
        border-top: 1px solid #f1f5f9;
    }

    .order-id {
        font-weight: 800;
        color: #111827;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="page-title">Pesanan Masuk</div>
            <div class="sub-title">Kelola pesanan pelanggan khusus produk KWT Anda</div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    <div class="table-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Invoice</th>
                        <th>Produk KWT</th>
                        <th>Total KWT</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $o)
                    @php
                    $kwtDetails = $o->details->filter(fn($d) => $d->product->user_id == Auth::id());
                    $totalKwt = $kwtDetails->sum(fn($d) => $d->harga_saat_ini * $d->jumlah);
                    @endphp
                    @if($kwtDetails->count() > 0)
                    <tr>
                        <td class="ps-4">
                            <div class="order-id">#ORD-{{ $o->id }}</div>
                            <small class="text-muted">{{ $o->created_at->format('d M Y H:i') }}</small>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $kwtDetails->first()->product->nama_produk }}</div>
                            @if($kwtDetails->count() > 1) <small class="text-primary">+{{ $kwtDetails->count() - 1 }} lainnya</small> @endif
                        </td>
                        <td class="fw-bold text-success">Rp {{ number_format($totalKwt, 0, ',', '.') }}</td>
                        <td><span class="status-badge bg-light">{{ ucfirst($o->status) }}</span></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-light border rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalKwt{{ $o->id }}">
                                <i class="bi bi-sliders2 me-1"></i> Kelola
                            </button>
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Belum ada pesanan masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL KELOLA SAMA PERSIS DENGAN ADMIN --}}
@foreach($orders as $o)
<div class="modal fade" id="modalKwt{{ $o->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('admin.order.status', $o->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-header border-0 py-3 px-4 bg-light">
                    <h5 class="fw-bold">Manajemen Pesanan #ORD-{{ $o->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="p-3 border rounded-4 bg-light mb-3">
                        <small class="text-muted text-uppercase fw-bold">Alamat:</small>
                        <p class="mb-0">{{ $o->alamat ?? 'Tidak tersedia' }}</p>
                    </div>

                    <label class="small fw-bold">STATUS PESANAN:</label>
                    <select name="status" class="form-select rounded-3 mb-3">
                        <option value="diproses" {{ $o->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="diantar" {{ $o->status == 'diantar' ? 'selected' : '' }}>Diantar</option>
                    </select>

                    <div class="border-top pt-3">
                        <label class="text-danger small fw-bold">TOLAK / BATALKAN PESANAN</label>
                        <textarea name="alasan_tolak" class="form-control mt-1" rows="2" placeholder="Masukkan alasan penolakan..."></textarea>
                        <button type="submit" name="status" value="batal" class="btn btn-outline-danger w-100 mt-2 rounded-pill">Tolak Pesanan</button>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection