<?php $__env->startSection('content'); ?>
<style>
    /* Style tetap sama */
    .kwt-card {
        transition: transform 0.2s;
        border: none;
        overflow: hidden;
    }

    .kwt-card:hover {
        transform: translateY(-5px);
    }

    .icon-shape {
        width: 48px;
        height: 48px;
        background-image: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.5rem;
    }

    .text-sm-custom {
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .bg-gradient-green {
        background: linear-gradient(45deg, #2d6a4f, #52b788);
        color: white;
    }

    .bg-gradient-blue {
        background: linear-gradient(45deg, #0077b6, #48cae4);
        color: white;
    }

    .bg-gradient-orange {
        background: linear-gradient(45deg, #e67e22, #f39c12);
        color: white;
    }

    .bg-gradient-purple {
        background: linear-gradient(45deg, #6d597a, #b56576);
        color: white;
    }

    .kwt-name-badge {
        background-color: #e8f5e9;
        color: #2e7d32;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 5px;
    }

    .illustration-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        animation: float 4s ease-in-out infinite;
    }

    .dashboard-illustration {
        width: 100%;
        max-width: 240px;
        height: auto;
        object-fit: contain;
        filter: drop-shadow(0 15px 25px rgba(0, 0, 0, 0.08));
    }

    @keyframes float {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }

        100% {
            transform: translateY(0px);
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <div class="kwt-name-badge shadow-sm text-uppercase">
                <i class="bi bi-house-heart me-1"></i> <?php echo e(Auth::user()->name); ?>

            </div>
            <h2 class="fw-bold text-dark mb-0">Selamat Pagi!</h2>
            <p class="text-muted mb-0">Berikut adalah laporan usaha kita hari ini.</p>
        </div>
        <div class="text-md-end">
            <span class="badge bg-white text-success border border-success px-3 py-2 rounded-pill shadow-sm">
                <i class="bi bi-check-circle-fill me-1"></i> Sistem Aktif
            </span>
            <div class="small text-muted mt-1"><i class="bi bi-calendar3 me-1"></i> <?php echo e(date('d F Y')); ?></div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card kwt-card shadow-sm rounded-4 bg-gradient-green p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-sm-custom fw-bold text-uppercase opacity-75">Hasil Penjualan</small>
                        <h3 class="fw-bold mb-0 mt-1">Rp <?php echo e(number_format($stats['total_received'], 0, ',', '.')); ?></h3>
                    </div>
                    <div class="icon-shape shadow-sm"><i class="bi bi-wallet2 text-white"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card kwt-card shadow-sm rounded-4 bg-gradient-blue p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-sm-custom fw-bold text-uppercase opacity-75">Produk Terjual</small>
                        <h3 class="fw-bold mb-0 mt-1"><?php echo e($stats['sold_count']); ?> <span class="fs-6 fw-normal">Item</span></h3>
                    </div>
                    <div class="icon-shape shadow-sm"><i class="bi bi-cart-check text-white"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card kwt-card shadow-sm rounded-4 bg-gradient-purple p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-sm-custom fw-bold text-uppercase opacity-75">Total Produk</small>
                        <h3 class="fw-bold mb-0 mt-1"><?php echo e($stats['total_products']); ?> <span class="fs-6 fw-normal">Jenis</span></h3>
                    </div>
                    <div class="icon-shape shadow-sm"><i class="bi bi-box-seam text-white"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card kwt-card shadow-sm rounded-4 bg-gradient-orange p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-sm-custom fw-bold text-uppercase opacity-75">Pesanan Masuk</small>
                        <h3 class="fw-bold mb-0 mt-1"><?php echo e($stats['pending_orders']); ?> <span class="fs-6 fw-normal">Baru</span></h3>
                    </div>
                    <div class="icon-shape shadow-sm"><i class="bi bi-bell-fill text-white"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row align-items-center bg-white mx-1 rounded-4 shadow-sm p-4 border-top border-success border-4 overflow-hidden">

        <!-- ILUSTRASI -->
        <div class="col-md-4 text-center">
            <div class="illustration-wrapper">
                <img
                    src="https://cdni.iconscout.com/illustration/premium/thumb/farmer-selling-organic-food-illustration-download-in-svg-png-gif-file-formats--vegetable-agriculture-pack-people-illustrations-4759507.png"
                    class="dashboard-illustration"
                    alt="KWT Illustration">
            </div>
        </div>

        <!-- TEXT -->
        <div class="col-md-8 text-center text-md-start mt-4 mt-md-0">
            <h4 class="fw-bold text-dark">
                Laporan Bisnis <?php echo e(Auth::user()->name); ?>

            </h4>

            <p class="text-muted small mb-3">
                Semua data di atas adalah hasil kerja keras kelompok kita.
                Mari terus menjaga kualitas produk dan meningkatkan penjualan setiap harinya.
            </p>

            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                <a href="<?php echo e(route('kwt.orders')); ?>"
                    class="btn btn-success rounded-pill px-4 shadow-sm">

                    <i class="bi bi-receipt me-2"></i>
                    Periksa Pesanan
                </a>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kwt', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\digitalmarketing\resources\views/kwt/dashboard.blade.php ENDPATH**/ ?>