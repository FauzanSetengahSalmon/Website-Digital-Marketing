@extends('layouts.admin')
@section('content')
<div class="container-fluid py-2">
    <h3 class="fw-bold mb-4">Laporan Penjualan Global</h3>
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Order ID</th>
                        <th>Customer</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                    <tr>
                        <td class="ps-4 fw-bold">#{{ $sale->id }}</td>
                        <td>{{ $sale->user->name }}</td>
                        <td class="text-success fw-bold">Rp {{ number_format($sale->total_harga,0,',','.') }}</td>
                        <td>
                            <span class="badge {{ $sale->status == 'selesai' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ strtoupper($sale->status) }}
                            </span>
                        </td>
                        <td>{{ $sale->created_at->format('d/m/y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection