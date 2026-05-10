@extends('layouts.kwt')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>

    body{
        background:#f5f7f4;
        font-family:'Manrope',sans-serif;
        color:#111827;
    }

    /* CONTAINER */
    .invoice-container{
        max-width:760px;
        margin:35px auto;
        padding:0 18px;
    }

    /* ACTION */
    .top-action{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:18px;
    }

    .back-btn{
        text-decoration:none;
        color:#166534;
        font-size:13px;
        font-weight:700;
    }

    .print-btn{
        border:none;
        background:#111827;
        color:white;
        padding:11px 20px;
        border-radius:999px;
        font-size:13px;
        font-weight:600;
        transition:.3s;
    }

    .print-btn:hover{
        background:#000;
        transform:translateY(-2px);
    }

    /* CARD */
    .invoice-card{
        background:white;
        border-radius:34px;
        overflow:hidden;
        box-shadow:0 18px 60px rgba(0,0,0,0.05);
    }

    /* HEADER */
    .invoice-header{
        background:linear-gradient(135deg,#1b4332,#2d6a4f);
        padding:38px;
        color:white;
        position:relative;
    }

    .invoice-header::after{
        content:'';
        position:absolute;
        width:180px;
        height:180px;
        background:rgba(255,255,255,0.06);
        border-radius:50%;
        top:-70px;
        right:-70px;
    }

    .invoice-header small{
        font-size:11px;
        letter-spacing:2px;
        text-transform:uppercase;
        opacity:.75;
    }

    .invoice-header h2{
        margin-top:10px;
        margin-bottom:6px;
        font-size:28px;
        font-weight:800;
    }

    .invoice-header p{
        margin:0;
        font-size:13px;
        opacity:.9;
    }

    /* BODY */
    .invoice-body{
        padding:32px;
    }

    /* INFO */
    .info-box{
        background:#f8faf8;
        border-radius:22px;
        padding:18px;
    }

    .info-box label{
        display:block;
        font-size:10px;
        font-weight:800;
        text-transform:uppercase;
        letter-spacing:1px;
        color:#9ca3af;
        margin-bottom:6px;
    }

    .info-box div{
        font-size:15px;
        font-weight:700;
    }

    /* PRODUCT */
    .section-title{
        font-size:15px;
        font-weight:800;
        margin-bottom:16px;
    }

    .product-card{
        background:#fafafa;
        border-radius:24px;
        padding:18px 20px;
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:14px;
    }

    .product-info h5{
        font-size:15px;
        font-weight:700;
        margin-bottom:5px;
    }

    .product-info p{
        margin:0;
        font-size:12px;
        color:#6b7280;
    }

    .product-total{
        text-align:right;
    }

    .product-total small{
        display:block;
        font-size:11px;
        color:#9ca3af;
        margin-bottom:4px;
    }

    .product-total strong{
        font-size:15px;
        color:#166534;
    }

    /* SUMMARY */
    .summary-box{
        margin-top:28px;
        background:#1b4332;
        color:white;
        border-radius:28px;
        padding:26px;
    }

    .summary-item{
        display:flex;
        justify-content:space-between;
        margin-bottom:14px;
        font-size:14px;
        opacity:.9;
    }

    .summary-total{
        margin-top:18px;
        padding-top:18px;
        border-top:1px solid rgba(255,255,255,.15);
        display:flex;
        justify-content:space-between;
        align-items:center;
    }

    .summary-total label{
        font-size:15px;
        font-weight:700;
    }

    .summary-total strong{
        font-size:30px;
        font-weight:800;
    }

    /* DELIVERY */
    .delivery-box{
        margin-top:22px;
        background:#f8faf8;
        border-radius:24px;
        padding:20px;
    }

    .delivery-top{
        display:flex;
        align-items:center;
        gap:14px;
    }

    .delivery-icon{
        width:52px;
        height:52px;
        border-radius:18px;
        background:white;
        display:flex;
        align-items:center;
        justify-content:center;
        color:#16a34a;
        font-size:20px;
    }

    .delivery-info label{
        display:block;
        font-size:10px;
        font-weight:800;
        letter-spacing:1px;
        text-transform:uppercase;
        color:#9ca3af;
        margin-bottom:4px;
    }

    .delivery-info div{
        font-size:14px;
        font-weight:700;
    }

    .delivery-phone{
        display:block;
        margin-top:6px;
        color:#6b7280;
        font-size:12px;
    }

    .note-box{
        margin-top:14px;
        background:white;
        border-radius:16px;
        padding:14px;
        font-size:12px;
        color:#6b7280;
        font-style:italic;
    }

    /* FOOTER */
    .footer{
        margin-top:30px;
        text-align:center;
    }

    .footer small{
        display:block;
        font-size:11px;
        color:#9ca3af;
        margin-bottom:6px;
    }

    .footer strong{
        font-size:14px;
        color:#166534;
    }

    @media(max-width:576px){

        .product-card{
            flex-direction:column;
            align-items:flex-start;
            gap:10px;
        }

        .product-total{
            text-align:left;
        }

        .top-action{
            flex-direction:column;
            align-items:flex-start;
            gap:12px;
        }

    }

    @media print{

        .no-print{
            display:none !important;
        }

        body{
            background:white;
        }

        .invoice-container{
            max-width:100%;
            margin:0;
        }

        .invoice-card{
            box-shadow:none;
        }

    }

</style>

<div class="invoice-container">

    <!-- ACTION -->
    <div class="top-action no-print">

        <a href="{{ route('kwt.orders') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

        <button onclick="window.print()" class="print-btn">
            <i class="bi bi-printer"></i>
            Cetak Invoice
        </button>

    </div>

    <!-- CARD -->
    <div class="invoice-card">

        <!-- HEADER -->
        <div class="invoice-header">

            <small>Invoice Pesanan</small>

            <h2>Kelompok Wanita Tani</h2>

            <p>
                Order #{{ $order->id }}
            </p>

        </div>

        <!-- BODY -->
        <div class="invoice-body">

            <!-- INFO -->
            <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">

                <div class="info-box" style="min-width:220px;">
                    <label>Customer</label>
                    <div>{{ $order->user->name }}</div>
                </div>

                <div class="info-box text-end" style="min-width:220px;">
                    <label>Tanggal</label>
                    <div>{{ $order->created_at->format('d F Y') }}</div>
                </div>

            </div>

            <!-- PRODUCT -->
            <div class="section-title">
                Produk Pesanan
            </div>

            @foreach($order->details as $detail)

            <div class="product-card">

                <div class="product-info">

                    <h5>
                        {{ $detail->product->nama_produk }}
                    </h5>

                    <p>
                        {{ $detail->jumlah }} {{ $detail->product->satuan }}
                        ×
                        Rp {{ number_format($detail->harga_saat_ini,0,',','.') }}
                    </p>

                </div>

                <div class="product-total">

                    <small>Subtotal</small>

                    <strong>
                        Rp {{ number_format($detail->harga_saat_ini * $detail->jumlah,0,',','.') }}
                    </strong>

                </div>

            </div>

            @endforeach

            <!-- SUMMARY -->
            <div class="summary-box">

                <div class="summary-item">
                    <span>Subtotal</span>

                    <span>
                        Rp {{ number_format($order->total_harga - $order->ongkir,0,',','.') }}
                    </span>
                </div>

                <div class="summary-item">
                    <span>Ongkir</span>

                    <span>
                        Rp {{ number_format($order->ongkir,0,',','.') }}
                    </span>
                </div>

                <div class="summary-total">

                    <label>Total Bayar</label>

                    <strong>
                        Rp {{ number_format($order->total_harga,0,',','.') }}
                    </strong>

                </div>

            </div>

            <!-- DELIVERY -->
            <div class="delivery-box">

                <div class="delivery-top">

                    <div class="delivery-icon">
                        <i class="bi bi-truck"></i>
                    </div>

                    <div class="delivery-info">

                        <label>Pengiriman</label>

                        <div>
                            {{ $order->kurir ?? 'Pengiriman Internal KWT' }}
                        </div>

                        <small class="delivery-phone">
                            No. Pengirim :
                            {{ $order->no_pengirim ?? '08xxxxxxxxxx' }}
                        </small>

                    </div>

                </div>

                @if($order->catatan)

                <div class="note-box">
                    "{{ $order->catatan }}"
                </div>

                @endif

            </div>

            <!-- FOOTER -->
            <div class="footer">

                <small>Tanda Tangan</small>

                <strong>{{ Auth::user()->name }}</strong>

            </div>

        </div>

    </div>

</div>

@endsection