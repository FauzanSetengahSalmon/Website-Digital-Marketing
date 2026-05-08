@extends('layouts.kwt')

@section('content')

<div class="container-fluid">

    <div class="row mb-4">

        <div class="col-md-8">

            <h4 class="fw-bold mb-1">
                Laporan Transaksi
            </h4>

            <p class="text-muted mb-0">
                Seluruh transaksi customer
            </p>

        </div>

        <div class="col-md-4">

            <div class="card border-0 shadow-sm rounded-4 bg-success text-white">
                <div class="card-body">

                    <small>Total Pendapatan</small>

                    <h3 class="fw-bold mb-0">
                        Rp {{ number_format($totalPendapatan,0,',','.') }}
                    </h3>

                </div>
            </div>

        </div>

    </div>

    <div class="card border-0 shadow-sm rounded-4">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Order</th>
                        <th>Pembeli</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-center">Detail</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($orders as $order)

                    <tr>

                        <td class="ps-4">
                            <div class="fw-bold">
                                #ORD-{{ $order->id }}
                            </div>

                            <small class="text-muted">
                                {{ $order->created_at->format('d M Y') }}
                            </small>
                        </td>

                        <td>
                            <div class="fw-semibold">
                                {{ $order->user->name }}
                            </div>

                            <small class="text-muted">
                                {{ $order->user->email }}
                            </small>
                        </td>

                        <td class="fw-bold text-success">
                            Rp {{ number_format($order->total_harga,0,',','.') }}
                        </td>

                        <td>

                            @if($order->status == 'menunggu')

                                <span class="badge bg-warning text-dark">
                                    Menunggu
                                </span>

                            @elseif($order->status == 'selesai')

                                <span class="badge bg-success">
                                    Selesai
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Ditolak
                                </span>

                            @endif

                        </td>

                        <td class="text-center">

                            <a href="{{ route('kwt.orders.detail',$order->id) }}"
                               class="btn btn-light border rounded-pill btn-sm px-3">

                                <i class="bi bi-eye"></i>
                                Detail

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5" class="text-center py-5">

                            <i class="bi bi-receipt fs-1 text-muted"></i>

                            <p class="text-muted mt-3">
                                Belum ada transaksi
                            </p>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection