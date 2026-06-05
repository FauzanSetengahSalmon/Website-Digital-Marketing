@extends('layouts.app')

@section('title', $product->nama_produk . ' - Tani Cibiru')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body {
        background-color: #f8faf9;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* Breadcrumb Smooth styling */
    .breadcrumb-item+.breadcrumb-item::before {
        content: "•";
        color: #ccd1d6;
    }

    /* Premium Detail Card */
    .detail-card {
        background: #ffffff;
        border-radius: 28px;
        border: 1px solid rgba(0, 0, 0, 0.03);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.03);
    }

    /* Image Wrapper with Hover Effect */
    .img-detail-wrapper {
        background: #fdfdfd;
        border-radius: 20px;
        overflow: hidden;
        height: 420px;
        border: 1px solid #f1f3f5;
        box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.01);
        position: relative;
    }

    .img-detail-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .img-detail-wrapper:hover img {
        transform: scale(1.04);
    }

    /* Badge & Info Styling */
    .kwt-badge {
        font-size: 11.5px;
        color: #1e5217;
        background: #e8f5e9;
        padding: 8px 16px;
        border-radius: 30px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        border: 1px solid rgba(76, 175, 80, 0.15);
        letter-spacing: 0.3px;
    }

    .price-tag {
        font-size: 2.2rem;
        font-weight: 800;
        color: #1e5217;
        letter-spacing: -0.5px;
    }

    /* QTY INPUT CONTAINER */
    .qty-input-container {
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
        border-radius: 12px;
        padding: 4px;
        border: 1px solid #e2e8f0;
        width: 130px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        transition: all 0.2s ease;
    }

    .qty-input-container:focus-within {
        border-color: #4caf50;
        box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
    }

    .qty-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid transparent;
        background: #f8fafc;
        color: #64748b;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .qty-btn:hover:not(:disabled) {
        background: #e8f5e9;
        color: #1e5217;
        border-color: #c8e6c9;
    }

    .qty-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        background: #f1f5f9;
        color: #94a3b8;
    }

    .qty-btn:active:not(:disabled) {
        transform: scale(0.95);
    }

    .qty-input {
        width: 40px;
        border: none;
        background: transparent;
        text-align: center;
        font-weight: 700;
        font-size: 1.05rem;
        color: #0f172a;
        padding: 0;
        margin: 0;
    }

    .qty-input:focus {
        outline: none;
    }

    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .qty-input[type=number] {
        -moz-appearance: textfield;
    }

    /* Call To Action Button */
    .btn-add-cart {
        background: #4caf50;
        color: white;
        border: none;
        border-radius: 14px;
        padding: 13px 24px;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.25);
    }

    .btn-add-cart:hover:not(:disabled) {
        background: #1e5217;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(30, 82, 23, 0.3);
    }

    .btn-add-cart:active:not(:disabled) {
        transform: translateY(-1px);
    }

    /* Modal Styling */
    .efood-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .efood-modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    .efood-modal-card {
        background: #ffffff;
        border-radius: 28px;
        width: 92%;
        max-width: 380px;
        padding: 35px 30px;
        text-align: center;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.8);
        transform: translateY(20px) scale(0.95);
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .efood-modal-overlay.active .efood-modal-card {
        transform: translateY(0) scale(1);
    }

    .efood-modal-icon-wrapper {
        width: 65px;
        height: 65px;
        background: #e8f5e9;
        color: #4caf50;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 22px auto;
        box-shadow: 0 8px 20px rgba(76, 175, 80, 0.15);
    }

    .efood-modal-icon-wrapper.warning {
        background: #fff3e0;
        color: #f57c00;
        box-shadow: 0 8px 20px rgba(245, 124, 0, 0.15);
    }

    .efood-modal-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 12px;
        letter-spacing: -0.5px;
    }

    .efood-modal-text {
        font-size: 0.92rem;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .efood-modal-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .efood-btn-primary {
        background: #4caf50;
        color: #ffffff;
        border: none;
        border-radius: 14px;
        padding: 13px 20px;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: block;
        width: 100%;
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.15);
    }

    .efood-btn-primary:hover {
        background: #1e5217;
        color: #ffffff;
    }

    .efood-btn-secondary {
        background: #f8fafc;
        color: #64748b;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 13px 20px;
        font-size: 0.95rem;
        font-weight: 700;
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
            <li class="breadcrumb-item"><a href="{{ route('customer.katalog') }}" class="text-success text-decoration-none fw-semibold">Katalog</a></li>
            <li class="breadcrumb-item active text-muted" aria-current="page">Detail Produk</li>
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
                        <i class="bi bi-image text-success opacity-25" style="font-size: 4.5rem;"></i>
                        <span class="small fw-semibold mt-2">Gambar Belum Tersedia</span>
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

                    <h1 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">{{ $product->nama_produk }}</h1>

                    <div class="d-flex align-items-center mb-4">
                        <span class="badge {{ $product->stok > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-3 py-2 fw-bold" style="font-size: 0.8rem;">
                            <i class="bi {{ $product->stok > 0 ? 'bi-check-circle-fill' : 'bi-dash-circle-fill' }} me-1"></i>
                            {{ $product->stok > 0 ? 'Stok Tersedia' : 'Stok Habis' }}
                        </span>
                        <span class="text-muted small ms-3 fw-medium">Sisa {{ $product->stok }} {{ $product->satuan }}</span>
                    </div>

                    <div class="mb-4 py-3 border-top border-bottom border-light">
                        <small class="text-muted d-block mb-1 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Harga Bersih Petani</small>
                        <div class="d-flex align-items-baseline">
                            <span class="price-tag">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                            <span class="text-muted ms-2 fw-medium">/ {{ $product->satuan }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-2" style="font-size: 1rem;">Deskripsi Produk</h6>
                        <p class="text-secondary mb-0 lh-base" style="font-size: 0.95rem;">
                            {{ $product->deskripsi ?? 'Produk sayur atau buah segar pilihan berkualitas tinggi, diproduksi langsung dari panen hasil sendiri oleh Kelompok Wanita Tani (KWT) mitra kami dengan metode yang aman, bersih, dan higienis.' }}
                        </p>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top border-light">
                    @if($product->stok > 0)
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-sm-auto text-center text-sm-start">
                            <label class="small fw-bold text-muted d-block mb-2 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Jumlah</label>
                            <div class="qty-input-container">
                                <button type="button" class="qty-btn" id="btn-minus">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" id="qty-value" class="qty-input" value="1" min="1" max="{{ $product->stok }}" readonly>
                                <button type="button" class="qty-btn" id="btn-plus">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-12 col-sm">
                            <button type="button" id="btn-submit-cart" class="btn-add-cart w-100 d-flex align-items-center justify-content-center">
                                <i class="bi bi-bag-plus-fill fs-5 me-2"></i> Masukkan Keranjang
                            </button>
                        </div>
                    </div>
                    @else
                    <button class="btn btn-light text-muted w-100 py-3 rounded-4 fw-bold border" disabled>
                        <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i> Produk Saat Ini Sedang Habis
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
            <button type="button" class="btn efood-btn-secondary d-inline-flex align-items-center justify-content-center" id="modalCloseBtn">
                Lanjut Belanja
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnMinus = document.getElementById('btn-minus');
        const btnPlus = document.getElementById('btn-plus');
        const qtyInput = document.getElementById('qty-value');
        const btnSubmit = document.getElementById('btn-submit-cart');

        const efoodModal = document.getElementById('efoodModal');
        const modalIcon = document.getElementById('modalIcon');
        const modalTitle = document.getElementById('modalTitle');
        const modalText = document.getElementById('modalText');
        const modalActionBtn = document.getElementById('modalActionBtn');
        const modalCloseBtn = document.getElementById('modalCloseBtn');

        // Mengamankan parsing maxStock dari Blade ke JS jika nilai kosong atau bermasalah
        const maxStock = parseInt("{{ $product->stok }}") || 0;

        // Fungsi kontrol state aktif/disabled tombol minus dan plus demi UX yang baik
        function updateButtonState() {
            if (!qtyInput) return;
            let currentVal = parseInt(qtyInput.value);

            btnMinus.disabled = (currentVal <= 1);
            btnPlus.disabled = (currentVal >= maxStock);
        }

        function showCustomModal(type, title, message, actionText, actionUrl) {
            if (type === 'success') {
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

        if (modalCloseBtn) {
            modalCloseBtn.addEventListener('click', function() {
                efoodModal.classList.remove('active');
            });
        }

        efoodModal.addEventListener('click', function(e) {
            if (e.target === efoodModal) {
                efoodModal.classList.remove('active');
            }
        });

        if (qtyInput) {
            // Jalankan inisialisasi awal tombol +/- state
            updateButtonState();

            btnMinus.addEventListener('click', function() {
                let val = parseInt(qtyInput.value);
                if (val > 1) {
                    qtyInput.value = val - 1;
                    updateButtonState();
                }
            });

            btnPlus.addEventListener('click', function() {
                let val = parseInt(qtyInput.value);
                if (val < maxStock) {
                    qtyInput.value = val + 1;
                    updateButtonState();
                }
            });

            btnSubmit.addEventListener('click', function() {
                const originalContent = btnSubmit.innerHTML;
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Menyimpan...';

                fetch(`/cart/add/{{ $product->id }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            quantity: parseInt(qtyInput.value)
                        })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Unauthorized/Error');
                        return response.json();
                    })
                    .then(data => {
                        btnSubmit.disabled = false;
                        btnSubmit.innerHTML = originalContent;

                        const cartBadge = document.getElementById('cart-badge');
                        if (cartBadge) {
                            cartBadge.innerText = data.cartCount;
                            cartBadge.classList.remove('d-none');
                        }

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