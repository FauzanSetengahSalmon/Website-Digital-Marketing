<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Batch Invoice KWT</title>
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }
        .invoice-card {
            max-width: 800px;
            margin: 0 auto 50px auto;
            padding: 30px;
            border: 1px solid #eee;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            page-break-after: always;
        }
        .invoice-card:last-child {
            page-break-after: avoid;
            margin-bottom: 0;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #2e7d32;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .logo-title h2 {
            margin: 0;
            color: #2e7d32;
            font-weight: 800;
            font-size: 24px;
        }
        .logo-title p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #666;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h1 {
            margin: 0;
            color: #333;
            font-size: 28px;
            font-weight: 700;
        }
        .invoice-title p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #888;
        }
        .details-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 20px;
        }
        .details-block {
            flex: 1;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #2e7d32;
        }
        .details-block h4 {
            margin: 0 0 10px 0;
            color: #2e7d32;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .details-block p {
            margin: 5px 0;
            font-size: 13px;
        }
        .kwt-section {
            margin-top: 25px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .kwt-header {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 10px 15px;
            font-weight: bold;
            font-size: 14px;
            border-bottom: 1px solid #e0e0e0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        th, td {
            padding: 12px 15px;
            font-size: 13px;
        }
        th {
            background-color: #f5f5f5;
            font-weight: 600;
            color: #555;
            border-bottom: 1px solid #e0e0e0;
        }
        td {
            border-bottom: 1px solid #f0f0f0;
        }
        .summary-table {
            margin-top: 20px;
            width: 50%;
            margin-left: auto;
        }
        .summary-table td {
            padding: 8px 15px;
            border: none;
        }
        .summary-table tr.total {
            font-weight: bold;
            font-size: 16px;
            color: #2e7d32;
            border-top: 2px solid #2e7d32;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .btn-print {
            display: inline-block;
            background-color: #2e7d32;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin-bottom: 20px;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-print:hover {
            background-color: #1b5e20;
        }
        @media print {
            body {
                padding: 0;
            }
            .invoice-card {
                border: none;
                box-shadow: none;
                padding: 0;
                margin: 0;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div style="text-align: center;">
        <button onclick="window.print()" class="btn-print">Cetak Semua Invoice ({{ count($allGrouped) }})</button>
    </div>

    @foreach($allGrouped as $orderId => $data)
        @php 
            $sale = $data['sale'];
            $groupedByKwt = $data['grouped'];
        @endphp
        <div class="invoice-card">
            <div class="header">
                <div class="logo-title">
                    <h2>KWT DESA CIBIRU WETAN</h2>
                    <p>E-Commerce & Pertanian Lokal Desa Cibiru Wetan</p>
                </div>
                <div class="invoice-title">
                    <h1>INVOICE KWT</h1>
                    <p>No: #INV-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>

            <div class="details-container">
                <div class="details-block">
                    <h4>Informasi Pelanggan</h4>
                    <p><strong>Nama:</strong> {{ $sale->user->name }}</p>
                    <p><strong>No. HP:</strong> {{ $sale->nomor_hp }}</p>
                    <p><strong>Alamat:</strong> {{ $sale->alamat }}</p>
                </div>
                <div class="details-block">
                    <h4>Detail Transaksi</h4>
                    <p><strong>Tanggal Order:</strong> {{ $sale->created_at->format('d M Y H:i') }}</p>
                    <p><strong>Status Pembayaran:</strong> <span style="color: #2e7d32; font-weight: bold;">Lunas (Midtrans)</span></p>
                    <p><strong>Status Pengiriman:</strong> {{ ucfirst($sale->status) }}</p>
                    @if($sale->jadwal_pengiriman)
                        <p><strong>Jadwal Kirim:</strong> {{ \Carbon\Carbon::parse($sale->jadwal_pengiriman)->format('d M Y') }}</p>
                    @endif
                </div>
            </div>

            @foreach($groupedByKwt as $kwtName => $details)
                <div class="kwt-section">
                    <div class="kwt-header">
                        Kelompok Wanita Tani: {{ $kwtName }}
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50%;">Nama Produk</th>
                                <th style="text-align: center; width: 15%;">Harga</th>
                                <th style="text-align: center; width: 15%;">Jumlah</th>
                                <th style="text-align: right; width: 20%;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $subtotalKwt = 0; @endphp
                            @foreach($details as $detail)
                                @php 
                                    $rowTotal = $detail->harga_saat_ini * $detail->jumlah;
                                    $subtotalKwt += $rowTotal;
                                @endphp
                                <tr>
                                    <td>{{ $detail->product->nama_produk ?? 'Produk Terhapus' }}</td>
                                    <td style="text-align: center;">Rp {{ number_format($detail->harga_saat_ini, 0, ',', '.') }}</td>
                                    <td style="text-align: center;">{{ $detail->jumlah }}</td>
                                    <td style="text-align: right;">Rp {{ number_format($rowTotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr style="background-color: #fafafa; font-weight: bold;">
                                <td colspan="3" style="text-align: right; padding: 10px 15px;">Subtotal {{ $kwtName }}:</td>
                                <td style="text-align: right; padding: 10px 15px; color: #2e7d32;">Rp {{ number_format($subtotalKwt, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach

            <table class="summary-table">
                <tr>
                    <td>Subtotal Produk:</td>
                    <td style="text-align: right;">Rp {{ number_format($sale->details->sum(fn($d) => $d->harga_saat_ini * $d->jumlah), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Biaya Pengiriman:</td>
                    <td style="text-align: right;">Rp {{ number_format($sale->ongkir, 0, ',', '.') }}</td>
                </tr>
                <tr class="total">
                    <td>Total Bayar:</td>
                    <td style="text-align: right;">Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</td>
                </tr>
            </table>

            <div class="footer">
                <p>Terima kasih telah membeli produk lokal Kelompok Wanita Tani (KWT) Desa Cibiru Wetan.</p>
                <p>&copy; {{ date('Y') }} KWT Desa Cibiru Wetan. All rights reserved.</p>
            </div>
        </div>
    @endforeach

</body>
</html>
