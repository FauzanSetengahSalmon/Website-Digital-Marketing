<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title', 'KWT Cibiru'); ?></title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <?php echo $__env->yieldPushContent('styles'); ?>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: #f6f9f4;
            font-family: 'Segoe UI', sans-serif;
            color: #222;
        }

        a {
            text-decoration: none;
        }

        /* ================= NAVBAR ================= */

        .navbar-efood {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #e2efda;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 0;
            gap: 20px;
        }

        .brand-logo-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 21px;
            color: #1b1b1b;
        }

        .brand-icon {
            width: 45px;
            height: 45px;
            border-radius: 14px;
            background: linear-gradient(135deg, #2d7a22, #4caf50);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 16px rgba(76, 175, 80, 0.25);
        }

        .brand-logo-wrap span {
            color: #2d7a22;
        }

        .brand-subtitle {
            font-size: 11px;
            color: #777;
            font-weight: 500;
            margin-top: -4px;
        }

        .nav-link-efood {
            color: #555;
            font-weight: 600;
            padding: 10px 16px;
            border-radius: 12px;
            transition: 0.2s ease;
            font-size: 14px;
        }

        .nav-link-efood:hover,
        .nav-link-efood.active {
            background: #eef8ea;
            color: #2d7a22;
        }

        /* ================= BUTTON ================= */

        .btn-cart {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: white;
            border: 1px solid #dcefd1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2d7a22;
            transition: 0.2s ease;
            position: relative;
        }

        .btn-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
        }

        .btn-cart i {
            font-size: 1.2rem;
        }

        .btn-masuk {
            padding: 10px 18px;
            border-radius: 12px;
            background: white;
            border: 1px solid #d9e8d0;
            color: #444;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .btn-masuk:hover {
            border-color: #2d7a22;
            color: #2d7a22;
        }

        .btn-daftar {
            padding: 10px 18px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #2d7a22, #4caf50);
            color: white;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .btn-daftar:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(76, 175, 80, 0.25);
            color: white;
        }

        /* ================= MAIN ================= */

        main {
            min-height: 80vh;
        }

        /* ================= FOOTER ================= */

        footer {
            margin-top: 70px;
            background: linear-gradient(to bottom, #f0f9eb, #ebf6e5);
            border-top: 1px solid #d5e9ca;
        }

        .footer-top {
            padding: 60px 0 45px;
        }

        .footer-brand-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 20px;
            font-weight: 700;
        }

        .footer-brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #2d7a22, #4caf50);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .footer-brand-wrap span {
            color: #2d7a22;
        }

        .footer-tagline {
            margin-top: 15px;
            font-size: 14px;
            color: #666;
            line-height: 1.7;
        }

        .footer-social {
            margin-top: 18px;
            display: flex;
            gap: 10px;
        }

        .footer-social a {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: white;
            border: 1px solid #d7ead0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2d7a22;
            transition: 0.2s ease;
        }

        .footer-social a:hover {
            background: #2d7a22;
            color: white;
            transform: translateY(-2px);
        }

        .footer-heading {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            color: #2d7a22;
            margin-bottom: 10px;
        }

        .footer-link {
            display: block;
            margin-bottom: 10px;
            color: #555;
            font-size: 14px;
            transition: 0.2s ease;
        }

        .footer-link:hover {
            color: #2d7a22;
            padding-left: 4px;
        }

        .footer-subscribe-input {
            width: 100%;
            border-radius: 12px;
            border: 1px solid #d6e7ce;
            padding: 11px 14px;
            font-size: 14px;
            margin-bottom: 10px;
            outline: none;
        }

        .footer-subscribe-input:focus {
            border-color: #4caf50;
            box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.12);
        }

        .btn-subscribe {
            width: 100%;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #2d7a22, #4caf50);
            color: white;
            padding: 11px;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .btn-subscribe:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(76, 175, 80, 0.25);
        }

        .footer-spam-note {
            margin-top: 10px;
            font-size: 12px;
            color: #777;
        }

        .footer-bottom {
            border-top: 1px solid #d9ead0;
            padding: 18px 0;
            font-size: 13px;
            color: #666;
        }

        @media (max-width: 768px) {
            .navbar-inner {
                flex-wrap: wrap;
            }

            .brand-logo-wrap {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>

    

    <nav class="navbar-efood">
        <div class="container">

            <div class="navbar-inner">

                
                <a href="<?php echo e(url('/')); ?>" class="brand-logo-wrap">

                    <div class="brand-icon">
                        <i class="bi bi-flower1 text-white fs-4"></i>
                    </div>

                    <div>
                        KWT <span>Cibiru</span>
                        <div class="brand-subtitle">
                            Kelompok Wanita Tani
                        </div>
                    </div>

                </a>

                
                <ul class="nav d-none d-lg-flex gap-1">

                    <li>
                        <a class="nav-link-efood <?php echo e(request()->is('/') ? 'active' : ''); ?>"
                            href="<?php echo e(route('home')); ?>">
                            Beranda
                        </a>
                    </li>

                    <li>
                        <a class="nav-link-efood <?php echo e(request()->is('katalog*') ? 'active' : ''); ?>"
                            href="<?php echo e(route('customer.katalog')); ?>">
                            Katalog
                        </a>
                    </li>

                    <li>
                        <a class="nav-link-efood <?php echo e(request()->is('riwayat-pesanan*') ? 'active' : ''); ?>"
                            href="<?php echo e(route('orders.history')); ?>">
                            Riwayat
                        </a>
                    </li>

                    <li>
                        <a class="nav-link-efood <?php echo e(request()->is('tentang-kami') ? 'active' : ''); ?>"
                            href="<?php echo e(route('about')); ?>">
                            Tentang Kami
                        </a>
                    </li>

                </ul>

                
                <div class="d-flex align-items-center gap-2">

                    
                    <a href="<?php echo e(route('cart.index')); ?>" class="btn-cart">

                        <i class="bi bi-cart3"></i>

                        <span id="cart-badge"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger
                        <?php echo e((auth()->check() && auth()->user()->carts->count() > 0) ? '' : 'd-none'); ?>"
                            style="font-size:10px;">

                            <?php echo e(auth()->check() ? auth()->user()->carts->count() : 0); ?>


                        </span>

                    </a>

                    <?php if(auth()->guard()->check()): ?>

                    <div class="dropdown">

                        <button class="btn-daftar dropdown-toggle"
                            data-bs-toggle="dropdown">

                            <i class="bi bi-person-circle me-1"></i>
                            <?php echo e(Auth::user()->name); ?>


                        </button>

                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-3">

                            <li>
                                <a class="dropdown-item py-2"
                                    href="<?php echo e(route('profile.edit')); ?>">

                                    <i class="bi bi-person me-2 text-success"></i>
                                    Profil Saya

                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>

                                <form method="POST"
                                    action="<?php echo e(route('logout')); ?>">

                                    <?php echo csrf_field(); ?>

                                    <button type="submit"
                                        class="dropdown-item py-2 text-danger">

                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        Logout

                                    </button>

                                </form>

                            </li>

                        </ul>

                    </div>

                    <?php else: ?>

                    <a href="<?php echo e(route('login')); ?>"
                        class="btn-masuk">
                        Masuk
                    </a>

                    <a href="<?php echo e(route('register')); ?>"
                        class="btn-daftar">
                        Daftar
                    </a>

                    <?php endif; ?>

                </div>

            </div>

        </div>
    </nav>

    

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    

    <footer>

        <div class="footer-top">

            <div class="container">

                <div class="row g-5">

                    
                    <div class="col-12 col-md-4">

                        <div class="footer-brand-wrap">

                            <div class="footer-brand-icon">
                                <i class="bi bi-flower1 text-white fs-5"></i>
                            </div>

                            <div>
                                KWT <span>Cibiru</span>
                                <div class="brand-subtitle">
                                    Kelompok Wanita Tani
                                </div>
                            </div>

                        </div>

                        <p class="footer-tagline">
                            Platform digital hasil tani lokal dari
                            Kelompok Wanita Tani Desa Cibiru.
                            Mendukung UMKM, petani lokal,
                            dan pangan sehat untuk masyarakat.
                        </p>

                        <div class="footer-social">

                            <a href="#">
                                <i class="bi bi-facebook"></i>
                            </a>

                            <a href="#">
                                <i class="bi bi-instagram"></i>
                            </a>

                            <a href="#">
                                <i class="bi bi-tiktok"></i>
                            </a>

                        </div>

                    </div>

                    
                    <div class="col-12 col-md-4">

                        <p class="footer-heading">
                            Laporkan Bug
                        </p>

                        <input type="email"
                            class="footer-subscribe-input"
                            placeholder="Masukkan email anda">

                        <button class="btn-subscribe">
                            Kirim Laporan
                        </button>

                        <p class="footer-spam-note">
                            Kami menjaga privasi data pengguna.
                        </p>

                    </div>

                    
                    <div class="col-6 col-md-2">

                        <p class="footer-heading">
                            Navigasi
                        </p>

                        <a href="<?php echo e(route('home')); ?>"
                            class="footer-link">
                            Beranda
                        </a>

                        <a href="<?php echo e(route('customer.katalog')); ?>"
                            class="footer-link">
                            Katalog
                        </a>

                        <a href="<?php echo e(route('about')); ?>"
                            class="footer-link">
                            Tentang Kami
                        </a>

                    </div>

                    
                    <div class="col-6 col-md-2">

                        <p class="footer-heading">
                            Cepat
                        </p>

                        <?php if(auth()->guard()->guest()): ?>

                        <a href="<?php echo e(route('login')); ?>"
                            class="footer-link">
                            Masuk
                        </a>

                        <a href="<?php echo e(route('register')); ?>"
                            class="footer-link">
                            Daftar
                        </a>

                        <?php endif; ?>

                        <?php if(auth()->guard()->check()): ?>

                        <a href="<?php echo e(route('cart.index')); ?>"
                            class="footer-link">
                            Keranjang
                        </a>

                        <a href="<?php echo e(route('orders.history')); ?>"
                            class="footer-link">
                            Riwayat
                        </a>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

        
        <div class="footer-bottom">

            <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">

                <span>
                    © <?php echo e(date('Y')); ?> KWT Cibiru — Kelompok Wanita Tani
                </span>

                <span style="color:#2d7a22;">
                    Dibuat untuk mendukung pangan lokal 🌱
                </span>

            </div>

        </div>

    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>

</html><?php /**PATH C:\laragon\www\digitalmarketing\resources\views/layouts/app.blade.php ENDPATH**/ ?>