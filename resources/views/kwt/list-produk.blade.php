@extends('layouts.kwt')

@section('content')
<style>
    /* 1. Tabel Lebih Kecil & Tipis */
    .table-container {
        background: white;
        border-radius: 12px;
        border: 1px solid #dee2e6;
    }

    .table-bordered-custom th {
        background-color: #f8fafc;
        font-weight: 700;
        color: #334155;
        font-size: 0.8rem;
        /* Font header dikecilkan */
        padding: 8px !important;
        text-align: center;
    }

    .table-bordered-custom td {
        border: 1px solid #dee2e6 !important;
        padding: 6px 10px !important;
        /* Padding dikurangi biar lebih tipis */
        font-size: 0.85rem;
    }

    /* 2. Gambar Produk Lebih Kecil */
    .img-wrapper {
        width: 45px;
        /* Kecilin dari 60px */
        height: 45px;
        border-radius: 6px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        margin: auto;
    }

    .img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .btn-action {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-edit {
        background: #ecfdf5;
        color: #10b981;
    }

    .btn-delete {
        background: #fff1f2;
        color: #f43f5e;
    }

    /* 3. Modal & Keterangan */
    .modal-content {
        border-radius: 20px;
        border: none;
    }

    .form-label-bold {
        font-size: 0.85rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 2px;
    }

    .input-clean {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.9rem;
    }

    .petunjuk-ibu {
        font-size: 0.7rem;
        color: #64748b;
        margin-top: 3px;
        display: block;
        line-height: 1.2;
        font-style: italic;
    }

    .preview-box {
        width: 100px;
        height: 100px;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        margin: 8px auto;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0">Daftar Produk KWT</h5>
            <p class="text-muted small mb-0">Kelola dagangan Ibu di sini.</p>
        </div>
        <button class="btn btn-success rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-1"></i> Tambah Barang
        </button>
    </div>

    <div class="table-container shadow-sm">
        <div class="table-responsive">
            <table class="table table-bordered-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th width="70">Foto</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr>
                        <td class="text-center">
                            <div class="img-wrapper">
                                @if($p->foto_produk)
                                <img src="{{ asset('storage/'.$p->foto_produk) }}" alt="produk">
                                @else
                                <i class="bi bi-camera text-muted"></i>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $p->nama_produk }}</div>
                            <small class="text-muted" style="font-size: 0.7rem;">ID: #{{ $p->id }}</small>
                        </td>
                        <td class="text-center">Rp{{ number_format($p->harga, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="badge {{ $p->stok <= 3 ? 'bg-danger' : 'bg-success' }}" style="font-size: 0.7rem;">
                                {{ $p->stok }} {{ $p->satuan }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn-action btn-edit" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $p->id }}">
                                    <i class="bi bi-pencil-fill" style="font-size: 0.8rem;"></i>
                                </button>
                                <form action="{{ route('kwt.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus barang ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete"><i class="bi bi-trash-fill" style="font-size: 0.8rem;"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada barang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
@foreach($products as $p)
<div class="modal fade" id="modalEdit{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <form action="{{ route('kwt.products.update', $p->id) }}" method="POST" enctype="multipart/form-data" class="modal-content shadow-lg">
            @csrf @method('PUT')
            <div class="modal-body p-4">
                <h6 class="fw-bold text-success mb-1"><i class="bi bi-pencil-square me-2"></i>Edit Barang</h6>
                <p class="text-muted small mb-4">Silakan ubah data barang Ibu di bawah ini.</p>

                <div class="mb-3">
                    <label class="form-label-bold">Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control input-clean" value="{{ $p->nama_produk }}" required>
                    <span class="petunjuk-ibu">Contoh: Keripik Pisang Manis</span>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label-bold">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control input-clean" value="{{ $p->harga }}" required>
                        <span class="petunjuk-ibu">Hanya angka saja</span>
                    </div>
                    <div class="col-6">
                        <label class="form-label-bold">Stok</label>
                        <input type="number" name="stok" class="form-control input-clean" value="{{ $p->stok }}" required>
                        <span class="petunjuk-ibu">Misal: 10</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-bold">Satuan</label>
                    <select name="satuan" class="form-select input-clean">
                        <option value="kg" {{ $p->satuan == 'kg' ? 'selected' : '' }}>Kg</option>
                        <option value="Ikat" {{ $p->satuan == 'Ikat' ? 'selected' : '' }}>Ikat</option>
                        <option value="Bungkus" {{ $p->satuan == 'Bungkus' ? 'selected' : '' }}>Bungkus</option>
                        <option value="Buah" {{ $p->satuan == 'Buah' ? 'selected' : '' }}>Buah</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label-bold">Foto (Boleh dikosongkan)</label>
                    <div class="preview-box">
                        <img id="edit-img-{{ $p->id }}" src="{{ $p->foto_produk ? asset('storage/'.$p->foto_produk) : '' }}" class="w-100 h-100 {{ $p->foto_produk ? '' : 'd-none' }}" style="object-fit: cover;">
                        @if(!$p->foto_produk) <i class="bi bi-image text-muted fs-2"></i> @endif
                    </div>
                    <input type="file" name="foto_produk" class="form-control input-clean" accept="image/*" onchange="previewEdit(this, '{{ $p->id }}')">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success fw-bold rounded-pill"><i class="bi bi-check-circle me-1"></i> Simpan Perubahan</button>
                    <button type="button" class="btn btn-light rounded-pill border small" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <form action="{{ route('kwt.products.store') }}" method="POST" enctype="multipart/form-data" class="modal-content shadow-lg">
            @csrf
            <div class="modal-body p-4">
                <h6 class="fw-bold text-success mb-1"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Barang Baru</h6>
                <p class="text-muted small mb-4">Isi data barang yang mau Ibu jual.</p>

                <div class="mb-3">
                    <label class="form-label-bold">Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control input-clean" placeholder="Nama barang..." required>
                    <span class="petunjuk-ibu">Tulis nama lengkap barangnya</span>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label-bold">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control input-clean" placeholder="0" required>
                        <span class="petunjuk-ibu">Tulis harganya saja</span>
                    </div>
                    <div class="col-6">
                        <label class="form-label-bold">Stok</label>
                        <input type="number" name="stok" class="form-control input-clean" placeholder="0" required>
                        <span class="petunjuk-ibu">Jumlah tersedia</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-bold">Satuan</label>
                    <select name="satuan" class="form-select input-clean">
                        <option value="kg">Kg</option>
                        <option value="Ikat">Ikat</option>
                        <option value="Bungkus">Bungkus</option>
                        <option value="Buah">Buah</option>
                    </select>
                    <span class="petunjuk-ibu">Pilih satuan jualnya</span>
                </div>

                <div class="mb-4">
                    <label class="form-label-bold">Foto Produk</label>
                    <div class="preview-box">
                        <i id="icon-add" class="bi bi-image text-muted fs-2"></i>
                        <img id="preview-add" src="" class="d-none w-100 h-100" style="object-fit: cover;">
                    </div>
                    <input type="file" name="foto_produk" class="form-control input-clean" accept="image/*" onchange="previewAdd(this)">
                    <span class="petunjuk-ibu">Pilih foto yang paling bagus ya, Bu</span>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success fw-bold rounded-pill"><i class="bi bi-check-circle-fill me-1"></i> Simpan Barang</button>
                    <button type="button" class="btn btn-light rounded-pill border small" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function previewEdit(input, id) {
        const preview = document.getElementById('edit-img-' + id);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewAdd(input) {
        const preview = document.getElementById('preview-add');
        const icon = document.getElementById('icon-add');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                icon.classList.add('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection