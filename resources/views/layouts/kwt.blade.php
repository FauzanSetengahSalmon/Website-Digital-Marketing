<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard KWT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { overflow-x: hidden; background: #f4f7f6; }
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #14452f; color: white; transition: all 0.3s; z-index: 1000; }
        .nav-link { color: #aec6ba; padding: 12px 20px; margin: 4px 15px; border-radius: 10px; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background: #226b4a; color: white; }
        .main-content { margin-left: 260px; min-height: 100vh; padding: 20px; }
        @media (max-width: 768px) { .sidebar { margin-left: -260px; } .main-content { margin-left: 0; } }
    </style>
</head>
<body>
    {{-- SIDEBAR --}}
    <div class="sidebar">
        <div class="p-4 text-center">
            <h4 class="fw-bold m-0">KWT DIGITAL</h4>
            <small class="opacity-50">Panel Pengelola</small>
        </div>
        <nav class="mt-4">
            <a href="{{ route('kwt.dashboard') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('kwt.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill me-3"></i> Dashboard
            </a>
            <a href="{{ route('kwt.products') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('kwt.products') ? 'active' : '' }}">
                <i class="bi bi-box-seam-fill me-3"></i> Produk Saya
            </a>
            <a href="{{ route('kwt.orders') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('kwt.orders') ? 'active' : '' }}">
                <i class="bi bi-cart-check-fill me-3"></i> Pesanan
            </a>
        </nav>
        <div class="position-absolute bottom-0 w-100 p-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-light border-0 w-100 d-flex align-items-center justify-content-center">
                    <i class="bi bi-box-arrow-left me-2"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>