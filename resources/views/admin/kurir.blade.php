@extends('layouts.admin')

@section('content')
<style>
    :root {
        --primary-green: #064e3b;
        --light-green: #10b981;
        --soft-green: #ecfdf5;
    }

    .form-label-bold {
        font-size: 0.85rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 6px;
    }

    .input-clean {
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        padding: 11px 14px;
        font-size: 0.9rem;
        transition: all 0.25s ease;
    }

    .input-clean:focus {
        border-color: var(--light-green);
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.12);
    }

    .input-group-text-custom {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-right: none;
        border-radius: 12px 0 0 12px;
        color: #64748b;
    }

    .input-clean-group {
        border-radius: 0 12px 12px 0 !important;
    }

    .petunjuk-admin {
        font-size: 0.73rem;
        color: #64748b;
        margin-top: 6px;
        display: block;
        font-style: italic;
    }

    .page-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .table thead th {
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        font-weight: 700;
        background: #f8fafc !important;
    }

    .table tbody tr:hover {
        background: #f8fafc;
    }

    .kurir-avatar {
        width: 40px;
        height: 40px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .income-box {
        background: #f0fdf4;
        border: 1px solid #dcfce7;
        border-radius: 14px;
        padding: 10px 14px;
    }

    .modal-content {
        border-radius: 24px !important;
    }

    .btn-success {
        background: var(--primary-green);
        border-color: var(--primary-green);
    }

    .btn-success:hover {
        background: #053b2c;
        border-color: #053b2c;
    }

    .btn-primary {
        background: var(--light-green);
        border-color: var(--light-green);
    }

    .btn-primary:hover {
        background: #059669;
        border-color: #059669;
    }

    /* Action Buttons Styling */
    .btn-action-edit {
        background-color: #eff6ff;
        color: #2563eb;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-action-edit:hover {
        background-color: #2563eb;
        color: #ffffff;
        transform: translateY(-1px);
    }

    .btn-action-delete {
        background-color: #fef2f2;
        color: #dc2626;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-action-delete:hover {
        background-color: #dc2626;
        color: #ffffff;
        transform: translateY(-1px);
    }

    /* 🌟 TOMBOL BARU: GARASI KENDARAAN 🌟 */
    .btn-action-garage {
        background-color: #e0f2fe;
        color: #0369a1;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-action-garage:hover {
        background-color: #0284c7;
        color: #ffffff;
        transform: translateY(-1px);
    }

    .custom-scrollbar::-webkit-scrollbar {
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
</style>

<div class="container-fluid py-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                Manajemen Kurir Internal
            </h2>
            <p class="text-muted mb-0">
                Kelola data kurir, garasi kendaraan operasional, dan pendapatan distribusi.
            </p>
        </div>

        <button type="button"
            class="btn btn-success rounded-pill px-4 py-2 shadow-sm fw-semibold w-10 w-md-auto"
            data-bs-toggle="modal"
            data-bs-target="#modalTambahKurir">
            <i class="bi bi-plus-lg me-2"></i> Tambah Kurir Baru
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="page-card shadow-sm p-3 p-md-4">
        <div class="table-responsive custom-scrollbar">
            <table class="table align-middle table-hover mb-0 text-nowrap">
                <thead>
                    <tr>
                        <th class="px-3 py-3">Kurir</th>
                        <th>No. WhatsApp</th>
                        <th>Pendapatan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kurirs as $kurir)
                    <tr>
                        <td class="px-3 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="kurir-avatar">
                                    <span class="fw-bold text-secondary">{{ strtoupper(substr($kurir->nama, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $kurir->nama }}</div>
                                    <small class="text-muted">
                                        {{ isset($kurir->kendaraans) ? $kurir->kendaraans->count() : 0 }} Kendaraan Terdaftar
                                    </small>
                                </div>
                            </div>
                        </td>

                        <td class="py-3">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kurir->no_hp) }}"
                                target="_blank" class="text-decoration-none text-secondary fw-medium">
                                <i class="bi bi-whatsapp text-success me-1"></i>
                                {{ $kurir->no_hp }}
                            </a>
                        </td>

                        <td class="py-3">
                            <div class="income-box">
                                <div class="fw-bold text-success mb-1">
                                    Rp {{ number_format($kurir->total_ongkir ?? 0, 0, ',', '.') }}
                                </div>
                                <small class="text-success fw-medium d-block" style="font-size: 0.75rem;">
                                    <i class="bi bi-shield-check me-1"></i> 100% Hak Kurir
                                </small>
                            </div>
                        </td>

                        <td class="text-center py-3">
                            @if($kurir->status == 'aktif')
                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Aktif</span>
                            @else
                            <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">Nonaktif</span>
                            @endif
                        </td>

                        <td class="text-center px-3 py-3">
                            <div class="d-flex justify-content-center gap-2">
                                {{-- Tombol Garasi Kendaraan --}}
                                <button type="button" class="btn btn-sm btn-action-garage rounded-pill px-3 fw-medium shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#modalKendaraan{{ $kurir->id }}">
                                    <i class="bi bi-car-front-fill me-1"></i> Garasi
                                </button>

                                {{-- Tombol Edit --}}
                                <button class="btn btn-sm btn-action-edit rounded-circle" style="width: 32px; height: 32px;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditKurir"
                                    data-id="{{ $kurir->id }}"
                                    data-nama="{{ $kurir->nama }}"
                                    data-nohp="{{ $kurir->no_hp }}"
                                    data-status="{{ $kurir->status }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.kurir.destroy', $kurir->id) }}" method="POST" class="m-0" onsubmit="return confirm('Hapus data kurir ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-action-delete rounded-circle" style="width: 32px; height: 32px;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <div class="py-4">
                                <i class="bi bi-truck fs-1 text-muted opacity-50 mb-2 d-block"></i>
                                Belum ada kurir terdaftar.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- 🌟 MODAL GARASI KENDARAAN (DI-LOOPING) 🌟 --}}
@foreach($kurirs as $kurir)
<div class="modal fade" id="modalKendaraan{{ $kurir->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0 mt-2 mx-2">
                <h5 class="fw-bold text-dark"><i class="bi bi-car-front-fill text-primary me-2"></i>Garasi Kendaraan - {{ $kurir->nama }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">

                {{-- Form Tambah Kendaraan --}}
                <div class="p-3 bg-light rounded-4 mb-4 border border-light-subtle shadow-sm">
                    <h6 class="fw-bold small mb-3 text-secondary">Daftarkan Kendaraan Baru</h6>
                    <form action="{{ route('admin.kurir.kendaraan.store', $kurir->id) }}" method="POST" class="row g-2 align-items-end">
                        @csrf
                        <div class="col-md-3">
                            <label class="form-label-bold">Jenis</label>
                            <select name="jenis_kendaraan" class="form-select input-clean" required>
                                <option value="">Pilih...</option>
                                <option value="Motor">🏍️ Motor</option>
                                <option value="Mobil">🚗 Mobil</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-bold">Merk Kendaraan</label>
                            <input type="text" name="merk_kendaraan" class="form-control input-clean" placeholder="Msl: Honda Beat" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-bold">Plat Nomor</label>
                            <input type="text" name="plat_nomor" class="form-control input-clean font-monospace text-uppercase" placeholder="D 1234 ABC" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100 fw-bold" style="border-radius: 12px; height: 44px;" title="Tambahkan">
                                <i class="bi bi-plus-lg"></i> Tambah
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Tabel Daftar Kendaraan --}}
                <h6 class="fw-bold small mb-2 text-dark">Daftar Kendaraan Aktif ({{ isset($kurir->kendaraans) ? $kurir->kendaraans->count() : 0 }} Unit)</h6>
                <div class="table-responsive border rounded-3">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-2 ps-3">Jenis</th>
                                <th class="py-2">Merk Kendaraan</th>
                                <th class="py-2">Plat Nomor</th>
                                <th class="py-2 text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kurir->kendaraans ?? [] as $kdr)
                            <tr>
                                <td class="py-2 ps-3 fw-bold">
                                    <i class="bi {{ $kdr->jenis_kendaraan == 'Motor' ? 'bi-bicycle' : 'bi-car-front-fill' }} text-primary me-1"></i> {{ $kdr->jenis_kendaraan }}
                                </td>
                                <td class="py-2">{{ $kdr->merk_kendaraan }}</td>
                                <td class="py-2"><span class="badge bg-secondary font-monospace">{{ $kdr->plat_nomor }}</span></td>
                                <td class="py-2 text-center">
                                    <form action="{{ route('admin.kurir.kendaraan.destroy', $kdr->id) }}" method="POST" onsubmit="return confirm('Hapus kendaraan ini dari garasi?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger py-0 px-2 rounded-2"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted small py-4">Belum ada kendaraan yang terdaftar di garasi ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endforeach

{{-- MODAL TAMBAH KURIR --}}
<div class="modal fade" id="modalTambahKurir" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 p-3 p-md-4 pb-2">
                <div>
                    <h5 class="fw-bold mb-1"><i class="bi bi-person-plus-fill text-success me-2"></i>Tambah Kurir Baru</h5>
                    <small class="text-muted">Daftarkan akun kurir internal baru.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.kurir.store') }}" method="POST">
                @csrf
                <div class="modal-body p-3 p-md-4 pt-2">
                    <div class="mb-3">
                        <label class="form-label-bold">Nama Lengkap Kurir</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-custom"><i class="bi bi-person"></i></span>
                            <input type="text" name="nama" class="form-control input-clean input-clean-group" placeholder="Masukkan nama..." required>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-md-6 mb-1">
                            <label class="form-label-bold">No. WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-custom"><i class="bi bi-phone"></i></span>
                                <input type="text" name="no_hp" class="form-control input-clean input-clean-group" placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-1">
                            <label class="form-label-bold">Status Awal</label>
                            <select name="status" class="form-select input-clean">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 bg-primary bg-opacity-10 mt-4 mb-0 rounded-3 small text-dark">
                        <i class="bi bi-info-circle-fill text-primary me-2"></i>
                        Setelah kurir dibuat, Anda bisa menambahkan Motor / Mobil di tombol <strong>Garasi</strong>.
                    </div>
                </div>
                <div class="modal-footer border-top-0 p-3 p-md-4 pt-0 d-grid gap-2 d-sm-flex justify-content-sm-end">
                    <button type="button" class="btn btn-light border rounded-pill px-4 w-100 w-sm-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 w-100 w-sm-auto"><i class="bi bi-check-lg me-1"></i>Simpan Kurir</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT KURIR --}}
<div class="modal fade" id="modalEditKurir" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 p-3 p-md-4 pb-2">
                <div>
                    <h5 class="fw-bold mb-1"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Data Kurir</h5>
                    <small class="text-muted">Perbarui informasi dasar kurir.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditKurir" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-3 p-md-4 pt-2">
                    <div class="mb-3">
                        <label class="form-label-bold">Nama Kurir</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-custom"><i class="bi bi-person"></i></span>
                            <input type="text" name="nama" id="edit-nama" class="form-control input-clean input-clean-group" required>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-md-6 mb-1">
                            <label class="form-label-bold">No. WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-custom"><i class="bi bi-phone"></i></span>
                                <input type="text" name="no_hp" id="edit-nohp" class="form-control input-clean input-clean-group" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-1">
                            <label class="form-label-bold">Status</label>
                            <select name="status" id="edit-status" class="form-select input-clean">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 p-3 p-md-4 pt-0 d-grid gap-2 d-sm-flex justify-content-sm-end">
                    <button type="button" class="btn btn-light border rounded-pill px-4 w-100 w-sm-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 w-100 w-sm-auto"><i class="bi bi-check-lg me-1"></i>Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Hanya mengatur data Kurir (Nama, HP, Status). Kendaraan diatur di Modal Garasi.
    const modalEditKurir = document.getElementById('modalEditKurir');
    if (modalEditKurir) {
        modalEditKurir.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');
            const nohp = button.getAttribute('data-nohp');
            const status = button.getAttribute('data-status');

            document.getElementById('edit-nama').value = nama;
            document.getElementById('edit-nohp').value = nohp;
            document.getElementById('edit-status').value = status;
            document.getElementById('formEditKurir').action = `/admin/kurir/update/${id}`;
        });
    }
</script>
@endpush
@endsection