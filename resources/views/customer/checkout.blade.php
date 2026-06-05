@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8faf9;
        color: #334155;
    }

    .text-success-kwt {
        color: #1e5217 !important;
        letter-spacing: -0.5px;
        font-weight: 800;
        font-size: 1.4rem;
    }

    .checkout-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        border: 1px solid rgba(0, 0, 0, 0.03);
    }

    .shop-group-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        border: 1px solid rgba(0, 0, 0, 0.03);
    }

    .shop-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 16px;
        border-bottom: 1px dashed #f1f5f9;
        margin-bottom: 16px;
    }

    .shop-badge {
        background: #e8f5e9;
        color: #1e5217;
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 700;
        border: 1px solid #c8e6c9;
    }

    .shop-name {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
    }

    .product-row {
        padding: 16px 0;
        border-bottom: 1px solid #f8fafc;
    }

    .product-row:last-child {
        border-bottom: none;
    }

    .product-img {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border: 1px solid #f1f5f9;
        border-radius: 10px;
        padding: 2px;
    }

    .shipping-section {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        margin-top: 16px;
    }

    .shipping-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #4caf50;
    }

    .summary-side {
        background: #ffffff;
        border-radius: 20px;
        padding: 28px;
        position: sticky;
        top: 24px;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.02);
    }

    .input-catatan-kwt {
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 0.85rem;
        color: #334155;
        transition: all 0.2s ease-in-out;
        resize: none;
    }

    .input-catatan-kwt::placeholder {
        color: #94a3b8;
    }

    .input-catatan-kwt:focus {
        background-color: #ffffff;
        border-color: #4caf50;
        box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        outline: none;
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
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-main:hover:not(:disabled) {
        background: #1e5217;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(30, 82, 23, 0.25);
    }

    .btn-main:active:not(:disabled) {
        transform: scale(0.98);
    }

    .btn-main:disabled {
        background: #e2e8f0;
        color: #94a3b8;
        box-shadow: none;
        cursor: not-allowed;
    }

    .info-label {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Penyesuaian Mobile */
    @media (max-width: 768px) {

        .checkout-card,
        .shop-group-card {
            padding: 16px;
            border-radius: 12px;
        }

        .summary-side {
            padding: 20px;
            position: static;
            margin-top: 10px;
            border-radius: 16px;
        }

        .product-img {
            width: 56px;
            height: 56px;
        }
    }
</style>

<div class="container py-4 py-md-5">
    <div class="row g-4">

        {{-- SEKTOR KIRI: DETAIL ALAMAT & ITEM PER TOKO --}}
        <div class="col-lg-7">
            <div class="d-flex align-items-center mb-4 pb-2 border-bottom border-light">
                <i class="bi bi-cart-check-fill fs-3 text-success me-3"></i>
                <h4 class="text-success-kwt mb-0">Checkout</h4>
            </div>

            {{-- 1. BLOK ALAMAT PENGIRIMAN --}}
            <div class="checkout-card mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-geo-alt-fill text-success fs-5"></i>
                    <h6 class="m-0 fw-bold" style="color: #0f172a; font-size: 1.05rem;">Alamat Pengiriman</h6>
                </div>

                @php
                $user = Auth::user();
                $isDataComplete = !empty($user->name) && !empty($user->phone_number) && !empty($user->address);

                $groupedItems = $cartItems->groupBy(function($item) {
                return $item->product->user->id;
                });
                $jumlahToko = count($groupedItems);

                $totalSemuaProduk = 0;
                $totalSemuaOngkirSetelahDiskon = 0;
                $totalSemuaPotonganOngkir = 0;
                @endphp

                @if($isDataComplete)
                <div class="row g-2 text-sm" style="font-size: 0.95rem;">
                    <div class="col-md-3 fw-bold text-dark">
                        {{ $user->name }} <br>
                        <span class="text-secondary fw-medium" style="font-size: 0.85rem;">{{ $user->phone_number }}</span>
                    </div>
                    <div class="col-md-9 text-slate-600">
                        <span class="text-dark fw-medium">{{ $user->address }}</span> <br>
                        Kec. {{ $user->district ?? '-' }}, {{ $user->city ?? '-' }}, {{ $user->province ?? '-' }}
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            <span class="badge bg-light text-dark border px-2 py-1">RT/RW: {{ $user->rt ?? '0' }}/{{ $user->rw ?? '0' }}</span>
                            <span class="badge bg-light text-dark border px-2 py-1">Patokan: {{ $user->address_detail ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                @else
                <div class="alert bg-danger-subtle border-0 rounded-3 p-3 mb-0">
                    <div class="d-flex align-items-center gap-2 text-danger mb-2">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        <span class="fw-bold">Alamat Utama Belum Lengkap</span>
                    </div>
                    <p class="small text-danger opacity-75 mb-3">Harap lengkapi Nama, WhatsApp, dan detail alamat pengiriman Anda pada halaman profil agar kami dapat mengirimkan pesanan.</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-danger btn-sm fw-bold px-3 py-2 rounded-2 shadow-sm">Lengkapi Profil <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
                @endif
            </div>

            {{-- 2. DAFTAR ITEM PER TOKO --}}
            @foreach($groupedItems as $shopId => $items)
            @php
            $shop = $items->first()->product->user;
            $subtotalShop = $items->sum(fn($i) => $i->jumlah * $i->product->harga);
            $totalSemuaProduk += $subtotalShop;

            $ongkirAsliPerToko = $ongkir;

            if($jumlahToko > 1) {
            $ongkirFinalPerToko = $ongkirAsliPerToko / $jumlahToko;
            } else {
            $ongkirFinalPerToko = $ongkirAsliPerToko;
            }

            $potonganOngkirPerToko = $ongkirAsliPerToko - $ongkirFinalPerToko;
            $totalSemuaOngkirSetelahDiskon += $ongkirFinalPerToko;
            $totalSemuaPotonganOngkir += $potonganOngkirPerToko;
            @endphp

            <div class="shop-group-card mb-4">
                <div class="shop-header">
                    <span class="shop-badge"><i class="bi bi-shop me-1"></i> KWT</span>
                    <span class="shop-name">{{ $shop->name ?? 'Kelompok Wanita Tani' }}</span>
                </div>

                @foreach($items as $item)
                <div class="product-row d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div class="d-flex align-items-center gap-3 w-100 w-md-auto">
                        <img src="{{ asset('storage/'.$item->product->foto_produk) }}" class="product-img shadow-sm">
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $item->product->nama_produk }}</div>
                            <div class="text-muted mt-1" style="font-size: 0.8rem;">
                                {{ $item->jumlah }} {{ $item->product->satuan }} x Rp {{ number_format($item->product->harga, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="fw-bold text-dark ms-auto ms-md-0 text-nowrap" style="font-size: 0.95rem;">
                        Rp {{ number_format($item->jumlah * $item->product->harga, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach

                <div class="shipping-section shadow-sm">
                    <div class="row align-items-center g-3">
                        <div class="col-md-5 col-12">
                            <span class="shipping-title d-block mb-1"><i class="bi bi-truck me-2"></i>Opsi Pengiriman</span>
                            <div class="small text-dark fw-semibold">Kurir Lokal KWT</div>
                            <div class="text-muted mt-1" style="font-size: 0.75rem;">Jarak ke rumah: {{ $jarak ?? 0 }} km</div>
                        </div>
                        <div class="col-md-7 col-12 text-md-end text-start">
                            <div class="small">
                                <span class="text-secondary d-block d-md-inline mb-1 mb-md-0 me-md-2">Ongkos Kirim:</span>
                                @if($potonganOngkirPerToko > 0)
                                <span class="text-decoration-line-through text-muted me-2" style="font-size: 0.85rem;">
                                    Rp {{ number_format($ongkirAsliPerToko, 0, ',', '.') }}
                                </span>
                                <span class="fw-bold text-success fs-6">
                                    Rp {{ number_format($ongkirFinalPerToko, 0, ',', '.') }}
                                </span>
                                <div class="text-success fw-medium mt-1 bg-success-subtle d-inline-block px-2 py-1 rounded" style="font-size: 0.75rem;">
                                    <i class="bi bi-tags-fill me-1"></i> Diskon Multi-Toko -Rp {{ number_format($potonganOngkirPerToko, 0, ',', '.') }}
                                </div>
                                @else
                                <span class="fw-bold text-dark fs-6">
                                    Rp {{ number_format($ongkirFinalPerToko, 0, ',', '.') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-light">
                    <span class="text-slate-600 fw-medium" style="font-size: 0.95rem;">Subtotal Toko</span>
                    <span class="fw-bold text-success fs-5">Rp {{ number_format(($subtotalShop + $ongkirFinalPerToko), 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- SEKTOR KANAN: RINGKASAN PEMBAYARAN KASIR --}}
        <div class="col-lg-5">
            <div class="summary-side">
                <h6 class="fw-bold mb-4" style="color: #0f172a; font-size: 1.1rem;">Ringkasan Belanja</h6>

                @php
                $settingData = \App\Models\Setting::first();
                $batasQty = $settingData->batas_jumlah_barang ?? 0;
                $qtyTotal = $cartItems->sum('jumlah');
                $adaEkstraVolume = ($batasQty > 0 && $qtyTotal > $batasQty);
                @endphp

                <div class="d-flex justify-content-between mb-3 text-slate-600" style="font-size: 0.95rem;">
                    <span>Total Harga Produk</span>
                    <span class="text-dark fw-semibold">Rp {{ number_format($totalSemuaProduk, 0, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between mb-3 text-slate-600" style="font-size: 0.95rem;">
                    <span>
                        Total Ongkos Kirim
                        @if($adaEkstraVolume)
                        <div class="text-info mt-1" style="font-size: 0.75rem; background: #f0fdf4; padding: 4px 8px; border-radius: 6px;">
                            <i class="bi bi-info-circle me-1"></i> Termasuk biaya volume besar
                        </div>
                        @endif
                    </span>
                    <span class="text-dark fw-semibold">
                        @if($isDataComplete)
                        Rp {{ number_format(($totalSemuaOngkirSetelahDiskon + $totalSemuaPotonganOngkir), 0, ',', '.') }}
                        @else
                        <span class="text-danger small">Menunggu Alamat</span>
                        @endif
                    </span>
                </div>

                @if($totalSemuaPotonganOngkir > 0)
                <div class="d-flex justify-content-between mb-3 text-success" style="font-size: 0.95rem;">
                    <span class="fw-medium"><i class="bi bi-patch-check-fill me-1"></i> Diskon Pengiriman</span>
                    <span class="fw-bold">-Rp {{ number_format($totalSemuaPotonganOngkir, 0, ',', '.') }}</span>
                </div>
                @endif

                <div class="d-flex justify-content-between mb-4 text-slate-600" style="font-size: 0.95rem;">
                    <span>Biaya Layanan Aplikasi</span>
                    <span class="text-dark fw-semibold">Rp {{ number_format($biayaLayanan ?? 0, 0, ',', '.') }}</span>
                </div>

                <hr style="border-top: 2px dashed #f1f5f9; margin: 20px 0;">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold" style="color: #0f172a;">Total Tagihan</span>
                    <span class="fw-bold fs-4" style="color: #1e5217;">Rp {{ number_format(($totalSemuaProduk + $totalSemuaOngkirSetelahDiskon + ($biayaLayanan ?? 0)), 0, ',', '.') }}</span>
                </div>

                <form id="checkoutForm">
                    @csrf
                    <input type="hidden" id="item_ids" value="{{ implode(',', $cartItems->pluck('id')->toArray()) }}">
                    <input type="hidden" id="ongkir" value="{{ $totalSemuaOngkirSetelahDiskon }}">
                    <input type="hidden" id="jarak" value="{{ $jarak ?? 0 }}">

                    <div class="mb-4">
                        <label class="info-label mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-pencil-square text-success"></i> Catatan Pesanan <span class="text-muted fw-normal text-lowercase">(opsional)</span>
                        </label>
                        <textarea id="catatan" class="form-control input-catatan-kwt" rows="2" placeholder="Cth: Titip di satpam, rumah warna putih pagar hitam, dll..."></textarea>
                    </div>

                    <button type="button" id="payButton" class="btn btn-main" {{ $isDataComplete ? '' : 'disabled' }}>
                        @if($isDataComplete)
                        <i class="bi bi-shield-lock-fill"></i> Bayar Sekarang
                        @else
                        <i class="bi bi-exclamation-circle-fill"></i> Lengkapi Alamat Dulu
                        @endif
                    </button>
                </form>

                <div class="mt-4 p-3 rounded-3 d-flex gap-3 align-items-start" style="background: #f8fafc; border: 1px solid #f1f5f9;">
                    <i class="bi bi-award-fill text-success fs-3 lh-1"></i>
                    <div style="font-size: 0.8rem; color: #64748b; line-height: 1.5;">
                        <strong class="text-dark d-block mb-1" style="font-size: 0.85rem;">Panen Hasil Sendiri</strong>
                        Belanjaanmu langsung dari kebun ibu-ibu KWT. Diproses higienis, aman, dan turut memberdayakan ekonomi lokal!
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    document.getElementById('payButton').addEventListener('click', function(e) {
        e.preventDefault();

        const button = this;
        button.disabled = true;
        button.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Memproses Pembayaran...`;

        const formData = {
            _token: "{{ csrf_token() }}",
            item_ids: document.getElementById('item_ids').value,
            ongkir: document.getElementById('ongkir').value,
            jarak: document.getElementById('jarak').value,
            catatan: document.getElementById('catatan').value
        };

        fetch("{{ route('checkout.process') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = "{{ url('/riwayat-pesanan') }}/" + data.order_id;
                        },
                        onPending: function(result) {
                            window.location.href = "{{ url('/riwayat-pesanan') }}/" + data.order_id;
                        },
                        onError: function(result) {
                            alert("Pembayaran gagal! Harap coba kembali.");
                            button.disabled = false;
                            button.innerHTML = `<i class="bi bi-shield-lock-fill"></i> Bayar Sekarang`;
                        },
                        onClose: function() {
                            alert('Anda menutup halaman pembayaran sebelum menyelesaikan transaksi.');
                            window.location.href = "{{ url('/riwayat-pesanan') }}/" + data.order_id;
                        }
                    });
                } else {
                    alert(data.message || "Gagal memproses pembuatan pesanan.");
                    button.disabled = false;
                    button.innerHTML = `<i class="bi bi-shield-lock-fill"></i> Bayar Sekarang`;
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Terjadi kesalahan pada jaringan server.");
                button.disabled = false;
                button.innerHTML = `<i class="bi bi-shield-lock-fill"></i> Bayar Sekarang`;
            });
    });
</script>
@endsection