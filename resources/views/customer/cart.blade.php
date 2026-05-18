@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8faf9;
        color: #334155;
    }

    /* List Area */
    .cart-item {
        border-bottom: 1px solid #f1f5f9;
        padding: 24px 0;
        transition: 0.2s;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    /* Image Slim & Premium */
    .img-wrapper {
        width: 80px;
        height: 80px;
        flex-shrink: 0;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 4px;
        background: #ffffff;
    }

    .img-cart {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }

    /* Qty Box (Sleek & Centered) */
    .qty-box {
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
        border-radius: 10px;
        padding: 4px;
        border: 1px solid #e2e8f0;
        width: 105px;
        transition: all 0.2s ease;
    }

    .qty-box:focus-within {
        border-color: #4caf50;
        box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
    }

    .btn-step {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        border: 1px solid transparent;
        background: #f8fafc;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-step:hover {
        background: #e8f5e9;
        color: #1e5217;
    }

    .btn-step:active {
        transform: scale(0.95);
    }

    .input-qty {
        width: 32px;
        border: none;
        background: transparent;
        text-align: center;
        font-weight: 600;
        font-size: 0.95rem;
        color: #0f172a;
        padding: 0;
        margin: 0;
    }

    .input-qty:focus {
        outline: none;
    }

    .input-qty::-webkit-outer-spin-button,
    .input-qty::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .input-qty[type=number] {
        -moz-appearance: textfield;
    }

    /* Typography & Badges */
    .text-success-kwt {
        color: #1e5217 !important;
        letter-spacing: -0.5px;
        font-weight: 800;
        font-size: 1.4rem;
    }

    .badge-cart-count {
        background-color: #ffffff;
        color: #4caf50;
        border: 1px solid #e8f5e9;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 8px rgba(76, 175, 80, 0.08);
    }

    /* Checkout Sidebar (Clean & Modern) */
    .summary-side {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.02);
        border-radius: 20px;
        padding: 28px;
        position: sticky;
        top: 24px;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.04);
    }

    .btn-main {
        background: #4caf50;
        border: none;
        color: white;
        padding: 14px;
        border-radius: 12px;
        font-weight: 700;
        width: 100%;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.2);
    }

    .btn-main:hover:not(:disabled) {
        background: #1e5217;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(30, 82, 23, 0.25);
    }

    .btn-main:disabled {
        background: #e2e8f0;
        color: #94a3b8;
        box-shadow: none;
        cursor: not-allowed;
    }

    .item-name {
        font-size: 15px;
        font-weight: 600;
        margin: 0;
        color: #1e293b;
        letter-spacing: -0.2px;
    }

    .item-price {
        font-size: 14px;
        color: #4caf50;
        font-weight: 700;
    }

    /* Checkbox Styles */
    .checkbox-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        padding-right: 4px;
    }

    .form-check-input {
        width: 22px;
        height: 22px;
        cursor: pointer;
        border: 1.5px solid #cbd5e1;
        border-radius: 6px;
        margin: 0 !important;
        transition: all 0.2s ease-in-out;
        background-color: #fff;
    }

    .form-check-input:checked {
        background-color: #4caf50;
        border-color: #4caf50;
        box-shadow: 0 2px 6px rgba(76, 175, 80, 0.2);
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        border-color: #4caf50;
    }

    .out-of-stock {
        opacity: 0.5;
        filter: grayscale(100%);
    }

    .btn-delete {
        color: #cbd5e1;
        transition: color 0.2s;
    }

    .btn-delete:hover {
        color: #ef4444;
    }
</style>

<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-7">
            <div class="d-flex align-items-center justify-content-between mb-2 pb-3 border-bottom border-light">
                <h5 class="text-success-kwt mb-0">Keranjang Belanja</h5>
                <div class="badge-cart-count">
                    <i class="bi bi-cart3 me-2"></i>
                    <span id="header-cart-count">{{ $cartItems->count() }} Produk</span>
                </div>
            </div>

            <div class="cart-list-container mt-3">
                @forelse($cartItems as $item)
                @php $isOutOfStock = $item->product->stok <= 0; @endphp

                    <div class="cart-item d-flex align-items-center gap-3 gap-md-4 {{ $isOutOfStock ? 'out-of-stock' : '' }}">

                    <div class="checkbox-wrapper">
                        <input class="form-check-input item-checkbox shadow-none" type="checkbox"
                            value="{{ $item->id }}"
                            data-price="{{ $item->product->harga }}"
                            {{ $isOutOfStock ? 'disabled' : '' }}
                            onchange="updateTotal()">
                    </div>

                    <div class="img-wrapper shadow-sm">
                        @if($item->product->foto_produk)
                        <img src="{{ asset('storage/'.$item->product->foto_produk) }}" class="img-cart">
                        @else
                        <div class="d-flex align-items-center justify-content-center h-100 bg-light rounded text-muted">
                            <i class="bi bi-image"></i>
                        </div>
                        @endif
                    </div>

                    <div class="flex-grow-1">
                        <p class="mb-1" style="font-size: 10px; font-weight: 700; color: #4caf50; text-transform: uppercase; letter-spacing: 0.8px;">
                            <i class="bi bi-shop me-1"></i> {{ $item->product->user->name }}
                        </p>

                        <p class="item-name mb-1">
                            {{ $item->product->nama_produk }}
                        </p>

                        <div class="d-flex align-items-center gap-1 mb-1">
                            <span class="item-price">
                                Rp {{ number_format($item->product->harga, 0, ',', '.') }}
                            </span>
                            <span class="text-muted" style="font-size: 12px; font-weight: 500;">
                                / {{ $item->product->satuan }}
                            </span>
                        </div>

                        @if($isOutOfStock)
                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1" style="font-size: 10px; font-weight: 600;">Habis</span>
                        @else
                        <span class="text-slate-400" style="font-size: 11px; color: #94a3b8;">Sisa stok: {{ $item->product->stok }}</span>
                        @endif
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        @if(!$isOutOfStock)
                        <div class="qty-box">
                            <button type="button" class="btn-step" onclick="changeQty('{{ $item->id }}', -1)">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" id="qty-{{ $item->id }}" class="input-qty"
                                value="{{ $item->jumlah }}" min="1" max="{{ $item->product->stok }}" readonly>
                            <button type="button" class="btn-step" onclick="changeQty('{{ $item->id }}', 1)">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        @endif

                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="m-0">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn p-2 border-0 btn-delete" onclick="return confirm('Hapus produk ini dari keranjang?')">
                                <i class="bi bi-trash3-fill" style="font-size: 1.1rem;"></i>
                            </button>
                        </form>
                    </div>
            </div>
            @empty
            <div class="py-5 text-center mt-4 bg-white rounded-4 border border-light">
                <i class="bi bi-bag-x mb-3 d-block" style="font-size: 3.5rem; color: #cbd5e1;"></i>
                <h6 class="fw-bold text-slate-700 mb-2">Keranjangmu masih kosong</h6>
                <p class="text-muted small mb-4">Yuk, cari sayur dan buah segar pilihan KWT!</p>
                <a href="{{ route('customer.katalog') }}" class="btn btn-main d-inline-flex" style="width: auto; padding: 10px 24px;">Belanja Sekarang</a>
            </div>
            @endforelse
        </div>
    </div>

    <div class="col-lg-5">
        <div class="summary-side">
            <h6 class="fw-bold mb-4" style="color: #0f172a; font-size: 1.1rem;">Ringkasan Pesanan</h6>

            <div class="d-flex justify-content-between mb-3 text-slate-600" style="font-size: 0.95rem;">
                <span>Total Barang Terpilih</span>
                <span id="count-display" class="fw-semibold">0</span>
            </div>

            <div class="d-flex justify-content-between mb-4 text-slate-600" style="font-size: 0.95rem;">
                <span>Subtotal Produk</span>
                <span id="sub-display" class="fw-semibold">Rp 0</span>
            </div>

            <hr style="border-top: 2px dashed #f1f5f9; margin: 20px 0;">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <span class="fw-bold" style="color: #0f172a;">Total Bayar</span>
                <span class="fw-bold fs-5" style="color: #1e5217;" id="total-display">Rp 0</span>
            </div>

            <button class="btn btn-main" id="btn-checkout" disabled>
                Lanjut Checkout
            </button>

            <div class="mt-4 p-3 rounded-3 d-flex gap-2" style="background: #f8fafc; font-size: 12px; color: #64748b; line-height: 1.6; border: 1px solid #f1f5f9;">
                <i class="bi bi-shield-check text-success fs-5"></i>
                <div>
                    Semua produk berasal dari <strong>Kelompok Wanita Tani (KWT)</strong> yang ditanam dengan metode organik, aman, dan higienis.
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    function changeQty(id, delta) {
        const input = document.getElementById('qty-' + id);
        if (!input) return;

        const max = parseInt(input.getAttribute('max'));
        let current = parseInt(input.value);
        let newVal = current + delta;

        if (newVal >= 1 && newVal <= max) {
            input.value = newVal;
            updateTotal();

            // Sync Database Silently
            fetch(`/cart/update/${id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    jumlah: newVal
                })
            }).catch(err => console.error("Gagal update jumlah di database", err));
        }
    }

    function updateTotal() {
        let total = 0;
        let count = 0;
        let selectedIds = [];

        document.querySelectorAll('.item-checkbox:checked').forEach(cb => {
            const id = cb.value;
            const qInput = document.getElementById('qty-' + id);
            const qty = qInput ? parseInt(qInput.value) : 1;

            total += parseInt(cb.dataset.price) * qty;
            count++;
            selectedIds.push(id);
        });

        const formatted = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('total-display').innerText = formatted;
        document.getElementById('sub-display').innerText = formatted;
        document.getElementById('count-display').innerText = count;

        const btnCheckout = document.getElementById('btn-checkout');
        btnCheckout.disabled = (count === 0);

        if (count > 0) {
            btnCheckout.onclick = function() {
                // Beri efek loading saat diklik
                const originalText = btnCheckout.innerText;
                btnCheckout.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memproses...';
                btnCheckout.disabled = true;

                window.location.href = "{{ route('checkout.index') }}?items=" + selectedIds.join(',');
            };
        }
    }
</script>
@endsection