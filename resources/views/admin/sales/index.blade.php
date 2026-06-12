@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4 bg-light min-vh-100">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">Daftar Penjualan Global</h4>
            <p class="text-muted small mb-0">Pantau transaksi masuk dari customer dan distribusikan penugasan armada kurir.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-semibold fs-7 d-flex align-items-center">
                <i class="bi bi-cart-check-fill me-1"></i> {{ $sales->count() }} Total Pesanan
            </span>
        </div>
    </div>

    {{-- SECTION PILIH KWT (REKAP PEMASUKAN) --}}
    <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="text-uppercase fw-bold text-secondary small tracking-widest mb-0">
                <i class="bi bi-wallet2 me-2 text-success"></i>Rekap Pemasukan KWT
            </h6>
        </div>

        <div class="row g-3">
            @forelse(\App\Models\User::where('role', 'kwt')->get() as $k)
            <div class="col-6 col-sm-4 col-md-3">
                <a href="{{ route('admin.kwt.laporan', $k->id) }}" class="text-decoration-none">
                    {{-- Tambahan h-100 agar tinggi card seragam --}}
                    <div class="card border-0 shadow-sm rounded-4 kwt-card transition-all overflow-hidden h-100">
                        <div class="card-body p-3 text-center d-flex flex-column justify-content-center">
                            <div class="avatar-lg bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 50px; height: 50px;">
                                {{ substr($k->name, 0, 1) }}
                            </div>
                            <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $k->name }}</h6>
                            <small class="text-success fw-semibold mt-auto">Lihat Laporan &raquo;</small>
                        </div>
                        <div class="kwt-card-footer bg-success py-1 opacity-0 transition-all"></div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center py-4 bg-white rounded-4 shadow-sm">
                <p class="text-muted mb-0">Belum ada mitra KWT terdaftar.</p>
            </div>
            @endforelse
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        {{-- Custom Scrollbar di pembungkus tabel --}}
        <div class="table-responsive custom-scrollbar">
            {{-- text-nowrap agar tabel utama tidak terhimpit --}}
            <table class="table align-middle mb-0 table-hover text-nowrap" id="admin-interactive-table">
                <thead class="bg-light border-bottom text-uppercase tracking-wider fs-7 fw-bold text-secondary">
                    <tr>
                        <th class="ps-4 py-3 sortable" data-sort="id" style="cursor: pointer;">
                            Order ID <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i>
                        </th>
                        <th class="py-3">Customer</th>
                        <th class="py-3">KWT</th>
                        <th class="py-3 text-end sortable" data-sort="subtotal" style="cursor: pointer;">
                            Total Harga
                        </th>
                        <th class="py-3 text-center sortable" data-sort="status" style="cursor: pointer;">
                            Status <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i>
                        </th>
                        <th class="py-3">Jadwal Kirim</th>
                        <th class="py-3 sortable" data-sort="date" style="cursor: pointer;">
                            Tanggal Transaksi <i class="bi bi-arrow-down-up ms-1 text-muted sort-icon"></i>
                        </th>
                        <th class="py-3 text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr class="align-middle border-bottom border-light data-row"
                        data-id="{{ $sale->id }}"
                        data-date="{{ $sale->created_at->timestamp }}"
                        data-status-text="{{ $sale->status_refund != 'tidak_ada' ? $sale->status_refund : $sale->status }}"
                        data-subtotal="{{ $sale->total_harga }}">
                        <td class="ps-4 py-3.5">
                            <span class="fw-bold text-success font-monospace">#{{ $sale->id }}</span>
                        </td>
                        <td class="py-3.5">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center fw-bold text-uppercase" style="width: 32px; height: 32px; font-size: 0.85rem; flex-shrink: 0;">
                                    {{ substr($sale->user->name ?? 'M', 0, 1) }}
                                </div>
                                <div>
                                    <span class="fw-semibold text-dark d-block lh-sm">{{ $sale->user->name ?? 'Masyarakat' }}</span>
                                    <small class="text-muted fs-8 font-monospace">{{ $sale->nomor_hp ?? $sale->user->phone_number ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5">
                            @php
                            $kwtNames = $sale->details->map(fn($d) => $d->product->user->name ?? 'KWT Umum')->unique()->filter()->implode(', ');
                            @endphp
                            @if($kwtNames)
                            <span class="fw-bold text-dark fs-7">{{ $kwtNames }}</span>
                            @else
                            <span class="text-muted small italic">-</span>
                            @endif
                        </td>
                        <td class="py-3.5 text-end">
                            <span class="fw-bold text-dark">Rp {{ number_format($sale->total_harga, 0, ',', '.') }}</span>
                        </td>
                        <td class="py-3.5 text-center">
                            {{-- PRIORITASKAN TAMPILAN STATUS REFUND --}}
                            @if($sale->status_refund == 'diajukan')
                            <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8"><i class="bi bi-exclamation-circle me-1"></i> Refund Diajukan</span>
                            @elseif($sale->status_refund == 'disetujui')
                            <span class="badge bg-success text-white rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8"><i class="bi bi-check-circle me-1"></i> Refund Disetujui</span>
                            @elseif($sale->status_refund == 'ditolak')
                            <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">Refund Ditolak</span>

                            {{-- JIKA TIDAK ADA REFUND, TAMPILKAN STATUS NORMAL --}}
                            @elseif($sale->status == 'menunggu')
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">⚡ Menunggu</span>
                            @elseif($sale->status == 'diproses')
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">📦 Diproses</span>
                            @elseif($sale->status == 'diantar')
                            <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">🚚 Diantar</span>
                            @elseif($sale->status == 'batal')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">❌ Batal</span>
                            @else
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">✅ {{ $sale->status }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 fs-7 fw-medium text-dark">
                            @if($sale->jadwal_pengiriman)
                            <span class="text-success bg-success bg-opacity-10 px-2.5 py-1 rounded-pill fw-bold"><i class="bi bi-calendar-check me-1"></i>{{ \Carbon\Carbon::parse($sale->jadwal_pengiriman)->format('d M Y') }}</span>
                            @else
                            <span class="text-muted small italic"><i class="bi bi-hourglass-split me-1"></i>Belum Dijadwalkan</span>
                            @endif
                        </td>
                        <td class="py-3.5 text-secondary fs-7">
                            <i class="bi bi-calendar3 me-1 text-muted"></i> {{ $sale->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                        </td>
                        <td class="py-3.5 text-center pe-4">
                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 fw-medium text-dark transition-all shadow-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalProsesAdmin{{ $sale->id }}">
                                    <i class="bi bi-sliders2 me-1 text-success"></i> Kelola
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row">
                        <td colspan="8" class="text-center py-5 text-muted">
                            <div class="py-3">
                                <i class="bi bi-inbox fs-2 mb-2 d-block opacity-50"></i>
                                <span class="d-block fw-medium">Belum ada pesanan masuk.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach($sales as $sale)
<div class="modal fade" id="modalProsesAdmin{{ $sale->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('admin.order.status', $sale->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="status" class="input-status-handler" value="diproses">

                <div class="modal-header border-0 py-3 px-4 bg-light align-items-start align-items-sm-center">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Manajemen & Verifikasi Pesanan</h5>
                        <small class="text-muted fs-7">Order ID: <span class="text-success fw-bold font-monospace">#{{ $sale->id }}</span></small>
                    </div>
                    <button type="button" class="btn-close mt-1 mt-sm-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-3 px-md-4 py-3">

                    <div class="p-3 border rounded-4 bg-light bg-opacity-50 mb-3">
                        <div class="row g-3">
                            <div class="col-6 col-md-4">
                                <small class="text-muted d-block text-uppercase fs-8 fw-semibold mb-0.5">Customer</small>
                                <span class="fw-bold text-dark">{{ $sale->user->name ?? 'Masyarakat' }}</span>
                            </div>
                            <div class="col-6 col-md-4">
                                <small class="text-muted d-block text-uppercase fs-8 fw-semibold mb-0.5">Kontak</small>
                                <span class="fw-semibold text-dark font-monospace">{{ $sale->nomor_hp ?? '-' }}</span>
                            </div>

                            <div class="col-12 col-md-4 text-start text-md-end mt-3 mt-md-0">
                                <small class="text-muted d-block text-uppercase fs-8 fw-semibold mb-0.5">Total Pembayaran</small>
                                <span class="fw-bold text-success fs-5 d-block mb-1">Rp {{ number_format($sale->total_harga, 0, ',', '.') }}</span>

                                @php
                                // Hitung mutlak harga sayur/produk KWT dari keranjang
                                $subtotalKWT = $sale->details->sum(fn($d) => $d->jumlah * $d->harga_saat_ini);

                                // Sisa uangnya dipastikan adalah biaya platform
                                $biayaPlatformAsli = $sale->total_harga - $sale->ongkir - $subtotalKWT;
                                @endphp

                                <div class="text-start text-md-end text-secondary fs-8 lh-sm">
                                    <div>Subtotal KWT: Rp {{ number_format($subtotalKWT, 0, ',', '.') }}</div>
                                    <div class="text-info fw-medium mt-1" title="Termasuk jarak & kapasitas kurir">Ongkir Kurir: +Rp {{ number_format($sale->ongkir, 0, ',', '.') }}</div>
                                    <div class="text-warning fw-medium mt-1">Biaya Platform: +Rp {{ number_format($biayaPlatformAsli, 0, ',', '.') }}</div>
                                </div>
                            </div>

                            <div class="col-12 border-top pt-2 mt-2">
                                <small class="text-muted d-block text-uppercase fs-8 fw-semibold mb-0.5"><i class="bi bi-geo-alt-fill text-danger me-1"></i>Alamat Pengiriman</small>
                                <span class="small text-dark fw-medium lh-base">{{ $sale->alamat ?? 'Alamat tidak terisi lengkap' }}</span>
                            </div>

                            @if($sale->jadwal_pengiriman)
                            <div class="col-12 border-top pt-2 mt-2">
                                <small class="text-success d-block text-uppercase fs-8 fw-bold mb-0.5"><i class="bi bi-calendar-check-fill me-1"></i>Jadwal Pengiriman Terpilih</small>
                                <span class="small text-success fw-bold bg-success bg-opacity-10 px-2 py-1.5 rounded d-inline-block">
                                    <i class="bi bi-clock-history me-1"></i>Armada meluncur pada: {{ \Carbon\Carbon::parse($sale->jadwal_pengiriman)->format('d F Y') }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- 🌟 AUDIT REKONSILIASI KEUANGAN --}}
                    <div class="p-3 border rounded-4 bg-white mb-4 shadow-sm text-dark small">
                        <h6 class="fw-bold text-dark mb-2 fs-7 text-uppercase"><i class="bi bi-cash-stack text-success me-2"></i>Status Rekonsiliasi Keuangan</h6>
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <strong>Log Arus Dana:</strong>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-2 px-2 py-1">Dana Masuk KWT</span>
                            </div>
                            <div class="col-12 col-md-6">
                                <strong>Status Refund:</strong>
                                @if($sale->status_refund == 'diajukan')
                                <span class="badge bg-warning text-dark rounded-2 px-2 py-1"><i class="bi bi-exclamation-circle me-1"></i>Menunggu Tinjauan</span>
                                @elseif($sale->status_refund == 'disetujui')
                                <span class="badge bg-success bg-opacity-10 text-success rounded-2 px-2 py-1">Disetujui Admin</span>
                                @elseif($sale->status_refund == 'ditolak')
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-2 px-2 py-1">Ditolak Admin</span>
                                @elseif($sale->status == 'batal')
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-2 px-2 py-1">Batal (Tanpa Refund)</span>
                                @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-2 px-2 py-1">Tidak Ada Pengajuan</span>
                                @endif
                            </div>
                            @if($sale->alasan_tolak)
                            <div class="col-12 border-top pt-2 mt-2 text-danger font-monospace">
                                <strong>Alasan Tolak KWT:</strong> "{{ $sale->alasan_tolak }}"
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-2 fs-7 text-uppercase tracking-wider text-secondary"><i class="bi bi-basket3-fill text-success me-2"></i>Komoditas Hasil Panen KWT</h6>

                        {{-- STATUS KONFIRMASI KWT --}}
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3 fs-7 text-uppercase tracking-wider text-secondary">
                                <i class="bi bi-check2-square text-success me-2"></i>
                                Status Konfirmasi KWT
                            </h6>

                            <div class="border rounded-4 overflow-hidden bg-white shadow-sm">
                                @php
                                $groupedKwt = $sale->details->groupBy(fn($d) => $d->product->user->name ?? 'KWT Umum');
                                @endphp

                                @foreach($groupedKwt as $kwtName => $items)
                                @php
                                $allReady = $items->every(fn($item) => $item->stok_ready);
                                $readyAt = $items->first()->stok_ready_at;
                                $readyBy = $items->first()->stok_ready_by;
                                @endphp

                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center p-3 border-bottom gap-2">
                                    <div>
                                        <div class="fw-bold text-dark">
                                            <i class="bi bi-people-fill text-success me-1"></i>
                                            {{ $kwtName }}
                                        </div>
                                        @if($allReady)
                                        <small class="text-success d-block d-sm-inline">
                                            Sudah ACC stok
                                            @if($readyAt)
                                            • {{ \Carbon\Carbon::parse($readyAt)->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB
                                            @endif
                                        </small>
                                        @else
                                        <small class="text-danger d-block d-sm-inline">
                                            Belum ACC stok
                                        </small>
                                        @endif
                                    </div>
                                    <div>
                                        @if($allReady)
                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                            <i class="bi bi-check-circle-fill me-1"></i> READY
                                        </span>
                                        @else
                                        <span class="badge bg-danger rounded-pill px-3 py-2">
                                            <i class="bi bi-hourglass-split me-1"></i> MENUNGGU
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Custom Scrollbar untuk Modal Table --}}
                        <div class="table-responsive custom-scrollbar rounded-3 border bg-white">
                            {{-- Penambahan text-nowrap --}}
                            <table class="table align-middle mb-0 sm-table text-nowrap">
                                <thead class="table-light text-secondary fs-8 fw-bold text-uppercase">
                                    <tr>
                                        <th class="ps-3 py-2">Nama Produk</th>
                                        <th class="py-2">Pemilik / Nama KWT</th>
                                        <th class="py-2 text-center">Kuantitas</th>
                                        <th class="py-2 text-end pe-3">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($sale->details)
                                    @foreach($sale->details as $detail)
                                    <tr>
                                        <td class="ps-3 py-2.5 fw-semibold text-dark">
                                            {{ $detail->product->nama_produk ?? 'Produk Terhapus' }}
                                        </td>
                                        <td class="py-2.5">
                                            <span class="badge bg-success-subtle text-success rounded-1 fw-semibold fs-8 px-2 py-1">
                                                <i class="bi bi-person-badge me-1"></i>{{ $detail->product->user->name ?? 'KWT Umum' }}
                                            </span>
                                        </td>
                                        <td class="text-center py-2.5 text-muted small fw-medium">
                                            {{ $detail->jumlah }} {{ $detail->product->satuan ?? 'Ikat' }}
                                        </td>
                                        <td class="text-end py-2.5 pe-3 fw-bold text-dark">
                                            Rp {{ number_format($detail->harga_saat_ini * $detail->jumlah, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if(empty($sale->jadwal_pengiriman) && $sale->status != 'batal' && $sale->status_refund != 'diajukan')
                    <div class="border-top pt-3 section-kurir-wrapper">
                        <h6 class="fw-bold text-dark mb-3 fs-7 text-uppercase tracking-wider text-secondary"><i class="bi bi-truck text-success me-2"></i>Penugasan Armada & Logistik</h6>

                        <div class="row g-3">
                            <div class="col-12 col-md-5">
                                <label class="form-label fw-bold text-dark small mb-1">Personel Armada</label>
                                {{-- 🌟 PERBAIKAN: MENGGUNAKAN data-phone SESUAI JAVASCRIPT 🌟 --}}
                                <select id="kurir-select-{{ $sale->id }}" class="form-select rounded-3 py-2 fs-7 border-secondary-subtle" onchange="updateKendaraanDanHp(this, '{{ $sale->id }}')" required>
                                    <option value="">-- Hubungkan Kurir --</option>
                                    @foreach($list_kurir as $k)
                                    <option value="{{ $k->nama }}"
                                        data-phone="{{ $k->no_hp }}"
                                        data-kendaraan="{{ json_encode($k->kendaraans) }}">
                                        {{ $k->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="kurir" id="kurir-hidden-{{ $sale->id }}">
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label fw-bold text-dark small mb-1">Kendaraan Operasional</label>
                                <select name="kendaraan_pengantar" id="kendaraan-select-{{ $sale->id }}" class="form-select rounded-3 py-2 fs-7 border-secondary-subtle" required>
                                    <option value="">-- Pilih Kurir Dulu --</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-3">
                                <label class="form-label fw-bold text-dark small mb-1">No. HP Kurir</label>
                                <input type="text" name="no_hp_kurir" id="nohp-kurir-{{ $sale->id }}" class="form-control rounded-3 py-2 fs-7 bg-light border-secondary-subtle font-monospace" placeholder="Otomatis..." readonly required>
                            </div>

                            <div class="col-12 mt-2">
                                <label class="form-label fw-bold text-dark small mb-1">Jadwal Pengiriman</label>
                                <input type="date" name="jadwal_pengiriman" class="form-control rounded-3 py-2 fs-7 border-secondary-subtle" min="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                    </div>
                    @else
                    <div class="border-top pt-3 p-3 bg-light rounded-3 border">
                        <h6 class="fw-bold text-secondary mb-3 fs-7 text-uppercase">
                            <i class="bi bi-shield-check text-success me-2"></i>
                            Status Logistik Terkunci
                        </h6>
                        <div class="row small g-3 text-dark">
                            <div class="col-12 col-md-4">
                                <div class="fw-semibold text-muted mb-1">Kurir Pengantar</div>
                                <div class="fw-bold">{{ $sale->kurir ?? '-' }}</div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="fw-semibold text-muted mb-1">Kontak Kurir</div>
                                <div class="fw-bold">{{ $sale->no_hp_kurir ?? '-' }}</div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="fw-semibold text-muted mb-1">Kendaraan Operasional</div>
                                <div class="fw-bold text-success">{{ $sale->kendaraan_pengantar ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- FOOTER MODAL: Diatur responsif menggunakan d-grid di HP dan flex di Desktop --}}
                <div class="modal-footer border-0 px-3 px-md-4 pb-4 bg-light bg-opacity-25 d-flex flex-column flex-md-row justify-content-md-between gap-3">
                    {{-- SISI KANAN: TOMBOL AKSI --}}
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end w-100 w-md-auto">
                        @if(($sale->status == 'menunggu' || $sale->status == 'diproses') && $sale->status_refund != 'diajukan')
                        <button type="button" class="btn btn-outline-danger rounded-pill px-3 fw-bold w-10"
                            data-bs-toggle="modal" data-bs-target="#modalTolakPesanan{{ $sale->id }}">
                            <i class="bi bi-x-circle me-1"></i> Tolak Pesanan
                        </button>
                        @endif
                        <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Kembali</button>

                        {{-- JIKA ADA PENGAJUAN REFUND, TOMBOL BIASA HILANG, GANTI DENGAN TOMBOL TINJAU REFUND --}}
                        @if($sale->status_refund == 'diajukan')
                        <button type="button" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalRefundAdmin{{ $sale->id }}">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Tinjau Refund
                        </button>
                        @else
                        {{-- TOMBOL KIRIM LINK WA KE KURIR --}}
                        @if($sale->status == 'diantar')
                        @php
                        if(!$sale->delivery_token) {
                        $sale->delivery_token = \Illuminate\Support\Str::random(32);
                        $sale->save();
                        }
                        $link = route('kurir.upload', [$sale->id, $sale->delivery_token]);
                        $pesan = rawurlencode("Halo Kurir, mohon segera foto bukti pesanan #".$sale->id." di sini: " . $link);
                        @endphp
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $sale->no_hp_kurir) }}?text={{ $pesan }}"
                            target="_blank" class="btn btn-success rounded-pill px-3 fw-bold shadow-sm">
                            <i class="bi bi-whatsapp"></i> Kirim Link Foto
                        </a>
                        @endif

                        @if($sale->status === 'diproses')
                        <button type="submit" onclick="this.form.querySelector('.input-status-handler').value='diantar';" class="btn btn-info text-white rounded-pill px-4 fw-bold shadow-sm">
                            <i class="bi bi-truck me-1"></i> Tandai Diantar
                        </button>
                        @endif

                        @if(empty($sale->jadwal_pengiriman) && $sale->status != 'batal')
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                            <i class="bi bi-send-check-fill me-1"></i> Verifikasi Pesanan
                        </button>
                        @endif
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL TINJAU REFUND ADMIN --}}
@if($sale->status_refund == 'diajukan')
<div class="modal fade" id="modalRefundAdmin{{ $sale->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-bottom-0 p-3 p-md-4">
                <h5 class="fw-bold mb-0 fs-6 fs-md-5"><i class="bi bi-exclamation-triangle-fill me-2"></i>Tinjau Pengajuan Refund</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.orders.refund', $sale->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-3 p-md-4 pt-3">
                    <p class="small text-muted mb-3">Mohon periksa alasan dan bukti pengajuan pengembalian dana dari pelanggan ini secara teliti.</p>

                    <div class="mb-3">
                        <label class="small fw-bold text-secondary mb-1">Alasan dari Customer:</label>
                        <div class="text-dark bg-light p-3 rounded border small mb-0 lh-base">
                            {{ $sale->alasan_refund ?? 'Tidak ada alasan yang diberikan.' }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="small fw-bold text-secondary d-block mb-2">Bukti Foto Kerusakan/Ketidaksesuaian:</label>
                        @if($sale->bukti_refund)
                        <div class="text-center bg-light border rounded p-2">
                            <img src="{{ asset('storage/' . $sale->bukti_refund) }}" alt="Bukti Refund" class="img-fluid rounded" style="max-height: 250px; object-fit: contain;">
                        </div>
                        @else
                        <p class="text-danger small mb-0"><i class="bi bi-x-circle me-1"></i> Tidak ada foto bukti yang dilampirkan.</p>
                        @endif
                    </div>

                    <hr class="border-danger opacity-25">

                    <div class="mb-3">
                        <label class="small fw-bold text-danger mb-1">Keputusan Akhir Admin:</label>
                        <select name="keputusan" class="form-select border-danger-subtle rounded-3" required>
                            <option value="">-- Pilih Keputusan Evaluasi --</option>
                            <option value="disetujui">✅ Setujui Refund (Uang Dikembalikan)</option>
                            <option value="ditolak">❌ Tolak Refund (Bukti Tidak Valid / Pesanan Selesai)</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="small fw-bold text-danger mb-1">Catatan Tambahan untuk Customer:</label>
                        <textarea name="catatan_admin_refund" class="form-control rounded-3" rows="2" placeholder="Cth: Dana direfund 100% / Maaf kerusakan terjadi pada saat perjalanan kurir..."></textarea>
                        <small class="text-muted" style="font-size: 0.7rem;">*Catatan dan notifikasi ini akan otomatis dikirim via Email ke Customer.</small>
                    </div>
                </div>
                <div class="modal-footer border-top-0 p-3 p-md-4 pt-0 d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-light border rounded-pill px-4 fw-bold small" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalProsesAdmin{{ $sale->id }}">Kembali ke Detail</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-send-check me-1"></i> Konfirmasi Keputusan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- MODAL FORM ALASAN PENOLAKAN PESANAN ADMIN --}}
<div class="modal fade" id="modalTolakPesanan{{ $sale->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0 p-4 pb-0">
                <h5 class="fw-bold text-dark mb-0 fs-6 fs-md-5"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Tolak & Batalkan Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.orders.reject', $sale->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4 pt-2">
                    <p class="text-muted small mb-3">Berikan alasan mengapa transaksi ini ditolak. Catatan audit trail aliran dana refund akan dikirim ke email pembeli.</p>
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-secondary mb-1">Alasan Pembatalan</label>
                        <textarea name="alasan_tolak" class="form-control text-dark p-2.5 small" rows="3" placeholder="Contoh: Maaf, stok bayam ikat dari kelompok tani KWT Mandiri saat ini habis..." required style="border-radius: 10px; resize: none;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 p-4 pt-0 d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-light border rounded-pill px-4 fw-bold small py-2" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold py-2" style="background: #dc2626; border:none;">
                        <i class="bi bi-check-lg me-1"></i> Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<style>
    .fs-7 {
        font-size: 0.85rem !important;
    }

    .fs-8 {
        font-size: 0.75rem !important;
    }

    .tracking-widest {
        letter-spacing: 0.1em;
    }

    /* KWT CARDS */
    .kwt-card {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.03);
        text-decoration: none !important;
    }

    .kwt-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .kwt-card:hover .kwt-card-footer {
        opacity: 1 !important;
    }

    /* BADGE STATUS */
    .badge-status {
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        display: inline-block;
    }

    .transition-all {
        transition: all 0.3s ease;
    }

    .table-hover tbody tr:hover {
        background-color: #fcfdfe !important;
    }

    /* CUSTOM SCROLLBAR MOBILE */
    .custom-scrollbar::-webkit-scrollbar {
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
</style>

<script>
    function updateKendaraanDanHp(selectElement, orderId) {
        const noHpInput = document.getElementById('nohp-kurir-' + orderId);
        const kendaraanSelect = document.getElementById('kendaraan-select-' + orderId);
        const kurirHiddenInput = document.getElementById('kurir-hidden-' + orderId);

        const selectedOption = selectElement.options[selectElement.selectedIndex];

        kendaraanSelect.innerHTML = '<option value="">-- Pilih Kendaraan --</option>';

        if (selectedOption.value !== "") {
            noHpInput.value = selectedOption.getAttribute('data-phone');
            kurirHiddenInput.value = selectedOption.value;

            const kendaraans = JSON.parse(selectedOption.getAttribute('data-kendaraan') || '[]');

            if (kendaraans.length > 0) {
                kendaraans.forEach(function(kdr) {
                    const teksKendaraan = kdr.jenis_kendaraan + ' - ' + kdr.merk_kendaraan + ' (' + kdr.plat_nomor + ')';
                    const option = new Option(teksKendaraan, teksKendaraan);
                    kendaraanSelect.add(option);
                });
            } else {
                kendaraanSelect.innerHTML = '<option value="">-- Garasi Kurir Kosong! --</option>';
            }
        } else {
            noHpInput.value = "";
            kurirHiddenInput.value = "";
            kendaraanSelect.innerHTML = '<option value="">-- Pilih Kurir Dulu --</option>';
        }
    }

    let sortDirection = {
        id: false,
        date: false,
        status: false,
        subtotal: false
    };
    const headers = document.querySelectorAll('th.sortable');

    headers.forEach(th => {
        th.addEventListener('click', () => {
            const sortType = th.getAttribute('data-sort');
            const tbody = document.querySelector('#admin-interactive-table tbody');
            const rows = Array.from(tbody.querySelectorAll('tr.data-row'));

            sortDirection[sortType] = !sortDirection[sortType];
            const isAsc = sortDirection[sortType];

            headers.forEach(header => {
                const icon = header.querySelector('.sort-icon');
                if (icon) icon.className = 'bi bi-arrow-down-up ms-1 text-muted sort-icon';
            });
            const activeIcon = th.querySelector('.sort-icon');
            if (activeIcon) {
                activeIcon.className = isAsc ? 'bi bi-arrow-up ms-1 text-success sort-icon' : 'bi bi-arrow-down ms-1 text-success sort-icon';
            }

            rows.sort((a, b) => {
                let valA, valB;
                if (sortType === 'id') {
                    valA = parseInt(a.getAttribute('data-id'));
                    valB = parseInt(b.getAttribute('data-id'));
                } else if (sortType === 'date') {
                    valA = parseInt(a.getAttribute('data-date'));
                    valB = parseInt(b.getAttribute('data-date'));
                } else if (sortType === 'subtotal') {
                    valA = parseFloat(a.getAttribute('data-subtotal'));
                    valB = parseFloat(b.getAttribute('data-subtotal'));
                } else if (sortType === 'status') {
                    valA = a.getAttribute('data-status-text').toLowerCase();
                    valB = b.getAttribute('data-status-text').toLowerCase();
                }

                if (valA < valB) return isAsc ? -1 : 1;
                if (valA > valB) return isAsc ? 1 : -1;
                return 0;
            });

            rows.forEach(row => {
                tbody.appendChild(row);
            });
        });
    });
</script>
@endsection