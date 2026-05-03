@extends('layouts.app')

@section('title', 'Katalog Premium - EFood')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    :root {
        --green-primary: #388e3c;
        --green-bg: #f2f8f2;
        --text-main: #2d3436;
        --text-muted: #636e72;
        --border-soft: #f1f3f5;
    }

    body {
        background-color: #fafbfc;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-main);
    }

    .filter-wrapper {
        background: #ffffff;
        padding: 12px 20px;
        border-radius: 16px;
        border: 1px solid var(--border-soft);
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.02);
        margin-bottom: 30px;
    }

    .search-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-container i {
        position: absolute;
        left: 15px;
        color: #b2bec3;
        font-size: 0.9rem;
    }

    .form-input-clean {
        width: 100%;
        border-radius: 10px;
        padding: 9px 15px 9px 40px;
        border: 1px solid #e9ecef;
        background: #f8f9fa;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .form-input-clean:focus {
        background: #fff;
        border-color: var(--green-primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(56, 142, 60, 0.08);
    }

    .btn-search-clean {
        background: var(--green-primary);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 9px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-search-clean:hover {
        background: #2e7d32;
        transform: translateY(-1px);
    }

    .product-card {
        background: white;
        border-radius: 20px;
        border: 1px solid var(--border-soft);
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        border-color: transparent;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        transform: translateY(-5px);
    }

    .img-container {
        height: 180px;
        overflow: hidden;
        border-radius: 20px 20px 0 0;
        background: #f8f9fa;
    }

    .img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .content-body {
        padding: 16px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .kwt-label {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--green-primary);
        background: var(--green-bg);
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 700;
        display: inline-block;
        margin-bottom: 8px;
    }

    .product-name {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 4px;
        color: var(--text-main);
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-info {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-bottom: 15px;
        line-height: 1.4;
    }

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
    }

    .btn-buy-now:hover:not(:disabled) {
        background: #2e7d32;
    }

    .btn-cart-outline {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        background: white;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }

    .btn-cart-outline:hover:not(:disabled) {
        border-color: var(--green-primary);
        color: var(--green-primary);
        background: var(--green-bg);
    }

    .badge-stock {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255, 255, 255, 0.9);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
        z-index: 2;
    }

    .stock-available {
        color: #2ecc71;
    }

    .stock-empty {
        color: #e74c3c;
    }
</style>
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- PENTING: Pastikan script ini dimuat paling awal di konten -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container py-5">
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="filter-wrapper">
                <form action="{{ route('customer.katalog') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-7">
                        <div class="search-container">
                            <i class="bi bi-search"></i>
                            <input type="text" name="search" class="form-input-clean" value="{{ request('search') }}" placeholder="Cari sayur atau buah segar...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="sort" class="form-input-clean ps-3" onchange="this.form.submit()">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="cheap" {{ request('sort') == 'cheap' ? 'selected' : '' }}>Termurah</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn-search-clean w-100">Cari</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($products as $product)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="product-card">
                <div class="badge-stock {{ $product->stok > 0 ? 'stock-available' : 'stock-empty' }}">
                    {{ $product->stok > 0 ? 'Tersedia' : 'Habis' }}
                </div>

                <div class="img-container">
                    @if($product->foto_produk)
                    <img src="{{ asset('storage/'.$product->foto_produk) }}" alt="{{ $product->nama_produk }}">
                    @else
                    <div class="d-flex flex-column align-items-center justify-content-center h-100 bg-light text-muted" style="opacity: 0.4;">
                        <i class="bi bi-image" style="font-size: 2rem;"></i>
                        <small style="font-size: 10px;">No Image</small>
                    </div>
                    @endif
                </div>

                <div class="content-body">
                    <span class="kwt-label">
                        <i class="bi bi-shop me-1"></i>
                        {{ $product->user->name }}
                    </span>

                    <h3 class="product-name">{{ $product->nama_produk }}</h3>
                    <p class="product-info">Tersedia {{ $product->stok }} {{ $product->satuan }}</p>

                    <div class="mt-auto">
                        <div class="d-flex align-items-baseline mb-3">
                            <span class="fw-bold" style="color: #2d7a22; font-size: 1.05rem;">Rp. {{ number_format($product->harga, 0, ',', '.') }}</span>
                            <span class="text-secondary" style="font-size: 0.8rem;">/{{ $product->satuan }}</span>
                        </div>

                        <div class="d-flex gap-2">
                            @if($product->stok > 0)
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-grow-1">
                                @csrf
                                <input type="hidden" name="direct_buy" value="1">
                                <button type="submit" class="btn-buy-now w-100">Beli</button>
                            </form>

                            <button type="button" class="btn-cart-outline add-to-cart-btn"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->nama_produk }}">
                                <i class="bi bi-cart-plus"></i>
                            </button>
                            @else
                            <button class="btn-buy-now w-100" disabled>Habis</button>
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
        <div class="col-12 text-center py-5">
            <div class="py-5" style="border: 1px dashed #e0e0e0; border-radius: 20px; background: #fff;">
                <i class="bi bi-wind text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                <h6 class="fw-bold text-dark mt-3">Hasil tani belum ditemukan</h6>
                <a href="{{ route('customer.katalog') }}" class="btn btn-sm btn-outline-success px-4 mt-2">Refresh Halaman</a>
            </div>
        </div>
        @endforelse
    </div>
</div>

<script>
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');
            const icon = this.querySelector('i');

            icon.className = 'bi bi-hourglass-split';
            this.disabled = true;

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
                .then(response => response.json())
                .then(data => {
                    icon.className = 'bi bi-cart-plus';
                    this.disabled = false;

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: productName + ' masuk keranjang!',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true
                        });
                    } else {
                        alert(productName + ' masuk keranjang!');
                    }
                    const cartBadge = document.getElementById('cart-badge');
                    if (cartBadge) {
                        if (data.cartCount !== undefined) {
                            cartBadge.innerText = data.cartCount;
                        } else {
                            let count = parseInt(cartBadge.innerText) || 0;
                            cartBadge.innerText = count + 1;
                        }
                        cartBadge.classList.remove('d-none');
                    }
                })
                .catch(error => {
                    icon.className = 'bi bi-cart-plus';
                    this.disabled = false;
                    console.error('Error:', error);
                });
        });
    });
</script>
@endsection