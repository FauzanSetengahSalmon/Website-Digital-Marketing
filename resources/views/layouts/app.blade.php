<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'EFood')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    @stack('styles')

    <style>
        * {
            box-sizing: border-box;
        }

        a {
            text-decoration: none;
        }

        body {
            background: #f5f6f7;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar-efood {
            background: #ffffff;
            border-bottom: 1px solid #e8f5e2;
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            gap: 16px;
        }

        /* Brand / Logo */
        .brand-logo-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #1a1a1a;
            font-weight: 700;
            font-size: 20px;
            letter-spacing: -0.3px;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: #2d7a22;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-logo-wrap span {
            color: #2d7a22;
        }

        .nav-link-efood {
            color: #555;
            font-weight: 500;
            font-size: 15px;
            padding: 8px 14px;
            border-radius: 8px;
            transition: all 0.15s;
        }

        .nav-link-efood:hover,
        .nav-link-efood.active {
            color: #2d7a22;
            background-color: #f0f9eb;
        }

        /* Cart button */
        .btn-cart {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 1.5px solid #ddd;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #444;
            font-size: 17px;
            transition: all 0.15s;
            position: relative;
            cursor: pointer;
        }

        .btn-cart:hover {
            border-color: #2d7a22;
            color: #2d7a22;
            background: #f0f9eb;
        }

        /* Auth buttons */
        .btn-masuk {
            padding: 8px 18px;
            border-radius: 8px;
            border: 1.5px solid #ddd;
            background: #fff;
            font-size: 14px;
            font-weight: 500;
            color: #444;
            transition: all 0.15s;
        }

        .btn-masuk:hover {
            border-color: #2d7a22;
            color: #2d7a22;
        }

        .btn-daftar {
            padding: 8px 18px;
            border-radius: 8px;
            border: none;
            background: #2d7a22;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            transition: background 0.15s;
            cursor: pointer;
        }

        .btn-daftar:hover {
            background: #1b5e20;
            color: #fff;
        }

        /* Dropdown user */
        .dropdown .btn-daftar {
            cursor: pointer;
        }

        /* ===== FOOTER ===== */
        footer {
            margin-top: 60px;
            background: #f0f9eb;
            border-top: 1px solid #c8e6b4;
        }

        .footer-top {
            padding: 50px 0 40px;
        }

        /* Footer brand */
        .footer-brand-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #1a1a1a;
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 12px;
        }

        .footer-brand-icon {
            width: 36px;
            height: 36px;
            background: #2d7a22;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .footer-brand-wrap span {
            color: #2d7a22;
        }

        .footer-tagline {
            font-size: 13px;
            color: #555;
            line-height: 1.65;
            margin-top: 10px;
            margin-bottom: 0;
        }

        /* Footer social */
        .footer-social {
            margin-top: 14px;
            display: flex;
            gap: 8px;
        }

        .footer-social a {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: #fff;
            border: 1.5px solid #c8e6b4;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2d7a22;
            font-size: 15px;
            transition: all 0.15s;
        }

        .footer-social a:hover {
            background: #2d7a22;
            color: #fff;
            border-color: #2d7a22;
        }

        /* Footer headings & links */
        .footer-heading {
            font-size: 12px;
            font-weight: 700;
            color: #1b5e20;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            margin-bottom: 14px;
        }

        .footer-link {
            display: block;
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
            transition: color 0.15s;
        }

        .footer-link:hover {
            color: #2d7a22;
        }

        /* Subscribe form */
        .footer-subscribe-input {
            width: 100%;
            padding: 9px 12px;
            border-radius: 8px;
            border: 1.5px solid #c8e6b4;
            background: #fff;
            font-size: 13px;
            color: #333;
            outline: none;
            margin-bottom: 8px;
            transition: border 0.15s;
        }

        .footer-subscribe-input:focus {
            border-color: #2d7a22;
        }

        .btn-subscribe {
            width: 100%;
            background: #2d7a22;
            border: none;
            padding: 9px;
            color: white;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-subscribe:hover {
            background: #1b5e20;
        }

        .footer-spam-note {
            font-size: 12px;
            color: #888;
            margin-top: 8px;
            margin-bottom: 0;
        }

        .footer-spam-note a {
            color: #2d7a22;
        }

        /* Footer bottom */
        .footer-bottom {
            border-top: 1px solid #c8e6b4;
            padding: 16px 0;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>

<body>

    {{-- ================= NAVBAR ================= --}}
    <nav class="navbar-efood">
        <div class="container">
            <div class="navbar-inner">

                {{-- Logo & Brand --}}
                <a href="{{ url('/') }}" class="brand-logo-wrap">
                    <div class="brand-icon">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                            <path d="M4 18c0-6 4-11 9-13" stroke="white" stroke-width="1.8" stroke-linecap="round" />
                            <path d="M13 5c2-3 6-4 8-2-1 3-5 5-8 2z" fill="white" opacity="0.9" />
                            <path d="M9 10c-2-3-5-3-7-1 1 3 5 5 7 1z" fill="white" opacity="0.7" />
                            <circle cx="16" cy="3" r="1.8" fill="white" opacity="0.55" />
                        </svg>
                    </div>
                    E<span>Food</span>
                </a>

                <ul class="nav d-none d-lg-flex gap-1">
                    <li>
                        <a class="nav-link-efood {{ request()->is('/') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li>
                        <a class="nav-link-efood {{ request()->is('katalog*') ? 'active' : '' }}" href="#">Katalog</a>
                    </li>
                    <li>
                        <a class="nav-link-efood {{ request()->is('riwayat-pesanan*') ? 'active' : '' }}" href="{{ route('orders.history') }}">Riwayat</a>
                    </li>
                    <li>
                        <a class="nav-link-efood {{ request()->is('tentang-kami') ? 'active' : '' }}" href="{{ route('about') }}">Tentang Kami</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <button class="btn-cart">
                        <i class="bi bi-cart3"></i>
                    </button>

                    @auth
                    <div class="dropdown">
                        <button class="btn-daftar dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person me-2 text-success"></i> Profil Saya
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="btn-masuk">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-daftar">Daftar</a>
                    @endauth
                </div>

            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="footer-top">
            <div class="container">
                <div class="row g-5">

                    <div class="col-12 col-md-3">
                        <div class="footer-brand-wrap">
                            <div class="footer-brand-icon">
                                <svg width="20" height="20" viewBox="0 0 22 22" fill="none">
                                    <path d="M4 18c0-6 4-11 9-13" stroke="white" stroke-width="1.8" stroke-linecap="round" />
                                    <path d="M13 5c2-3 6-4 8-2-1 3-5 5-8 2z" fill="white" opacity="0.9" />
                                    <path d="M9 10c-2-3-5-3-7-1 1 3 5 5 7 1z" fill="white" opacity="0.7" />
                                    <circle cx="16" cy="3" r="1.8" fill="white" opacity="0.55" />
                                </svg>
                            </div>
                            E<span>Food</span>
                        </div>
                        <p class="footer-tagline">
                            Lebih Cepat, Lebih Mudah, Lebih Nikmat –<br>
                            Makanan Pilihan Kini Sampai di Rumahmu!
                        </p>
                        <div class="footer-social">
                            <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" title="Instagram"><i class="bi bi-instagram"></i></a>
                            <a href="#" title="TikTok"><i class="bi bi-tiktok"></i></a>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <p class="footer-heading">Laporkan Bug</p>
                        <input type="email" class="footer-subscribe-input" placeholder="email@gmail.com">
                        <button class="btn-subscribe">Kirim Laporan</button>
                        <p class="footer-spam-note">
                            Kami tidak akan melakukan spam. Baca <a href="#">kebijakan email kami</a>.
                        </p>
                    </div>

                    <div class="col-6 col-md-2 offset-md-1">
                        <p class="footer-heading">Tentang Aplikasi</p>
                        <a class="footer-link {{ request()->is('tentang-kami') ? 'active' : '' }}" href="{{ route('about') }}">Tentang Kami</a>
                    </div>

                    <div class="col-6 col-md-2">
                        <p class="footer-heading">Aksi Cepat</p>
                        <a href="#" class="footer-link">Katalog Produk</a>
                        <a class="footer-link {{ request()->is('tentang-kami') ? 'active' : '' }}" href="{{ route('about') }}">Tentang Kami</a>

                        @guest
                        <a href="{{ route('register') }}" class="footer-link">Buat Akun</a>
                        <a href="#" class="footer-link">Belanja Produk</a>
                        @endguest

                        @auth
                        <a href="#" class="footer-link">Keranjang</a>
                        <a class="footer-link {{ request()->is('riwayat-pesanan*') ? 'active' : '' }}" href="{{ route('orders.history') }}">Riwayat</a>
                        @endauth
                    </div>

                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>© {{ date('Y') }} KWT — Kelompok Wanita Tani Desa Cibiru</span>
                <span style="color:#2d7a22; font-size:13px;">Dibuat dengan ♥ untuk masyarakat lokal</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>

</html>