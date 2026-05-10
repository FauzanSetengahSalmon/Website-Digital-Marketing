@extends('layouts.kwt')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('kwt.orders') }}" class="btn btn-light border rounded-pill px-3 mb-3">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <h4 class="fw-bold">Proses Pengiriman #ORD-{{ $order->id }}</h4>
    </div>

    <div class="row">
        <!-- Kolom Produk & Pembeli -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-4">Ringkasan Produk</h6>
                    @foreach($order->details as $detail)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ asset('storage/'.$detail->product->foto_produk) }}" width="50" height="50" class="rounded-3" style="object-fit: cover;">
                            <div>
                                <div class="fw-bold small">{{ $detail->product->nama_produk }}</div>
                                <small class="text-muted">Qty : {{ $detail->jumlah }}</small>
                            </div>
                        </div>
                        <div class="fw-bold text-success small">Rp {{ number_format($detail->harga_saat_ini,0,',','.') }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Kolom Form Kurir -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 border-top border-primary border-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-4">Input Data Pengiriman</h6>
                    
                    <form action="{{ route('kwt.order.status', $order->id) }}" method="POST">
                        @csrf
                        {{-- Status diubah ke 'selesai' saat form dikirim --}}
                        <input type="hidden" name="status" value="selesai">

                        <div class="mb-3">
                            <label class="small fw-bold text-muted">Nama Pembeli</label>
                            <div class="p-2 bg-light rounded">{{ $order->user->name }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Pilih Kurir</label>
                            <select name="kurir" class="form-select rounded-3 border-primary" required>
                                <option value="" selected disabled>-- Pilih Kurir --</option>
                                <option value="Kurir Internal KWT">Kurir Internal KWT</option>
                                <option value="Gojek / Grab">Gojek / Grab</option>
                                <option value="Ambil Sendiri">Ambil di Tempat</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">No. HP Kurir / Pengirim</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-primary">+62</span>
                                <input type="number" name="no_hp_kurir" class="form-control border-primary" placeholder="812xxxxx" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm">
                            Konfirmasi & Selesaikan Pesanan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection