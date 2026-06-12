@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4 bg-light min-vh-100">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">Rekapitulasi Armada Kurir</h4>
            <p class="text-muted small mb-0">
                Pantau daftar armada kurir operasional beserta akumulasi pendapatan dari tugas pengantaran.
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-semibold fs-7 d-flex align-items-center">
                <i class="bi bi-truck me-1"></i>
                {{ $list_kurir->count() }} Total Kurir
            </span>
        </div>
    </div>

    {{-- SECTION PILIH KURIR --}}
    <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="text-uppercase fw-bold text-secondary small tracking-widest mb-0">
                <i class="bi bi-wallet2 me-2 text-primary"></i>Pintas Laporan Kurir
            </h6>
        </div>

        <div class="row g-3">
            @forelse($list_kurir as $kurir)
            <div class="col-6 col-sm-4 col-md-3">
                <a href="{{ route('admin.kurir.laporan', $kurir->id) }}"
                    class="text-decoration-none">

                    <div class="card border-0 shadow-sm rounded-4 kwt-card transition-all overflow-hidden h-100">

                        <div class="card-body p-3 text-center d-flex flex-column justify-content-center">

                            <div class="avatar-lg bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center fw-bold fs-4"
                                style="width: 55px; height: 55px;">
                                {{ substr($kurir->nama, 0, 1) }}
                            </div>

                            <h6 class="fw-bold text-dark mb-1 text-truncate">
                                {{ $kurir->nama }}
                            </h6>

                            <small class="text-muted d-block mb-2 text-truncate">
                                {{ $kurir->kendaraan ?? 'Kurir Pengiriman' }}
                            </small>

                            <small class="text-primary fw-semibold mt-auto">
                                Lihat Laporan &raquo;
                            </small>

                        </div>

                        <div class="kwt-card-footer bg-primary py-1 opacity-0 transition-all"></div>
                    </div>
                </a>
            </div>

            @empty
            <div class="col-12">
                <div class="bg-white shadow-sm rounded-4 p-5 text-center">
                    <i class="bi bi-truck fs-1 text-muted opacity-50 d-block mb-3"></i>
                    <h6 class="fw-bold text-dark">Belum Ada Kurir Terdaftar</h6>
                    <p class="text-muted small mb-0">
                        Tambahkan data kurir terlebih dahulu untuk melihat laporan penghasilan.
                    </p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- TABLE DATA KURIR --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">

        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold text-dark mb-1">
                        <i class="bi bi-card-list text-success me-2"></i>
                        Data Pendapatan Seluruh Kurir
                    </h6>
                    <small class="text-muted">
                        Daftar armada yang ditugaskan beserta total hasil tarikan ongkos kirim.
                    </small>
                </div>
            </div>
        </div>

        <div class="card-body p-0 mt-3">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover text-nowrap">

                    <thead class="bg-light border-bottom text-uppercase tracking-wider fs-8 fw-bold text-secondary">
                        <tr>
                            <th class="ps-4 py-3">Kurir</th>
                            <th class="py-3">Kendaraan</th>
                            <th class="py-3">Kontak WhatsApp</th>
                            <th class="py-3 text-end">Total Pendapatan <i class="bi bi-info-circle ms-1" title="Termasuk tarif jarak & tambahan kapasitas bawaan" style="cursor:help;"></i></th>
                            <th class="py-3 text-center">Status Aktivitas</th>
                            <th class="py-3 pe-4">Tanggal Gabung</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($list_kurir as $kurir)
                        @php
                        $pendapatan = \App\Models\Order::where('kurir', $kurir->nama)
                        ->where('status', 'selesai')
                        ->sum('ongkir');
                        @endphp
                        <tr class="border-bottom border-light">

                            {{-- KURIR --}}
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-2">

                                    <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                        style="width: 40px; height: 40px; flex-shrink: 0;">
                                        {{ substr($kurir->nama, 0, 1) }}
                                    </div>

                                    <div>
                                        <span class="fw-bold text-dark d-block">
                                            {{ $kurir->nama }}
                                        </span>

                                        <small class="text-muted">
                                            Armada Kurir
                                        </small>
                                    </div>

                                </div>
                            </td>

                            {{-- KENDARAAN --}}
                            <td class="py-3">
                                <span class="badge bg-light text-dark border border-secondary-subtle rounded-pill px-3 py-1 fw-semibold fs-8">
                                    {{ $kurir->kendaraan ?? 'Belum Diatur' }}
                                </span>
                            </td>

                            {{-- KONTAK --}}
                            <td class="py-3">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kurir->no_hp) }}" target="_blank" class="text-decoration-none font-monospace small text-secondary">
                                    <i class="bi bi-whatsapp text-success me-1"></i> {{ $kurir->no_hp ?? '-' }}
                                </a>
                            </td>

                            {{-- TOTAL PENDAPATAN (RINCIAN VOLUME) --}}
                            <td class="py-3 text-end">
                                <span class="fw-bold text-success fs-6">
                                    Rp {{ number_format($pendapatan, 0, ',', '.') }}
                                </span>
                                <small class="d-block text-muted mt-1" style="font-size: 0.7rem;">(Jarak + Volume)</small>
                            </td>

                            {{-- STATUS --}}
                            <td class="py-3 text-center">
                                @if($kurir->status == 'aktif')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-bold text-uppercase fs-8">
                                    <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                </span>
                                @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2 fw-bold text-uppercase fs-8">
                                    <i class="bi bi-x-circle-fill me-1"></i> Nonaktif
                                </span>
                                @endif
                            </td>

                            {{-- TANGGAL GABUNG --}}
                            <td class="py-3 pe-4 text-secondary fs-7">
                                <i class="bi bi-calendar3 me-1 text-muted"></i>
                                {{ $kurir->created_at ? $kurir->created_at->format('d M Y') : '-' }}
                            </td>

                        </tr>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">

                                <div class="py-4">
                                    <i class="bi bi-inbox fs-1 opacity-50 d-block mb-3"></i>

                                    <h6 class="fw-bold text-dark">
                                        Belum Ada Data Kurir
                                    </h6>

                                    <p class="small text-muted mb-0">
                                        Daftar armada kurir yang ditambahkan akan muncul di sini.
                                    </p>
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

    .kwt-card {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.03);
    }

    .kwt-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .kwt-card:hover .kwt-card-footer {
        opacity: 1 !important;
    }

    .transition-all {
        transition: all 0.3s ease;
    }

    .table-hover tbody tr:hover {
        background-color: #fcfdfe !important;
    }

    /* Tambahan agar scrollbar tabel di HP tidak terlalu memakan tempat */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
</style>
@endsection