@extends('layouts.kwt')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Pusat Pengaduan Komplain</h4>
            <p class="text-muted small mb-0">Kelola dan berikan solusi atas keluhan produk Anda.</p>
        </div>
    </div>

    @if($reports->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-envelope-open text-muted fs-1"></i>
        <p class="text-muted mt-2">Belum ada laporan komplain masuk.</p>
    </div>
    @else
    <div class="row justify-content-center g-4">
        @forelse($reports as $report)
        <div class="col-12 col-md-9 mb-3">
            <div class="card card-report border-0 shadow-sm rounded-4 {{ $report->tanggapan_kwt ? 'border-start border-4 border-success' : '' }}">
                <div class="card-body p-4">
                    <div class="row g-4 align-items-start">
                        
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-danger-subtle text-danger px-3 py-1 rounded-pill fw-bold">{{ $report->tipe_pengaduan }}</span>
                                @if($report->tanggapan_kwt)
                                    <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-1 fw-bold ms-2"><i class="bi bi-check-circle-fill me-1"></i> DITANGGAPI</span>
                                @endif
                                <span class="ms-3 text-muted small font-monospace">#INV-{{ str_pad($report->order_id, 5, '0', STR_PAD_LEFT) }}</span>
                            </div>

                            <h6 class="fw-bold text-dark mt-3">Produk: {{ $report->product->nama_produk ?? 'Produk Terhapus' }}</h6>
                            <p class="small text-muted mb-3">Pelapor: <strong>{{ $report->customer->name ?? 'Pelanggan' }}</strong></p>

                            <div class="p-3 bg-light rounded-3 text-secondary small border-start border-4 border-secondary mb-3">
                                <strong class="text-dark d-block mb-1">Kronologi Keluhan:</strong>
                                "{!! nl2br(e($report->pesan)) !!}"
                            </div>

                            <div class="mt-3">
                                @if($report->tanggapan_kwt)
                                    <div class="p-3 bg-success bg-opacity-10 border border-success-subtle rounded-3">
                                        <strong class="text-success d-block small mb-1"><i class="bi bi-check-circle-fill me-1"></i> Tanggapan Anda:</strong>
                                        <p class="small text-dark mb-2 lh-base">"{!! nl2br(e($report->tanggapan_kwt)) !!}"</p>
                                        <hr class="my-2 border-success-subtle opacity-25">
                                        <button type="button" class="btn btn-sm btn-outline-success border-0 px-2 py-1 rounded small w-100" onclick="this.nextElementSibling.classList.toggle('d-none')">
                                            <i class="bi bi-pencil-square me-1"></i> Ubah Tanggapan
                                        </button>
                                        <div class="d-none mt-2">
                                            <form action="{{ route('kwt.reports.update-tanggapan', $report->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <textarea name="tanggapan_kwt" class="form-control form-control-sm mb-2 rounded-3" rows="3" placeholder="Tulis solusi untuk pelanggan...">{{ $report->tanggapan_kwt }}</textarea>
                                                <button type="submit" class="btn btn-sm btn-success fw-bold rounded-3 w-100 py-1.5 shadow-sm">
                                                    Simpan Tanggapan Baru
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <form action="{{ route('kwt.reports.update-tanggapan', $report->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <label class="small fw-bold text-dark mb-1">Tanggapan KWT:</label>
                                        <textarea name="tanggapan_kwt" class="form-control form-control-sm mb-3 rounded-3" rows="3" placeholder="Tulis solusi untuk pelanggan...">{{ $report->tanggapan_kwt }}</textarea>
                                    
                                        <button type="submit" class="btn btn-success fw-bold rounded-3 w-100 py-2 shadow-sm">
                                            <i class="bi bi-send-fill me-1"></i> Kirim Tanggapan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4 border-start ps-md-4 align-self-center">
                            <div class="mb-4 text-center">
                                <label class="form-label small fw-bold text-secondary mb-2 d-block text-start">Bukti Foto:</label>
                                @if($report->foto_bukti)
                                    <a href="{{ asset('storage/' . $report->foto_bukti) }}" target="_blank" class="d-inline-block">
                                        <img src="{{ asset('storage/' . $report->foto_bukti) }}" 
                                            class="img-fluid img-thumbnail shadow-sm rounded-3" 
                                            style="max-height: 140px; object-fit: cover;" 
                                            alt="Bukti">
                                    </a>
                                @else
                                    <div class="p-3 bg-light rounded-3 text-muted small italic text-center border">
                                        <i class="bi bi-image text-secondary d-block fs-3 mb-1"></i>
                                        Tanpa foto
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary mb-1">Aksi Status:</label>
                                <form action="{{ route('kwt.reports.update-status', $report->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <select name="status" class="form-select form-select-sm rounded-3" onchange="this.form.submit()">
                                        <option value="menunggu" {{ $report->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="diproses" {{ $report->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="selesai" {{ $report->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </form>
                            </div>

                            <div>
                                <label class="small fw-bold text-secondary d-block mb-1">Status Saat Ini:</label>
                                @php
                                    $badgeColor = match($report->status) {
                                        'menunggu' => 'warning',
                                        'diproses' => 'info',
                                        'selesai'  => 'success',
                                        default    => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeColor }} w-100 py-2 rounded-3 text-uppercase fw-bold shadow-sm">
                                    {{ $report->status }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1"></i>
            <p>Tidak ada pengaduan yang masuk saat ini.</p>
        </div>
        @endforelse
    </div>
    @endif
</div>
@endsection