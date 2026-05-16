@extends('layouts.kwt')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Atas Halaman -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h2 class="fw-bold text-dark mb-0">Manajemen Kurir Internal</h2>
            <p class="text-muted mb-0">Kelola kurir kelompok tani untuk pengantaran langsung ke customer.</p>
        </div>
        <!-- BUTTON BERADA DI ATAS SEBELAH KANAN TABEL -->
        <div>
            <button type="button" class="btn btn-success rounded-3 px-4 shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambahKurir">
                <i class="bi bi-plus-lg me-2"></i> Tambah Kurir Baru
            </button>
        </div>
    </div>

    <!-- Notifikasi Sukses Terupdate -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Card Data Tabel Utama -->
    <div class="card border-0 rounded-4 shadow-sm p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-0 px-3 py-3">Nama Kurir</th>
                        <th class="border-0 py-3">No. Handphone</th>
                        <th class="border-0 py-3">Info Kendaraan</th>
                        <th class="border-0 py-3 text-center">Status</th>
                        <th class="border-0 px-3 py-3 text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kurirs as $kurir)
                    <tr>
                        <td class="fw-bold text-dark px-3">{{ $kurir->nama }}</td>
                        <td>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kurir->no_hp) }}" target="_blank" class="text-decoration-none text-secondary">
                                <i class="bi bi-whatsapp text-success me-1"></i> {{ $kurir->no_hp }}
                            </a>
                        </td>
                        <td>{{ $kurir->kendaraan ?? '-' }}</td>
                        <td class="text-center">
                            @if($kurir->status == 'aktif')
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill small fw-medium">Aktif</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill small fw-medium">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center px-3">
                            <div class="d-flex justify-content-center gap-1">
                                <!-- Aksi Edit via Modal Dynamic ID -->
                                <button class="btn btn-sm btn-outline-primary rounded-3" data-bs-toggle="modal" data-bs-target="#modalEditKurir{{ $kurir->id }}">
                                    <i class="bi bi-pencil shadow-sm"></i>
                                </button>
                                <!-- Aksi Delete Form -->
                                <form action="{{ route('kwt.kurir.destroy', $kurir->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kurir ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                        <i class="bi bi-trash shadow-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- MODAL DI DALAM LOOP UNTUK EDIT DATA KURIR -->
                    <div class="modal fade" id="modalEditKurir{{ $kurir->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header border-bottom-0 pb-0">
                                    <h5 class="fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-primary"></i> Ubah Data Kurir</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('kwt.kurir.update', $kurir->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body py-3">
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Nama Lengkap</label>
                                            <input type="text" name="nama" class="form-control rounded-3" value="{{ $kurir->nama }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">No. HP / WhatsApp</label>
                                            <input type="text" name="no_hp" class="form-control rounded-3" value="{{ $kurir->no_hp }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Kendaraan</label>
                                            <input type="text" name="kendaraan" class="form-control rounded-3" value="{{ $kurir->kendaraan }}">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label small fw-semibold">Status Keaktifan</label>
                                            <select name="status" class="form-select rounded-3" required>
                                                <option value="aktif" {{ $kurir->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                <option value="nonaktif" {{ $kurir->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-bicycle fs-2 d-block text-secondary opacity-50 mb-2"></i>
                            Belum ada kurir terdaftar. Klik tombol di atas untuk menambahkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL POP UP FORM UNTUK TAMBAH KURIR BARU -->
<div class="modal fade" id="modalTambahKurir" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="fw-bold text-dark"><i class="bi bi-person-plus-fill me-2 text-success"></i> Tambah Kurir Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('kwt.kurir.store') }}" method="POST">
                @csrf
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Kurir</label>
                        <input type="text" name="nama" class="form-control rounded-3" required placeholder="Nama lengkap kurir">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" class="form-control rounded-3" required placeholder="Contoh: 081234567xxx">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Kendaraan (Opsional)</label>
                        <input type="text" name="kendaraan" class="form-control rounded-3" placeholder="Contoh: Motor Honda Beat / Plat No">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Status Awal</label>
                        <select name="status" class="form-select rounded-3" required>
                            <option value="aktif">Aktif (Siap Ditugaskan)</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection