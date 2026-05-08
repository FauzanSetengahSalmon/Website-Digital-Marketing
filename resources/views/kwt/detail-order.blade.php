@extends('layouts.kwt')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <a href="{{ url()->previous() }}"
           class="btn btn-light border rounded-pill px-3 mb-3">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

        <h4 class="fw-bold">
            Detail Pesanan #ORD-{{ $order->id }}
        </h4>

    </div>

    <div class="row">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4 mb-4">

                <div class="card-body">

                    <h6 class="fw-bold mb-4">
                        Produk Dipesan
                    </h6>

                    @foreach($order->details as $detail)

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div class="d-flex align-items-center gap-3">

                            <img src="{{ asset('storage/'.$detail->product->foto_produk) }}"
                                 width="70"
                                 height="70"
                                 style="object-fit:cover;border-radius:12px;">

                            <div>

                                <div class="fw-bold">
                                    {{ $detail->product->nama_produk }}
                                </div>

                                <small class="text-muted">
                                    Qty : {{ $detail->jumlah }}
                                </small>

                            </div>

                        </div>

                        <div class="fw-bold text-success">
                            Rp {{ number_format($detail->harga_saat_ini,0,',','.') }}
                        </div>

                    </div>

                    @endforeach

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <h6 class="fw-bold mb-4">
                        Informasi Pembeli
                    </h6>

                    <div class="mb-3">
                        <small class="text-muted">Nama</small>
                        <div class="fw-semibold">
                            {{ $order->user->name }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Email</small>
                        <div class="fw-semibold">
                            {{ $order->user->email }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Status</small>
                        <div>
                            <span class="badge bg-success">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Total</span>

                        <span class="fw-bold text-success">
                            Rp {{ number_format($order->total_harga,0,',','.') }}
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection