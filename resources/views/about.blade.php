@extends('layouts.app')

@section('title','Tentang Kami - EFood')

@push('styles')
<style>

:root {
    --green-dark: #2d7a22;
    --green-primary: #4caf50;
    --green-light: #d6f0c2;
}

/* HERO */
.about-hero{
    margin:30px 0 70px;
    border-radius:20px;
    overflow:hidden;
    min-height:420px;
    display:flex;
    align-items:center;
    position: relative;
    background: url('{{ asset("image/Screenshot 2026-04-16 233530.png") }}') center/cover;
}

.about-hero::before{
    content:"";
    position:absolute;
    inset:0;
    background: rgba(0,0,0,0.35); /* transparansi smooth */
}

.about-wrapper{
    position: relative;
    z-index: 2;
    display:grid;
    grid-template-columns:420px 1fr;
    gap:50px;
    padding:60px;
    align-items:center;
}

.about-img{
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 20px 40px rgba(0,0,0,.25);
}
.about-img img{
    width:100%;
    height:260px;
    object-fit:cover;
}

.about-title{
    color:white;
    font-size:2.6rem;
    font-weight:800;
}
.about-sub{
    color:#f1f1f1;
    font-size:1.5rem;
    font-weight:700;
}
.about-desc{
    color:#eaeaea;
    max-width:520px;
    margin-top:10px;
    line-height:1.7;
}

/* SECTION */
.section-title{
    font-size:2rem;
    font-weight:800;
    text-align:center;
    color: var(--green-dark);
}
.section-sub{
    text-align:center;
    color:#777;
    margin-bottom:50px;
}

/* KATEGORI (UPGRADE CARD) */
.kategori-card{
    text-align:center;
    padding:15px;
    border-radius:16px;
    background:white;
    transition:.25s;
}
.kategori-card:hover{
    transform: translateY(-6px);
    box-shadow:0 15px 30px rgba(0,0,0,.1);
}

.kategori img{
    width:100%;
    height:150px;
    object-fit:cover;
    border-radius:12px;
}
.kategori-title{
    margin-top:12px;
    font-weight:700;
    font-size:1rem;
    color: var(--green-dark);
}
.kategori-desc{
    font-size:.82rem;
    color:#777;
    margin-top:4px;
}

.why-section{
    margin:60px 0;
    padding:50px 0;
    background: linear-gradient(135deg, #d6f0c2, #ffffff);
}

.why-card{
    text-align:center;
    padding:20px;
    border-radius:16px;
    background:white;
    transition:.25s;
}
.why-card:hover{
    transform: translateY(-5px);
    box-shadow:0 12px 25px rgba(0,0,0,.08);
}
.why-icon{
    font-size:2rem;
    color:var(--green-primary);
    margin-bottom:10px;
}
.why-card h5{
    font-size:1.05rem;
    margin-bottom:6px;
    color: var(--green-dark);
}
.why-card p{
    font-size:.85rem;
    color:#666;
    line-height:1.6;
}

/* GALERI */
.gallery-card{
    text-align:center;
    transition:.25s;
}
.gallery-card:hover{
    transform: scale(1.03);
}
.gallery-card img{
    width:100%;
    height:130px;
    object-fit:cover;
    border-radius:12px;
    box-shadow:0 8px 18px rgba(0,0,0,.15);
}
.gallery-title{
    font-weight:600;
    font-size:.95rem;
    margin-top:10px;
}
.gallery-desc{
    font-size:.8rem;
    color:#777;
}

</style>
@endpush


@section('content')

<div class="container">

<!-- HERO -->
<section class="about-hero">
    <div class="about-wrapper">

        <div class="about-img">
            <img src="{{ asset('image/Screenshot 2026-04-16 233530.png') }}">
        </div>

        <div>
            <h1 class="about-title">Tentang Kami</h1>
            <h3 class="about-sub">Dari Tangan Perempuan Hebat</h3>

            <p class="about-desc">
                EFood merupakan platform digital yang membantu pemasaran hasil panen 
                <b>Kelompok Wanita Tani (KWT)</b> secara langsung ke konsumen. 
                Kami percaya bahwa pertanian lokal yang dikelola perempuan desa 
                memiliki potensi besar untuk meningkatkan kesejahteraan keluarga 
                sekaligus menjaga keberlanjutan lingkungan.
            </p>
        </div>

    </div>
</section>

<!-- APA YANG KAMI JUAL -->
<h2 class="section-title">Apa yang kami jual</h2>
<p class="section-sub">Hasil panen dan olahan terbaik dari kebun organik</p>

<div class="row g-4 mb-5 kategori">

    <div class="col-md-4">
        <div class="kategori-card">
            <img src="{{ asset('image/Screenshot 2026-04-16 233530.png') }}">
            <div class="kategori-title">Sayuran Organik Segar</div>
            <div class="kategori-desc">Bayam, kangkung, sawi dan sayuran panen harian bebas pestisida.</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="kategori-card">
            <img src="{{ asset('image/Screenshot 2026-04-16 233530.png') }}">
            <div class="kategori-title">Buah Musiman Alami</div>
            <div class="kategori-desc">Dipetik langsung dari kebun saat matang alami dan kaya nutrisi.</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="kategori-card">
            <img src="{{ asset('image/Screenshot 2026-04-16 233530.png') }}">
            <div class="kategori-title">Produk Olahan Rumah</div>
            <div class="kategori-desc">Keripik, sambal, dan olahan sehat buatan ibu-ibu KWT.</div>
        </div>
    </div>

</div>

</div>


<!-- WHY -->
<section class="why-section">
<div class="container">
    <h2 class="section-title">Mengapa Memilih Kami?</h2>
    <p class="section-sub">Panen organik segar dan berkualitas</p>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="why-card">
                <div class="why-icon"><i class="bi bi-flower1"></i></div>
                <h5>Dari Lahan KWT Langsung</h5>
                <p>Produk dipanen langsung dari kebun wanita tani tanpa perantara.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="why-card">
                <div class="why-icon"><i class="bi bi-cash-coin"></i></div>
                <h5>Harga Transparan</h5>
                <p>Harga jujur dari petani ke meja makan tanpa biaya tambahan.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="why-card">
                <div class="why-icon"><i class="bi bi-heart"></i></div>
                <h5>Mendukung Desa</h5>
                <p>Setiap pembelian membantu ekonomi perempuan desa berkembang.</p>
            </div>
        </div>

    </div>
</div>
</section>


<!-- GALERI -->
<div class="container mb-5">
    <h2 class="section-title">Galeri Kegiatan</h2>
    <p class="section-sub">Aktivitas Kelompok Wanita Tani</p>

    <div class="row g-4">

        <div class="col-md-3">
            <div class="gallery-card">
                <img src="{{ asset('image/Screenshot 2026-04-16 233530.png') }}">
                <div class="gallery-title">Menanam Bibit</div>
                <div class="gallery-desc">Proses penanaman sayuran organik di lahan KWT.</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="gallery-card">
                <img src="{{ asset('image/Screenshot 2026-04-16 233530.png') }}">
                <div class="gallery-title">Perawatan Tanaman</div>
                <div class="gallery-desc">Pemupukan alami tanpa bahan kimia berbahaya.</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="gallery-card">
                <img src="{{ asset('image/Screenshot 2026-04-16 233530.png') }}">
                <div class="gallery-title">Panen Bersama</div>
                <div class="gallery-desc">Kegiatan panen sayuran segar setiap minggu.</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="gallery-card">
                <img src="{{ asset('image/Screenshot 2026-04-16 233530.png') }}">
                <div class="gallery-title">Pengemasan Produk</div>
                <div class="gallery-desc">Proses sortir dan pengemasan sebelum dikirim ke pelanggan.</div>
            </div>
        </div>

    </div>
</div>

@endsection