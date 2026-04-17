<nav class="navbar-efood">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between w-100">

            <a href="{{ url('/') }}" class="navbar-brand-efood">
                <svg class="brand-icon" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="20" r="19" fill="#d6f0c2" stroke="#2d7a22" stroke-width="1.5" />
                    <path d="M14 28c0-5 3-10 8-12" stroke="#2d7a22" stroke-width="2" stroke-linecap="round" />
                    <path d="M22 16c2-3 5-4 7-3-1 3-4 5-7 3z" fill="#2d7a22" />
                    <path d="M18 20c-2-3-5-3-7-2 1 3 4 5 7 2z" fill="#4caf50" />
                    <circle cx="25" cy="13" r="2" fill="#2d7a22" opacity="0.6" />
                </svg>
                <span class="brand-name">E<span>Food</span></span>
            </a>

            <ul class="nav d-none d-lg-flex align-items-center gap-1 mb-0">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link-efood {{ request()->is('/') ? 'active' : '' }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link-efood {{ request()->routeIs('katalog.*') ? 'active' : '' }}">Katalog</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link-efood {{ request()->routeIs('riwayat.*') ? 'active' : '' }}">Riwayat</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('about') }}"
                        class="nav-link-efood {{ request()->routeIs('about') ? 'active' : '' }}">
                        Tentang Kami
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <button class="btn-cart">
                    <i class="bi bi-cart3"></i>
                    @if(isset($cartCount) && $cartCount > 0)
                    <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </button>

                @auth
                <div class="dropdown">
                    <button class="btn-daftar dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profil') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                        <li><a class="dropdown-item" href="{{ route('riwayat.index') }}"><i class="bi bi-clock-history me-2"></i>Riwayat</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="#" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @else
                <a href="#" class="btn-daftar">Daftar/Masuk</a>
                @endauth

                <button class="btn-cart d-lg-none" data-bs-toggle="collapse" data-bs-target="#mobileMenu">
                    <i class="bi bi-list fs-5"></i>
                </button>
            </div>
        </div>

        <div class="collapse d-lg-none" id="mobileMenu">
            <ul class="nav flex-column py-3 gap-1">
                <li><a href="{{ url('/') }}" class="nav-link-efood">Beranda</a></li>
                <li><a href="#" class="nav-link-efood">Katalog</a></li>
                <li><a href="#" class="nav-link-efood">Riwayat</a></li>
                <li><a href="#" class="nav-link-efood">Tentang Kami</a></li>
            </ul>
        </div>
    </div>
</nav>