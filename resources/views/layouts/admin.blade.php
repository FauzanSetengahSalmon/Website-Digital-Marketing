<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Admin Dashboard')
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
          rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            background:#f4f7fb;
            font-family:'Segoe UI',sans-serif;
            overflow-x:hidden;
        }

        a{
            text-decoration:none;
        }

        /* SIDEBAR */

        .sidebar{
            width:270px;
            height:100vh;

            position:fixed;
            top:0;
            left:0;

            background:
                linear-gradient(
                    180deg,
                    #1b5e20 0%,
                    #2e7d32 100%
                );

            z-index:999;

            overflow-y:auto;

            box-shadow:
                5px 0 25px rgba(0,0,0,.08);

            transition:.3s;
        }

        /* HEADER */

        .sidebar-header{
            padding:28px 24px;

            border-bottom:
                1px solid rgba(255,255,255,.08);
        }

        .brand{
            display:flex;
            align-items:center;
            gap:14px;
        }

        .brand-logo{
            width:55px;
            height:55px;

            border-radius:18px;

            background:
                rgba(255,255,255,.14);

            display:flex;
            align-items:center;
            justify-content:center;

            overflow:hidden;
        }

        .brand-logo img{
            width:34px;
            height:34px;
            object-fit:contain;
        }

        .brand-text h5{
            color:white;
            margin:0;
            font-weight:700;
        }

        .brand-text small{
            color:rgba(255,255,255,.7);
        }

        /* PROFILE */

        .profile-card{
            margin:22px;

            padding:18px;

            border-radius:22px;

            background:
                rgba(255,255,255,.1);

            display:flex;
            align-items:center;
            gap:14px;

            backdrop-filter:blur(8px);
        }

        .profile-avatar{
            width:50px;
            height:50px;

            border-radius:16px;

            background:white;

            color:#2e7d32;

            display:flex;
            align-items:center;
            justify-content:center;

            font-weight:700;
            font-size:20px;
        }

        .profile-info h6{
            margin:0;
            color:white;
            font-weight:700;
        }

        .profile-info small{
            color:rgba(255,255,255,.7);
        }

        /* MENU */

        .sidebar-menu{
            padding:0 16px 20px;
        }

        .menu-label{
            color:rgba(255,255,255,.5);

            font-size:11px;
            font-weight:700;

            text-transform:uppercase;
            letter-spacing:1px;

            margin:
                24px 14px 10px;
        }

        .menu-link{
            display:flex;
            align-items:center;
            gap:14px;

            padding:14px 16px;

            border-radius:16px;

            color:rgba(255,255,255,.88);

            font-weight:600;

            transition:.2s ease;

            margin-bottom:8px;
        }

        .menu-link i{
            font-size:18px;
        }

        .menu-link:hover{
            background:
                rgba(255,255,255,.12);

            color:white;

            transform:translateX(4px);
        }

        .menu-link.active{
            background:white;

            color:#2e7d32;

            box-shadow:
                0 10px 25px rgba(0,0,0,.12);
        }

        /* CONTENT */

        .main-content{
            margin-left:270px;
            min-height:100vh;
            padding:30px;
        }

        /* MOBILE */

        .mobile-topbar{
            display:none;
        }

        /* LOGOUT */

        .logout-box{
            padding:20px;
        }

        .btn-logout{
            width:100%;

            border:none;

            background:
                rgba(255,255,255,.1);

            color:white;

            padding:14px;

            border-radius:16px;

            font-weight:600;

            transition:.2s;
        }

        .btn-logout:hover{
            background:#dc3545;
        }

        /* RESPONSIVE */

        @media(max-width:768px){

            .sidebar{
                left:-270px;
            }

            .sidebar.show{
                left:0;
            }

            .main-content{
                margin-left:0;
                padding:20px;
            }

            .mobile-topbar{
                display:flex;

                align-items:center;
                justify-content:space-between;

                background:white;

                padding:14px 18px;

                border-radius:18px;

                margin-bottom:20px;

                box-shadow:
                    0 10px 30px rgba(0,0,0,.05);
            }
        }

    </style>

    @stack('styles')

</head>

<body>

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">

        {{-- HEADER --}}
        <div class="sidebar-header">

            <div class="brand">

                <div class="brand-logo">

                    <img src="https://cdn-icons-png.flaticon.com/512/766/766239.png"
                         alt="logo">

                </div>

                <div class="brand-text">

                    <h5>Admin KWT</h5>

                    <small>
                        Management Panel
                    </small>

                </div>

            </div>

        </div>

        {{-- PROFILE --}}
        <div class="profile-card">

            <div class="profile-avatar">
                {{ strtoupper(substr(Auth::user()->name,0,1)) }}
            </div>

            <div class="profile-info">

                <h6>
                    {{ Auth::user()->name }}
                </h6>

                <small>
                    Administrator
                </small>

            </div>

        </div>

        {{-- MENU --}}
        <div class="sidebar-menu">

            <div class="menu-label">
                Menu Utama
            </div>

            <a href="{{ route('admin.dashboard') }}"
               class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                <i class="bi bi-grid-fill"></i>
                Dashboard

            </a>

            <a href="{{ route('admin.kwt') }}"
               class="menu-link {{ request()->routeIs('admin.kwt*') ? 'active' : '' }}">

                <i class="bi bi-people-fill"></i>
                Kelola KWT

            </a>

        </div>

        {{-- LOGOUT --}}
        <div class="logout-box">

            <form method="POST"
                  action="{{ route('logout') }}">

                @csrf

                <button type="submit"
                        class="btn-logout">

                    <i class="bi bi-box-arrow-right me-2"></i>
                    Logout

                </button>

            </form>

        </div>

    </aside>

    {{-- MAIN --}}
    <main class="main-content">

        {{-- MOBILE --}}
        <div class="mobile-topbar">

            <h6 class="m-0 fw-bold text-success">
                Admin KWT
            </h6>

            <button class="btn btn-success"
                    onclick="toggleSidebar()">

                <i class="bi bi-list"></i>

            </button>

        </div>

        @yield('content')

    </main>

    <script>

        function toggleSidebar()
        {
            document
                .getElementById('sidebar')
                .classList
                .toggle('show');
        }

    </script>

    {{-- BOOTSTRAP JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>
</html>