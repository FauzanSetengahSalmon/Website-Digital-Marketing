<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Pengelola - <?php echo e(Auth::user()->name); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f3f5;
            color: #334155;
        }

        /* Sidebar */
        .sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            background: #064e3b; 
            transition: all 0.3s;
            z-index: 1000;
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
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
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

        /* Navigasi */
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
            text-align: left;
        }

        /* Main Content */
        .main-content {
            margin-left: 240px;
            padding: 24px;
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

        @media (max-width: 768px) {
            .sidebar { margin-left: -240px; }
            .main-content { margin-left: 0; }
            .sidebar.show { margin-left: 0; }
        }
    </style>
</head>

<body>
    
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="profile-box">
                <div class="avatar-circle">
                    <?php if(Auth::user()->profile_photo): ?>
                        <img src="<?php echo e(asset('storage/' . Auth::user()->profile_photo)); ?>" alt="Profile">
                    <?php else: ?>
                        <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                    <?php endif; ?>
                </div>
                
                <div class="profile-info">
                    <h6><?php echo e(Auth::user()->name); ?></h6>
                    <small style="color: #4ade80; font-size: 0.7rem;">Admin KWT</small>
                </div>
            </div>
        </div>

        <div class="nav-list">
            <div class="nav-label">Menu Utama</div>
            <a href="<?php echo e(route('kwt.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('kwt.dashboard') ? 'active' : ''); ?>">
                <i class="bi bi-house-door"></i> Beranda
            </a>

            <div class="nav-label">Manajemen</div>
            <a href="<?php echo e(route('kwt.products')); ?>" class="nav-link <?php echo e(request()->routeIs('kwt.products') ? 'active' : ''); ?>">
                <i class="bi bi-box-seam"></i> Produk Kita
            </a>

            <a href="<?php echo e(route('kwt.orders')); ?>" class="nav-link <?php echo e(request()->routeIs('kwt.orders') ? 'active' : ''); ?>">
                <i class="bi bi-cart3"></i> Pesanan
            </a>

            <a href="<?php echo e(route('kwt.laporan')); ?>" class="nav-link <?php echo e(request()->routeIs('kwt.laporan') ? 'active' : ''); ?>">
                <i class="bi bi-receipt"></i> Laporan Transaksi
            </a>

            <!-- MENU BARU UNTUK HALAMAN KURIR -->
            <a href="<?php echo e(route('kwt.kurir.index')); ?>" class="nav-link <?php echo e(request()->routeIs('kwt.kurir.*') ? 'active' : ''); ?>">
                <i class="bi bi-bicycle"></i> Data Kurir
            </a>

            <div class="nav-label">Akun</div>
            <a href="<?php echo e(route('kwt.profile')); ?>" class="nav-link <?php echo e(request()->routeIs('kwt.profile') ? 'active' : ''); ?>">
                <i class="bi bi-person-gear"></i> Profil KWT
            </a>
        </div>

        <div class="position-absolute bottom-0 w-100 p-3">
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn-logout fw-semibold">
                    <i class="bi bi-power me-2"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    
    <div class="main-content">
        <div class="d-md-none d-flex justify-content-between align-items-center mb-3 bg-white p-3 rounded-3 shadow-sm">
            <span class="fw-bold text-success">KWT Digital</span>
            <button class="btn btn-sm btn-success" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
        </div>

        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>
</body>

</html><?php /**PATH C:\laragon\www\digitalmarketing\resources\views/layouts/kwt.blade.php ENDPATH**/ ?>