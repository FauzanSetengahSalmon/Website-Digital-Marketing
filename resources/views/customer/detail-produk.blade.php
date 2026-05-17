@extends('layouts.app')

@section('title', $product->nama_produk . ' - EFood')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    body {
        background-color: #fafbfc;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .detail-card {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid #f1f3f5;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.02);
    }

    .img-detail-wrapper {
        background: #f8f9fa;
        border-radius: 20px;
        overflow: hidden;
        height: 400px;
    }

    .img-detail-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .kwt-badge {
        font-size: 11px;
        color: #388e3c;
        background: #f2f8f2;
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
    }

    .price-tag {
        font-size: 2rem;
        font-weight: 700;
        color: #2d7a22;
    }

    .qty-input-container {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border-radius: 12px;
        padding: 4px;
        max-width: 150px;
        border: 1px solid #e9ecef;
    }

    .qty-btn {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        border: none;
        background: white;
        color: #2d3436;
        font-size: 1.2rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        transition: 0.2s;
        cursor: pointer;
    }

    .qty-btn:hover {
        background: #388e3c;
        color: white;
    }

    .qty-input {
        width: 55px;
        border: none;
        background: transparent;
        text-align: center;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .qty-input:focus {
        outline: none;
    }

    .btn-add-cart {
        background: #388e3c;
        color: white;
        border: none;
        border-radius: 14px;
        padding: 14px 28px;
        font-weight: 600;
        transition: 0.2s;
        cursor: pointer;
    }

    .btn-add-cart:hover {
        background: #2e7d32;
        transform: translateY(-2px);
        color: white;
    }

    /* -------------------------------------------------------------------------
       DESAIN MODAL KONFIRMASI CUSTOM ULTRA-ESTETIK & MINIMALIS (NO SWEETALERT)
    ------------------------------------------------------------------------- */
    .efood-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.2); /* Latar belakang gelap tipis */
        backdrop-filter: blur(8px); /* Efek blur kaca iOS premium */
        -webkit-backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .efood-modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    .efood-modal-card {
        background: #ffffff;
        border-radius: 24px;
        width: 90%;
        max-width: 400px;
        padding: 32px 28px;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(241, 243, 245, 0.8);
        transform: scale(0.92);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .efood-modal-overlay.active .efood-modal-card {
        transform: scale(1);
    }

    /* Ikon Centang Estetik Bulat Clean */
    .efood-modal-icon-wrapper {
        width: 60px;
        height: 60px;
        background: #e8f5e9;
        color: #388e3c;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin: 0 auto 20px auto;
    }

    .efood-modal-icon-wrapper.warning {
        background: #fff3e0;
        color: #f57c00;
    }

    .efood-modal-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
        letter-spacing: -0.3px;
    }

    .efood-modal-text {
        font-size: 0.9rem;
        color: #64748b;
        line-height: 1.5;
        margin-bottom: 28px;
        padding: 0 10px;
    }

    /* Group Tombol Aksi Minimalis */
    .efood-modal-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .efood-btn-primary {
        background: #388e3c;
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 12px 20px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
        display: block;
        width: 100%;
    }

    .efood-btn-primary:hover {
        background: #2e7d32;
        color: #ffffff;
    }

    .efood-btn-secondary {
        background: #f8f9fa;
        color: #64748b;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 20px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .efood-btn-secondary:hover {
        background: #f1f5f9;
        color: #334155;
    }
</style>
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('customer.katalog') }}" class="text-success text-decoration-none">Katalog</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Produk</li>
        </ol>
    </nav>

    <div class="card detail-card p-4 p-lg-5 mb-5">
        <div class="row g-4 lg-g-5">
            <div class="col-12 col-md-5">
                <div class="img-detail-wrapper">
                    @if($product->foto_produk)
                        <img src="{{ asset('storage/'.$product->foto_produk) }}" alt="{{ $product->nama_produk }}">
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center h-100 bg-light text-muted">
                            <i class="bi bi-image" style="font-size: 4rem; opacity: 0.3;"></i>
                            <span>Gambar Tidak Tersedia</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-12 col-md-7 d-flex flex-column justify-content-between">
                <div>
                    <div class="mb-3">
                        <span class="kwt-badge">
                            <i class="bi bi-shop me-2"></i> {{ $product->user->name }}
                        </span>
                    </div>

                    <h1 class="fw-bold text-dark mb-2" style="font-size: 2.2rem;">{{ $product->nama_produk }}</h1>
                    
                    <div class="d-flex align-items-center mb-4">
                        <span class="badge {{ $product->stok > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-3 py-2 fw-semibold">
                            {{ $product->stok > 0 ? 'Stok Tersedia' : 'Stok Habis' }}
                        </span>
                        <span class="text-muted small ms-3">Tersedia {{ $product->stok }} {{ $product->satuan }}</span>
                    </div>

                    <div class="mb-4 py-3 border-top border-bottom">
                        <small class="text-muted d-block mb-1">Harga Spesial</small>
                        <div class="d-flex align-items-baseline">
                            <span class="price-tag">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                            <span class="text-muted ms-1">/ {{ $product->satuan }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-2">Deskripsi Produk</h6>
                        <p class="text-secondary mb-0" style="line-height: 1.6;">
                            {{ $product->deskripsi ?? 'Produk sayur atau buah segar pilihan berkualitas tinggi, diproduksi langsung oleh Kelompok Wanita Tani (KWT) mitra kami dengan metode pertanian yang aman, bersih, dan higienis.' }}
                        </p>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top">
                    @if($product->stok > 0)
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label class="small fw-semibold text-muted d-block mb-2">Jumlah</label>
                                <div class="qty-input-container">
                                    <button type="button" class="qty-btn" id="btn-minus" style="line-height: 1;">&minus;</button>
                                    <input type="number" id="qty-value" class="qty-input" value="1" min="1" max="{{ $product->stok }}" readonly>
                                    <button type="button" class="qty-btn" id="btn-plus" style="line-height: 1;">&plus;</button>
                                </div>
                            </div>
                            <div class="col">
                                <label class="small fw-semibold text-muted d-block mb-2">&nbsp;</label>
                                <button type="button" id="btn-submit-cart" class="btn-add-cart w-100 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cart-plus fs-5 me-2"></i> Masukkan Keranjang
                                </button>
                            </div>
                        </div>
                    @else
                        <button class="btn btn-secondary w-100 py-3 rounded-4" disabled>
                            <i class="bi bi-exclamation-triangle me-2"></i> Produk Saat Ini Sedang Habis
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

<div class="efood-modal-overlay" id="efoodModal">
    <div class="efood-modal-card">
        <div class="efood-modal-icon-wrapper" id="modalIcon">
            <i class="bi bi-check-lg"></i>
        </div>
        <h3 class="efood-modal-title" id="modalTitle">Berhasil Ditambahkan</h3>
        <p class="efood-modal-text" id="modalText">Produk pilihan Anda telah berhasil dimasukkan ke keranjang belanja.</p>
        <div class="efood-modal-buttons">
            <a href="{{ route('cart.index') }}" class="efood-btn-primary" id="modalActionBtn">Lihat Keranjang</a>
            <button class="efood-btn-secondary" id="modalCloseBtn">Lanjut Belanja</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnMinus = document.getElementById('btn-minus');
    const btnPlus = document.getElementById('btn-plus');
    const qtyInput = document.getElementById('qty-value');
    const btnSubmit = document.getElementById('btn-submit-cart');
    
    // Elemen Custom Modal
    const efoodModal = document.getElementById('efoodModal');
    const modalIcon = document.getElementById('modalIcon');
    const modalTitle = document.getElementById('modalTitle');
    const modalText = document.getElementById('modalText');
    const modalActionBtn = document.getElementById('modalActionBtn');
    const modalCloseBtn = document.getElementById('modalCloseBtn');
    
    const maxStock = parseInt("{{ $product->stok }}");

    // Fungsi Pembuka Modal Custom
    function showCustomModal(type, title, message, actionText, actionUrl) {
        if(type === 'success') {
            modalIcon.className = "efood-modal-icon-wrapper";
            modalIcon.innerHTML = '<i class="bi bi-check-lg"></i>';
        } else {
            modalIcon.className = "efood-modal-icon-wrapper warning";
            modalIcon.innerHTML = '<i class="bi bi-exclamation-lg"></i>';
        }
        modalTitle.innerText = title;
        modalText.innerText = message;
        modalActionBtn.innerText = actionText;
        modalActionBtn.href = actionUrl;
        
        efoodModal.classList.add('active');
    }

    // Fungsi Penutup Modal Custom
    modalCloseBtn.addEventListener('click', function() {
        efoodModal.classList.remove('active');
    });

    if(qtyInput) {
        btnMinus.addEventListener('click', function() {
            let val = parseInt(qtyInput.value);
            if(val > 1) qtyInput.value = val - 1;
        });

        btnPlus.addEventListener('click', function() {
            let val = parseInt(qtyInput.value);
            if(val < maxStock) qtyInput.value = val + 1;
        });

        btnSubmit.addEventListener('click', function() {
            const originalContent = btnSubmit.innerHTML;
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...';

            fetch(`/cart/add/{{ $product->id }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: parseInt(qtyInput.value) })
            })
            .then(response => {
                if (!response.ok) throw new Error('Unauthorized/Error');
                return response.json();
            })
            .then(data => {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = originalContent;

                // REALTIME BADGE UPDATE
                const cartBadge = document.getElementById('cart-badge');
                if (cartBadge) {
                    cartBadge.innerText = data.cartCount;
                    cartBadge.classList.remove('d-none');
                }

                // Panggil Modal Custom Cantik & Estetik
                showCustomModal(
                    'success', 
                    'Masuk Keranjang!', 
                    'Produk segar pilihanmu sudah tersimpan rapi di dalam keranjang belanja.', 
                    'Lihat Keranjang', 
                    "{{ route('cart.index') }}"
                );
            })
            .catch(error => {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = originalContent;
                
                // Panggil Modal Custom untuk Sesi Login Expiry
                showCustomModal(
                    'warning', 
                    'Sesi Belum Tersedia', 
                    'Silakan login ke akun Anda terlebih dahulu untuk mulai mengisi keranjang belanja.', 
                    'Login Sekarang', 
                    "{{ route('login') }}"
                );
            });
        });
    }
});
</script>
@endsection