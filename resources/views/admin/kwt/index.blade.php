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
                                <div class="avatar-sm me-3 bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold;">
                                    {{ strtoupper(substr($item->name, 0, 1)) }}
                                </div>
                                <span class="fw-bold">{{ $item->name }}</span>
                            </div>
                        </td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3">Edit</button>
                            <button class="btn btn-sm btn-outline-danger rounded-pill px-3">Hapus</button>
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
@endsection