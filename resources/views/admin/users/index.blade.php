@extends('layouts.admin')

@section('content')
<style>
    /* Styling agar garis tabel tipis dan elegan */
    .table-bordered {
        border: 1px solid #e2e8f0 !important;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #e2e8f0 !important;
    }

    .avatar-sm {
        width: 36px;
        height: 36px;
        font-size: 14px;
    }
</style>

<div class="container-fluid py-2">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Data Semua Pelanggan</h3>
        <p class="text-muted">Daftar pelanggan yang terdaftar dalam sistem.</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3 text-center">Nama</th>
                        <th class="py-3 text-center">Email</th>
                        <th class="py-3 text-center">Bergabung Pada</th>
                        <th class="py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="px-4 py-3 text-center">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-3">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="fw-bold text-dark">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="text-secondary text-center">{{ $user->email }}</td>
                        <td class="text-center text-secondary">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm">
                                <i class="bi bi-slash-circle me-1"></i> Blokir
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">Belum ada customer terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection