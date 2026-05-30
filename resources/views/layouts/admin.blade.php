<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Dashboard') - {{ Auth::user()->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f3f5;
            color: #334155;
            overflow-x: hidden;
        }

        /* SIDEBAR: Kontainer Flexbox setinggi layar */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #064e3b;
            z-index: 1000;
            transition: all 0.3s;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar-wrapper {
            flex-grow: 1;
            overflow-y: auto;
            scrollbar-width: none;
        }

        .sidebar-wrapper::-webkit-scrollbar {
            display: none;
        }

        .sidebar-header {
            padding: 30px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* PROFILE BOX */
        .profile-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 12px;
        }

        .avatar-circle {
            width: 65px;
            height: 65px;
            background: #10b981;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
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
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* NAV LIST */
        .nav-list {
            padding: 20px 0;
        }

        .nav-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #4ade80;
            margin: 20px 25px 10px;
            font-weight: 800;
            opacity: 0.7;
        }

        .nav-link {
            color: #94a3b8;
            padding: 12px 20px;
            margin: 4px 15px;
            border-radius: 10px;
            transition: 0.2s ease;
            font-size: 0.88rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .nav-link i {
            font-size: 1.2rem;
            margin-right: 12px;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #ecfdf5;
            transform: translateX(5px);
        }

        .nav-link.active {
            background: #ffffff !important;
            color: #064e3b !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            font-weight: 700;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 260px;
            padding: 30px;
            min-height: 100vh;
        }

        /* LOGOUT SECTION */
        .logout-section {
            width: 100%;
            padding: 20px;
            background: #054131;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            flex-shrink: 0;
        }

        .btn-logout {
            width: 100%;
            background: rgba(248, 113, 113, 0.1);
            border: 1px solid rgba(248, 113, 113, 0.2);
            color: #f87171;
            padding: 10px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: 0.3s;
        }

        .btn-logout:hover {
            background: #991b1b;
            color: white;
            border-color: transparent;
        }

        /* MOBILE RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                left: -260px;
                position: fixed;
                height: 100vh;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .mobile-header {
                display: flex !important;
            }
        }

        .mobile-header {
            display: none;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 15px 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
    </style>
    @stack('styles')
</head>

<body>

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-wrapper">
            <div class="sidebar-header">
                <div class="profile-box">
                    <div class="avatar-circle">
                        @if(Auth::user()->profile_photo)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Admin">
                        @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="profile-info">
                        <h6>{{ Auth::user()->name }}</h6>
                        <small>Super Admin</small>
                    </div>
                </div>
            </div>

            <nav class="nav-list">
                <div class="nav-label">Menu Utama</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>

                <div class="nav-label">Manajemen Data</div>
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Data Semua User
                </a>
                <a href="{{ route('admin.kwt') }}" class="nav-link {{ request()->routeIs('admin.kwt') ? 'active' : '' }}">
                    <i class="bi bi-shop-window"></i> Kelola Akun KWT
                </a>
                <a href="{{ route('admin.kurir.index') }}"
                    class="nav-link {{ request()->routeIs('admin.kurir.*') && !request()->routeIs('admin.kurir.pencairan*') && !request()->routeIs('admin.kurir.laporan') ? 'active' : '' }}">
                    <i class="bi bi-bicycle"></i> Data Kurir
                </a>
                <div class="nav-label">Laporan Global</div>
                <a href="{{ route('admin.sales.index') }}" class="nav-link {{ request()->routeIs('admin.sales*') || request()->routeIs('admin.kwt.laporan') ? 'active' : '' }}">
                    <i class="bi bi-cart-check-fill"></i> Penjualan Semua
                </a>
                <a href="{{ route('admin.kurir.pencairan') }}"
                    class="nav-link {{ request()->routeIs('admin.kurir.pencairan*') || request()->routeIs('admin.kurir.laporan') ? 'active' : '' }}">
                    <i class="bi bi-wallet2"></i> Pencairan Kurir
                </a>
                <div class="nav-label">Sistem</div>
                <a href="{{ route('admin.profile') }}" class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i> Profile Admin
                </a>
            </nav>
        </div>
        <div class="logout-section">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-power me-2"></i> Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="main-content">
        <div class="mobile-header">
            <span class="fw-bold text-success">Admin KWT</span>
            <button class="btn btn-success btn-sm" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
        </div>

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>
    @stack('scripts')

</body>

</html>