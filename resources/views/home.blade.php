@extends('layouts.app')

@section('title', 'EFood - Produk Organik Segar dari Tangan Petani')

@push('styles')
<style>
    :root {
        --green-dark: #2d7a22;
        --green-primary: #4caf50;
        --green-light: #d6f0c2;
        --text-dark: #1f2937;
        --text-light: #6b7280;
    }

    /* Animasi masuk yang halus */
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

    .hero-section {
        margin: 24px 0 40px;
        border-radius: 25px;
        overflow: hidden;
        min-height: 380px;
        display: flex;
        align-items: center;
        background: linear-gradient(to right, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.1)),
            url('https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?w=1200&q=80') center/cover no-repeat;
    }

    .hero-content {
        padding: 60px;
        max-width: 550px;
        color: white;
    }

    .hero-title {
        font-size: 2.4rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 15px;
    }

    /* Tombol-tombol */
    .btn-green {
        background: var(--green-dark);
        color: white;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-green:hover {
        background: var(--green-primary);
        color: white;
        transform: translateY(-2px);
    }

    .btn-outline-green {
        border: 2px solid white;
        color: white;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s;
    }

    .btn-outline-green:hover {
        background: white;
        color: var(--green-dark);
    }

    /* Produk Section */
    .section-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .section-subtitle {
        color: var(--text-light);
        margin-bottom: 35px;
        font-size: 1rem;
    }

    .product-card {
        border: 1px solid #f0f0f0;
        border-radius: 20px;
        overflow: hidden;
        transition: 0.3s ease;
        background: white;
        height: 100%;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
        border-color: var(--green-light);
    }

    .product-img {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }

    .badge-organic {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--green-dark);
        color: white;
        padding: 4px 12px;
        font-size: 0.7rem;
        font-weight: 700;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .product-info {
        padding: 20px;
    }

    .product-kwt {
        font-size: 0.85rem;
        color: var(--text-light);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .product-price {
        font-weight: 700;
        color: var(--green-dark);
        font-size: 1rem;
    }

    /* Keranjang Button Bulat */
    .btn-cart {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--green-light);
        color: var(--green-dark);
        border: none;
        transition: 0.3s;
    }

    .btn-cart:hover {
        background: var(--green-dark);
        color: white;
    }

    /* CTA Section Ringkas */
    .cta-section {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border-radius: 25px;
        border: 1px solid var(--green-light);
        padding: 50px !important;
    }

    .cta-section h2 {
        color: var(--green-dark);
        font-size: 1.8rem;
        font-weight: 800;
    }

    .cta-btn-outline {
        border: 2px solid var(--green-dark);
        color: var(--green-dark);
        padding: 12px 28px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
    }

    .cta-btn-outline:hover {
        background: var(--green-dark);
        color: white;
    }
</style>
@endpush

@section('content')

<div class="container fade-in-up">

    <section class="hero-section shadow-sm">
        <div class="hero-content">
            <h1 class="hero-title">
                Produk Organik Segar<br>dari Petani Lokal
            </h1>
            <p class="mb-4 opacity-90">
                Nikmati hasil panen terbaik yang dipetik langsung dengan penuh kasih sayang dari kebun organik kami.
            </p>
            <div class="d-flex gap-3">
                <a href="#" class="btn-green">
                    <i class="bi bi-bag-check"></i> Belanja Sekarang
                </a>
                <a href="{{ route('about') }}" class="btn-outline-green">Tentang Kami</a>
            </div>
        </div>
    </section>

    <section class="py-4">
        <div class="text-center mb-5">
            <h2 class="section-title" style="color: var(--green-dark);">Produk Pilihan</h2>
            <p class="section-subtitle">Kualitas terbaik langsung dari kelompok tani</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="product-card position-relative">
                    <span class="badge-organic">Organik</span>
                    <img src="image/Screenshot 2026-04-16 233530.png" class="product-img">
                    <div class="product-info">
                        <h5 class="fw-bold mb-1">Bayam Organik</h5>
                        <div class="product-kwt mb-3">
                            <i class="bi bi-shop"></i> KWT Lestari Makmur
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="product-price">Rp 10.000</span>
                            <button class="btn-cart btn-cart-action">
                                <i class="bi bi-cart-plus-fill fs-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="product-card position-relative">
                    <span class="badge-organic">Organik</span>
                    <img src="image/Screenshot 2026-04-16 233530.png" class="product-img">
                    <div class="product-info">
                        <h5 class="fw-bold mb-1">Tomat Segar</h5>
                        <div class="product-kwt mb-3">
                            <i class="bi bi-shop"></i> KWT Melati Putih
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="product-price">Rp 12.000</span>
                            <button class="btn-cart btn-cart-action">
                                <i class="bi bi-cart-plus-fill fs-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="product-card position-relative">
                    <span class="badge-organic">Organik</span>
                    <img src="image/Screenshot 2026-04-16 233530.png" class="product-img">
                    <div class="product-info">
                        <h5 class="fw-bold mb-1">Wortel Organik</h5>
                        <div class="product-kwt mb-3">
                            <i class="bi bi-shop"></i> KWT Berkah Alam
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="product-price">Rp 8.000</span>
                            <button class="btn-cart btn-cart-action">
                                <i class="bi bi-cart-plus-fill fs-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
    <div class="container">
        <div class="cta-section text-center p-5 shadow">

            <h2 class="fw-bolder mb-3">
                Dukung Petani Lokal & Hidup Lebih Sehat 🌱
            </h2>

            <p class="mb-4">
                Setiap pembelian membantu kesejahteraan KWT dan menghadirkan makanan sehat ke keluarga Anda.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="#" class="btn-green">Belanja Sekarang</a>
                <a href="{{ route('about') }}" class="cta-btn-outline">Pelajari Lebih Lanjut</a>
            </div>
        </div>
    </section>

</div>
@endsection