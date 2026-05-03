@extends('layouts.kwt')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-0 text-success">Manajemen Produk</h4>
                    <p class="text-muted small">Kelola stok dan harga katalog Anda</p>
                </div>
                <button class="btn btn-success rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Produk
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Foto</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok / Satuan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $p)
                        <tr>
                            <td>
                                <img src="{{ asset('storage/'.$p->foto_produk) }}" class="rounded-3" width="50" height="50" onerror="this.src='https://placehold.co/50x50?text=No+Img'">
                            </td>
                            <td><span class="fw-bold">{{ $p->nama_produk }}</span></td>
                            <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $p->stok }} {{ strtoupper($p->satuan) }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- TOMBOL EDIT -->
                                    <a href="{{ route('kwt.products.edit', $p->id) }}" class="btn btn-sm btn-outline-warning border-0">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>

                                    <!-- TOMBOL HAPUS -->
                                    <form action="{{ route('kwt.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger border-0"><i class="bi bi-trash"></i> Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada produk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah (Tetap Sama) -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('kwt.products.store') }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 rounded-4 shadow">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Tambah Produk Baru</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold">Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control" placeholder="Contoh: Cabai Merah" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="small fw-bold">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control" placeholder="0" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="small fw-bold">Stok</label>
                        <input type="number" name="stok" class="form-control" placeholder="0" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold">Satuan Jual</label>
                    <select name="satuan" class="form-select">
                        <option value="kg">Per Kilo (kg)</option>
                        <option value="ikat">Per Ikat</option>
                        <option value="buah">Per Buah</option>
                        <option value="gram">Per Gram</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold">Foto Produk</label>
                    <input type="file" name="foto_produk" class="form-control">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success px-4">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>
@endsection