<!DOCTYPE html>
<html lang="id">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pengiriman #{{ $order->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="bg-light p-3">
    <div class="card shadow-sm mx-auto" style="max-width: 450px;">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0 fw-bold"><i class="bi bi-truck"></i> Bukti Pengiriman #{{ $order->id }}</h5>
        </div>
        <div class="card-body">
            {{-- DATA PELANGGAN --}}
            <div class="mb-3">
                <small class="text-muted text-uppercase fw-bold">Penerima:</small>
                <div class="fw-bold fs-5">{{ $order->user->name ?? 'Pelanggan' }}</div>
                <div class="text-muted"><i class="bi bi-telephone-fill"></i> {{ $order->nomor_hp ?? '-' }}</div>
                <div class="small mt-1"><i class="bi bi-geo-alt-fill text-danger"></i> {{ $order->alamat ?? '-' }}</div>
            </div>

            <hr>

            {{-- DETAIL PRODUK --}}
            <div class="mb-3">
                <small class="text-muted text-uppercase fw-bold">Daftar Produk:</small>
                <ul class="list-group list-group-flush mt-2">
                    @foreach($order->details as $detail)
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold">{{ $detail->product->nama_produk ?? 'Produk' }}</span>
                            <div class="text-muted small">{{ $detail->jumlah }} {{ $detail->product->satuan ?? 'Pcs' }}</div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

            <hr>

            {{-- LOGIKA PENGECEKAN BUKTI FOTO --}}
            @if($order->bukti_sampai)
            {{-- JIKA SUDAH DIFOTO: Tampilkan Alert dan Fotonya --}}
            <div class="alert alert-success text-center mb-3 border-0 shadow-sm">
                <i class="bi bi-check-circle-fill fs-1 text-success d-block mb-2"></i>
                <h6 class="fw-bold mb-1">Pesanan Sudah Diantar!</h6>
                <small>Bukti foto berhasil disimpan ke sistem.</small>
            </div>

            <div class="text-center mt-3">
                <p class="fw-bold small text-muted mb-2">FOTO BUKTI DILOKASI:</p>
                <img src="{{ asset('storage/' . $order->bukti_sampai) }}" alt="Bukti Penerimaan" class="img-fluid rounded-3 border shadow-sm" style="max-height: 300px; width: 100%; object-fit: cover;">
            </div>
            @else
            {{-- JIKA BELUM DIFOTO: Tampilkan Form Upload --}}
            <form action="{{ route('kurir.store', [$order->id, $order->delivery_token]) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <div class="mb-3">
                    <label class="fw-bold">Foto Bukti Penerimaan:</label>
                    <input type="file" name="bukti_sampai" class="form-control" accept="image/*" capture="environment" required>
                    <small class="text-muted d-block mt-1"><i class="bi bi-info-circle"></i> Gunakan kamera untuk memotret paket di lokasi.</small>
                </div>

                <button type="submit" class="btn btn-success w-100 py-2 fw-bold" onclick="this.innerHTML='<span class=\'spinner-border spinner-border-sm me-2\'></span>Mengunggah...'; this.form.submit(); this.disabled=true;">
                    <i class="bi bi-camera-fill"></i> Upload Bukti & Selesaikan
                </button>
            </form>
            @endif

        </div>
    </div>
</body>

</html>