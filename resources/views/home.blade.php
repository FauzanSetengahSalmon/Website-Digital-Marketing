@extends('layouts.app')

@section('title', 'EFood - Produk Organik Segar dari Tangan Petani')

@push('styles')
<style>
    :root {
        --green-dark: #2d7a22;
        --green-primary: #4caf50;
        --green-light: #d6f0c2;
    }

    .hero-section {
        margin: 24px 0 48px;
        border-radius: 20px;
        overflow: hidden;
        min-height: 400px;
        display: flex;
        align-items: center;
        opacity: 0.9;
        background: url('https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?w=1200&q=80') center/cover no-repeat;
    }

    .hero-content {
        padding: 60px 48px;
        max-width: 500px;
        color: white;
    }

    .hero-title {
        font-size: 2.6rem;
        font-weight: 700;
    }

    .btn-green {
        background: var(--green-dark);
        color: white;
        padding: 10px 22px;
        border-radius: 999px;
        text-decoration: none;
        transition: 0.2s;
        border: none;
    }

    .btn-green:hover {
        background: var(--green-primary);
        color: white;
    }

    .btn-outline-green {
        border: 1.5px solid white;
        color: white;
        padding: 10px 22px;
        border-radius: 999px;
        text-decoration: none;
    }

    .section-title {
        text-align: center;
        font-weight: 700;
        color: var(--green-dark);
    }

    .section-subtitle {
        text-align: center;
        color: #777;
        margin-bottom: 40px;
    }

    .product-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        transition: 0.25s;
        background: white;
    }

    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .product-img {
        height: 220px;
        object-fit: cover;
    }

    .product-kwt {
        font-size: 0.8rem;
        color: #777;
    }

    .product-price {
        font-weight: 700;
        color: var(--green-dark);
        font-size: 1.1rem;
    }

    .badge-organic {
        position: absolute;
        top: 12px;
        left: 12px;
        background: var(--green-light);
        color: var(--green-dark);
        padding: 5px 12px;
        font-size: 0.75rem;
        border-radius: 20px;
    }

    .cta-section {
        background: linear-gradient(135deg, #d6f0c2, #f4fbef, #ffffff);
        border-radius: 20px;
        color: #444;
        position: relative;
        overflow: hidden;
    }

    .cta-section h2 {
        color: var(--green-dark);
        font-weight: 800;
    }

    .cta-section p {
        color: #666;
        max-width: 600px;
        margin: 0 auto 20px;
    }

    .cta-btn-primary {
        background: var(--green-dark);
        color: white;
        padding: 10px 24px;
        border-radius: 999px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.2s;
    }

    .cta-btn-primary:hover {
        background: var(--green-primary);
        color: white;
    }

    .cta-btn-outline {
        border: 1.5px solid var(--green-dark);
        color: var(--green-dark);
        padding: 10px 24px;
        border-radius: 999px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.2s;
    }

    .cta-btn-outline:hover {
        background: var(--green-light);
    }
</style>
@endpush

@section('content')

<div class="container">

    <section class="hero-section">
        <div class="hero-content">

            <h1 class="hero-title">
                Produk Organik Segar<br>dari Petani Lokal
            </h1>

            <p class="mb-4">
                Nikmati hasil panen terbaik langsung dari kebun organik.
            </p>

            <div class="d-flex gap-3 flex-wrap">
                <a href="#" class="btn-green">Belanja Sekarang</a>
                <a href="{{ route("about") }}" class="btn-outline-green">Tentang Kami</a>
            </div>

        </div>
    </section>
</div>

<section class="py-5 bg-light">
    <div class="container">

        <h2 class="section-title">Produk Kami</h2>
        <p class="section-subtitle">Pilihan terbaik dari kebun organik</p>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="product-card position-relative">
                    <span class="badge-organic">Organik</span>

                    <img src="image/Screenshot 2026-04-16 233530.png"
                        class="w-100 product-img">

                    <div class="p-4">
                        <h5 class="fw-bold mb-1">Bayam Organik</h5>

                        <div class="product-kwt mb-2">
                            <i class="bi bi-people"></i> KWT 1
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="product-price">Rp 10.000</span>

                            <button class="btn btn-sm btn-green px-3 btn-cart">
                                <i class="bi bi-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="product-card position-relative">
                    <span class="badge-organic">Organik</span>

                    <img src="image/Screenshot 2026-04-16 233530.png"
                        class="w-100 product-img">

                    <div class="p-4">
                        <h5 class="fw-bold mb-1">Tomat Segar</h5>

                        <div class="product-kwt mb-2">
                            <i class="bi bi-people"></i> KWT 2
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="product-price">Rp 12.000</span>

                            <button class="btn btn-sm btn-green px-3 btn-cart">
                                <i class="bi bi-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="product-card position-relative">
                    <span class="badge-organic">Organik</span>

                    <img src="image/Screenshot 2026-04-16 233530.png"
                        class="w-100 product-img">

                    <div class="p-4">
                        <h5 class="fw-bold mb-1">Wortel Organik</h5>

                        <div class="product-kwt mb-2">
                            <i class="bi bi-people"></i> KWT 3
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="product-price">Rp 8.000</span>

                            <button class="btn btn-sm btn-green px-3 btn-cart">
                                <i class="bi bi-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="cta-section text-center p-5 shadow">

            <h2 class="fw-bold mb-3">
                Dukung Petani Lokal & Hidup Lebih Sehat 🌱
            </h2>

            <p class="mb-4">
                Setiap pembelian membantu kesejahteraan KWT dan menghadirkan makanan sehat ke keluarga Anda.
            </p>

            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="#" class="cta-btn-primary">
                    Belanja Sekarang
                </a>
                <a href="{{ route("about") }}" class="cta-btn-outline">
                    Pelajari Lebih Lanjut
                </a>
            </div>

        </div>
    </div>
</section>

<script>
    document.querySelectorAll('.btn-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            this.innerHTML = '<i class="bi bi-check"></i> Ditambahkan';
            this.disabled = true;
        });
    });
</script>

@endsection