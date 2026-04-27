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
    }

    .img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .content-body {
        padding: 16px;
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

    .btn-cart-outline:hover {
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
        color: #2ecc71;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center mb-4">
        <div class="col-lg-10">
            <div class="filter-wrapper">
                <form class="row g-2 align-items-center">
                    <div class="col-md-7">
                        <div class="search-container">
                            <i class="bi bi-search"></i>
                            <input type="text" class="form-input-clean" placeholder="Cari sayur atau buah segar...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-input-clean ps-3">
                            <option value="">Terbaru</option>
                            <option value="">Termurah</option>
                             <option value="">Terlaris</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn-search-clean w-100">Cari</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-3">
        @for ($i = 1; $i <= 8; $i++)
            <div class="col-6 col-md-4 col-lg-3">
            <div class="product-card">
                <div class="badge-stock">Tersedia</div>
                <div class="img-container">
                    <img src="image/Screenshot 2026-04-16 233530.png" alt="Produk">
                </div>
                <div class="content-body">
                    <span class="kwt-label">KWT Cibiru</span>
                    <h3 class="product-name">Kangkung Segar</h3>
                    <p class="product-info">Organik, tanpa pestisida, dipetik langsung dari kebun.</p>

                    <div class="d-flex align-items-baseline mb-3">
                        <span class="price-tag">Rp 4.500</span>
                        <span class="price-unit ms-1">/ ikat</span>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn-buy-now">Beli</button>
                        <button class="btn-cart-outline">
                            <i class="bi bi-cart-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
    </div>
    @endfor
</div>
</div>
@endsection