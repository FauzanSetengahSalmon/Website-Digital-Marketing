

<?php $__env->startSection('title', 'EFood - Produk Segar dari Tangan Petani'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    :root {
        --green-dark: #2d7a22;
        --green-primary: #4caf50;
        --green-light: #d6f0c2;
        --text-dark: #1f2937;
        --text-light: #6b7280;
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hero-section {
        margin: 24px 0 40px;
        border-radius: 25px;
        overflow: hidden;
        min-height: 380px;
        display: flex;
        align-items: center;
        background:
            linear-gradient(to right, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.1)),
            url('https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?w=1200&q=80') center/cover no-repeat;
    }

    .hero-content {
        padding: 60px;
        max-width: 550px;
        color: white;
    }

    .hero-title {
        font-size: 2.4rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 15px;
    }

    .btn-green {
        background: var(--green-dark);
        color: white;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-green:hover {
        background: var(--green-primary);
        color: white;
        transform: translateY(-2px);
    }

    .btn-outline-green {
        border: 2px solid white;
        color: white;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s;
    }

    .btn-outline-green:hover {
        background: white;
        color: var(--green-dark);
    }

    .section-title {
        font-size: 2rem;
        font-weight: 800;
        color: #1f2937;
        position: relative;
        display: inline-block;
        padding-bottom: 12px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        left: 50%;
        bottom: 0;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background-color: #4caf50;
        border-radius: 2px;
    }

    .section-subtitle {
        color: var(--text-light);
        margin-top: 15px;
        margin-bottom: 40px;
        font-size: 1.1rem;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    .product-card {
        border: 1px solid #f0f0f0;
        border-radius: 20px;
        overflow: hidden;
        transition: 0.3s ease;
        background: white;
        height: 100%;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
        border-color: var(--green-light);
    }

    .btn-buy-now {
        background: var(--green-primary);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 15px;
        font-size: 0.8rem;
        font-weight: 600;
        flex: 1;
        transition: 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-buy-now:hover:not(:disabled) {
        background: #2e7d32;
        color: white;
    }

    .btn-cart-outline {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        background: white;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }

    .btn-cart-outline:hover:not(:disabled) {
        border-color: var(--green-primary);
        color: var(--green-primary);
        background: #f2f8f2;
    }

    .cta-section {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border-radius: 25px;
        border: 1px solid var(--green-light);
        padding: 50px !important;
    }

    .cta-section h2 {
        color: var(--green-dark);
        font-size: 1.8rem;
        font-weight: 800;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<div class="container fade-in-up">

    
    <section class="hero-section shadow-sm">
        <div class="hero-content">
            <h1 class="hero-title">
                Produk Segar<br>
                dari Petani Lokal
            </h1>

            <p class="mb-4 opacity-90">
                Nikmati hasil panen terbaik yang dipetik langsung dengan penuh kasih sayang dari kebun kami.
            </p>

            <div class="d-flex gap-3">
                <a href="<?php echo e(route('customer.katalog')); ?>" class="btn-green">
                    <i class="bi bi-bag-check"></i>
                    Belanja Sekarang
                </a>

                <a href="<?php echo e(route('about')); ?>" class="btn-outline-green">
                    Tentang Kami
                </a>
            </div>
        </div>
    </section>

    
    <section class="py-4" id="produk">

        <div class="text-center mb-4">

            <h2 class="section-title">
                Produk Pilihan
            </h2>

            <p class="section-subtitle">
                Temukan koleksi sayuran dan hasil bumi terbaik langsung dari lahan pertanian lokal oleh kelompok tani kami.
            </p>

        </div>

        <div class="row g-3">

            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <div class="col-lg-3 col-md-4 col-6">

                <div class="product-card border-0 shadow-sm">

                    
                    <div class="position-relative">

                        <?php if($product->foto_produk): ?>

                        <img
                            src="<?php echo e(asset('storage/' . $product->foto_produk)); ?>"
                            style="height: 140px; width:100%; object-fit:cover;">

                        <?php else: ?>

                        <img
                            src="https://via.placeholder.com/300x200"
                            style="height:140px; width:100%; object-fit:cover;">

                        <?php endif; ?>

                        <span
                            class="badge bg-white text-success position-absolute top-0 end-0 m-2 px-2 py-1"
                            style="border-radius:50px; font-weight:700; font-size:0.6rem;">

                            <?php echo e($product->stok > 0 ? 'Tersedia' : 'Habis'); ?>


                        </span>

                    </div>

                    
                    <div class="p-3">

                        
                        <div
                            class="d-inline-flex align-items-center gap-1 px-2 py-1 mb-1"
                            style="background-color:#f0fdf4; color:#2d7a22; border-radius:5px; font-size:0.65rem; font-weight:700;">

                            <i class="bi bi-shop me-1"></i>

                            <span class="text-uppercase">
                                <?php echo e($product->user->name ?? 'KETUA KWT'); ?>

                            </span>

                        </div>

                        
                        <h6
                            class="fw-bold text-dark mb-0"
                            style="font-size:0.9rem; line-height:2;">

                            <?php echo e($product->nama_produk); ?>


                        </h6>

                        
                        <p
                            class="text-secondary mb-2"
                            style="font-size:0.75rem;">

                            Tersedia <?php echo e($product->stok); ?> <?php echo e($product->satuan); ?>


                        </p>

                        
                        <div class="mt-1 mb-2">

                            <span
                                class="fw-bold"
                                style="color:#2d7a22; font-size:1.05rem;">

                                Rp. <?php echo e(number_format($product->harga, 0, ',', '.')); ?>


                            </span>

                            <span
                                class="text-secondary"
                                style="font-size:0.8rem;">

                                /<?php echo e($product->satuan); ?>


                            </span>

                        </div>

                        
                        <div class="d-flex gap-2 mt-2">

                            <?php if($product->stok > 0): ?>

                            
                            <button
                                type="button"
                                class="btn-buy-now handle-cart"
                                data-id="<?php echo e($product->id); ?>"
                                data-name="<?php echo e($product->nama_produk); ?>"
                                data-type="direct">

                                Beli

                            </button>

                            
                            <button
                                type="button"
                                class="btn-cart-outline handle-cart"
                                data-id="<?php echo e($product->id); ?>"
                                data-name="<?php echo e($product->nama_produk); ?>"
                                data-type="cart">

                                <i class="bi bi-cart-plus"></i>

                            </button>

                            <?php else: ?>

                            <button
                                class="btn-buy-now w-100"
                                disabled>

                                Habis

                            </button>

                            <button
                                class="btn-cart-outline"
                                disabled>

                                <i class="bi bi-cart-x"></i>

                            </button>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <div class="col-12 text-center py-5">

                <p class="text-muted">
                    Belum ada produk.
                </p>

            </div>

            <?php endif; ?>

        </div>

    </section>

    
    <section class="py-5">

        <div class="container">

            <div class="cta-section text-center p-5 shadow">

                <h2 class="fw-bolder mb-3">
                    Dukung Petani Lokal & Hidup Lebih Sehat 🌱
                </h2>

                <p class="mb-4">
                    Setiap pembelian membantu kesejahteraan KWT dan menghadirkan makanan sehat ke keluarga Anda.
                </p>

                <div class="d-flex justify-content-center gap-3 flex-wrap">

                    <a
                        href="<?php echo e(route('customer.katalog')); ?>"
                        class="btn-green">

                        Belanja Sekarang

                    </a>

                    <a
                        href="<?php echo e(route('about')); ?>"
                        class="btn btn-outline-success px-4 py-2 fw-bold"
                        style="border-radius:12px; border-width:2px;">

                        Pelajari Lebih Lanjut

                    </a>

                </div>

            </div>

        </div>

    </section>

</div>


<script>
    document.querySelectorAll('.handle-cart').forEach(button => {

        button.addEventListener('click', function() {

            const productId = this.dataset.id;
            const productName = this.dataset.name;
            const type = this.dataset.type;

            const originalContent = this.innerHTML;

            this.disabled = true;

            this.innerHTML =
                '<span class="spinner-border spinner-border-sm"></span>';

            fetch(`/cart/add/${productId}`, {

                    method: 'POST',

                    headers: {

                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

                        'Content-Type': 'application/json',

                        'Accept': 'application/json'
                    },

                    body: JSON.stringify({
                        quantity: 1
                    })

                })

                .then(response => {

                    if (!response.ok) {
                        throw new Error('Unauthorized');
                    }

                    return response.json();

                })

                .then(data => {

                    this.disabled = false;
                    this.innerHTML = originalContent;

                    if (type === 'direct') {

                        window.location.href =
                            "<?php echo e(route('cart.index')); ?>";

                    } else {

                        if (typeof Swal !== 'undefined') {

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: productName + ' masuk keranjang!',
                                showConfirmButton: false,
                                timer: 2000
                            });

                        }

                        const cartBadge =
                            document.getElementById('cart-badge');

                        if (cartBadge) {

                            cartBadge.innerText = data.cartCount;
                            cartBadge.classList.remove('d-none');

                        }
                    }
                })

                .catch(error => {

                    this.disabled = false;
                    this.innerHTML = originalContent;

                    window.location.href =
                        "<?php echo e(route('login')); ?>";

                });

        });

    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\digitalmarketing\resources\views/home.blade.php ENDPATH**/ ?>