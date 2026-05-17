@extends('layouts.admin')

@section('content')
<div class="container-fluid py-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark">Manajemen Akun KWT</h3>
        <button class="btn btn-success rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambahKwt">
            <i class="bi bi-plus-lg me-2"></i> Tambah KWT Baru
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Nama KWT</th>
                        <th>Email</th>
                        <th>Tanggal Terdaftar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kwt as $item)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                {{-- LOGIKA DETEKSI FOTO PROFIL KWT --}}
                                @if($item->photo && file_exists(public_path('storage/' . $item->photo)))
                                    <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->name }}" class="rounded-circle me-3 object-fit-cover" style="width: 40px; height: 40px; flex-shrink: 0;">
                                @elseif($item->photo && (str_contains($item->photo, 'http://') || str_contains($item->photo, 'https://')))
                                    <img src="{{ $item->photo }}" alt="{{ $item->name }}" class="rounded-circle me-3 object-fit-cover" style="width: 40px; height: 40px; flex-shrink: 0;">
                                @else
                                    <div class="avatar-sm me-3 bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold; flex-shrink: 0;">
                                        {{ strtoupper(substr($item->name, 0, 1)) }}
                                    </div>
                                @endif
                                
                                <span class="fw-bold">{{ $item->name }}</span>
                            </div>
                        </td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                        <td class="text-center">
                            <!-- Tombol Edit Terintegrasi Data Properti -->
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEditKwt" 
                                    data-id="{{ $item->id }}" 
                                    data-name="{{ $item->name }}" 
                                    data-email="{{ $item->email }}">
                                Edit
                            </button>
                            
                            <!-- Form Delete Terintegrasi Konfirmasi -->
                            <form action="{{ route('admin.kwt.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun KWT ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">Belum ada akun KWT yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH KWT --}}
<div class="modal fade" id="modalTambahKwt" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Buat Akun KWT Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.kwt.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama KWT</label>
                        <input type="text" name="name" class="form-control rounded-3" placeholder="Contoh: KWT Melati" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email KWT</label>
                        <input type="email" name="email" class="form-control rounded-3" placeholder="kwt@email.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Default</label>
                        <input type="password" name="password" class="form-control rounded-3" placeholder="Minimal 8 karakter" required>
                        <small class="text-muted">Berikan password ini ke pengurus KWT terkait.</small>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT KWT --}}
<div class="modal fade" id="modalEditKwt" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Ubah Akun KWT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditKwt" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama KWT</label>
                        <input type="text" name="name" id="edit-name" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email KWT</label>
                        <input type="email" name="email" id="edit-email" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Baru</label>
                        <input type="password" name="password" class="form-control rounded-3" placeholder="Kosongkan jika tidak ingin diubah">
                        <small class="text-muted">Isi hanya jika ingin memperbarui kata sandi akun KWT ini.</small>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const modalEditKwt = document.getElementById('modalEditKwt');
    if (modalEditKwt) {
        modalEditKwt.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            
            // Ekstrak data dari atribut data-* tombol edit
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const email = button.getAttribute('data-email');
            
            // Masukkan data ke field input dalam modal edit
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-email').value = email;
            
            // Sesuaikan endpoint action form secara dinamis
            document.getElementById('formEditKwt').action = `/admin/kwt/update/${id}`;
        });
    }
</script>
@endpush
@endsection