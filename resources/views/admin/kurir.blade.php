@extends('layouts.admin')

@section('content')
<style>
    .form-label-bold {
        font-size: 0.85rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 4px;
    }

    .input-clean {
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .input-clean:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }

    .input-group-text-custom {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-right: none;
        border-radius: 10px 0 0 10px;
        color: #64748b;
    }

    .input-clean-group {
        border-radius: 0 10px 10px 0 !important;
    }

    .petunjuk-admin {
        font-size: 0.72rem;
        color: #64748b;
        margin-top: 4px;
        display: block;
        font-style: italic;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h2 class="fw-bold text-dark mb-0">Manajemen Kurir Internal</h2>
            <p class="text-muted mb-0">Kelola kurir dan pantau pendapatan (Potongan Admin 15%).</p>
        </div>
        <div>
            <button type="button" class="btn btn-success rounded-pill px-4 shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambahKurir">
                <i class="bi bi-plus-lg me-2"></i> Tambah Kurir Baru
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 rounded-4 shadow-sm p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-0 px-3 py-3">Nama Kurir</th>
                        <th class="border-0 py-3">Kendaraan</th>
                        <th class="border-0 py-3">No. Handphone</th>
                        <th class="border-0 py-3">Pendapatan Bersih</th>
                        <th class="border-0 py-3 text-center">Status</th>
                        <th class="border-0 px-3 py-3 text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kurirs as $kurir)
                    <tr>
                        <td class="fw-bold text-dark px-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    <i class="bi bi-person text-secondary"></i>
                                </div>
                                <span>{{ $kurir->nama }}</span>
                            </div>
                        </td>
                        <td class="text-secondary small">{{ $kurir->kendaraan ?? '-' }}</td>
                        <td>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kurir->no_hp) }}" target="_blank" class="text-decoration-none text-secondary">
                                <i class="bi bi-whatsapp text-success me-1"></i> {{ $kurir->no_hp }}
                            </a>
                        </td>
                        <td>
                            <div class="fw-bold text-success">Rp {{ number_format($kurir->pendapatan_bersih ?? 0, 0, ',', '.') }}</div>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">
                                Total: Rp {{ number_format($kurir->total_ongkir ?? 0, 0, ',', '.') }}
                                <span class="text-danger">(Admin 15%: {{ number_format($kurir->potongan_admin ?? 0, 0, ',', '.') }})</span>
                            </small>
                        </td>
                        <td class="text-center">
                            @if($kurir->status == 'aktif')
                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill small fw-medium">Aktif</span>
                            @else
                            <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill small fw-medium">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center px-3">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('admin.kurir.laporan', $kurir->id) }}" class="btn btn-sm btn-outline-success rounded-3" title="Cetak Laporan Penghasilan">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-primary rounded-3" data-bs-toggle="modal" data-bs-target="#modalEditKurir{{ $kurir->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.kurir.destroy', $kurir->id) }}" method="POST" onsubmit="return confirm('Hapus data kurir?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- MODAL EDIT KURIR --}}
                    <div class="modal fade" id="modalEditKurir{{ $kurir->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
                            <div class="modal-content rounded-4 border-0 shadow-lg">
                                <div class="modal-header border-bottom-0 p-4 pb-2">
                                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Ubah Data Kurir</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.kurir.update', $kurir->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body p-4 pt-2">
                                        <p class="text-muted small mb-4">Perbarui informasi data diri dan status kurir internal.</p>

                                        <div class="mb-3">
                                            <label class="form-label-bold">Nama Lengkap Kurir</label>
                                            <div class="input-group">
                                                <span class="input-group-text input-group-text-custom"><i class="bi bi-person"></i></span>
                                                <input type="text" name="nama" class="form-control input-clean input-clean-group" value="{{ $kurir->nama }}" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label-bold">No. Handphone (WhatsApp)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text input-group-text-custom"><i class="bi bi-phone"></i></span>
                                                    <input type="text" name="no_hp" class="form-control input-clean input-clean-group" value="{{ $kurir->no_hp }}" placeholder="Contoh: 08123xxx" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label-bold">Status Keaktifan</label>
                                                <select name="status" class="form-select input-clean">
                                                    <option value="aktif" {{ $kurir->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                    <option value="nonaktif" {{ $kurir->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label-bold">Detail Kendaraan</label>
                                            <div class="input-group">
                                                <span class="input-group-text input-group-text-custom"><i class="bi bi-truck"></i></span>
                                                <input type="text" name="kendaraan" class="form-control input-clean input-clean-group" value="{{ $kurir->kendaraan }}" placeholder="Contoh: Motor Beat (D 123 XY)">
                                            </div>
                                            <small class="petunjuk-admin">*Status nonaktif membuat kurir tidak dapat menerima pesanan baru</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0 p-4 pt-0 d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-light border rounded-pill px-4 fw-bold small py-2" data-bs-dismiss="modal">
                                            <i class="bi bi-x-lg me-1"></i> Batal
                                        </button>
                                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold py-2">
                                            <i class="bi bi-check-lg me-1"></i> Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">Belum ada kurir terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH KURIR --}}
<div class="modal fade" id="modalTambahKurir" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0 p-4 pb-2">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-plus-circle-fill text-success me-2"></i>Tambah Kurir Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.kurir.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 pt-2">
                    <p class="text-muted small mb-4">Daftarkan personel kurir internal baru untuk menangani distribusi logistik KWT.</p>

                    <div class="mb-3">
                        <label class="form-label-bold">Nama Lengkap Kurir</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-custom"><i class="bi bi-person"></i></span>
                            <input type="text" name="nama" class="form-control input-clean input-clean-group" placeholder="Masukkan nama kurir..." required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-bold">No. Handphone (WhatsApp)</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-custom"><i class="bi bi-phone"></i></span>
                                <input type="text" name="no_hp" class="form-control input-clean input-clean-group" placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-bold">Status Awal</label>
                            <select name="status" class="form-select input-clean">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label-bold">Detail Kendaraan</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-custom"><i class="bi bi-truck"></i></span>
                            <input type="text" name="kendaraan" class="form-control input-clean input-clean-group" placeholder="Contoh: Motor Tossa / Suzuki Carry">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 p-4 pt-0 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light border rounded-pill px-4 fw-bold small py-2" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold py-2">
                        <i class="bi bi-check-lg me-1"></i> Simpan Kurir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection