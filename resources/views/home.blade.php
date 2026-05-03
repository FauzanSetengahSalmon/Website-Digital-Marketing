@extends('layouts.app')

@section('title', 'EFood - Produk Segar dari Tangan Petani')

@push('styles')
<style>
    :root {
        --green-dark: #2d7a22;
        --green-primary: #4caf50;
        --green-light: #d6f0c2;
        --text-dark: #1f2937;
        --text-light: #6b7280;
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

    .section-title {
        font-size: 2rem;
        font-weight: 800;
        color: #1f2937;
        position: relative;
        display: inline-block;
        padding-bottom: 12px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        left: 50%;
        bottom: 0;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background-color: #4caf50;
        border-radius: 2px;
    }

    .section-subtitle {
        color: var(--text-light);
        margin-top: 15px;
        margin-bottom: 40px;
        font-size: 1.1rem;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
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
</style>
@endpush

@section('content')
<div class="container fade-in-up">

    <section class="hero-section shadow-sm">
        <div class="hero-content">
            <h1 class="hero-title">Produk Segar<br>dari Petani Lokal</h1>
            <p class="mb-4 opacity-90">
                Nikmati hasil panen terbaik yang dipetik langsung dengan penuh kasih sayang dari kebun kami.
            </p>
            <div class="d-flex gap-3">
                <a href="#produk" class="btn-green"><i class="bi bi-bag-check"></i> Belanja Sekarang</a>
                <a href="{{ route('about') }}" class="btn-outline-green">Tentang Kami</a>
            </div>
        </div>
    </section>

    <section class="py-4" id="produk">
        <div class="text-center mb-4">
            <h2 class="section-title" style="color: var(--green-dark); font-weight: 800; position: relative; display: inline-block; padding-bottom: 10px;">
                Produk Pilihan
                <div style="position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 50px; height: 3px; background: var(--green-primary); border-radius: 2px;"></div>
            </h2>
            <p class="section-subtitle" style="margin-top: 15px; color: var(--text-light); font-size: 0.9rem;">
                Temukan koleksi sayuran dan hasil bumi terbaik langsung dari lahan pertanian lokal oleh kelompok tani kami.
            </p>
        </div>

        <div class="row g-3">
            @forelse($products as $product)
            <div class="col-lg-3 col-md-4 col-6">
                <div class="product-card border-0 shadow-sm" style="border-radius: 15px; background: #fff; overflow: hidden; transition: 0.3s;">
                    <!-- Gambar -->
                    <div class="position-relative">
                        @if($product->foto_produk)
                        <img src="{{ asset('storage/' . $product->foto_produk) }}" style="height: 140px; width: 100%; object-fit: cover;">
                        @else
                        <img src="https://via.placeholder.com/300x200" style="height: 140px; width: 100%; object-fit: cover;">
                        @endif
                        <span class="badge bg-white text-success position-absolute top-0 end-0 m-2 px-2 py-1" style="border-radius: 50px; font-weight: 700; font-size: 0.6rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            Tersedia
                        </span>
                    </div>

                    <div class="p-3">
                        <!-- Label KWT -->
                        <div class="d-inline-flex align-items-center gap-1 px-2 py-1 mb-1" style="background-color: #f0fdf4; color: #2d7a22; border-radius: 5px; font-size: 0.65rem; font-weight: 700;">
                            <i class="bi bi-shop me-1"></i>
                            <span class="text-uppercase">{{ $product->user->name ?? 'KETUA KWT' }}</span>
                        </div>

                        <!-- Nama & Stok (Dibuat sangat rapat) -->
                        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem; line-height: 2;">{{ $product->nama_produk }}</h6>
                        <p class="text-secondary mb-2" style="font-size: 0.75rem;">Tersedia {{ $product->stok }} {{ $product->satuan }}.</p>

                        <div class="mt-1">
                            <div class="mt-1">
                                <span class="fw-bold" style="color: #2d7a22; font-size: 1.05rem;">Rp. {{ number_format($product->harga, 0, ',', '.') }}</span><span class="text-secondary" style="font-size: 0.8rem;">/{{ $product->satuan }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Belum ada produk.</p>
            </div>
            @endforelse
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="cta-section text-center p-5 shadow">
                <h2 class="fw-bolder mb-3">Dukung Petani Lokal & Hidup Lebih Sehat 🌱</h2>
                <p class="mb-4">Setiap pembelian membantu kesejahteraan KWT dan menghadirkan makanan sehat ke keluarga Anda.</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="#produk" class="btn-green">Belanja Sekarang</a>
                    <a href="{{ route('about') }}" class="btn btn-outline-success px-4 py-2 fw-bold" style="border-radius: 12px; border-width: 2px;">Pelajari Lebih Lanjut</a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection