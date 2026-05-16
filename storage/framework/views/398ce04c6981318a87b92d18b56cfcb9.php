<?php $__env->startSection('content'); ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #f8fafc;
    }

    .main-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #eef2f7;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .04);
        overflow: hidden;
    }

    .table thead th {
        background: #fcfdfd;
        color: #94a3b8;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 20px;
        border: none;
    }

    .table tbody td {
        padding: 20px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .btn-action {
        border-radius: 14px;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 700;
        transition: .25s;
        border: none;
    }

    .btn-terima {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        box-shadow: 0 8px 20px rgba(34, 197, 94, .2);
    }

    .btn-terima:hover {
        transform: translateY(-2px);
        opacity: 0.9;
        color: white;
    }

    .modal-content {
        border-radius: 28px;
        border: none;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
    }
</style>

<div style="padding:30px;">
    <div class="main-card">
        <div style="padding:24px 28px; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h4 style="margin:0; font-weight:800; color:#15803d;">Pesanan Masuk</h4>
                <p style="margin:4px 0 0; color:#94a3b8; font-size:13px;">Kelola pesanan dan tugaskan kurir KWT</p>
            </div>
            <div style="background:#f0fdf4; color:#15803d; padding:10px 16px; border-radius:14px; font-weight:700; font-size:13px;">
                <?php echo e($orders->count()); ?> Pesanan
            </div>
        </div>

        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Produk</th>
                        <th class="text-center">Qty</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="font-weight:700;">#ORD-<?php echo e($o->id); ?></td>
                        <td>
                            <div style="font-weight:700;"><?php echo e($o->details->first()->product->nama_produk ?? 'Produk'); ?></div>
                            <?php if($o->details->count() > 1): ?>
                            <small class="text-muted">+<?php echo e($o->details->count() - 1); ?> produk lainnya</small>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <span style="background:#f8fafc; padding:6px 12px; border-radius:10px; font-weight:700;"><?php echo e($o->details->sum('jumlah')); ?></span>
                        </td>
                        <td style="color:#16a34a; font-weight:800;">Rp <?php echo e(number_format($o->total_harga,0,',','.')); ?></td>
                        <td>
                            <span style="background:#fef9c3; color:#a16207; padding:6px 14px; border-radius:999px; font-size:11px; font-weight:700;">
                                <?php echo e(strtoupper($o->status)); ?>

                            </span>
                        </td>
                        <td class="text-center">
                            <?php if($o->status == 'menunggu'): ?>
                            <button type="button" class="btn-action btn-terima" data-bs-toggle="modal" data-bs-target="#modalKurir<?php echo e($o->id); ?>">
                                <i class="bi bi-check2-circle me-1"></i> Terima & Pilih Kurir
                            </button>
                            <?php else: ?>
                            <a href="<?php echo e(route('kwt.orders.detail', $o->id)); ?>" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">Detail</a>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalKurir<?php echo e($o->id); ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content p-3">
                                <form action="<?php echo e(route('kwt.order.status', $o->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <input type="hidden" name="status" value="diproses">
                                    <div class="modal-header border-0">
                                        <h5 class="fw-800">Tugaskan Kurir</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-muted">PILIH KURIR KWT</label>
                                            <select name="kurir" class="form-select border-0 bg-light select-kurir" style="border-radius:15px; padding:12px;" required>
                                                <option value="">-- Klik untuk memilih --</option>
                                                <?php $__currentLoopData = $list_kurir; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kurir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($kurir->nama); ?>" data-phone="<?php echo e($kurir->no_hp); ?>"><?php echo e($kurir->nama); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label small fw-bold text-muted">NOMOR HP KURIR</label>
                                            <input type="text" name="no_hp_kurir" class="form-control border-0 bg-light input-phone" placeholder="Terisi otomatis..." style="border-radius:15px; padding:12px;">
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="submit" class="btn btn-success w-100 fw-bold rounded-pill py-3">Konfirmasi & Proses Pesanan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center" style="padding:80px;">
                            <i class="bi bi-mailbox text-success" style="font-size: 40px;"></i>
                            <h5 class="mt-3 fw-bold">Belum Ada Pesanan Masuk</h5>
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
            const selectedOption = this.options[this.selectedIndex];
            const phone = selectedOption.getAttribute('data-phone');
            const modal = this.closest('.modal');
            const phoneInput = modal.querySelector('.input-phone');

            if (phone) {
                phoneInput.value = phone;
            } else {
                phoneInput.value = '';
            }
        });
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kwt', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\digitalmarketing\resources\views/kwt/orders.blade.php ENDPATH**/ ?>