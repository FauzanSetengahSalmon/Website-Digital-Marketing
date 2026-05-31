@extends('layouts.app')

@section('title','Tentang Kami - Tani Cibiru')

@push('styles')
<style>
    :root {
        --green-dark: #2d7a22;
        --green-primary: #4caf50;
        --green-light: #d6f0c2;
    }

    /* HERO */
    .about-hero {
        margin: 30px 0 70px;
        border-radius: 20px;
        overflow: hidden;
        min-height: 420px;
        display: flex;
        align-items: center;
        position: relative;
        background: url('{{ asset("image/Metik kangkung.jpg") }}') center/cover;
    }

    .about-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
    }

    .about-wrapper {
        position: relative;
        z-index: 2;
        display: grid;
        grid-template-columns: 420px 1fr;
        gap: 50px;
        padding: 60px;
        align-items: center;
    }

    .about-img {
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, .25);
    }

    .about-img img {
        width: 100%;
        height: 260px;
        object-fit: cover;
    }

    .about-title {
        color: white;
        font-size: 2.6rem;
        font-weight: 800;
    }

    .about-sub {
        color: #f1f1f1;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .about-desc {
        color: #eaeaea;
        max-width: 550px;
        margin-top: 10px;
        line-height: 1.7;
        font-size: 1.05rem;
    }

    /* SECTION */
    .section-title {
        font-size: 2rem;
        font-weight: 800;
        text-align: center;
        color: var(--green-dark);
    }

    .section-sub {
        text-align: center;
        color: #777;
        margin-bottom: 50px;
    }

    /* JOURNEY CARDS (Pengganti Kategori) */
    .journey-card {
        text-align: center;
        padding: 20px;
        border-radius: 16px;
        background: white;
        transition: .25s;
        height: 100%;
        border: 1px solid #f1f5f9;
    }

    .journey-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, .08);
    }

    .journey-icon {
        width: 70px;
        height: 70px;
        background: var(--green-light);
        color: var(--green-dark);
        font-size: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 15px;
    }

    .journey-title {
        font-weight: 800;
        font-size: 1.15rem;
        color: var(--green-dark);
        margin-bottom: 10px;
    }

    .journey-desc {
        font-size: .9rem;
        color: #666;
        line-height: 1.6;
    }

    /* WHY SECTION */
    .why-section {
        margin: 60px 0;
        padding: 60px 0;
        background: linear-gradient(135deg, #eefbe6, #ffffff);
        border-radius: 30px;
    }

    .why-card {
        text-align: center;
        padding: 30px 20px;
        border-radius: 16px;
        background: white;
        transition: .25s;
        height: 100%;
        border: 1px solid #e5e7eb;
    }

    .why-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, .08);
        border-color: var(--green-light);
    }

    .why-icon-small {
        font-size: 2.5rem;
        color: var(--green-primary);
        margin-bottom: 15px;
    }

    .why-card h5 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--green-dark);
    }

    .why-card p {
        font-size: .9rem;
        color: #666;
        line-height: 1.6;
        margin-bottom: 0;
    }

    /* GALERI */
    .gallery-card {
        text-align: center;
        transition: .25s;
        cursor: pointer;
    }

    .gallery-card:hover {
        transform: translateY(-5px);
    }

    .gallery-card img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 8px 18px rgba(0, 0, 0, .10);
    }

    .gallery-title {
        font-weight: 700;
        font-size: 1rem;
        margin-top: 12px;
        color: var(--green-dark);
    }

    .gallery-desc {
        font-size: .85rem;
        color: #777;
        margin-top: 4px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .about-wrapper {
            grid-template-columns: 1fr;
            padding: 40px 20px;
            text-align: center;
        }

        .about-desc {
            margin: 15px auto 0;
        }
    }
</style>
@endpush


@section('content')

<div class="container mb-5">

    <!-- HERO -->
    <section class="about-hero mt-4">
        <div class="about-wrapper">

            <div class="about-img">
                <img src="{{ asset('image/Metik kangkung.jpg') }}" alt="Kegiatan KWT">
            </div>

            <div>
                <h1 class="about-title">Tentang Tani Cibiru</h1>
                <h3 class="about-sub">Sayuran Sehat dari Tangan Ibu</h3>

                <p class="about-desc">
                    Tani Cibiru adalah platform yang menghubungkan Anda dengan sayuran segar hasil panen
                    <b>Kelompok Wanita Tani (KWT)</b>. Kami mendistribusikan sayuran yang ditanam dengan cinta,
                    dirawat tanpa bahan kimia berbahaya, dan dipanen langsung dari pekarangan desa untuk
                    menjaga kesehatan keluarga Anda.
                </p>
            </div>

        </div>
    </section>
    <!-- PERJALANAN SAYURAN -->
    <h2 class="section-title mt-5">Perjalanan Sayuran Kami</h2>
    <p class="section-sub">Dari tanah pekarangan desa, langsung ke meja makan keluarga Anda</p>

    <div class="row g-4 mb-5">

        <div class="col-md-4">
            <div class="journey-card shadow-sm">
                {{-- Ikon matahari (alam terbuka) --}}
                <div class="journey-icon"><i class="bi bi-sun"></i></div>
                <div class="journey-title">1. Ditanam Sepenuh Hati</div>
                <div class="journey-desc">Bukan dari lahan industri besar, tapi murni dari kebun pekarangan desa. Benih sayur pilihan ditanam dan dirawat langsung oleh tangan terampil ibu-ibu KWT.</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="journey-card shadow-sm">
                {{-- Ikon air --}}
                <div class="journey-icon"><i class="bi bi-droplet"></i></div>
                <div class="journey-title">2. Dirawat Tanpa Kimia</div>
                <div class="journey-desc">Kami sama sekali tidak menggunakan pestisida beracun. Tanaman hanya diberi pupuk kompos dan organik supaya sayurannya benar-benar aman dan sehat untuk dikonsumsi.</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="journey-card shadow-sm">
                {{-- Ikon keranjang panen --}}
                <div class="journey-icon"><i class="bi bi-basket"></i></div>
                <div class="journey-title">3. Petik & Langsung Antar</div>
                <div class="journey-desc">Sayur baru akan dicabut dari tanah begitu ada pesanan masuk. Jadi, sayur yang sampai ke dapur Anda dijamin masih renyah, segar, dan tidak layu di jalan.</div>
            </div>
        </div>
    </div>
</div>

<!-- WHY CHOOSE US -->
<section class="why-section">
    <div class="container">
        <h2 class="section-title">Mengapa Memilih Sayuran Kami?</h2>
        <p class="section-sub">Lebih segar, lebih sehat, dan membawa dampak positif</p>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="why-card">
                    <div class="why-icon-small"><i class="bi bi-sun"></i></div>
                    <h5>Panen Harian (Fresh)</h5>
                    <p>Bukan sayuran yang berhari-hari layu di pasar. Sayuran kami dipetik di hari yang sama untuk menjaga tekstur renyah dan vitaminnya.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="why-card">
                    <div class="why-icon-small"><i class="bi bi-tags"></i></div>
                    <h5>Harga Petani Langsung</h5>
                    <p>Karena Anda membeli langsung dari lahan KWT tanpa lewat tengkulak, harganya jauh lebih terjangkau dengan kualitas premium.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="why-card">
                    <div class="why-icon-small"><i class="bi bi-heart-fill"></i></div>
                    <h5>Bantu Ekonomi Desa</h5>
                    <p>Setiap ikat sayur yang Anda beli menjadi penyemangat dan sumber penghasilan mandiri bagi ibu-ibu penggerak pertanian desa.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- GALERI (Fokus ke Sayuran) -->
<div class="container my-5 pt-4">
    <h2 class="section-title">Dari Kebun Kita</h2>
    <p class="section-sub">Melihat langsung aktivitas ibu-ibu KWT merawat sayuran Anda</p>

    <div class="row g-4">

        <div class="col-md-3 col-6">
            <div class="gallery-card">
                <img src="{{ asset('image/Bibit.jpg') }}" alt="Menyemai Benih">
                <div class="gallery-title">Menyemai Benih</div>
                <div class="gallery-desc">Proses awal menanam benih sayuran hijau.</div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="gallery-card">
                <img src="{{ asset('image/Bergotong royomg.jpg') }}" alt="Menyiram Sayuran">
                <div class="gallery-title">Perawatan Rutin</div>
                <div class="gallery-desc">Penyiraman pagi hari agar sayur tumbuh subur.</div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="gallery-card">
                <img src="{{ asset('image/Selada.jpg') }}" alt="Memetik Sayur">
                <div class="gallery-title">Waktu Panen</div>
                <div class="gallery-desc">Ibu-ibu KWT memetik sayuran dengan hati-hati.</div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="gallery-card">
                <img src="{{ asset('image/Metik.jpg') }}" alt="Sayur Siap Kirim">
                <div class="gallery-title">Sortir & Kemas</div>
                <div class="gallery-desc">Membersihkan sayur akar sebelum diantar ke Anda.</div>
            </div>
        </div>

    </div>
</div>

@endsection