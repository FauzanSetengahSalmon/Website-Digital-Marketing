<?php $__env->startSection('content'); ?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    body {
        font-family: 'Inter', sans-serif;
        background: #f6f7fb;
    }

    /* HEADER */
    .page-title {
        font-weight: 800;
        font-size: 26px;
    }

    .sub-title {
        color: #6b7280;
        font-size: 13px;
    }

    /* CARD */
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 18px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, .04);
        height: 100%;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .icon-green {
        background: #e8f7ee;
        color: #16a34a;
    }

    .icon-blue {
        background: #eef4ff;
        color: #2563eb;
    }

    .icon-orange {
        background: #fff4e6;
        color: #f97316;
    }

    /* TABLE */
    .table-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .04);
        overflow: hidden;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        font-size: 11px;
        color: #6b7280;
        font-weight: 600;
        border-bottom: none;
        padding: 14px 18px;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 14px 18px;
        vertical-align: middle;
        border-top: 1px solid #f1f5f9;
        font-size: 12px;
        color: #111827;
    }

    .table tbody tr:hover {
        background: #fafafa;
    }

    /* TEXT */
    .product-name {
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 1px;
    }

    .product-extra {
        font-size: 10px;
        color: #9ca3af;
    }

    .total-price {
        font-size: 12px;
        font-weight: 700;
        color: #16a34a;
    }

    /* BUTTON */
    .btn-soft {
        border-radius: 999px;
        padding: 6px 13px;
        font-weight: 600;
        font-size: 11px;
        transition: .2s;
    }

    .btn-dark-soft {
        background: #111827;
        color: white;
        border: none;
    }

    .btn-dark-soft:hover {
        background: black;
        color: white;
    }

    .btn-light-soft {
        background: white;
        border: 1px solid #d1d5db;
        color: #111827;
    }

    .btn-light-soft:hover {
        background: #f9fafb;
        color: #111827;
    }

    /* EMPTY */
    .empty-box {
        padding: 60px 20px;
        text-align: center;
        color: #9ca3af;
        font-size: 13px;
    }

    /* MODAL */
    .modal-content {
        border: none;
        border-radius: 20px;
    }

    .form-control,
    .form-select {
        border: none;
        background: #f9fafb;
        border-radius: 14px;
        padding: 12px;
        box-shadow: none !important;
        font-size: 13px;
    }

    .form-label {
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
    }
</style>

<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <div class="page-title">Pesanan Masuk</div>

            <div class="sub-title">
                Kelola pesanan pelanggan yang masuk
            </div>
        </div>

    </div>

    <!-- STATS -->
    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="stat-card">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <small class="text-muted">Total Pesanan</small>

                        <h5 class="fw-bold mb-0 mt-1">
                            <?php echo e($orders->count()); ?>

                        </h5>
                    </div>

                    <div class="stat-icon icon-blue">
                        <i class="bi bi-bag-check"></i>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="stat-card">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <small class="text-muted">Pesanan Menunggu</small>

                        <h5 class="fw-bold mb-0 mt-1">
                            <?php echo e($orders->where('status','menunggu')->count()); ?>

                        </h5>
                    </div>

                    <div class="stat-icon icon-orange">
                        <i class="bi bi-clock-history"></i>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="stat-card">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <small class="text-muted">Sedang Diproses</small>

                        <h5 class="fw-bold mb-0 mt-1">
                            <?php echo e($orders->where('status','diproses')->count()); ?>

                        </h5>
                    </div>

                    <div class="stat-icon icon-green">
                        <i class="bi bi-truck"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- TABLE -->
    <div class="table-card">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th class="ps-4">Order ID</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr>

                        <!-- ORDER -->
                        <td class="ps-4 fw-bold">
                            #ORD-<?php echo e($o->id); ?>

                        </td>

                        <!-- PRODUK -->
                        <td>

                            <div class="product-name">
                                <?php echo e($o->details->first()->product->nama_produk ?? 'Produk'); ?>

                            </div>

                            <?php if($o->details->count() > 1): ?>

                            <div class="product-extra">
                                +<?php echo e($o->details->count() - 1); ?> produk lainnya
                            </div>

                            <?php endif; ?>

                        </td>

                        <!-- JUMLAH -->
                        <td>
                            <?php echo e($o->details->sum('jumlah')); ?> Item
                        </td>

                        <!-- TOTAL -->
                        <td class="total-price">
                            Rp <?php echo e(number_format($o->total_harga,0,',','.')); ?>

                        </td>

                        <!-- STATUS -->
                        <td>

                            <?php if($o->status == 'menunggu'): ?>

                            <span class="text-warning fw-semibold">
                                Menunggu
                            </span>

                            <?php elseif($o->status == 'diproses'): ?>

                            <span class="text-primary fw-semibold">
                                Diproses
                            </span>

                            <?php else: ?>

                            <span class="text-success fw-semibold">
                                <?php echo e(ucfirst($o->status)); ?>

                            </span>

                            <?php endif; ?>

                        </td>

                        <!-- BUTTON -->
                        <td class="text-center">

                            <?php if($o->status == 'menunggu'): ?>

                            <button
                                type="button"
                                class="btn btn-soft btn-dark-soft"
                                data-bs-toggle="modal"
                                data-bs-target="#modalKurir<?php echo e($o->id); ?>">

                                <i class="bi bi-check2-circle me-1"></i>
                                Terima

                            </button>

                            <?php else: ?>

                            <a href="<?php echo e(route('kwt.orders.detail', $o->id)); ?>"
                                class="btn btn-soft btn-light-soft">

                                Detail

                            </a>

                            <?php endif; ?>

                        </td>

                    </tr>

                    <!-- MODAL -->
                    <div class="modal fade"
                        id="modalKurir<?php echo e($o->id); ?>"
                        tabindex="-1">

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content">

                                <form action="<?php echo e(route('kwt.order.status', $o->id)); ?>"
                                    method="POST">

                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>

                                    <input type="hidden"
                                        name="status"
                                        value="diproses">

                                    <div class="modal-header border-0 pb-0">

                                        <h5 class="fw-bold">
                                            Tugaskan Kurir
                                        </h5>

                                        <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal">
                                        </button>

                                    </div>

                                    <div class="modal-body">

                                        <div class="mb-3">

                                            <label class="form-label">
                                                PILIH KURIR
                                            </label>

                                            <select
                                                name="kurir"
                                                class="form-select select-kurir"
                                                required>

                                                <option value="">
                                                    -- Pilih Kurir --
                                                </option>

                                                <?php $__currentLoopData = $list_kurir; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kurir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                <option
                                                    value="<?php echo e($kurir->nama); ?>"
                                                    data-phone="<?php echo e($kurir->no_hp); ?>">

                                                    <?php echo e($kurir->nama); ?>


                                                </option>

                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            </select>

                                        </div>

                                        <div>

                                            <label class="form-label">
                                                NOMOR HP
                                            </label>

                                            <input
                                                type="text"
                                                name="no_hp_kurir"
                                                class="form-control input-phone"
                                                placeholder="Nomor otomatis muncul">

                                        </div>

                                    </div>

                                    <div class="px-4 pb-4">

                                        <button
                                            type="submit"
                                            class="btn btn-dark w-100 rounded-pill py-3 fw-semibold">

                                            Konfirmasi & Proses Pesanan

                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>

                        <td colspan="6">

                            <div class="empty-box">

                                <i class="bi bi-bag-x fs-1 d-block mb-2"></i>

                                Belum ada pesanan masuk

                            </div>

                        </td>

                    </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>
    document.querySelectorAll('.select-kurir').forEach(select => {

        select.addEventListener('change', function() {

            const selectedOption =
                this.options[this.selectedIndex];

            const phone =
                selectedOption.getAttribute('data-phone');

            const modal =
                this.closest('.modal');

            const phoneInput =
                modal.querySelector('.input-phone');

            phoneInput.value = phone || '';

        });

    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kwt', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\digitalmarketing\resources\views/kwt/orders.blade.php ENDPATH**/ ?>