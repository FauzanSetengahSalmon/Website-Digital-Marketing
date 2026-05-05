@extends('layouts.app')
@section('content')
<div class="container py-5">
    <h5 class="fw-bold mb-4">Pesanan Masuk (KWT)</h5>
    @foreach($orders as $order)
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <h6>Order #{{ $order->id }} - <span class="text-muted small">{{ $order->user->name }}</span></h6>
                <span class="badge bg-info text-dark uppercase">{{ $order->status }}</span>
            </div>
            <hr>
            @foreach($order->details as $detail)
                <p class="mb-1 small">{{ $detail->product->nama_produk }} (x{{ $detail->jumlah }})</p>
            @endforeach
            <div class="mt-3 d-flex gap-2">
                @if($order->status == 'menunggu')
                <form action="{{ route('kwt.order.status', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="disetujui">
                    <button type="submit" class="btn btn-success btn-sm">Setujui Pesanan</button>
                </form>
                <form action="{{ route('kwt.order.status', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="ditolak">
                    <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection