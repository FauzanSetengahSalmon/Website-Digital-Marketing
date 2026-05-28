@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Styling agar garis tabel tipis dan elegan */
    .table-bordered {
        border: 1px solid #e2e8f0 !important;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #e2e8f0 !important;
    }

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
    }

    .custom-table td {
        padding: 16px 20px !important;
        color: #334155;
        font-size: 14px;
    }

    .modal-premium .modal-content {
        border: none !important;
        border-radius: 24px !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1) !important;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
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
            <table class="table table-hover table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-3 py-3">Nama KWT</th>
                        <th class="py-3 text-center">Email</th>
                        <th class="py-3 text-center">No. Handphone</th>
                        <th class="py-3 text-center">Status Login</th>
                        <th class="py-3 text-center">Tanggal Terdaftar</th>
                        <th class="px-3 py-3 text-center" style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kwt as $item)
                    <tr>
                        <td class="fw-bold text-dark px-3" style="font-size: 14px;">
                            <div class="d-flex align-items-center">
                                @if($item->photo && file_exists(public_path('storage/' . $item->photo)))
                                <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->name }}" class="rounded-circle me-3 object-fit-cover" style="width: 30px; height: 30px;">
                                @elseif($item->photo && (str_contains($item->photo, 'http://') || str_contains($item->photo, 'https://')))
                                <img src="{{ $item->photo }}" alt="{{ $item->name }}" class="rounded-circle me-3 object-fit-cover" style="width: 30px; height: 30px;">
                                @else
                                <div class="me-3 bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 30px; height: 30px; background-color: #16a34a !important; font-size: 14px;">
                                    {{ strtoupper(substr($item->name, 0, 1)) }}
                                </div>
                                @endif
                                <span style="font-size: 14px;">{{ $item->name }}</span>
                            </div>
                        </td>
                        <td class="text-secondary text-center" style="font-size: 14px;">{{ $item->email }}</td>
                        <td class="text-secondary text-center" style="font-size: 14px;">
                            @if($item->phone_number || $item->phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->phone_number ?? $item->phone) }}" target="_blank" class="text-decoration-none text-secondary" style="font-size: 14px;">
                                <i class="bi bi-whatsapp text-success me-1"></i> {{ $item->phone_number ?? $item->phone }}
                            </a>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center" style="font-size: 14px;">
                            @if($item->email_verified_at)
                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill small fw-medium">Terverifikasi</span>
                            @else
                            <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill small fw-medium">Perlu Verifikasi</span>
                            @endif
                        </td>
                        <td class="text-secondary text-center" style="font-size: 14px;">
                            {{ $item->created_at->format('d M Y') }}
                        </td>
                        <td class="text-center px-3">
                            <div class="d-flex justify-content-center gap-1">
                                <button class="btn btn-sm btn-outline-primary rounded-3"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditKwt"
                                    data-id="{{ $item->id }}"
                                    data-name="{{ $item->name }}"
                                    data-email="{{ $item->email }}"
                                    data-phone="{{ $item->phone_number ?? $item->phone ?? '' }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
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
@endsection