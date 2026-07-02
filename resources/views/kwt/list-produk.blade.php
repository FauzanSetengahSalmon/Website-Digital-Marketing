@extends('layouts.kwt')

@section('content')
<style>
    body {
        background: #f8fafc;
    }

    /* HEADER */
    .page-header {
        background: linear-gradient(135deg, #065f46, #10b981);
        border-radius: 24px;
        padding: 28px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        right: -50px;
        top: -50px;
        width: 180px;
        height: 180px;
        background: rgba(255, 255, 255, .08);
        border-radius: 50%;
        pointer-events: none;
    }

    .page-header h4 {
        font-weight: 800;
        margin-bottom: 4px;
    }

    .page-header p {
        margin-bottom: 0;
        opacity: .9;
        font-size: .9rem;
    }

    /* KOTAK PANEL INSTAGRAM */
    .panel-instagram-kwt {
        border-radius: 20px;
        overflow: hidden;
        border: none;
    }

    .btn-ig-feed {
        background: linear-gradient(135deg, #4f46e5, #3b82f6);
        color: white;
        font-weight: 700;
        border: none;
        border-radius: 12px;
        transition: 0.25s;
    }

    .btn-ig-feed:hover {
        opacity: 0.9;
        color: white;
    }

    /* TABLE */
    .table-wrapper {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .table-custom th {
        background: #f8fafc;
        color: #475569;
        font-size: .78rem;
        font-weight: 800;
        padding: 14px !important;
        text-transform: uppercase;
        border-bottom: 1px solid #e2e8f0;
    }

    .table-custom td {
        padding: 14px !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-custom tbody tr:hover {
        background: #f8fafc;
    }

    /* FOTO */
    .product-image {
        width: 65px;
        height: 65px;
        border-radius: 16px;
        overflow: hidden;
        background: #f1f5f9;
        border: 2px solid #ecfdf5;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* BUTTON */
    .btn-add {
        border-radius: 999px;
        padding: 11px 22px;
        font-weight: 700;
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        box-shadow: 0 8px 20px rgba(16, 185, 129, .25);
    }

    .btn-action {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: .25s;
    }

    .btn-edit {
        background: #ecfdf5;
        color: #10b981;
    }

    .btn-edit:hover {
        background: #10b981;
        color: white;
    }

    .btn-delete {
        background: #fff1f2;
        color: #f43f5e;
    }

    .btn-delete:hover {
        background: #f43f5e;
        color: white;
    }

    .btn-share-ig {
        background: #fdf2f8;
        color: #db2777;
    }

    .btn-share-ig:hover {
        background: #db2777;
        color: white;
    }

    /* BADGE */
    .badge-stock {
        padding: 8px 14px;
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 700;
    }

    .badge-aman {
        background: #dcfce7;
        color: #166534;
    }

    .badge-habis {
        background: #fee2e2;
        color: #991b1b;
    }

    /* MODAL */
    .modal-modern {
        border-radius: 28px;
        overflow-y: auto;
        max-height: 85vh;
        border: none;
    }

    .modal-left {
        background: linear-gradient(180deg, #ecfdf5, #f0fdf4);
        padding: 28px;
        border-right: 1px solid #d1fae5;
    }

    .modal-right {
        padding: 28px;
    }

    .modal-title-modern {
        font-size: 1.2rem;
        font-weight: 800;
        color: #065f46;
    }

    .modal-subtitle {
        color: #64748b;
        font-size: .82rem;
    }

    /* PREVIEW */
    .preview-modern {
        width: 100%;
        height: 270px;
        border-radius: 22px;
        border: 2px dashed #a7f3d0;
        background: white;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 16px;
        position: relative;
    }

    .preview-modern img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* FORM */
    .label-modern {
        font-size: .82rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }

    .input-clean {
        border-radius: 14px;
        border: 1px solid #dbe4ea;
        padding: 12px 14px;
        font-size: .92rem;
        box-shadow: none !important;
        transition: 0.3s;
    }

    .input-clean:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, .12) !important;
    }

    .shake-invalid {
        border-color: #f43f5e !important;
        box-shadow: 0 0 0 4px rgba(244, 63, 94, .15) !important;
    }

    .petunjuk-ibu {
        font-size: .72rem;
        color: #64748b;
        margin-top: 5px;
        display: block;
    }

    .btn-modern-save {
        border-radius: 16px;
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        padding: 12px;
        font-weight: 700;
    }

    .btn-modern-cancel {
        border-radius: 16px;
        padding: 12px;
        font-weight: 700;
    }

    @media(max-width:768px) {
        .page-header {
            padding: 20px;
            border-radius: 16px;
        }

        .page-header h4 {
            font-size: 1.25rem;
        }

        .table-wrapper {
            border-radius: 16px;
        }

        .modal-dialog {
            margin: 1rem auto;
        }

        .modal-modern {
            border-radius: 20px;
        }

        .modal-left {
            border-right: none;
            border-bottom: 1px solid #d1fae5;
            padding: 20px;
        }

        .modal-right {
            padding: 20px;
        }

        .preview-modern {
            height: 200px;
            border-radius: 16px;
        }
    }

    @media(max-width:576px) {
        .page-header {
            padding: 16px;
        }

        .modal-left,
        .modal-right {
            padding: 16px;
        }

        .btn-add {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="page-header shadow-sm mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4>Daftar Produk KWT</h4>
                <p>Kelola hasil panen dan produk jualan Ibu dengan mudah.</p>
            </div>

            <button class="btn btn-success btn-add" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-camera-fill me-1"></i>
                Tambah Produk
            </button>
        </div>
    </div>

    {{-- PANEL OTOMATIS SAAT BARU TAMBAH PRODUK BARU --}}
    @if(session('share_url'))
    <div class="card panel-instagram-kwt border-success shadow-sm mb-4">
        <div class="bg-success text-white p-3 d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <h6 class="fw-bold mb-0">Produk Berhasil Disimpan di Website!</h6>
        </div>
        <div class="p-3 bg-white">
            <p class="text-muted small mb-3">
                Yuk bu, langsung promosikan produk <strong>{{ session('product_name') }}</strong> ini ke akun Instagram resmi KWT Tani Cibiru Wetan:
            </p>

            <textarea id="captionFeedKWT" class="d-none">Beli {{ session('product_name') }} segar hasil panen KWT Tani Cibiru Wetan langsung di website kami! 

Harga: Rp {{ session('product_price') }}

👉 Cara Pesan: Klik link yang ada di BIO PROFIL Instagram kami ya, atau ketik link berikut di browser HP Ibu:

{{ session('share_url') }}</textarea>

            <div class="p-3 border rounded-3 bg-light">
                <div class="mb-3">
                    <h6 class="fw-bold text-dark mb-1">Buat Postingan (Feed / Reels)</h6>
                    <p class="text-muted small mb-0" style="font-size: 0.75rem;">Link belanja otomatis disalin ke HP. Ibu tinggal pilih foto lalu Tempel (Paste) di kolom tulisan Instagram.</p>
                </div>
                <button onclick="salinDanBukaFeedKWT('captionFeedKWT')" class="btn btn-ig-feed w-100 py-2">
                    <i class="bi bi-clipboard-check me-1"></i> Salin Tulisan & Buka Feed
                </button>
            </div>
        </div>
    </div>
    @elseif(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- TABLE --}}
    <div class="table-wrapper shadow-sm">
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th width="90">Foto</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th width="160" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr>
                        <td>
                            <div class="product-image mx-auto">
                                @if($p->foto_produk)
                                <img src="{{ asset('storage/'.$p->foto_produk) }}">
                                @else
                                <i class="bi bi-image text-muted fs-3"></i>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $p->nama_produk }}</div>
                            <small class="text-muted">ID Produk #{{ $p->id }}</small>
                        </td>
                        <td>
                            <span class="fw-bold text-success">
                                Rp {{ number_format($p->harga,0,',','.') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-stock {{ $p->stok <= 3 ? 'badge-habis' : 'badge-aman' }}">
                                {{ $p->stok }} {{ $p->satuan }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn-action btn-share-ig" data-bs-toggle="modal" data-bs-target="#modalPromosi{{ $p->id }}" title="Promosikan ke IG">
                                    <i class="bi bi-instagram"></i>
                                </button>

                                <button class="btn-action btn-edit" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $p->id }}">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>

                                <form action="{{ route('kwt.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-basket fs-1 d-block mb-2 opacity-50"></i>
                            Belum ada produk ditambahkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL PROMOSI INSTAGRAM UNTUK PRODUK LAMA --}}
@foreach($products as $p)
<div class="modal fade" id="modalPromosi{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content modal-modern shadow-lg">
            <div class="p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Promosikan Produk</h5>
                        <p class="text-muted small mb-0">Promosikan produk <strong>{{ $p->nama_produk }}</strong> ke Postingan Instagram.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                @php
                $productUrl = url('/products/'.$p->id);
                @endphp

                <textarea id="captionFeedKWT{{ $p->id }}" class="d-none">Beli {{ $p->nama_produk }} segar hasil panen KWT Tani Cibiru Wetan langsung di website kami! 
Harga: Rp {{ number_format($p->harga,0,',','.') }}

Pesan mudah, klik link berikut di browser HP Ibu:

{{ $productUrl }}</textarea>

                <div class="p-3 border rounded-3 bg-light">
                    <div class="mb-3">
                        <h6 class="fw-bold text-dark mb-1">Buat Postingan (Feed / Reels)</h6>
                        <p class="text-muted small mb-0" style="font-size: 0.75rem;">Link belanja otomatis disalin ke HP. Ibu tinggal pilih foto lalu Tempel (Paste) di kolom tulisan Instagram.</p>
                    </div>
                    <button onclick="salinDanBukaFeedKWT('captionFeedKWT{{ $p->id }}')" class="btn btn-ig-feed w-100 py-2">
                        <i class="bi bi-clipboard-check me-1"></i> Salin Tulisan & Buka Feed
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

{{-- MODAL EDIT --}}
@foreach($products as $p)
<div class="modal fade" id="modalEdit{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-modern shadow-lg">
            <form action="{{ route('kwt.products.update', $p->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-0">
                    <div class="col-md-5 modal-left">
                        <div class="modal-title-modern">Edit Produk</div>
                        <div class="modal-subtitle">Perbarui informasi dan foto produk hasil panen.</div>
                        <div class="preview-modern">
                            @if($p->foto_produk)
                            <img id="edit-img-{{ $p->id }}" src="{{ asset('storage/'.$p->foto_produk) }}">
                            @else
                            <i class="bi bi-image text-success" style="font-size:4rem;"></i>
                            <img id="edit-img-{{ $p->id }}" src="" class="d-none">
                            @endif
                        </div>
                        <div class="mt-3">
                            <label class="label-modern">Ambil/Ganti Foto Produk</label>
                            <input type="file" name="foto_produk" class="form-control input-clean" accept="image/*" onchange="previewEdit(this, '{{ $p->id }}')">
                            <small class="petunjuk-ibu">*Kosongkan jika tidak ingin mengganti foto</small>
                        </div>
                    </div>

                    <div class="col-md-7 modal-right">
                        <div class="mb-3">
                            <label class="label-modern">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="nama_produk" class="form-control input-clean" value="{{ $p->nama_produk }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="label-modern">Harga <span class="text-danger">*</span></label>
                                <input type="number" name="harga" class="form-control input-clean" value="{{ $p->harga }}" required>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <label class="label-modern">Stok <span class="text-danger">*</span></label>
                                <input type="number" name="stok" class="form-control input-clean" value="{{ $p->stok }}" required>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <label class="label-modern">Satuan <span class="text-danger">*</span></label>
                                <select name="satuan" class="form-select input-clean">
                                    <option value="kg" {{ $p->satuan == 'kg' ? 'selected' : '' }}>Kg</option>
                                    <option value="Ikat" {{ $p->satuan == 'Ikat' ? 'selected' : '' }}>Ikat</option>
                                    <option value="Bungkus" {{ $p->satuan == 'Bungkus' ? 'selected' : '' }}>Bungkus</option>
                                    <option value="Buah" {{ $p->satuan == 'Buah' ? 'selected' : '' }}>Buah</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="label-modern">Deskripsi Produk <span class="text-danger">*</span></label>
                            <textarea name="deskripsi" rows="4" class="form-control input-clean" required>{{ $p->deskripsi }}</textarea>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button" class="btn btn-light border w-100 btn-modern-cancel" data-bs-dismiss="modal">Batal</button>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-success w-100 btn-modern-save">
                                    <i class="bi bi-check-circle-fill me-1"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-modern shadow-lg">
            <form action="{{ route('kwt.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-0">
                    <div class="col-md-5 modal-left">
                        <div class="modal-title-modern">Tambah Produk</div>
                        <div class="modal-subtitle">Pilih foto dari Galeri atau jepret langsung dengan Kamera.</div>
                        <div class="preview-modern">
                            <i id="icon-add" class="bi bi-image text-success" style="font-size:4rem;"></i>
                            <img id="preview-add" src="" class="d-none">
                        </div>
                        <div class="mt-3">
                            <label class="label-modern">Pilih / Jepret Foto Produk <span class="text-danger">*</span></label>
                            <input type="file" name="foto_produk" class="form-control input-clean" accept="image/*" onchange="previewAdd(this)" required>
                            <small class="petunjuk-ibu mt-2 text-danger fw-bold">*Maksimal ukuran foto 2MB!</small>
                        </div>
                    </div>

                    <div class="col-md-7 modal-right">
                        <div class="mb-3">
                            <label class="label-modern">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="nama_produk" class="form-control input-clean" placeholder="Contoh: Bayam Segar" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="label-modern">Harga <span class="text-danger">*</span></label>
                                <input type="number" name="harga" class="form-control input-clean" placeholder="5000" required>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <label class="label-modern">Stok <span class="text-danger">*</span></label>
                                <input type="number" name="stok" class="form-control input-clean" placeholder="10" required>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <label class="label-modern">Satuan <span class="text-danger">*</span></label>
                                <select name="satuan" class="form-select input-clean">
                                    <option value="kg">Kg</option>
                                    <option value="Ikat">Ikat</option>
                                    <option value="Bungkus">Bungkus</option>
                                    <option value="Buah">Buah</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="label-modern">Deskripsi Produk <span class="text-danger">*</span></label>
                            <textarea name="deskripsi" rows="4" class="form-control input-clean" placeholder="Contoh: Sayur organik segar..." required></textarea>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button" class="btn btn-light border w-100 btn-modern-cancel" data-bs-dismiss="modal">Batal</button>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-success w-100 btn-modern-save">
                                    <i class="bi bi-check-circle-fill me-1"></i> Simpan Produk
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function salinDanBukaFeedKWT(elementId) {
        var copyText = document.getElementById(elementId);

        navigator.clipboard.writeText(copyText.value).then(function() {
            alert("✅ SUDAH TERSALIN BU!\n\nTulisan promosi & link belanja sudah disalin otomatis ke HP Ibu.\n\nSekaras silakan klik + (Tambah) di Instagram, pilih Feed/Postingan, lalu TEMPEL/PASTE tulisan tadi.");
            window.location.href = "instagram://library";
            setTimeout(function() {
                window.location.href = "instagram://";
            }, 500);
        }).catch(function(err) {
            window.location.href = "https://instagram.com";
        });
    }

    function previewAdd(input) {
        const file = input.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                let ukuranMB = (file.size / 1024 / 1024).toFixed(1);
                alert('GAGAL: Ukuran foto terlalu besar (' + ukuranMB + 'MB). \nServer maksimal 2MB! \n\nSilakan pakai foto lain atau turunkan resolusi kamera HP Anda.');
                input.value = '';
                document.getElementById('preview-add').classList.add('d-none');
                document.getElementById('icon-add').classList.remove('d-none');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-add').src = e.target.result;
                document.getElementById('preview-add').classList.remove('d-none');
                const icon = document.getElementById('icon-add');
                if (icon) {
                    icon.classList.add('d-none');
                }
            }
            reader.readAsDataURL(file);
        }
    }

    function previewEdit(input, id) {
        const file = input.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                let ukuranMB = (file.size / 1024 / 1024).toFixed(1);
                alert('GAGAL: Ukuran foto terlalu besar (' + ukuranMB + 'MB). \nServer maksimal 2MB!');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('edit-img-' + id);
                img.src = e.target.result;
                img.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('invalid', function(event) {
                event.preventDefault();
                let invalidField = event.target;

                invalidField.classList.add('shake-invalid');
                invalidField.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                setTimeout(() => {
                    invalidField.classList.remove('shake-invalid');
                }, 3000);
            }, true);
        });
    });
</script>
@endsection