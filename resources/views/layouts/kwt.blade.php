<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Panel Pengelola - {{ Auth::user()->name }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        :root{
            --primary:#2f8f2f;
            --primary-dark:#256d1f;
            --white:#ffffff;
            --text:#1e293b;
            --text-soft:#64748b;
            --bg:#f3f7f2;
        }

        body{
            font-family:'Inter',sans-serif;
            background:var(--bg);
            color:var(--text);
            overflow-x:hidden;
        }

        /* ================= SIDEBAR ================= */

        .sidebar{
            width:280px;
            height:100vh;
            position:fixed;
            top:0;
            left:0;
            z-index:100;

            background:
                linear-gradient(
                    180deg,
                    #36a336 0%,
                    #2f8f2f 40%,
                    #256d1f 100%
                );

            overflow-y:auto;

            box-shadow:
                10px 0 35px rgba(0,0,0,0.08);

            transition:.35s ease;
        }

        .sidebar::-webkit-scrollbar{
            width:5px;
        }

        .sidebar::-webkit-scrollbar-thumb{
            background:rgba(255,255,255,.15);
            border-radius:20px;
        }

        /* ================= HEADER ================= */

        .sidebar-header{
            padding:30px 24px 24px;
            border-bottom:1px solid rgba(255,255,255,0.08);
        }

        .brand-box{
            display:flex;
            align-items:center;
            gap:14px;
        }

        .avatar-circle{
            width:60px;
            height:60px;
            border-radius:20px;

            background:
                rgba(255,255,255,0.18);

            backdrop-filter:blur(10px);

            display:flex;
            align-items:center;
            justify-content:center;

            color:white;
            font-size:1.3rem;
            font-weight:800;

            box-shadow:
                inset 0 1px 2px rgba(255,255,255,0.2),
                0 6px 18px rgba(0,0,0,0.15);
        }

        .profile-info h6{
            margin:0;
            color:white;
            font-size:1.05rem;
            font-weight:700;
        }

        .profile-info small{
            color:rgba(255,255,255,.75);
            font-size:.78rem;
        }

        /* ================= NAVIGATION ================= */

        .nav-list{
            padding:22px 16px 120px;
        }

        .nav-label{
            color:rgba(255,255,255,.45);
            font-size:.72rem;
            font-weight:700;
            text-transform:uppercase;
            letter-spacing:1px;

            margin:18px 14px 14px;
        }

        .nav-link{
            position:relative;

            display:flex;
            align-items:center;
            gap:14px;

            padding:15px 18px;
            margin-bottom:10px;

            border-radius:18px;

            text-decoration:none;
            color:rgba(255,255,255,.9);

            font-size:.95rem;
            font-weight:600;

            transition:.25s ease;

            overflow:hidden;
        }

        .nav-link i{
            font-size:1.1rem;
            min-width:22px;
        }

        /* hover effect */

        .nav-link::before{
            content:'';
            position:absolute;
            inset:0;

            background:
                rgba(255,255,255,.08);

            opacity:0;
            transition:.25s;
        }

        .nav-link:hover::before{
            opacity:1;
        }

        .nav-link:hover{
            transform:translateX(5px);
            color:white;
        }

        /* active */

        .nav-link.active{
            background:white;
            color:var(--primary-dark) !important;

            box-shadow:
                0 8px 24px rgba(0,0,0,.12);
        }

        .nav-link.active i{
            color:var(--primary);
        }

        .nav-link.active::after{
            content:'';
            position:absolute;
            right:14px;

            width:8px;
            height:8px;
            border-radius:50%;

            background:var(--primary);
        }

        /* ================= FOOTER ================= */

        .sidebar-footer{
            position:absolute;
            bottom:0;
            width:100%;

            padding:18px;

            background:
                linear-gradient(
                    to top,
                    rgba(0,0,0,.12),
                    transparent
                );
        }

        .btn-logout{
            width:100%;
            border:none;

            background:
                rgba(255,255,255,.12);

            color:white;

            padding:14px;
            border-radius:18px;

            font-weight:600;

            transition:.25s ease;
        }

        .btn-logout:hover{
            background:#dc3545;
            transform:translateY(-2px);
        }

        /* ================= MAIN CONTENT ================= */

        .main-content{
            margin-left:280px;
            min-height:100vh;
            padding:36px;

            position:relative;
        }

        /* top mobile */

        .mobile-topbar{
            display:none;
        }

        /* ================= CARD DESIGN ================= */

        .content-card{
            background:white;
            border-radius:26px;
            padding:26px;

            box-shadow:
                0 10px 30px rgba(0,0,0,.05);
        }

        /* ================= MODAL FIX ================= */

        .modal-backdrop{
            z-index:1040 !important;
        }

        .modal{
            z-index:1055 !important;
        }

        .modal-content{
            border:none;
            border-radius:24px;
        }

        /* ================= MOBILE ================= */

        @media(max-width:768px){

            .sidebar{
                margin-left:-280px;
            }

            .sidebar.show{
                margin-left:0;
            }

            .main-content{
                margin-left:0;
                padding:20px;
            }

            .mobile-topbar{
                display:flex;
                align-items:center;
                justify-content:space-between;

                margin-bottom:20px;
            }

            .mobile-title{
                font-size:1.1rem;
                font-weight:700;
            }

            .menu-btn{
                width:45px;
                height:45px;
                border:none;
                border-radius:14px;

                background:var(--primary);
                color:white;

                font-size:1.2rem;
            }
        }

    </style>
</head>

<body>

<!-- ================= SIDEBAR ================= -->

<div class="sidebar" id="sidebar">

    <div class="sidebar-header">

        <div class="brand-box">

            <div class="avatar-circle">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>

            <div class="profile-info">
                <h6>{{ Auth::user()->name }}</h6>
                <small>Administrator KWT</small>
            </div>

        </div>

    </div>

    <!-- MENU -->

    <div class="nav-list">

        <div class="nav-label">
            Menu Utama
        </div>

        <a href="{{ route('kwt.dashboard') }}"
           class="nav-link {{ request()->routeIs('kwt.dashboard') ? 'active' : '' }}">

            <i class="bi bi-grid-1x2-fill"></i>
            Dashboard
        </a>

        <a href="{{ route('kwt.products') }}"
           class="nav-link {{ request()->routeIs('kwt.products*') ? 'active' : '' }}">

            <i class="bi bi-box-seam-fill"></i>
            Produk
        </a>

        <a href="{{ route('kwt.orders') }}"
           class="nav-link {{ request()->routeIs('kwt.orders*') ? 'active' : '' }}">

            <i class="bi bi-bag-check-fill"></i>
            Pesanan
        </a>

        <a href="{{ route('kwt.laporan') }}"
           class="nav-link {{ request()->routeIs('kwt.laporan*') ? 'active' : '' }}">

            <i class="bi bi-bar-chart-fill"></i>
            Laporan
        </a>

        <div class="nav-label">
            Pengaturan
        </div>

        <a href="{{ route('kwt.profile') }}"
           class="nav-link {{ request()->routeIs('kwt.profile') ? 'active' : '' }}">

            <i class="bi bi-person-circle"></i>
            Profil
        </a>

    </div>

    <!-- FOOTER -->

    <div class="sidebar-footer">

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-right me-2"></i>
                Keluar
            </button>
        </form>

    </div>

</div>

<!-- ================= MAIN CONTENT ================= -->

<div class="main-content">

    <!-- MOBILE TOPBAR -->

    <div class="mobile-topbar">

        <div class="mobile-title">
            KWT Digital
        </div>

        <button class="menu-btn" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>

    </div>

    @yield('content')

</div>

<!-- ================= SCRIPT ================= -->

<script>

function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('show');
}

document.addEventListener('shown.bs.modal', function () {

    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
        backdrop.style.pointerEvents = 'none';
    });

});

</script>

<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>