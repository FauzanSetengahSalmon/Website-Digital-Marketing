@extends('layouts.kwt')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="panel-title"><i class="bi bi-shield-exclamation me-2"></i>Pusat Pengaduan Komplain Pembeli</div>
            <p class="text-muted mb-0">Kelola dan tanggapi keluhan kualitas komoditas hasil panen KWT Anda.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
    @endif

    @if($reports->isEmpty())
    @else
    <div class="row g-4">
        @foreach($reports as $report)
        <div class="col-12">
            <div class="card-report">
                <div class="row g-3">
                    <div class="col-md-7">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-danger-subtle text-danger px-3 py-1 rounded-pill fw-bold">{{ $report->tipe_pengaduan }}</span>
                            <span class="ms-3 text-muted small font-monospace">#INV-{{ str_pad($report->order_id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        <h6 class="fw-bold text-dark mt-2">Produk: {{ $report->product->nama_produk ?? 'Produk Terhapus' }}</h6>
                        <p class="small text-muted mb-2">Pelapor: <strong>{{ $report->customer->name ?? 'Pelanggan' }}</strong></p>

                        <div class="p-3 bg-light rounded-3 text-secondary small border-start border-4 border-secondary">
                            <strong class="text-dark d-block mb-1">Kronologi Keluhan:</strong>
                            "{{ $report->pesan }}"
                        </div>

                        <div class="mt-3">
                            <form action="{{ route('kwt.reports.update-tanggapan', $report->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <label class="small fw-bold text-dark mb-1">Tanggapan KWT:</label>
                                <textarea name="tanggapan_kwt" class="form-control form-control-sm mb-2" rows="2" placeholder="Tulis solusi untuk pelanggan...">{{ $report->tanggapan_kwt }}</textarea>
                                <button type="submit" class="btn btn-sm btn-outline-success fw-bold rounded-3">
                                    <i class="bi bi-send-fill"></i> Kirim Tanggapan
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-2 text-center">
                        @if($report->foto_bukti)
                        <a href="{{ asset('storage/' . $report->foto_bukti) }}" target="_blank">
                            <img src="{{ asset('storage/' . $report->foto_bukti) }}" class="img-evidence border shadow-sm" alt="Bukti">
                        </a>
                        @else
                        <div class="text-muted small italic pt-4">Tanpa foto</div>
                        @endif
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-secondary">Aksi Status:</label>
                        <form action="{{ route('kwt.reports.update-status', $report->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <select name="status" class="form-select mb-3" onchange="this.form.submit()">
                                <option value="menunggu" {{ $report->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="diproses" {{ $report->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="selesai" {{ $report->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </form>

                        <div class="text-center">
                            <span class="badge bg-{{ ['menunggu'=>'warning','diproses'=>'info','selesai'=>'success'][$report->status] }} w-100 py-2">
                                {{ strtoupper($report->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection