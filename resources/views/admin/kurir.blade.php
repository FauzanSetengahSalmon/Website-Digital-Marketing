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

    .kendaraan-badge {
        background: #ecfdf5;
        color: #047857;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
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
                Kelola data kurir, kendaraan operasional, dan pendapatan distribusi.
            </p>
        </div>

        <button type="button"
            class="btn btn-success rounded-pill px-4 py-2 shadow-sm fw-semibold w-100 w-md-auto"
            data-bs-toggle="modal"
            data-bs-target="#modalTambahKurir">

            <i class="bi bi-plus-lg me-2"></i>
            Tambah Kurir Baru
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}

        <button type="button"
            class="btn-close"
            data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="page-card shadow-sm p-3 p-md-4">

        <div class="table-responsive custom-scrollbar">

            <table class="table align-middle table-hover mb-0 text-nowrap">

                <thead>
                    <tr>
                        <th class="px-3 py-3">Kurir</th>
                        <th>Kendaraan</th>
                        <th>No. WhatsApp</th>
                        <th>Pendapatan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($kurirs as $kurir)

                    <tr>

                        <td class="px-3 py-3">

                            <div class="d-flex align-items-center gap-3">

                                <div class="kurir-avatar">
                                    <i class="bi bi-person text-secondary"></i>
                                </div>

                                <div>
                                    <div class="fw-bold text-dark">
                                        {{ $kurir->nama }}
                                    </div>

                                    <small class="text-muted">
                                        Kurir Internal
                                    </small>
                                </div>

                            </div>

                        </td>

                        <td class="py-3">

                            @php
                            $kendaraan = strtolower($kurir->kendaraan ?? '');
                            @endphp

                            <span class="kendaraan-badge shadow-sm border border-success border-opacity-10">

                                @if(str_contains($kendaraan, 'mobil') || str_contains($kendaraan, 'carry'))
                                <i class="bi bi-car-front-fill me-1"></i>
                                @elseif(str_contains($kendaraan, 'motor') || str_contains($kendaraan, 'beat') || str_contains($kendaraan, 'vario'))
                                <i class="bi bi-bicycle me-1"></i>
                                @else
                                <i class="bi bi-truck me-1"></i>
                                @endif

                                {{ $kurir->kendaraan ?? '-' }}

                            </span>

                        </td>

                        <td class="py-3">

                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kurir->no_hp) }}"
                                target="_blank"
                                class="text-decoration-none text-secondary fw-medium">

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

                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                Aktif
                            </span>

                            @else

                            <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                Nonaktif
                            </span>

                            @endif

                        </td>

                        <td class="text-center px-3 py-3">

                            <div class="d-flex justify-content-center gap-2">

                                <button class="btn btn-sm btn-outline-primary rounded-3"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditKurir{{ $kurir->id }}">

                                    <i class="bi bi-pencil"></i>
                                </button>

                                <form action="{{ route('admin.kurir.destroy', $kurir->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Hapus data kurir ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="btn btn-sm btn-outline-danger rounded-3">

                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    <div class="modal fade"
                        id="modalEditKurir{{ $kurir->id }}"
                        tabindex="-1"
                        data-bs-backdrop="static">

                        <div class="modal-dialog modal-dialog-centered"
                            style="max-width: 540px;">

                            <div class="modal-content border-0 shadow-lg">

                                <div class="modal-header border-0 p-3 p-md-4 pb-2">

                                    <div>
                                        <h5 class="fw-bold mb-1">
                                            <i class="bi bi-pencil-square text-primary me-2"></i>
                                            Edit Data Kurir
                                        </h5>

                                        <small class="text-muted">
                                            Perbarui informasi kurir internal.
                                        </small>
                                    </div>

                                    <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"></button>
                                </div>

                                <form action="{{ route('admin.kurir.update', $kurir->id) }}"
                                    method="POST">

                                    @csrf
                                    @method('PUT')

                                    <div class="modal-body p-3 p-md-4 pt-2">

                                        <div class="mb-3">
                                            <label class="form-label-bold">
                                                Nama Kurir
                                            </label>

                                            <div class="input-group">
                                                <span class="input-group-text input-group-text-custom">
                                                    <i class="bi bi-person"></i>
                                                </span>

                                                <input type="text"
                                                    name="nama"
                                                    class="form-control input-clean input-clean-group"
                                                    value="{{ $kurir->nama }}"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="row g-3">

                                            <div class="col-12 col-md-6 mb-1">

                                                <label class="form-label-bold">
                                                    No. WhatsApp
                                                </label>

                                                <div class="input-group">
                                                    <span class="input-group-text input-group-text-custom">
                                                        <i class="bi bi-phone"></i>
                                                    </span>

                                                    <input type="text"
                                                        name="no_hp"
                                                        class="form-control input-clean input-clean-group"
                                                        value="{{ $kurir->no_hp }}"
                                                        required>
                                                </div>

                                            </div>

                                            <div class="col-12 col-md-6 mb-1">

                                                <label class="form-label-bold">
                                                    Status
                                                </label>

                                                <select name="status"
                                                    class="form-select input-clean">

                                                    <option value="aktif" {{ $kurir->status == 'aktif' ? 'selected' : '' }}>
                                                        Aktif
                                                    </option>

                                                    <option value="nonaktif" {{ $kurir->status == 'nonaktif' ? 'selected' : '' }}>
                                                        Nonaktif
                                                    </option>

                                                </select>

                                            </div>

                                        </div>

                                        @php
                                        $dbKendaraan = $kurir->kendaraan ?? '';
                                        $isMobil = str_contains(strtolower($dbKendaraan), 'mobil');

                                        $parts = explode(' - ', $dbKendaraan, 2);
                                        if (count($parts) == 2) {
                                        $platValue = $parts[1];
                                        } else {
                                        $platValue = trim(str_ireplace(['motor', 'mobil', '-'], '', $dbKendaraan));
                                        }
                                        @endphp

                                        <div class="mb-2 mt-3">

                                            <label class="form-label-bold">
                                                Tipe Kendaraan & Detail
                                            </label>

                                            <div class="input-group">
                                                <select id="jenis_edit_{{ $kurir->id }}" class="form-select input-clean" style="max-width: 130px; border-radius: 12px 0 0 12px; background-color: #f8fafc; font-weight: 600;" onchange="updateKendaraanEdit('{{ $kurir->id }}')">
                                                    <option value="Motor" {{ !$isMobil ? 'selected' : '' }}>🏍️ Motor</option>
                                                    <option value="Mobil" {{ $isMobil ? 'selected' : '' }}>🚗 Mobil</option>
                                                </select>

                                                <input type="text"
                                                    id="plat_edit_{{ $kurir->id }}"
                                                    class="form-control input-clean input-clean-group"
                                                    value="{{ $platValue }}"
                                                    placeholder="Cth: Beat - D 1234 XYZ"
                                                    oninput="updateKendaraanEdit('{{ $kurir->id }}')"
                                                    required>
                                            </div>

                                            <input type="hidden" name="kendaraan" id="hasil_kendaraan_edit_{{ $kurir->id }}" value="{{ $dbKendaraan }}">

                                            <small class="petunjuk-admin">
                                                Format akan digabung jadi "Motor - Beat D 1234 XYZ" untuk dilihat oleh pelanggan.
                                            </small>

                                        </div>

                                    </div>

                                    <div class="modal-footer border-0 p-3 p-md-4 pt-0 d-grid gap-2 d-sm-flex justify-content-sm-end">

                                        <button type="button"
                                            class="btn btn-light border rounded-pill px-4 w-100 w-sm-auto"
                                            data-bs-dismiss="modal">
                                            Batal
                                        </button>

                                        <button type="submit"
                                            class="btn btn-primary rounded-pill px-4 w-100 w-sm-auto">
                                            <i class="bi bi-check-lg me-1"></i>
                                            Simpan
                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                    @empty

                    <tr>
                        <td colspan="6"
                            class="text-center text-muted py-5">
                            Belum ada kurir terdaftar.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<div class="modal fade"
    id="modalTambahKurir"
    tabindex="-1"
    data-bs-backdrop="static">

    <div class="modal-dialog modal-dialog-centered"
        style="max-width: 540px;">

        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header border-0 p-3 p-md-4 pb-2">

                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-plus-circle-fill text-success me-2"></i>
                        Tambah Kurir Baru
                    </h5>

                    <small class="text-muted">
                        Tambahkan data kurir internal baru.
                    </small>
                </div>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('admin.kurir.store') }}" method="POST">
                @csrf

                <div class="modal-body p-3 p-md-4 pt-2">

                    <div class="mb-3">
                        <label class="form-label-bold">Nama Lengkap Kurir</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-custom">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" name="nama" class="form-control input-clean input-clean-group" placeholder="Masukkan nama kurir..." required>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6 mb-1">
                            <label class="form-label-bold">No. WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-custom">
                                    <i class="bi bi-phone"></i>
                                </span>
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

                    <div class="mb-2 mt-3">
                        <label class="form-label-bold">Tipe Kendaraan & Detail</label>

                        <div class="input-group">
                            <select id="jenis_tambah" class="form-select input-clean" style="max-width: 130px; border-radius: 12px 0 0 12px; background-color: #f8fafc; font-weight: 600;" onchange="updateKendaraanTambah()">
                                <option value="Motor">🏍️ Motor</option>
                                <option value="Mobil">🚗 Mobil</option>
                            </select>

                            <input type="text" id="plat_tambah" class="form-control input-clean input-clean-group" placeholder="Cth: Beat - D 1234 XYZ" oninput="updateKendaraanTambah()" required>
                        </div>

                        <input type="hidden" name="kendaraan" id="hasil_kendaraan_tambah" value="Motor - ">

                        <small class="petunjuk-admin mt-1">
                            Isi dengan model kendaraan dan plat nomor (seperti Gojek/Grab) untuk ditunjukkan ke pelanggan.
                        </small>
                    </div>

                </div>

                <div class="modal-footer border-0 p-3 p-md-4 pt-0 d-grid gap-2 d-sm-flex justify-content-sm-end">
                    <button type="button" class="btn btn-light border rounded-pill px-4 w-100 w-sm-auto" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-success rounded-pill px-4 w-100 w-sm-auto">
                        <i class="bi bi-check-lg me-1"></i>
                        Simpan Kurir
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

<script>
    function updateKendaraanTambah() {
        let jenis = document.getElementById('jenis_tambah').value;
        let plat = document.getElementById('plat_tambah').value;

        document.getElementById('hasil_kendaraan_tambah').value = jenis + ' - ' + plat;
    }

    function updateKendaraanEdit(id) {
        let jenis = document.getElementById('jenis_edit_' + id).value;
        let plat = document.getElementById('plat_edit_' + id).value;

        document.getElementById('hasil_kendaraan_edit_' + id).value = jenis + ' - ' + plat;
    }
</script>
@endsection