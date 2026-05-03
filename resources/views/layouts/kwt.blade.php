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

        /* Sidebar - Ukuran lebih ramping */
        .sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            background: #064e3b;
            /* Hijau Emerald Gelap */
            transition: all 0.3s;
            z-index: 1000;
        }

        /* Profil Section - Lebih Minimalis */
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .profile-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar-circle {
            width: 38px;
            height: 38px;
            background: #10b981;
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .profile-info h6 {
            font-size: 0.85rem;
            margin: 0;
            color: white;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 140px;
        }

        /* Navigasi - Font Size Dikecilkan */
        .nav-list {
            padding: 15px 0;
        }

        .nav-link {
            color: #94a3b8;
            padding: 10px 20px;
            margin: 2px 12px;
            border-radius: 8px;
            transition: 0.2s;
            font-size: 0.88rem;
            /* Ukuran font lebih proporsional */
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
            background: #10b981;
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }

        /* Label Section */
        .nav-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #4ade80;
            margin: 15px 25px 8px;
            font-weight: 700;
            opacity: 0.7;
        }

        /* Main Content */
        .main-content {
            margin-left: 240px;
            padding: 24px;
        }

        /* Logout Button - Lebih Simpel */
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

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -240px;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar.show {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    {{-- SIDEBAR --}}
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="profile-box">
                <div class="avatar-circle">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="profile-info">
                    <h6>{{ Auth::user()->name }}</h6>
                    <small style="color: #4ade80; font-size: 0.7rem;">Admin KWT</small>
                </div>
            </div>
        </div>

        <div class="nav-list">
            <div class="nav-label">Menu Utama</div>
            <a href="{{ route('kwt.dashboard') }}" class="nav-link {{ request()->routeIs('kwt.dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door"></i> Beranda
            </a>

            <div class="nav-label">Manajemen</div>
            <a href="{{ route('kwt.products') }}" class="nav-link {{ request()->routeIs('kwt.products') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Produk Kita
            </a>

            <a href="{{ route('kwt.orders') }}" class="nav-link {{ request()->routeIs('kwt.orders') ? 'active' : '' }}">
                <i class="bi bi-cart3"></i> Pesanan
            </a>

            <a href="#" class="nav-link {{ request()->routeIs('kwt.transactions') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Laporan Transaksi
            </a>

            <div class="nav-label">Akun</div>
            <a href="{{ route('kwt.profile') }}" class="nav-link {{ request()->routeIs('kwt.profile') ? 'active' : '' }}">
                <i class="bi bi-person-gear"></i> Profil KWT
            </a>
        </div>

        <div class="position-absolute bottom-0 w-100 p-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout fw-semibold">
                    <i class="bi bi-power me-2"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="main-content">
        <!-- Top Mobile Nav -->
        <div class="d-md-none d-flex justify-content-between align-items-center mb-3 bg-white p-3 rounded-3 shadow-sm">
            <span class="fw-bold text-success">KWT Digital</span>
            <button class="btn btn-sm btn-success" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
        </div>

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>
</body>

</html>