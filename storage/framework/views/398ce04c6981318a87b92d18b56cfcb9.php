<?php $__env->startSection('content'); ?>
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Pesanan Masuk</h3>
            <p class="text-muted small mb-0">Kelola pesanan pelanggan yang masuk dan atur penugasan kurir.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary p-3 rounded-4 me-3">
                        <i class="bi bi-bag-check fs-3"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block fw-semibold">Total Pesanan</span>
                        <h4 class="fw-bold text-dark mb-0">
                            <?php echo e($orders->count()); ?> <span class="fs-6 fw-normal text-muted">Pesanan</span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="bg-warning-subtle text-warning p-3 rounded-4 me-3">
                        <i class="bi bi-clock-history fs-3"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block fw-semibold">Pesanan Menunggu</span>
                        <h4 class="fw-bold text-dark mb-0">
                            <?php echo e($orders->where('status','menunggu')->count()); ?> <span class="fs-6 fw-normal text-muted">Pesanan</span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="bg-success-subtle text-success p-3 rounded-4 me-3">
                        <i class="bi bi-truck fs-3"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block fw-semibold">Sedang Diproses</span>
                        <h4 class="fw-bold text-dark mb-0">
                            <?php echo e($orders->where('status','diproses')->count()); ?> <span class="fs-6 fw-normal text-muted">Pesanan</span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="fw-bold text-dark mb-0">Daftar Antrean Pesanan</h5>
            <span class="badge bg-light text-dark rounded-pill px-3 py-2 fw-semibold">Total: <?php echo e($orders->count()); ?></span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead class="bg-light text-secondary text-uppercase fs-7 fw-bold border-bottom">
                    <tr>
                        <th class="ps-4 py-3">Order ID</th>
                        <th class="py-3">Produk</th>
                        <th class="py-3">Jumlah</th>
                        <th class="py-3">Total</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-dark">
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4 py-3">
                            <span class="fw-bold text-primary">#ORD-<?php echo e($o->id); ?></span>
                        </td>

                        <td class="py-3">
                            <div class="fw-semibold text-dark"><?php echo e($o->details->first()->product->nama_produk ?? 'Produk'); ?></div>
                            <?php if($o->details->count() > 1): ?>
                            <small class="text-muted" style="font-size: 0.75rem;">
                                +<?php echo e($o->details->count() - 1); ?> produk lainnya
                            </small>
                            <?php endif; ?>
                        </td>

                        <td class="py-3 text-secondary">
                            <?php echo e($o->details->sum('jumlah')); ?> Item
                        </td>

                        <td class="py-3">
                            <span class="fw-bold text-success">
                                Rp <?php echo e(number_format($o->total_harga, 0, ',', '.')); ?>

                            </span>
                        </td>

                        <td class="py-3">
                            <?php if($o->status == 'menunggu'): ?>
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                <i class="bi bi-hourglass-split me-1"></i> MENUNGGU
                            </span>
                            <?php elseif($o->status == 'diproses'): ?>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                <i class="bi bi-arrow-repeat me-1"></i> DIPROSES
                            </span>
                            <?php else: ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                <i class="bi bi-check-circle-fill me-1"></i> <?php echo e(strtoupper($o->status)); ?>

                            </span>
                            <?php endif; ?>
                        </td>

                        <td class="py-3 text-center pe-4">
                            <div class="d-flex justify-content-center gap-2">
                                <?php if($o->status == 'menunggu'): ?>
                                <button type="button" class="btn btn-sm btn-dark rounded-pill px-3"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalKurir<?php echo e($o->id); ?>">
                                    <i class="bi bi-check2-circle me-1"></i> Terima
                                </button>
                                <?php endif; ?>
                                <a href="<?php echo e(route('kwt.orders.detail', $o->id)); ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </a>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalKurir<?php echo e($o->id); ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow rounded-4">
                                <form action="<?php echo e(route('kwt.order.status', $o->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>

                                    <input type="hidden" name="status" value="diproses">

                                    <div class="modal-header border-0 py-3 px-4 bg-light">
                                        <h5 class="modal-title fw-bold text-dark">
                                            <i class="bi bi-truck me-2 text-primary"></i>Tugaskan Kurir (#ORD-<?php echo e($o->id); ?>)
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body px-4 py-3">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">PILIH KURIR INTERN</label>
                                            <select name="kurir" class="form-select select-kurir py-2.5 border-0 bg-light rounded-3 text-dark" style="font-size: 0.85rem;" required>
                                                <option value="">-- Pilih Anggota Kurir --</option>
                                                <?php $__currentLoopData = $list_kurir; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kurir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($kurir->nama); ?>" data-phone="<?php echo e($kurir->no_hp); ?>"><?php echo e($kurir->nama); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label text-muted fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">NOMOR HP / WHATSAPP</label>
                                            <input type="text" name="no_hp_kurir" class="form-control input-phone py-2.5 border-0 bg-light rounded-3 text-dark fw-medium" style="font-size: 0.85rem;" placeholder="Nomor otomatis terisi..." readonly>
                                        </div>
                                    </div>

                                    <div class="modal-footer border-0 px-4 pb-4 pt-0">
                                        <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-dark rounded-pill px-4 fw-semibold">Konfirmasi & Proses</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <div class="py-3">
                                <i class="bi bi-bag-x fs-1 text-muted mb-2 d-block"></i>
                                <span>Belum terdapat data antrean pesanan masuk saat ini.</span>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .fs-7 {
        font-size: 0.78rem !important;
        letter-spacing: 0.5px;
    }

    .bg-success-subtle {
        background-color: #e8f5e9 !important;
    }

    .bg-primary-subtle {
        background-color: #e3f2fd !important;
    }

    .bg-warning-subtle {
        background-color: #fff8e1 !important;
    }

    .bg-danger-subtle {
        background-color: #ffebee !important;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa !important;
    }
</style>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Kita pakai cara yang lebih 'galak' supaya pasti terdeteksi
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('select-kurir')) {
                const select = e.target;
                const selectedOption = select.options[select.selectedIndex];

                // Ambil nomor dari atribut data-phone
                const phone = selectedOption.getAttribute('data-phone');

                // Cari input nomor hp di dalam modal yang sedang terbuka
                const modal = select.closest('.modal-content');
                const phoneInput = modal.querySelector('.input-phone');

                if (phoneInput) {
                    phoneInput.value = phone || '';
                    console.log("Nomor otomatis terisi: " + phone);
                }
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kwt', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\digitalmarketing\resources\views/kwt/orders.blade.php ENDPATH**/ ?>