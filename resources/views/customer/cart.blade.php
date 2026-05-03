@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #fcfcfc;
        color: #333;
    }

    /* List Area */
    .cart-item {
        border-bottom: 1px solid #eee;
        padding: 20px 0;
        transition: 0.2s;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    /* Image Slim */
    .img-wrapper {
        width: 70px;
        height: 70px;
        flex-shrink: 0;
    }

    .img-cart {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }

    .qty-box {
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        background: #fff;
        height: 32px;
        width: fit-content;
        padding: 0 4px;
    }

    .btn-step {
        width: 20px;
        height: 100%;
        border: none;
        background: #fff;
        color: #666;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: 0.2s;
    }

    .btn-step:hover {
        background: #f8f9fa;
        color: #28a745;
    }

    .input-qty::-webkit-outer-spin-button,
    .input-qty::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .text-success-kwt {
        color: #28a745 !important;
        letter-spacing: -0.5px;
        font-weight: 900;
        font-size: 1.25rem;
    }

    .border-bottom {
        border-bottom: 2px solid #f1f1f1 !important;
    }

    .badge-cart-count {
        background-color: #ffffff;
        color: #28a745;
        border: 1px solid #e8f5e9;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.08);
    }

    .badge-cart-count i {
        font-size: 14px;
    }

    .input-qty {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        vertical-align: middle;
        width: 35px;
        height: 32px;
        border: none;
        font-size: 14px;
        font-weight: 700;
        background: transparent;
        padding: 0;
        margin: 0;
        line-height: 32px;
    }

    /* Checkout Sidebar */
    .summary-side {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 24px;
        position: sticky;
        top: 20px;
    }

    .btn-main {
        background: #28a745;
        border: none;
        color: white;
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
        width: 100%;
        transition: 0.3s;
    }

    .btn-main:disabled {
        background: #e0e0e0;
        cursor: not-allowed;
    }

    .item-name {
        font-size: 14px;
        font-weight: 600;
        margin: 0;
        color: #2d3436;
    }

    .item-price {
        font-size: 13px;
        color: #28a745;
        font-weight: 600;
    }

    /* Custom Checkbox Slim */
    .form-check-input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }

    .out-of-stock {
        opacity: 0.5;
    }
</style>

<div class="container py-5">
    <div class="row g-5">
        <!-- Main Content -->
        <div class="col-lg-7">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                <h5 class="text-success-kwt">Keranjang Belanja</h5>
                <div class="badge-cart-count">
                    <i class="bi bi-cart3 me-2"></i>
                    <span>{{ $cartItems->count() }} Produk</span>
                </div>
            </div>

            @forelse($cartItems as $item)
            @php $isOutOfStock = $item->product->stok <= 0; @endphp
                <div class="cart-item d-flex align-items-center gap-3 {{ $isOutOfStock ? 'out-of-stock' : '' }}">
                <!-- Checkbox -->
                <div class="form-check">
                    <input class="form-check-input item-checkbox shadow-none" type="checkbox"
                        value="{{ $item->id }}"
                        data-price="{{ $item->product->harga }}"
                        {{ $isOutOfStock ? 'disabled' : '' }}
                        onchange="updateTotal()">
                </div>

                <!-- Product Img -->
                <div class="img-wrapper">
                    <img src="{{ asset('storage/'.$item->product->foto_produk) }}" class="img-cart">
                </div>

                <div class="flex-grow-1">
                    <p class="mb-0" style="font-size: 10px; font-weight: 700; color: #28a745; text-transform: uppercase; letter-spacing: 0.5px;">
                        {{ $item->product->user->name }}
                    </p>

                    <p class="item-name" style="font-size: 15px; font-weight: 600; color: #2d3436; margin-bottom: 2px;">
                        {{ $item->product->nama_produk }}
                    </p>

                    <div class="d-flex align-items-center gap-1">
                        <span class="item-price" style="font-size: 14px; color: #28a745; font-weight: 700;">
                            Rp {{ number_format($item->product->harga, 0, ',', '.') }}
                        </span>
                        <span class="text-muted" style="font-size: 12px;">
                            / {{ $item->product->satuan }}
                        </span>
                    </div>

                    @if($isOutOfStock)
                    <span class="text-danger" style="font-size: 11px; font-weight: 600;">Produk sudah habis</span>
                    @else
                    <span class="text-muted" style="font-size: 11px;">Tersedia: {{ $item->product->stok }} {{ $item->product->satuan }}</span>
                    @endif
                </div>

                <!-- Actions -->
                <div class="d-flex align-items-center gap-3">
                    @if(!$isOutOfStock)
                    <div class="qty-box">
                        <button type="button" class="btn-step" onclick="changeQty('{{ $item->id }}', -1)">-</button>
                        <input type="number" id="qty-{{ $item->id }}" class="input-qty"
                            value="{{ $item->jumlah }}" min="1" max="{{ $item->product->stok }}" readonly>
                        <button type="button" class="btn-step" onclick="changeQty('{{ $item->id }}', 1)">+</button>
                    </div>
                    @endif

                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn p-0 border-0 text-muted" onclick="return confirm('Hapus?')">
                            <i class="bi bi-x-lg" style="font-size: 14px;"></i>
                        </button>
                    </form>
                </div>
        </div>
        @empty
        <div class="py-5 text-center">
                <i class="bi bi-cart-x" style="font-size: 3rem; color: #ccc;"></i>
                <p class="text-muted small">Keranjang kosong.</p>
            <a href="{{ route('customer.katalog') }}" class="btn btn-outline-success btn-sm">Mulai Belanja</a>
        </div>
        @endforelse
    </div>

    <!-- Sidebar Summary -->
    <div class="col-lg-5">
        <div class="summary-side shadow-sm">
            <h6 class="fw-bold mb-4">Ringkasan Pesanan</h6>

            <div class="d-flex justify-content-between mb-2 small">
                <span class="text-muted">Total Barang</span>
                <span id="count-display">0</span>
            </div>

            <div class="d-flex justify-content-between mb-4 small">
                <span class="text-muted">Subtotal</span>
                <span id="sub-display">Rp 0</span>
            </div>

            <hr style="border-top: 1px dashed #eee;">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <span class="fw-bold small">Total Bayar</span>
                <h5 class="fw-bold text-success m-0" id="total-display">Rp 0</h5>
            </div>

            <button class="btn btn-main shadow-sm" id="btn-checkout" disabled>
                Lanjut Checkout
            </button>

            <div class="mt-4 p-3 bg-light rounded-3" style="font-size: 11px; line-height: 1.6;">
                <i class="bi bi-shield-check text-success"></i> Semua produk berasal dari <strong>Kelompok Wanita Tani (KWT)</strong> yang dikelola secara organik dan higienis.
            </div>
        </div>
    </div>
</div>
</div>

<script>
    function changeQty(id, delta) {
        const input = document.getElementById('qty-' + id);
        const max = parseInt(input.getAttribute('max'));
        let current = parseInt(input.value);
        let newVal = current + delta;

        if (newVal >= 1 && newVal <= max) {
            input.value = newVal;
            updateTotal();

            // Sync Database
            fetch(`/cart/update/${id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    jumlah: newVal
                })
            });
        }
    }

    function updateTotal() {
        let total = 0;
        let count = 0;

        document.querySelectorAll('.item-checkbox:checked').forEach(cb => {
            const id = cb.value;
            const qInput = document.getElementById('qty-' + id);
            const qty = qInput ? parseInt(qInput.value) : 0;
            total += parseInt(cb.dataset.price) * qty;
            count++;
        });

        const formatted = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('total-display').innerText = formatted;
        document.getElementById('sub-display').innerText = formatted;
        document.getElementById('count-display').innerText = count;
        document.getElementById('btn-checkout').disabled = (count === 0);
    }
</script>
@endsection