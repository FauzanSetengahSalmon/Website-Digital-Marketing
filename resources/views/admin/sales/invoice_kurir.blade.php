<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan Kurir - #INV-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
            background-color: #f4f6f9;
            /* Latar abu-abu halus agar invoice terlihat menonjol di layar */
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            background-color: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #0288d1;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .logo-title h2 {
            margin: 0;
            color: #0288d1;
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
            border-left: 4px solid #0288d1;
        }

        .details-block h4 {
            margin: 0 0 10px 0;
            color: #0288d1;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .details-block p {
            margin: 5px 0;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        th,
        td {
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

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            text-align: center;
        }

        .signature-block {
            flex: 1;
            max-width: 200px;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            padding-top: 5px;
            font-weight: bold;
            font-size: 13px;
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
            background-color: #0288d1;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin-bottom: 20px;
            border: none;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            box-shadow: 0 4px 6px rgba(2, 136, 209, 0.2);
        }

        .btn-print:hover {
            background-color: #01579b;
            transform: translateY(-2px);
        }

        .btn-print:active {
            transform: translateY(0);
        }

        /* TAMPILAN RESPONSIVE KHUSUS LAYAR HP */
        @media screen and (max-width: 768px) {
            body {
                padding: 10px;
            }

            .invoice-box {
                padding: 20px 15px;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .invoice-title {
                text-align: left;
            }

            .details-container {
                flex-direction: column;
                gap: 15px;
            }

            /* Styling khusus wrapper tabel agar bisa digeser */
            .table-responsive {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table-responsive::-webkit-scrollbar {
                height: 6px;
            }

            .table-responsive::-webkit-scrollbar-thumb {
                background-color: rgba(0, 0, 0, 0.15);
                border-radius: 10px;
            }

            table {
                min-width: 500px;
                /* Menjaga tabel tidak hancur tergencet */
            }

            .signatures {
                flex-wrap: wrap;
                justify-content: space-around;
                gap: 30px;
                margin-top: 30px;
            }

            .signature-block {
                min-width: 140px;
            }
        }

        /* FORMAT SAAT DICETAK KE KERTAS ATAU PDF */
        @media print {
            body {
                padding: 0;
                background-color: #fff;
            }

            .invoice-box {
                border: none;
                box-shadow: none;
                padding: 0;
                margin: 0;
                max-width: 100%;
            }

            .btn-print {
                display: none;
            }

            /* Hilangkan batas scroll saat print agar semua data tercetak */
            .table-responsive {
                overflow: visible !important;
            }
        }
    </style>
</head>

<body>

    <div style="text-align: center;">
        <button onclick="window.print()" class="btn-print">🖨️ Cetak Surat Jalan</button>
    </div>

    <div class="invoice-box">
        <div class="header">
            <div class="logo-title">
                <h2>KWT DESA CIBIRU WETAN</h2>
                <p>E-Commerce & Pertanian Lokal Desa Cibiru Wetan</p>
            </div>
            <div class="invoice-title">
                <h1>SURAT JALAN KURIR</h1>
                <p>No: #INV-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <div class="details-container">
            <div class="details-block">
                <h4>Penerima / Customer</h4>
                <p><strong>Nama:</strong> {{ $sale->user->name }}</p>
                <p><strong>No. HP:</strong> {{ $sale->nomor_hp }}</p>
                <p><strong>Alamat Kirim:</strong> {{ $sale->alamat }}</p>
            </div>
            <div class="details-block">
                <h4>Informasi Pengiriman</h4>
                <p><strong>Kurir Penanggung Jawab:</strong> {{ $sale->kurir ?? '-' }}</p>
                <p><strong>No. HP Kurir:</strong> {{ $sale->no_hp_kurir ?? '-' }}</p>
                <p><strong>Jadwal Pengiriman:</strong> {{ $sale->jadwal_pengiriman ? \Carbon\Carbon::parse($sale->jadwal_pengiriman)->format('d M Y') : '-' }}</p>
                <p><strong>Catatan Pembeli:</strong> <span style="font-style: italic;">"{{ $sale->catatan ?? 'Tidak ada catatan' }}"</span></p>
            </div>
        </div>

        {{-- Dibungkus dengan table-responsive --}}
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 10%; text-align: center;">No.</th>
                        <th style="width: 50%;">Nama Produk / Hasil Panen</th>
                        <th style="width: 20%; text-align: center;">Jumlah Item</th>
                        <th style="width: 20%;">Asal KWT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->details as $index => $detail)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td><strong>{{ $detail->product->nama_produk ?? 'Produk Terhapus' }}</strong></td>
                        <td style="text-align: center; font-size: 14px;"><strong>{{ $detail->jumlah }}</strong> pcs</td>
                        <td>{{ $detail->product->user->name ?? 'KWT Umum' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="signatures">
            <div class="signature-block">
                <p>Penerima,</p>
                <div class="signature-line">( ____________________ )</div>
            </div>
            <div class="signature-block">
                <p>Kurir,</p>
                <div class="signature-line">{{ $sale->kurir ?? '____________________' }}</div>
            </div>
            <div class="signature-block">
                <p>Hormat Kami (Admin),</p>
                <div class="signature-line">( ____________________ )</div>
            </div>
        </div>

        <div class="footer">
            <p>Harap periksa kondisi barang saat serah terima. Komplain setelah tanda tangan tidak dapat dilayani kecuali disertai foto/video unboxing.</p>
            <p>&copy; {{ date('Y') }} KWT Desa Cibiru Wetan. All rights reserved.</p>
        </div>
    </div>

</body>

</html>