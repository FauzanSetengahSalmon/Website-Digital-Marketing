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

    /* Ramping & Clean Filter Bar */
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

    /* Card Product Minimalist */
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
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .price-tag {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--green-primary);
    }

    .price-unit {
        font-size: 0.7rem;
        color: var(--text-muted);
        font-weight: 400;
    }

    /* Action Buttons */
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

    .btn-buy-now:disabled {
        background: #ccc;
        cursor: not-allowed;
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

    .btn-cart-outline:disabled {
        color: #ccc;
        cursor: not-allowed;
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
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        z-index: 2;
    }

    .stock-available {
        color: #2ecc71;
    }

    .stock-empty {
        color: #e74c3c;
    }

    /* Empty State Styling */
    .empty-state-wrapper {
        padding: 80px 20px;
        background: #ffffff;
        border-radius: 24px;
        border: 1px dashed #e0e0e0;
    }

    .empty-icon-circle {
        width: 100px;
        height: 100px;
        background: var(--green-bg);
        color: var(--green-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 20px;
        font-size: 2.5rem;
        opacity: 0.7;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <!-- Filter Bar (Sama kayak sebelumnya) -->
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
                    @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->nama_produk }}">
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
                        {{ $product->user->name ?? 'KWT E-Food' }}
                    </span>

                    <h3 class="product-name" style="line-height: 2;">{{ $product->nama_produk }}</h3>
                    <p class="product-info" style="margin-bottom: 8px;">Tersedia {{ $product->stok }} {{ $product->satuan }}</p>

                    <div class="mt-auto">
                        <div class="d-flex align-items-baseline mb-3">
                            <span class="fw-bold" style="color: #2d7a22; font-size: 1.05rem;">Rp. {{ number_format($product->harga, 0, ',', '.') }}</span><span class="text-secondary" style="font-size: 0.8rem;">/{{ $product->satuan }}</span>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn-buy-now" {{ $product->stok <= 0 ? 'disabled' : '' }}>Beli</button>
                            <button class="btn-cart-outline" {{ $product->stok <= 0 ? 'disabled' : '' }}>
                                <i class="bi bi-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <!-- Tampilan Pas Data Tidak Ditemukan (Tipis & Bagus) -->
        <div class="col-12 text-center py-5">
            <div class="py-5" style="border: 1px dashed #e0e0e0; border-radius: 20px; background: #fff;">
                <div class="mb-3">
                    <i class="bi bi-wind text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                </div>
                <h6 class="fw-bold text-dark">Hasil tani belum ditemukan</h6>
                <p class="text-muted small">Coba cari dengan kata kunci lain atau cek kategori berbeda.</p>
                <a href="{{ route('customer.katalog') }}" class="btn btn-sm btn-outline-success px-4" style="border-radius: 8px;">Refresh Halaman</a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection