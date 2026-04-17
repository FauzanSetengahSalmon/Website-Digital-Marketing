@extends('layouts.app')

@section('title', 'EFood - Produk Organik Segar dari Tangan Petani')

@push('styles')
<style>
    .hero-section {
        margin: 24px 0 48px;
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        min-height: 400px;
        display: flex;
        align-items: center;
        background: linear-gradient(rgba(30,60,20,0.45), rgba(30,60,20,0.35)),
                    url('https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?w=1200&q=80') center/cover no-repeat;
    }

    .hero-content {
        padding: 60px 48px;
        max-width: 500px;
        position: relative;
        z-index: 2;
    }

    .hero-title {
        font-size: 2.6rem;
        font-weight: 700;
        color: white;
        line-height: 1.2;
        margin-bottom: 16px;
    }

    .hero-desc {
        font-size: 0.92rem;
        color: rgba(255,255,255,0.88);
        line-height: 1.6;
        margin-bottom: 28px;
    }

    .hero-dots {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 6px;
    }

    .hero-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255,255,255,0.4);
    }

    .hero-dot.active {
        background: white;
        width: 22px;
        border-radius: 4px;
    }

    .btn-belanja {
        background: white;
        color: var(--text-dark);
        border: none;
        padding: 10px 22px;
        border-radius: 8px;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .btn-belanja:hover {
        background: var(--green-light);
        color: var(--green-primary);
        transform: translateY(-1px);
    }

    .btn-about {
        background: transparent;
        color: white;
        border: 1.5px solid rgba(255,255,255,0.6);
        padding: 10px 22px;
        border-radius: 8px;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .btn-about:hover {
        background: rgba(255,255,255,0.15);
        border-color: white;
        color: white;
    }

    .section-title {
        font-size: 2rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 8px;
    }

    .section-subtitle {
        text-align: center;
        color: var(--text-muted);
        font-size: 0.92rem;
        margin-bottom: 48px;
    }

    .fitur-section {
        padding: 20px 0 20px 0;
    }

    .fitur-card {
        text-align: center;
        padding: 10px 0 50px 0;
    }

    .fitur-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: var(--green-primary);
        border: 2px solid var(--green-light);
        border-radius: 16px;
        background: rgba(214,240,194,0.3);
    }

    .fitur-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .fitur-desc {
        font-size: 0.85rem;
        color: var(--text-muted);
        line-height: 1.6;
    }

    .produk-section {
        padding: 10px 0 80px 0;
    }

    .produk-highlight {
        background: linear-gradient(135deg, #d6f5b0 0%, #e8f5a3 100%);
        border-radius: 20px;
        overflow: hidden;
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 380px;
    }

    .produk-highlight-content {
        padding: 48px 44px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .produk-tag {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--green-primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }

    .produk-title {
        font-size: 1.8rem;
        font-weight: 700;
        line-height: 1.25;
        margin-bottom: 14px;
        color: var(--text-dark);
    }

    .produk-desc {
        font-size: 0.88rem;
        color: #555;
        line-height: 1.65;
        margin-bottom: 28px;
    }

    .btn-green {
        background: var(--green-primary);
        color: white;
        border: none;
        padding: 11px 26px;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .btn-green:hover {
        background: var(--green-btn);
        color: white;
        transform: translateY(-1px);
    }

    .produk-highlight-img {
        position: relative;
        overflow: hidden;
    }

    .produk-highlight-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    @media (max-width: 768px) {
        .hero-title { font-size: 1.8rem; }
        .hero-content { padding: 40px 28px; }
        .produk-highlight { grid-template-columns: 1fr; }
        .produk-highlight-img { height: 240px; }
        .section-title { font-size: 1.6rem; }
        .hero-section { min-height: 320px; }
    }
</style>
@endpush

@section('content')
<div class="container">
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Produk Organik segar<br>dari tangan petani</h1>
            <p class="hero-desc">
                Kami menghadirkan produk organik berkualitas tinggi langsung dari
                kebun komunitas wanita tani. Dukung pertanian berkelanjutan
                sambil menikmati kesegaran alami setiap hari.
            </p>
            <div class="d-flex gap-3 flex-wrap">
                <a href="#" class="btn-belanja">Belanja</a>
                <a href="#" class="btn-about">About us</a>
            </div>
        </div>
    </section>

</div>

<section class="fitur-section">
    <div class="container">
        <h2 class="section-title">Apa yang kami tawarkan</h2>
        <p class="section-subtitle">Hasil panen terbaik dari kebun organik</p>

        <div class="row g-4 justify-content-center">
            <div class="col-12 col-md-4">
                <div class="fitur-card">
                    <div class="fitur-icon">
                        <i class="bi bi-basket3"></i>
                    </div>
                    <h3 class="fitur-title">Produk segar dari kebun kami</h3>
                    <p class="fitur-desc">Sayuran, buah, dan biji-bijian tanpa pestisida berbahaya</p>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="fitur-card">
                    <div class="fitur-icon">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <h3 class="fitur-title">Strategi digital untuk bisnis</h3>
                    <p class="fitur-desc">Layanan pemasaran online yang dirancang khusus untuk UMKM lokal</p>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="fitur-card">
                    <div class="fitur-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3 class="fitur-title">Komunitas wanita tani berkembang</h3>
                    <p class="fitur-desc">Setiap pembelian mendukung pendidikan dan kesejahteraan petani lokal</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="produk-section">
    <div class="container">
        <h2 class="section-title">Produk unggulan kami</h2>
        <p class="section-subtitle">Hasil panen terbaik dari kebun organik</p>

        <div class="produk-highlight">
            <div class="produk-highlight-content">
                <p class="produk-tag">Produk Organik dan hasil olahan</p>
                <h3 class="produk-title">Bayam organik segar dari kebun kami</h3>
                <p class="produk-desc">
                    Ditanam tanpa pestisida sintetis dan dipanen pada puncak kesegaran.
                    Kaya akan nutrisi dan rasa alami yang autentik.
                </p>
                <div>
                    <a href="#" class="btn-green">Belanja</a>
                </div>
            </div>
            <div class="produk-highlight-img">
                <img src="{{ asset('image/Screenshot 2026-04-16 233530.png') }}"alt="Bayam Organik Segar">
            </div>
        </div>
    </div>
</section>
@endsection