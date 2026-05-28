@extends('layouts.admin')

@section('content')
<<<<<<< HEAD
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Global Card & Table Styling */
    .custom-card {
        background: #ffffff;
        border-radius: 20px !important;
        border: none !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03) !important;
    }

    .custom-table thead {
        background-color: #f8fafc;
    }

    .custom-table th {
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 18px 20px !important;
        border-bottom: 1px solid #edf2f7 !important;
    }

    .custom-table td {
        padding: 16px 20px !important;
        color: #334155;
        font-size: 14px;
        border-bottom: 1px solid #f1f5f9 !important;
    }

    .custom-table tr:last-child td {
        border-bottom: none !important;
    }

    /* Status Badge Styling */
    .badge-verified {
        background-color: #dcfce7 !important;
        color: #15803d !important;
        font-weight: 600;
        padding: 6px 14px;
        font-size: 12px;
    }

    .badge-unverified {
        background-color: #fef3c7 !important;
        color: #b45309 !important;
        font-weight: 600;
        padding: 6px 14px;
        font-size: 12px;
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

    /* Modal Form Premium Styling */
    .modal-premium .modal-content {
        border: none !important;
        border-radius: 24px !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1) !important;
    }

    .input-field-modal {
        position: relative;
        width: 100%;
    }

    .input-field-modal>i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #10b981;
        font-size: 14px;
        z-index: 5;
    }

    .input-field-modal input {
        width: 100%;
        padding: 12px 40px 12px 48px !important;
        background: #f9fafb;
        border: 1.5px solid #e5e7eb;
        border-radius: 14px !important;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .input-field-modal input:focus {
        border-color: #10b981 !important;
        background: white;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1) !important;
        outline: none;
    }

    .password-toggle-modal {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6b7280;
        z-index: 5;
        padding: 4px;
    }

    .password-toggle-modal:hover {
        color: #10b981;
    }

    .btn-premium-save {
        background: #10b981 !important;
        border: none !important;
        color: white !important;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-premium-save:hover {
        background: #065f46 !important;
        transform: translateY(-1px);
    }
</style>

<div class="container-fluid py-4 px-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
=======
<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
>>>>>>> 331fc6b73615be611e4252b2c16ffde800b6bb68
        <div>
            <h2 class="fw-bold text-dark mb-0">Manajemen Akun KWT</h2>
            <p class="text-muted mb-0">Kelola hak akses dan verifikasi akun Kelompok Wanita Tani.</p>
        </div>
        <div>
            <button type="button" class="btn btn-success rounded-3 px-4 shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambahKwt">
                <i class="bi bi-plus-lg me-2"></i> Tambah KWT Baru
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
<<<<<<< HEAD
                        <th class="ps-4">Nama KWT</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Status Login</th>
                        <th>Tanggal Terdaftar</th>
                        <th class="text-center" style="width: 150px;">Aksi</th>
=======
                        <th class="border-0 px-3 py-3">Nama KWT</th>
                        <th class="border-0 py-3">Email</th>
                        <th class="border-0 py-3">No. Handphone</th>
                        <th class="border-0 py-3 text-center">Status Login</th>
                        <th class="border-0 py-3">Tanggal Terdaftar</th>
                        <th class="border-0 px-3 py-3 text-center" style="width: 200px;">Aksi</th>
>>>>>>> 331fc6b73615be611e4252b2c16ffde800b6bb68
                    </tr>
                </thead>
                <tbody>
                    @forelse($kwt as $item)
                    <tr>
                        <td class="fw-bold text-dark px-3">
                            <div class="d-flex align-items-center">
                                @if($item->photo && file_exists(public_path('storage/' . $item->photo)))
                                <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->name }}" class="rounded-circle me-3 object-fit-cover" style="width: 38px; height: 38px;">
                                @elseif($item->photo && (str_contains($item->photo, 'http://') || str_contains($item->photo, 'https://')))
                                <img src="{{ $item->photo }}" alt="{{ $item->name }}" class="rounded-circle me-3 object-fit-cover" style="width: 38px; height: 38px;">
                                @else
                                <div class="me-3 bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; background-color: #16a34a !important; font-size: 14px;">
                                    {{ strtoupper(substr($item->name, 0, 1)) }}
                                </div>
                                @endif
                                <span>{{ $item->name }}</span>
                            </div>
                        </td>
                        <td class="text-secondary">{{ $item->email }}</td>
                        <td>
                            @if($item->phone_number || $item->phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->phone_number ?? $item->phone) }}" target="_blank" class="text-decoration-none text-secondary">
                                <i class="bi bi-whatsapp text-success me-1"></i> {{ $item->phone_number ?? $item->phone }}
                            </a>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->email_verified_at)
                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill small fw-medium">Terverifikasi</span>
                            @else
                            <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill small fw-medium">Perlu Verifikasi</span>
                            @endif
                        </td>
                        <td class="text-secondary">{{ $item->created_at->format('d M Y') }}</td>
<<<<<<< HEAD
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                {{-- Tombol Edit --}}
                                <button type="button" class="btn btn-sm btn-action-edit rounded-pill px-3 fw-medium"
                                    data-bs-toggle="modal"
=======
                        <td class="text-center px-3">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('admin.kwt.laporan', $item->id) }}" class="btn btn-sm btn-outline-success rounded-3" title="Cetak Laporan Penghasilan">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-primary rounded-3" 
                                    data-bs-toggle="modal" 
>>>>>>> 331fc6b73615be611e4252b2c16ffde800b6bb68
                                    data-bs-target="#modalEditKwt"
                                    data-id="{{ $item->id }}"
                                    data-name="{{ $item->name }}"
                                    data-email="{{ $item->email }}"
                                    data-phone="{{ $item->phone_number ?? $item->phone ?? '' }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
<<<<<<< HEAD

                                {{-- Tombol Hapus --}}
=======
>>>>>>> 331fc6b73615be611e4252b2c16ffde800b6bb68
                                <form action="{{ route('admin.kwt.destroy', $item->id) }}" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun KWT ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">Belum ada akun KWT yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH KWT --}}
<div class="modal fade" id="modalTambahKwt" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="fw-bold text-dark">Tambah KWT Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.kwt.store') }}" method="POST" id="tambahKwtForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama KWT</label>
                        <input type="text" name="name" class="form-control rounded-3" placeholder="Contoh: KWT Melati" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Alamat Email KWT</label>
                        <input type="email" name="email" class="form-control rounded-3" placeholder="kwtmelati@email.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">No. Telepon Pengurus</label>
                        <input type="text" name="phone_number" class="form-control rounded-3" placeholder="0812xxxxxxx" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Password Utama</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password_tambah" class="form-control rounded-3" placeholder="Min. 8 Karakter" required style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordModal('password_tambah', this)" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="submit" class="btn btn-success rounded-pill px-4" id="submitBtnTambah">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT KWT --}}
<div class="modal fade" id="modalEditKwt" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="fw-bold text-dark">Ubah Akun KWT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditKwt" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama KWT</label>
                        <input type="text" name="name" id="edit-name" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Alamat Email KWT</label>
                        <input type="email" name="email" id="edit-email" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">No. Telepon Pengurus</label>
                        <input type="text" name="phone_number" id="edit-phone" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Password Baru (Opsional)</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password_edit" class="form-control rounded-3" placeholder="Kosongkan jika tidak ingin diubah" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordModal('password_edit', this)" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
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
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const email = button.getAttribute('data-email');
            const phone = button.getAttribute('data-phone');

            document.getElementById('edit-name').value = name ?? '';
            document.getElementById('edit-email').value = email ?? '';
            document.getElementById('edit-phone').value = phone ?? '';
            document.getElementById('formEditKwt').action = `/admin/kwt/update/${id}`;
        });
    }

<<<<<<< HEAD
    function togglePasswordModal(id, el) {
=======
    // Toggle Lihat/Sembunyikan Password Modal
    function togglePasswordModal(id, btn) {
>>>>>>> 331fc6b73615be611e4252b2c16ffde800b6bb68
        const input = document.getElementById(id);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    document.getElementById('tambahKwtForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtnTambah');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memproses...';
    });
</script>
@endpush
@endsection