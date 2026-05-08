<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'KWT Cibiru')</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    @stack('styles')

    <style>
        * { box-sizing: border-box; }
        a { text-decoration: none; }

        body {
            background: #f5f6f7;
            font-family: 'Segoe UI', sans-serif;
            color: #334155;
        }

        .navbar-efood {
            background: #ffffff;
            border-bottom: 1px solid #e8f5e2;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 0;
            gap: 16px;
        }

        .brand-logo-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #1a1a1a;
            font-weight: 700;
            font-size: 20px;
        }

        .brand-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            overflow: hidden;
            background: #f0f9eb;
            border: 2px solid #dcefd5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .brand-title {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .brand-title strong {
            color: #2d7a22;
            font-size: 20px;
        }

        .brand-title small {
            color: #64748b;
            font-size: 11px;
        }

        .nav-link-efood {
            color: #555;
            font-weight: 600;
            font-size: 14px;
            padding: 10px 15px;
            border-radius: 10px;
            transition: .2s;
        }

        .nav-link-efood:hover,
        .nav-link-efood.active {
            color: #2d7a22;
            background: #f0f9eb;
        }

        .btn-cart {
            background: white;
            color: #2d7a22;
            border: 1px solid #dfeedd;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .btn-masuk {
            padding: 9px 18px;
            border-radius: 10px;
            border: 1px solid #d8e6d5;
            background: white;
            color: #444;
            font-weight: 600;
        }

        .btn-daftar {
            padding: 9px 18px;
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, #2d7a22, #43a047);
            color: white;
            font-weight: 600;
        }
    </style>
</head>

<body>

<nav class="navbar-efood">
    <div class="container">
        <div class="navbar-inner">

            <a href="{{ url('/') }}" class="brand-logo-wrap">
                <div class="brand-icon">
                    <img src="https://cdn-icons-png.flaticon.com/512/2909/2909762.png">
                </div>
                <div class="brand-title">
                    <strong>KWT Cibiru</strong>
                    <small>Kelompok Wanita Tani Digital</small>
                </div>
            </a>

            <ul class="nav d-none d-lg-flex gap-1">
                <li><a class="nav-link-efood {{ request()->is('/') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a></li>
                <li><a class="nav-link-efood {{ request()->is('katalog*') ? 'active' : '' }}" href="{{ route('customer.katalog') }}">Katalog</a></li>
                <li><a class="nav-link-efood {{ request()->is('riwayat-pesanan*') ? 'active' : '' }}" href="{{ route('orders.history') }}">Riwayat</a></li>
                <li><a class="nav-link-efood {{ request()->is('tentang-kami') ? 'active' : '' }}" href="{{ route('about') }}">Tentang Kami</a></li>
            </ul>

            <div class="d-flex align-items-center gap-2">

                {{-- CART FIX --}}
                <a href="{{ route('cart.index') }}" class="btn-cart">
                    <i class="bi bi-cart3"></i>

                    @php
                        $cartTotal = auth()->check()
                            ? auth()->user()->carts()->sum('jumlah')
                            : 0;
                    @endphp

                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $cartTotal > 0 ? '' : 'd-none' }}">
                        {{ $cartTotal }}
                    </span>
                </a>

                @auth

                {{-- 🔥 DROPDOWN FIX (INI YANG KAMU TANYA) --}}
                <div class="dropdown">
                    <button class="btn-daftar dropdown-toggle" data-bs-toggle="dropdown">
                        {{ Auth::user()->name }}
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                Profile
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger" type="submit">
                                    Logout
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>