@extends('layouts.app')

@section('title', 'Katalog Premium - Tani Cibiru')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
        --green-dark: #2d7a22;
        --green-primary: #4caf50;
        --green-light: #d6f0c2;
        --green-bg: #e8f5e9;
        --text-dark: #1f2937;
        --text-light: #6b7280;
        --border-soft: #f1f5f9;
    }

    body {
        background-color: #fafbfc;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Filter & Search Section */
    .filter-wrapper {
        background: #ffffff;
        padding: 16px 24px;
        border-radius: 24px;
        border: 1px solid rgba(0, 0, 0, 0.03);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.03);
        margin-bottom: 35px;
    }

    .search-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-container i {
        position: absolute;
        left: 18px;
        color: #94a3b8;
        font-size: 1rem;
    }

    .form-input-clean {
        width: 100%;
        border-radius: 14px;
        padding: 12px 18px 12px 45px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        color: var(--text-dark);
    }

    .form-input-clean:focus {
        background: #ffffff;
        border-color: var(--green-primary);
        outline: none;
        box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.1);
    }

    select.form-input-clean {
        padding-left: 18px;
        /* Reset padding for select */
        cursor: pointer;
    }

    .btn-search-clean {
        background: var(--green-dark);
        color: white;
        border: none;
        border-radius: 14px;
        padding: 12px 24px;
        font-size: 0.95rem;
        font-weight: 700;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 12px rgba(45, 122, 34, 0.2);
    }

    .btn-search-clean:hover {
        background: var(--green-primary);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(76, 175, 80, 0.25);
    }

    /* Product Card Styling (Disamakan dengan Home) */
    .product-card {
        border: 1px solid #f0f0f0;
        border-radius: 20px;
        overflow: hidden;
        transition: 0.3s ease;
        background: white;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
        border-color: var(--green-light);
    }

    .badge-stock {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 0.65rem;
        font-weight: 700;
        z-index: 2;
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .stock-available {
        color: var(--green-dark);
    }

    .stock-empty {
        color: #dc3545;
    }

    .img-container {
        height: 160px;
        overflow: hidden;
        background: #f8fafc;
        position: relative;
    }

    .img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .product-card:hover .img-container img {
        transform: scale(1.05);
    }

    .content-body {
        padding: 16px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .kwt-label {
        background-color: #f0fdf4;
        color: #2d7a22;
        border-radius: 5px;
        font-size: 0.65rem;
        font-weight: 700;
        width: fit-content;
        padding: 4px 8px;
        display: inline-flex;
        align-items: center;
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    .product-name {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-dark);
        line-height: 1.4;
        margin-bottom: 8px;
    }

    .desc-container {
        background: #fdfdfd;
        padding: 2px 0px 2px 10px;
        margin-top: 4px;
        margin-bottom: 10px;
        border-left: 2px solid #a7f3d0;
    }

    .product-description-text {
        font-size: 0.76rem;
        color: #64748b;
        margin-bottom: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
        font-style: italic;
    }

    .product-info {
        font-size: 0.75rem;
        color: var(--text-light);
        margin-bottom: 12px;
    }

    /* Buttons (Disamakan dengan Home) */
    .btn-buy-now {
        background: var(--green-primary);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 15px;
        font-size: 0.8rem;
        font-weight: 600;
        flex: 1;
        transition: 0.2s;
        text-align: center;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-buy-now:hover:not(:disabled) {
        background: #2e7d32;
        color: white;
    }

    .btn-cart-outline {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        background: white;
        color: #636e72;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }

    .btn-cart-outline:hover:not(:disabled) {
        border-color: var(--green-primary);
        color: var(--green-primary);
        background: #f2f8f2;
    }

    .btn-cart-outline:disabled,
    .btn-buy-now:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Empty State */
    .empty-state-wrapper {
        border: 1px dashed #cbd5e1;
        border-radius: 24px;
        background: #ffffff;
        padding: 60px 20px;
        text-align: center;
    }
</style>
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-5 fade-in-up">
    <div class="row justify-content-center mb-4">
        <div class="col-lg-10">
            <div class="filter-wrapper">
                <form action="{{ route('customer.katalog') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-12 col-md-7">
                        <div class="search-container">
                            <i class="bi bi-search"></i>
                            <input type="text" name="search" class="form-input-clean" value="{{ request('search') }}" placeholder="Cari sayur atau buah segar pilihanmu...">
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <select name="sort" class="form-input-clean" onchange="this.form.submit()">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Paling Baru</option>
                            <option value="cheap" {{ request('sort') == 'cheap' ? 'selected' : '' }}>Harga Termurah</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-md-2">
                        <button type="submit" class="btn-search-clean w-100">Cari</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-3">
        @forelse ($products as $product)
        <div class="col-lg-3 col-md-4 col-6">
            <div class="product-card border-0 shadow-sm">

                <div class="position-relative">
                    <div class="badge-stock {{ $product->stok > 0 ? 'stock-available' : 'stock-empty' }}">
                        {{ $product->stok > 0 ? 'Tersedia' : 'Habis' }}
                    </div>

                    <div class="img-container">
                        @if($product->foto_produk)
                        <img src="{{ asset('storage/'.$product->foto_produk) }}" alt="{{ $product->nama_produk }}">
                        @else
                        <div class="d-flex flex-column align-items-center justify-content-center h-100 w-100 bg-light text-muted opacity-50">
                            <i class="bi bi-image" style="font-size: 2.5rem;"></i>
                            <small class="fw-medium mt-2">Belum Ada Gambar</small>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="content-body">
                    <span class="kwt-label">
                        <i class="bi bi-shop me-1"></i> {{ $product->user->name ?? 'KETUA KWT' }}
                    </span>

                    <h6 class="product-name">{{ $product->nama_produk }}</h6>

                    <div class="desc-container">
                        <p class="product-description-text">
                            {{ $product->deskripsi ?? 'Sayur dan buah segar dari hasil panen petani lokal terbaik.' }}
                        </p>
                    </div>

                    <p class="product-info m-0">Tersedia {{ $product->stok }} {{ $product->satuan }}</p>

                    <div class="mt-auto pt-2">
                        <div class="mb-2">
                            <span class="fw-bold" style="color: #2d7a22; font-size: 1.05rem;">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                            <span class="text-secondary ms-1" style="font-size: 0.8rem;">/ {{ $product->satuan }}</span>
                        </div>

                        <div class="d-flex gap-2">
                            @if($product->stok > 0)
                            <a href="{{ route('customer.products.show', $product->id) }}" class="btn-buy-now">
                                Detail
                            </a>

                            <button type="button" class="btn-cart-outline add-to-cart-ajax"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->nama_produk }}">
                                <i class="bi bi-cart-plus"></i>
                            </button>
                            @else
                            <button class="btn-buy-now w-100 bg-secondary" disabled>Habis</button>
                            <button class="btn-cart-outline" disabled>
                                <i class="bi bi-cart-x"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="empty-state-wrapper">
                <i class="bi bi-search text-muted mb-3" style="font-size: 3.5rem; opacity: 0.2;"></i>
                <h5 class="fw-bold text-dark mt-2">Produk Tidak Ditemukan</h5>
                <p class="text-muted mb-4">Maaf, hasil tani atau sayuran yang Anda cari sedang tidak tersedia saat ini.</p>
                <a href="{{ route('customer.katalog') }}" class="btn btn-search-clean d-inline-flex align-items-center">
                    <i class="bi bi-arrow-clockwise me-2"></i> Muat Ulang Katalog
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.add-to-cart-ajax').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                const productName = this.getAttribute('data-name');
                const originalContent = this.innerHTML;

                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

                fetch(`/cart/add/${productId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            quantity: 1
                        })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Unauthorized/Error');
                        return response.json();
                    })
                    .then(data => {
                        this.disabled = false;
                        this.innerHTML = '<i class="bi bi-check2"></i>';

                        setTimeout(() => {
                            this.innerHTML = originalContent;
                        }, 2000);

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: `${productName} ditambahkan!`,
                                showConfirmButton: false,
                                timer: 2500,
                                timerProgressBar: true
                            });
                        } else {
                            alert(`${productName} berhasil dimasukkan ke keranjang!`);
                        }

                        const cartBadge = document.getElementById('cart-badge');
                        if (cartBadge) {
                            cartBadge.innerText = data.cartCount;
                            cartBadge.classList.remove('d-none');
                            cartBadge.classList.add('animate__animated', 'animate__bounceIn');
                            setTimeout(() => {
                                cartBadge.classList.remove('animate__animated', 'animate__bounceIn');
                            }, 1000);
                        }
                    })
                    .catch(error => {
                        this.disabled = false;
                        this.innerHTML = originalContent;

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Belum Login',
                                text: 'Silakan login terlebih dahulu untuk menambah produk ke keranjang.',
                                confirmButtonColor: '#4caf50',
                                confirmButtonText: 'Login Sekarang'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('login') }}";
                                }
                            });
                        } else {
                            alert('Silakan login terlebih dahulu untuk menambah keranjang!');
                            window.location.href = "{{ route('login') }}";
                        }
                    });
            });
        });
    });
</script>
@endsection