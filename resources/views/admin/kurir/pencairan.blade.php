@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4 bg-light min-vh-100">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">Riwayat Pencairan Kurir</h4>
            <p class="text-muted small mb-0">
                Kelola pencairan penghasilan kurir dan pantau histori distribusi dana operasional pengantaran.
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-semibold fs-7 d-flex align-items-center">
                <i class="bi bi-cash-coin me-1"></i>
                {{ $pencairan->count() }} Total Pencairan
            </span>

            <button class="btn btn-success rounded-pill px-4 shadow-sm fw-semibold"
                data-bs-toggle="modal"
                data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle me-1"></i>
                Tambah Pencairan
            </button>
        </div>
    </div>

    {{-- SECTION PILIH KURIR --}}
    <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="text-uppercase fw-bold text-secondary small tracking-widest mb-0">
                <i class="bi bi-truck me-2 text-primary"></i>Rekap Penghasilan Kurir
            </h6>
        </div>

        <div class="row g-3">
            @forelse($list_kurir as $kurir)
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.kurir.laporan', $kurir->id) }}"
                    class="text-decoration-none">

                    <div class="card border-0 shadow-sm rounded-4 kwt-card transition-all overflow-hidden">

                        <div class="card-body p-3 text-center">

                            <div class="avatar-lg bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center fw-bold fs-4"
                                style="width: 55px; height: 55px;">
                                {{ substr($kurir->nama, 0, 1) }}
                            </div>

                            <h6 class="fw-bold text-dark mb-1 text-truncate">
                                {{ $kurir->nama }}
                            </h6>

                            <small class="text-muted d-block mb-2">
                                {{ $kurir->kendaraan ?? 'Kurir Pengiriman' }}
                            </small>

                            <small class="text-primary fw-semibold">
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

    {{-- ALERT --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">

        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold text-dark mb-1">
                        <i class="bi bi-clock-history text-success me-2"></i>
                        Histori Pencairan Dana Kurir
                    </h6>
                    <small class="text-muted">
                        Seluruh aktivitas pencairan penghasilan kurir tercatat otomatis.
                    </small>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">

                <thead class="bg-light border-bottom text-uppercase tracking-wider fs-8 fw-bold text-secondary">
                    <tr>
                        <th class="ps-4 py-3">Kurir</th>
                        <th class="py-3">Penerima</th>
                        <th class="py-3">Kontak</th>
                        <th class="py-3 text-end">Total Pencairan</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3">Tanggal</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($pencairan as $item)
                    <tr class="border-bottom border-light">

                        {{-- KURIR --}}
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-2">

                                <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                    style="width: 40px; height: 40px;">
                                    {{ substr($item->nama_kurir, 0, 1) }}
                                </div>

                                <div>
                                    <span class="fw-bold text-dark d-block">
                                        {{ $item->nama_kurir }}
                                    </span>

                                    <small class="text-muted">
                                        Armada Kurir
                                    </small>
                                </div>

                            </div>
                        </td>

                        {{-- PENERIMA --}}
                        <td class="py-3">
                            <span class="fw-semibold text-dark">
                                {{ $item->nama_penerima }}
                            </span>
                        </td>

                        {{-- KONTAK --}}
                        <td class="py-3">
                            <span class="font-monospace small text-secondary">
                                {{ $item->no_hp ?? '-' }}
                            </span>
                        </td>

                        {{-- TOTAL --}}
                        <td class="py-3 text-end">
                            <span class="fw-bold text-success fs-6">
                                Rp {{ number_format($item->total_cair, 0, ',', '.') }}
                            </span>
                        </td>

                        {{-- STATUS --}}
                        <td class="py-3 text-center">
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-bold text-uppercase fs-8">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                Berhasil
                            </span>
                        </td>

                        {{-- TANGGAL --}}
                        <td class="py-3 text-secondary fs-7">
                            <i class="bi bi-calendar3 me-1 text-muted"></i>
                            {{ $item->created_at->format('d M Y, H:i') }} WIB
                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">

                            <div class="py-4">
                                <i class="bi bi-inbox fs-1 opacity-50 d-block mb-3"></i>

                                <h6 class="fw-bold text-dark">
                                    Belum Ada Data Pencairan
                                </h6>

                                <p class="small text-muted mb-0">
                                    Data pencairan kurir akan muncul di sini.
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

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">

            <form action="{{ route('admin.kurir.pencairan.store') }}" method="POST">
                @csrf

                <div class="modal-header border-0 p-4 pb-2">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">
                            Tambah Pencairan Kurir
                        </h5>

                        <small class="text-muted">
                            Input pencairan dana penghasilan kurir.
                        </small>
                    </div>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4 pt-2">

                    {{-- KURIR --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">
                            Pilih Kurir
                        </label>

                        <select name="nama_kurir"
                            class="form-select rounded-3"
                            required>

                            <option value="">-- Pilih Kurir --</option>

                            @foreach($list_kurir as $kurir)
                            <option value="{{ $kurir->nama }}">
                                {{ $kurir->nama }}
                            </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- PENERIMA --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">
                            Nama Penerima
                        </label>

                        <input type="text"
                            name="nama_penerima"
                            class="form-control rounded-3"
                            placeholder="Masukkan nama penerima"
                            required>
                    </div>

                    {{-- NO HP --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">
                            Nomor HP
                        </label>

                        <input type="text"
                            name="no_hp"
                            class="form-control rounded-3"
                            placeholder="08xxxxxxxxxx">
                    </div>

                    {{-- TOTAL --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">
                            Total Pencairan
                        </label>

                        <input type="number"
                            name="total_cair"
                            class="form-control rounded-3"
                            placeholder="Masukkan total pencairan"
                            required>
                    </div>

                    {{-- CATATAN --}}
                    <div class="mb-2">
                        <label class="form-label fw-semibold small text-dark">
                            Catatan Tambahan
                        </label>

                        <textarea name="catatan"
                            rows="3"
                            class="form-control rounded-3"
                            placeholder="Opsional..."></textarea>
                    </div>

                </div>

                <div class="modal-footer border-0 p-4 pt-0">

                    <button type="button"
                        class="btn btn-light border rounded-pill px-4"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                        class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-save2 me-1"></i>
                        Simpan Pencairan
                    </button>

                </div>
            </form>

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
</style>
@endsection