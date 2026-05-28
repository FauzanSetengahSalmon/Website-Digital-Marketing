@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between mb-4">
        <h4 class="fw-bold">Riwayat Pencairan Kurir</h4>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Pencairan</button>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Kurir</th><th>Penerima</th><th>Total</th><th>Tanggal</th></tr></thead>
                <tbody>
                    @foreach($pencairan as $item)
                    <tr>
                        <td class="fw-bold">{{ $item->kurir_nama }}</td>
                        <td>{{ $item->nama_penerima }}</td>
                        <td class="text-success fw-bold">Rp {{ number_format($item->total_cair, 0, ',', '.') }}</td>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection