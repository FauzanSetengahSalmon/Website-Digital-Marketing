@extends('layouts.admin')

@section('content')
<style>
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
        flex-shrink: 0;
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
            <h3 class="fw-bold text-dark mb-1">Data Semua Pelanggan</h3>
            <p class="text-muted mb-0">Daftar pelanggan yang terdaftar dalam sistem.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive custom-scrollbar">
            <table class="table table-hover table-bordered align-middle mb-0 text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3 text-center">Nama</th>
                        <th class="py-3 text-center">Email</th>
                        <th class="py-3 text-center">Bergabung Pada</th>
                        <th class="py-3 text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-3">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="fw-bold text-dark">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="text-secondary text-center">{{ $user->email }}</td>
                        <td class="text-center text-secondary">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="text-center pe-4">
                            <button class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm">
                                <i class="bi bi-slash-circle me-1"></i> Blokir
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-2 mb-2 d-block opacity-50"></i>
                            Belum ada customer terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection