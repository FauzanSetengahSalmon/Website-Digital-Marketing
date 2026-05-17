@extends('layouts.admin')

@section('content')
<div class="container-fluid py-3">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Laporan Penjualan Global</h3>
            <p class="text-muted small mb-0">Pantau seluruh riwayat transaksi dan status pendapatan masuk.</p>
        </div>
        <div>
            <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Cetak Laporan
            </button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="bg-success-subtle text-success p-3 rounded-4 me-3">
                        <i class="bi bi-currency-dollar fs-3"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block fw-semibold">Total Omzet Bersih</span>
                        <h4 class="fw-bold text-dark mb-0">
                            Rp {{ number_format($sales->where('status', 'selesai')->sum('total_harga'), 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary p-3 rounded-4 me-3">
                        <i class="bi bi-bag-check fs-3"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block fw-semibold">Transaksi Berhasil</span>
                        <h4 class="fw-bold text-dark mb-0">
                            {{ $sales->where('status', 'selesai')->count() }} <span class="fs-6 fw-normal text-muted">Pesanan</span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="bg-warning-subtle text-warning p-3 rounded-4 me-3">
                        <i class="bi bi-clock-history fs-3"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block fw-semibold">Pesanan Tertunda/Proses</span>
                        <h4 class="fw-bold text-dark mb-0">
                            {{ $sales->whereIn('status', ['menunggu', 'diproses'])->count() }} <span class="fs-6 fw-normal text-muted">Pesanan</span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="fw-bold text-dark mb-0">Riwayat Transaksi</h5>
            <span class="badge bg-light text-dark rounded-pill px-3 py-2 fw-semibold">Total Data: {{ $sales->count() }}</span>
        </div>
        
        <div class="table-responsive">
            <table class="table align-middle mb-0 custom-table-hover">
                <thead class="bg-light text-secondary text-uppercase fs-7 fw-bold border-bottom">
                    <tr>
                        <th class="ps-4 py-3">Order ID</th>
                        <th class="py-3">Customer</th>
                        <th class="py-3">Total Harga</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Tanggal Transaksi</th>
                        <th class="py-3 text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-dark">
                    @forelse($sales as $sale)
                    <tr>
                        <td class="ps-4 py-3">
                            <span class="fw-bold text-primary">#{{ $sale->id }}</span>
                        </td>
                        
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-info me-2 bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-weight: 600; font-size: 0.85rem;">
                                    {{ strtoupper(substr($sale->user->name, 0, 1)) }}
                                </div>
                                <span class="fw-semibold">{{ $sale->user->name }}</span>
                            </div>
                        </td>
                        
                        <td class="py-3">
                            <span class="fw-bold {{ $sale->status == 'selesai' ? 'text-success' : 'text-dark' }}">
                                Rp {{ number_format($sale->total_harga, 0, ',', '.') }}
                            </span>
                        </td>
                        
                        <td class="py-3">
                            @if($sale->status == 'selesai')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-bold fs-8">
                                    <i class="bi bi-check-circle-fill me-1"></i> SELESAI
                                </span>
                            @elseif($sale->status == 'diproses')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 fw-bold fs-8">
                                    <i class="bi bi-arrow-repeat me-1"></i> DIPROSES
                                </span>
                            @elseif($sale->status == 'menunggu')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-2 fw-bold fs-8">
                                    <i class="bi bi-hourglass-split me-1"></i> MENUNGGU
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2 fw-bold fs-8">
                                    <i class="bi bi-x-circle-fill me-1"></i> {{ strtoupper($sale->status) }}
                                </span>
                            @endif
                        </td>
                        
                        <td class="py-3 text-secondary small">
                            <i class="bi bi-calendar3 me-1"></i> {{ $sale->created_at->format('d M Y') }} 
                            <span class="text-muted ms-1"><i class="bi bi-clock me-1"></i>{{ $sale->created_at->format('H:i') }}</span>
                        </td>

                        <td class="py-3 text-center pe-4">
                            @php
                                $detailsWithProduct = $sale->details->loadMissing('product');

                                $customDetails = $detailsWithProduct->map(function($item) {
                                    $namaTerdeteksi = 'Produk tidak ditemukan';
                                    
                                    if ($item->product) {
                                        $namaTerdeteksi = $item->product->nama_produk 
                                            ?? $item->product->name 
                                            ?? $item->product->nama 
                                            ?? $item->product->title 
                                            ?? 'Nama Kolom Salah';
                                    }

                                    return [
                                        'nama_produk' => $namaTerdeteksi,
                                        'jumlah' => $item->jumlah ?? $item->qty ?? 0,
                                        'harga_saat_ini' => $item->harga_saat_ini ?? $item->harga ?? 0
                                    ];
                                });
                            @endphp

                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 btn-detail"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalDetailPesanan"
                                    data-id="{{ $sale->id }}"
                                    data-customer="{{ $sale->user->name }}"
                                    data-total="Rp {{ number_format($sale->total_harga, 0, ',', '.') }}"
                                    data-tanggal="{{ $sale->created_at->format('d M Y H:i') }}"
                                    data-status="{{ strtoupper($sale->status) }}"
                                    data-details="{{ json_encode($customDetails) }}">
                                <i class="bi bi-eye me-1"></i> Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <div class="py-3">
                                <i class="bi bi-cart-x fs-1 text-muted mb-2 d-block"></i>
                                <span>Belum terdapat data transaksi penjualan yang tercatat.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL DETAIL PESANAN --}}
<div class="modal fade" id="modalDetailPesanan" tabindex="-1" aria-labelledby="modalDetailPesananLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 py-3 px-4 bg-light">
                <h5 class="modal-title fw-bold text-dark" id="modalDetailPesananLabel">
                    <i class="bi bi-receipt me-2 text-primary"></i>Rincian Pesanan <span id="det-id" class="text-primary"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="row g-3 mb-4 p-3 bg-light rounded-4">
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block mb-1">Customer</small>
                        <span id="det-customer" class="fw-bold text-dark"></span>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block mb-1">Tanggal Transaksi</small>
                        <span id="det-tanggal" class="fw-semibold text-dark"></span>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block mb-1">Status Pesanan</small>
                        <span id="det-status" class="badge rounded-pill fw-bold fs-8"></span>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block mb-1">Total Pembayaran</small>
                        <span id="det-total" class="fw-bold text-success fs-5"></span>
                    </div>
                </div>

                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-box-seam me-2"></i>Produk Yang Dibeli</h6>
                <div class="table-responsive rounded-3 border">
                    <table class="table align-middle mb-0">
                        <thead class="table-light text-secondary fs-8 fw-bold">
                            <tr>
                                <th class="ps-3 py-2">Nama Produk</th>
                                <th class="py-2 text-center">Jumlah</th>
                                <th class="py-2 text-end">Harga Satuan</th>
                                <th class="py-2 text-end pe-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="det-items-body">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    .fs-7 { font-size: 0.78rem !important; letter-spacing: 0.5px; }
    .fs-8 { font-size: 0.75rem !important; }
    .bg-success-subtle { background-color: #e8f5e9 !important; }
    .bg-primary-subtle { background-color: #e3f2fd !important; }
    .bg-warning-subtle { background-color: #fff8e1 !important; }
    .bg-danger-subtle { background-color: #ffebee !important; }

    .custom-table-hover tbody tr {
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }
    .custom-table-hover tbody tr:hover {
        background-color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
        position: relative;
        z-index: 1;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalDetail = document.getElementById('modalDetailPesanan');
    if (modalDetail) {
        modalDetail.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            
            const id = button.getAttribute('data-id');
            const customer = button.getAttribute('data-customer');
            const total = button.getAttribute('data-total');
            const tanggal = button.getAttribute('data-tanggal');
            const status = button.getAttribute('data-status');
            const details = JSON.parse(button.getAttribute('data-details'));
            
            document.getElementById('det-id').textContent = '#' + id;
            document.getElementById('det-customer').textContent = customer;
            document.getElementById('det-tanggal').textContent = tanggal;
            document.getElementById('det-total').textContent = total;
            
            const statusBadge = document.getElementById('det-status');
            statusBadge.textContent = status;
            statusBadge.className = 'badge rounded-pill px-3 py-2 fw-bold fs-8';
            if(status === 'SELESAI') statusBadge.classList.add('bg-success-subtle', 'text-success');
            else if(status === 'DIPROSES') statusBadge.classList.add('bg-primary-subtle', 'text-primary');
            else if(status === 'MENUNGGU') statusBadge.classList.add('bg-warning-subtle', 'text-warning');
            else statusBadge.classList.add('bg-danger-subtle', 'text-danger');

            const itemsBody = document.getElementById('det-items-body');
            itemsBody.innerHTML = '';
            
            if (Array.isArray(details)) {
                details.forEach(item => {
                    const namaProduk = item.nama_produk || 'Produk Tidak Diketahui';
                    const harga = parseFloat(item.harga_saat_ini || 0);
                    const jumlah = parseInt(item.jumlah || 0);
                    const subtotal = harga * jumlah;

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="ps-3 py-2 fw-semibold text-dark">${namaProduk}</td>
                        <td class="text-center py-2">${jumlah} Pcs</td>
                        <td class="text-end py-2 text-secondary">Rp ${harga.toLocaleString('id-ID')}</td>
                        <td class="text-end py-2 pe-3 fw-bold text-dark">Rp ${subtotal.toLocaleString('id-ID')}</td>
                    `;
                    itemsBody.appendChild(tr);
                });
            }
        });
    }
});
</script>
@endpush
@endsection