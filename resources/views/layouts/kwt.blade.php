<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Pengelola - {{ Auth::user()->name }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f3f5;
            color: #334155;
        }

        /* === Sidebar Styles === */
        .sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            background: #064e3b;
            transition: transform 0.3s ease-in-out;
            z-index: 1050; /* Di atas elemen lain */
            top: 0;
            left: 0;
            overflow-y: auto; /* Mencegah konten terpotong jika layar pendek */
        }

        .sidebar-header {
            padding: 30px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            text-align: center;
        }

        .profile-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .avatar-circle {
            width: 60px;
            height: 60px;
            background: #10b981;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.5rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .avatar-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info h6 {
            font-size: 0.95rem;
            margin: 0;
            color: white;
            font-weight: 600;
        }

        .profile-info small {
            color: #4ade80;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* === Navigation === */
        .nav-list {
            padding: 15px 0;
            padding-bottom: 80px; /* Space for logout button */
        }

        .nav-link {
            color: #94a3b8;
            padding: 10px 20px;
            margin: 2px 12px;
            border-radius: 8px;
            transition: 0.2s;
            font-size: 0.88rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .nav-link i {
            font-size: 1.1rem;
            margin-right: 12px;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #ecfdf5;
        }

        .nav-link.active {
            background: #ffffff !important;
            color: #064e3b !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            font-weight: 700;
        }

        .nav-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #4ade80;
            margin: 15px 25px 8px;
            font-weight: 700;
            opacity: 0.7;
        }

        /* === Logout Button === */
        .logout-wrapper {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 1rem;
            background: #064e3b; /* Solid background to mask scrolling content */
        }

        .btn-logout {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #f87171;
            font-size: 0.85rem;
            padding: 8px;
            width: 100%;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-logout:hover {
            background: #991b1b;
            color: white;
            border-color: transparent;
        }

        /* === Main Content & Mobile Responsive === */
        .main-content {
            margin-left: 240px;
            padding: 24px;
            transition: margin-left 0.3s ease-in-out;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-overlay.show {
                display: block;
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    {{-- OVERLAY UNTUK MOBILE --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="profile-box">
                <div class="avatar-circle">
                    @if(Auth::user()->profile_photo)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profile">
                    @else
                        {{ substr(Auth::user()->name, 0, 1) }}
                    @endif
                </div>

                <div class="profile-info">
                    <h6>{{ Auth::user()->name }}</h6>
                    <small style="font-size: 10px;">{{ Auth::user()->email }}</small>
                </div>
            </div>
        </div>

        <nav class="nav-list">
            <div class="nav-label">Menu Utama</div>
            <a href="{{ route('kwt.dashboard') }}" class="nav-link {{ request()->routeIs('kwt.dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door"></i> Dashboard
            </a>

            <div class="nav-label">Manajemen</div>
            <a href="{{ route('kwt.products') }}" class="nav-link {{ request()->routeIs('kwt.products') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Produk Kita
            </a>

            <a href="{{ route('kwt.orders') }}" class="nav-link {{ request()->routeIs('kwt.orders*') ? 'active' : '' }}">
                <i class="bi bi-cart3"></i> Pesanan
            </a>

            <a href="{{ route('kwt.reports.index') }}" class="nav-link {{ request()->routeIs('kwt.reports.index') ? 'active' : '' }}">
                <i class="bi bi-shield-exclamation"></i> Komplain Pelanggan
            </a>

            <a href="{{ route('kwt.laporan') }}" class="nav-link {{ request()->routeIs('kwt.laporan') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Laporan Transaksi
            </a>

            <div class="nav-label">Akun</div>
            <a href="{{ route('kwt.profile') }}" class="nav-link {{ request()->routeIs('kwt.profile') ? 'active' : '' }}">
                <i class="bi bi-person-gear"></i> Profil KWT
            </a>
        </nav>

        <div class="logout-wrapper">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout fw-semibold">
                    <i class="bi bi-power me-2"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- CONTENT --}}
    <main class="main-content">
        {{-- Navbar Mobile --}}
        <div class="d-md-none d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-3 shadow-sm">
            <span class="fw-bold text-success">KWT Digital</span>
            <button class="btn btn-sm btn-success" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
                <i class="bi bi-list"></i>
            </button>
        </div>

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
            
            // Mencegah scroll pada body saat sidebar terbuka di mobile
            if (window.innerWidth <= 768) {
                document.body.style.overflow = document.body.style.overflow === 'hidden' ? 'auto' : 'hidden';
            }
        }
    </script>
</body>

</html>