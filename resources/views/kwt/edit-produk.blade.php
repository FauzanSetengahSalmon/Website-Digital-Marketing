@extends('layouts.kwt')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h4 class="fw-bold mb-0 text-success">Edit Produk</h4>
                        <p class="text-muted small">Perbarui informasi produk Anda</p>
                    </div>

                    <form action="{{ route('kwt.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="small fw-bold">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" value="{{ $product->nama_produk }}" required>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="small fw-bold">Harga (Rp)</label>
                                <input type="number" name="harga" class="form-control" value="{{ $product->harga }}" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="small fw-bold">Stok</label>
                                <input type="number" name="stok" class="form-control" value="{{ $product->stok }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold">Satuan Jual</label>
                            <select name="satuan" class="form-select">
                                <option value="kg" {{ $product->satuan == 'kg' ? 'selected' : '' }}>Per Kilo (kg)</option>
                                <option value="ikat" {{ $product->satuan == 'ikat' ? 'selected' : '' }}>Per Ikat</option>
                                <option value="buah" {{ $product->satuan == 'buah' ? 'selected' : '' }}>Per Buah</option>
                                <option value="gram" {{ $product->satuan == 'gram' ? 'selected' : '' }}>Per Gram</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold">Foto Produk (Opsional)</label>
                            <div class="mb-2">
                                <img src="{{ asset('storage/'.$product->foto_produk) }}" class="rounded-3 shadow-sm" width="80" height="80" onerror="this.src='https://placehold.co/80x80?text=No+Img'">
                                <span class="text-muted small ms-2">Foto saat ini</span>
                            </div>
                            <input type="file" name="foto_produk" class="form-control">
                            <p class="text-muted x-small mt-1" style="font-size: 0.75rem;">*Kosongkan jika tidak ingin mengganti foto</p>
                        </div>

                        <div class="d-flex gap-2 pt-3">
                            <a href="{{ route('kwt.products') }}" class="btn btn-light flex-fill">Batal</a>
                            <button type="submit" class="btn btn-success flex-fill">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection