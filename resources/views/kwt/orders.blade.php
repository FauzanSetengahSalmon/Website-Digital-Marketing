@extends('layouts.kwt')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Pesanan Masuk</h4>
            <p class="text-muted mb-0">Kelola pesanan customer</p>
        </div>

        <div class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
            {{ $orders->count() }} Pesanan
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
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($orders as $order)

                    <tr>

                        <td class="ps-4">
                            <div class="fw-bold">#ORD-{{ $order->id }}</div>
                            <small class="text-muted">
                                {{ $order->created_at->format('d M Y H:i') }}
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

                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                    Menunggu
                                </span>

                            @elseif($order->status == 'selesai')

                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    Selesai
                                </span>

                            @else

                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                    Ditolak
                                </span>

                            @endif

                        </td>

                        <td class="text-center">

                            <div class="d-flex justify-content-center gap-2">

                                <a href="{{ route('kwt.orders.detail', $order->id) }}"
                                   class="btn btn-sm btn-light border rounded-pill px-3">
                                    <i class="bi bi-eye"></i>
                                    Detail
                                </a>

                                @if($order->status == 'menunggu')

                                <form action="{{ route('kwt.order.status',$order->id) }}"
                                      method="POST">
                                    @csrf

                                    <input type="hidden"
                                           name="status"
                                           value="selesai">

                                    <button class="btn btn-success btn-sm rounded-pill px-3">
                                        Selesai
                                    </button>
                                </form>

                                @endif

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="5" class="text-center py-5">

                            <i class="bi bi-cart-x fs-1 text-muted"></i>

                            <p class="text-muted mt-3">
                                Belum ada pesanan masuk
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