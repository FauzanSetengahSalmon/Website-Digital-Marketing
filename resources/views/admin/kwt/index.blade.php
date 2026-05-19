@extends('layouts.admin')

@section('content')
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

    /* Pembungkus Input Icon */
    .input-field-modal {
        position: relative;
        width: 100%;
    }

    /* Icon Sisi Kiri Input */
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
        /* Spasi kanan kiri aman untuk ikon */
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

    /* Tombol Toggle Mata Terkunci Sempurna Di Dalam Input */
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
        <div>
            <h3 class="fw-bold text-dark m-0" style="letter-spacing: -0.5px;">Manajemen Akun KWT</h3>
            <p class="text-muted small m-0 mt-1">Kelola hak akses dan verifikasi akun Kelompok Wanita Tani.</p>
        </div>
        <button class="btn btn-success rounded-pill px-4 py-2 fw-semibold border-0 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKwt" style="background-color: #10b981;">
            <i class="fa-solid fa-plus me-2"></i> Tambah KWT Baru
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 p-3 d-flex align-items-center" style="background-color: #dcfce7; color: #15803d;">
        <i class="fa-solid fa-circle-check me-2 fs-5"></i>
        <span class="fw-medium">{{ session('success') }}</span>
    </div>
    @endif

    <div class="card custom-card overflow-hidden">
        <div class="table-responsive">
            <table class="table custom-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Nama KWT</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Status Login</th>
                        <th>Tanggal Terdaftar</th>
                        <th class="text-center" style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kwt as $item)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                @if($item->photo && file_exists(public_path('storage/' . $item->photo)))
                                <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->name }}" class="rounded-circle me-3 object-fit-cover" style="width: 38px; height: 38px;">
                                @elseif($item->photo && (str_contains($item->photo, 'http://') || str_contains($item->photo, 'https://')))
                                <img src="{{ $item->photo }}" alt="{{ $item->name }}" class="rounded-circle me-3 object-fit-cover" style="width: 38px; height: 38px;">
                                @else
                                <div class="me-3 bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; background-color: #10b981 !important; font-size: 14px;">
                                    {{ strtoupper(substr($item->name, 0, 1)) }}
                                </div>
                                @endif
                                <span class="fw-semibold text-dark">{{ $item->name }}</span>
                            </div>
                        </td>
                        <td class="text-secondary">{{ $item->email }}</td>
                        <td class="text-secondary">{{ $item->phone_number ?? $item->phone ?? '-' }}</td>
                        <td>
                            @if($item->email_verified_at)
                            <span class="badge badge-verified rounded-pill">
                                <i class="fa-solid fa-circle-check me-1"></i> Terverifikasi
                            </span>
                            @else
                            <span class="badge badge-unverified rounded-pill">
                                <i class="fa-solid fa-circle-exclamation me-1"></i> Perlu Verifikasi
                            </span>
                            @endif
                        </td>
                        <td class="text-secondary">{{ $item->created_at->format('d M Y') }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-action-edit rounded-pill px-3 fw-medium"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditKwt"
                                    data-id="{{ $item->id }}"
                                    data-name="{{ $item->name }}"
                                    data-email="{{ $item->email }}"
                                    data-phone="{{ $item->phone_number ?? $item->phone ?? '' }}">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                </button>

                                <form action="{{ route('admin.kwt.destroy', $item->id) }}" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun KWT ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-action-delete rounded-pill px-3 fw-medium">
                                        <i class="fa-solid fa-trash-can me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-folder-open fs-2 mb-3 d-block text-black-50"></i>
                            Belum ada akun KWT yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH KWT --}}
<div class="modal fade modal-premium" id="modalTambahKwt" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0 pt-4 px-4 d-flex align-items-start justify-content-between">
                <div>
                    <h4 class="modal-title fw-bold text-dark" style="letter-spacing: -0.5px;">Buat Akun KWT</h4>
                    <p class="text-muted small mb-0">Mulai integrasikan kelompok tani baru ke dalam sistem.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.kwt.store') }}" method="POST" id="tambahKwtForm">
                @csrf
                <div class="modal-body px-4 pt-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small mb-1">Nama KWT</label>
                        <div class="input-field-modal">
                            <i class="fa-solid fa-signature"></i>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: KWT Melati" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small mb-1">Alamat Email KWT</label>
                        <div class="input-field-modal">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" name="email" class="form-control" placeholder="kwtmelati@email.com" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small mb-1">No. Telepon Pengurus</label>
                        <div class="input-field-modal">
                            <i class="fa-solid fa-phone"></i>
                            <input type="text" name="phone_number" class="form-control" placeholder="0812xxxxxxx" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small mb-1">Password Utama</label>
                        <div class="input-field-modal">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" name="password" id="password_tambah" class="form-control" placeholder="Min. 8 Karakter" required>
                            <span class="password-toggle-modal" onclick="togglePasswordModal('password_tambah', this)">
                                <i class="fa-solid fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 mt-2">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-medium" data-bs-dismiss="modal" style="border-radius: 12px !important; padding: 10px 20px;">Batal</button>
                    <button type="submit" class="btn btn-premium-save rounded-pill px-4" id="submitBtnTambah" style="border-radius: 12px !important; padding: 10px 20px;">
                        Simpan Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT KWT --}}
<div class="modal fade modal-premium" id="modalEditKwt" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0 pt-4 px-4 d-flex align-items-start justify-content-between">
                <div>
                    <h4 class="modal-title fw-bold text-dark" style="letter-spacing: -0.5px;">Ubah Akun KWT</h4>
                    <p class="text-muted small mb-0">Perbarui data informasi profil akun KWT terpilih.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditKwt" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body px-4 pt-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small mb-1">Nama KWT</label>
                        <div class="input-field-modal">
                            <i class="fa-solid fa-signature"></i>
                            <input type="text" name="name" id="edit-name" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small mb-1">Alamat Email KWT</label>
                        <div class="input-field-modal">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" name="email" id="edit-email" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small mb-1">No. Telepon Pengurus</label>
                        <div class="input-field-modal">
                            <i class="fa-solid fa-phone"></i>
                            <input type="text" name="phone_number" id="edit-phone" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small mb-1">Password Baru (Opsional)</label>
                        <div class="input-field-modal">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" name="password" id="password_edit" class="form-control" placeholder="Kosongkan jika tidak ingin diubah">
                            <span class="password-toggle-modal" onclick="togglePasswordModal('password_edit', this)">
                                <i class="fa-solid fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 mt-2">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-medium" data-bs-dismiss="modal" style="border-radius: 12px !important; padding: 10px 20px;">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold" style="border-radius: 12px !important; padding: 10px 20px; background-color: #2563eb; border: none;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Inject Data ke Modal Edit secara Otomatis
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

    // Toggle Lihat/Sembunyikan Password Modal
    function togglePasswordModal(id, el) {
        const input = document.getElementById(id);
        if (input.type === 'password') {
            input.type = 'text';
            el.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
        } else {
            input.type = 'password';
            el.innerHTML = '<i class="fa-solid fa-eye"></i>';
        }
    }

    // Efek Loading Transisi Saat Tambah Data
    document.getElementById('tambahKwtForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtnTambah');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Memproses...';
    });
</script>
@endpush
@endsection