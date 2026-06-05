@extends('layouts.admin')

@section('content')
<style>
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
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.12);
        outline: none;
    }

    .btn-success {
        background: #064e3b;
        border-color: #064e3b;
    }

    .btn-success:hover {
        background: #053b2c;
        border-color: #053b2c;
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

    /* CUSTOM SCROLLBAR MOBILE */
    .custom-scrollbar::-webkit-scrollbar {
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
</style>

<div class="container-fluid py-4">

    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">
            Kelola Akun KWT
        </h2>
        <p class="text-muted mb-0">Manajemen akses dan pendaftaran Kelompok Wanita Tani.</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-4 border-0 mb-4">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        {{-- KOLOM FORM TAMBAH --}}
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-3 px-md-4">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-person-plus-fill text-success me-2"></i>Tambah Akun Baru</h5>
                </div>
                <div class="card-body p-3 p-md-4">
                    <form method="POST" action="{{ route('admin.kwt.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label-bold">Nama KWT</label>
                            <input type="text" name="name" class="form-control input-clean" placeholder="Contoh: KWT Mekar Jaya" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-bold">Email</label>
                            <input type="email" name="email" class="form-control input-clean" placeholder="kwt@example.com" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-bold">Password</label>
                            <input type="password" name="password" class="form-control input-clean" placeholder="Minimal 8 karakter" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success rounded-pill fw-bold py-2 shadow-sm">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Akun
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM TABEL DAFTAR KWT --}}
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-3 px-md-4">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-card-list text-success me-2"></i>Daftar Akun KWT</h5>
                </div>
                <div class="card-body p-3 p-md-4 pt-3">
                    <div class="table-responsive custom-scrollbar border rounded-3">
                        <table class="table table-hover align-middle text-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3 py-3 border-bottom-0">Nama</th>
                                    <th class="py-3 pe-3 border-bottom-0">Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kwts as $kwt)
                                <tr>
                                    <td class="ps-3 py-3 fw-semibold text-dark">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-sm bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; flex-shrink: 0;">
                                                {{ strtoupper(substr($kwt->name, 0, 1)) }}
                                            </div>
                                            {{ $kwt->name }}
                                        </div>
                                    </td>
                                    <td class="py-3 pe-3 text-secondary">{{ $kwt->email }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block opacity-50 mb-2"></i>
                                        Belum ada akun KWT terdaftar.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection