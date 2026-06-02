@extends('layouts.admin')

@section('content')
<style>
    .admin-card {
        border: none;
        border-radius: 20px;
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .admin-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
    }

    .icon-shape {
        width: 55px;
        height: 55px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
    }

    .gradient-green {
        background: linear-gradient(135deg, #166534 0%, #22c55e 100%);
        color: white;
    }

    .gradient-blue {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
    }

    .gradient-orange {
        background: linear-gradient(135deg, #9a3412 0%, #f97316 100%);
        color: white;
    }

    .gradient-purple {
        background: linear-gradient(135deg, #6b21a8 0%, #a855f7 100%);
        color: white;
    }

    .kwt-table {
        background: white;
        border-radius: 24px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .badge-status {
        background: #dcfce7;
        color: #166534;
        padding: 6px 16px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.75rem;
        border: 1px solid #bbf7d0;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
    }

    .card-link-wrapper {
        text-decoration: none;
        display: block;
        height: 100%;
    }

    .kwt-link:hover .admin-card {
        border-color: #22c55e !important;
    }

    .kurir-link:hover .admin-card {
        border-color: #3b82f6 !important;
    }
</style>

@php
// 🌟 AMBIL NILAI FILTER DARI URL 🌟
$selectedMonth = request('bulan', date('m'));
$selectedYear = request('tahun', date('Y'));

$months = [
'01'=>'Jan', '02'=>'Feb', '03'=>'Mar', '04'=>'Apr',
'05'=>'Mei', '06'=>'Jun', '07'=>'Jul', '08'=>'Agu',
'09'=>'Sep', '10'=>'Okt', '11'=>'Nov', '12'=>'Des'
];

// 1. DATA SALDO SIAP CAIR (SELALU ALL-TIME KARENA INI HUTANG NYATA)
$kwtPending = \App\Models\OrderDetail::whereHas('order', function ($q) {
$q->where('status', 'selesai')->where(function ($sub) {
$sub->where('status_refund', '!=', 'disetujui')->orWhereNull('status_refund');
});
})->where('is_cair_kwt', false)->sum(DB::raw('harga_saat_ini * jumlah'));

$kurirPending = \App\Models\Order::where('status', 'selesai')
->where(function ($sub) {
$sub->where('status_refund', '!=', 'disetujui')->orWhereNull('status_refund');
})->where('is_paid_out', false)->sum('ongkir');

// 2. STATISTIK UMUM YANG DIFILTER BERDASARKAN BULAN & TAHUN
// Total Pesanan Bulan Ini (Semua Status: Batal, Refund, Selesai)
$totalPesananFiltered = \App\Models\Order::whereMonth('created_at', $selectedMonth)
->whereYear('created_at', $selectedYear)
->count();

// Total Seluruh Pesanan All-Time (Semua Status: Batal, Refund, Selesai)
$totalPesananAllTime = \App\Models\Order::count();

$totalPendapatanFiltered = \App\Models\Order::where('status', 'selesai')
->whereMonth('created_at', $selectedMonth)
->whereYear('created_at', $selectedYear)
->where(function ($q) {
$q->where('status_refund', '!=', 'disetujui')
->orWhereNull('status_refund');
})
->sum('total_harga');

$totalPendapatanAllTime = \App\Models\Order::where('status', 'selesai')
->where(function ($q) {
$q->where('status_refund', '!=', 'disetujui')
->orWhereNull('status_refund');
})
->sum('total_harga');

// 3. KELOLA DATA TABEL KWT BERDASARKAN FILTER BULAN
$kwtsFiltered = \App\Models\User::where('role', 'kwt')->with('products')->get();
$penjualanPerKwtFiltered = $kwtsFiltered->map(function ($kwt) use ($selectedMonth, $selectedYear) {
$omzet = \App\Models\OrderDetail::whereHas('product', function ($q) use ($kwt) {
$q->where('user_id', $kwt->id);
})->whereHas('order', function ($q) use ($selectedMonth, $selectedYear) {
$q->where('status', 'selesai')
->whereMonth('created_at', $selectedMonth)
->whereYear('created_at', $selectedYear)
->where(function ($sub) {
$sub->where('status_refund', '!=', 'disetujui')->orWhereNull('status_refund');
});
})->sum(DB::raw('harga_saat_ini * jumlah'));

$sudahDicairkan = \App\Models\OrderDetail::whereHas('product', function ($q) use ($kwt) {
$q->where('user_id', $kwt->id);
})->whereHas('order', function ($q) use ($selectedMonth, $selectedYear) {
$q->whereMonth('created_at', $selectedMonth)
->whereYear('created_at', $selectedYear)
->where(function ($sub) {
$sub->where('status_refund', '!=', 'disetujui')->orWhereNull('status_refund');
});
})->where('is_cair_kwt', true)->sum(DB::raw('harga_saat_ini * jumlah'));

return ['nama' => $kwt->name, 'omzet' => $omzet, 'sudah_dicairkan' => $sudahDicairkan];
})->sortByDesc('omzet');

// 4. KELOLA DATA TABEL KURIR BERDASARKAN FILTER BULAN
$penjualanPerKurirFiltered = \App\Models\Kurir::all()->map(function ($kurir) use ($selectedMonth, $selectedYear) {
$totalOngkir = \App\Models\Order::where('kurir', $kurir->nama)->where('status', 'selesai')
->whereMonth('created_at', $selectedMonth)->whereYear('created_at', $selectedYear)
->where(function ($q) {
$q->where('status_refund', '!=', 'disetujui')->orWhereNull('status_refund');
})->sum('ongkir');

$sudahDicairkan = \App\Models\Order::where('kurir', $kurir->nama)->where('status', 'selesai')
->where('is_paid_out', true)->whereMonth('created_at', $selectedMonth)->whereYear('created_at', $selectedYear)
->where(function ($q) {
$q->where('status_refund', '!=', 'disetujui')->orWhereNull('status_refund');
})->sum('ongkir');

return ['nama' => $kurir->nama, 'total_ongkir' => $totalOngkir, 'sudah_dicairkan' => $sudahDicairkan];
})->sortByDesc('total_ongkir');

// UPDATE TOTAL KESELURUHAN (HANYA UNTUK KARTU PENDAPATAN BULAN INI)
$totalOmzetKwt = collect($penjualanPerKwtFiltered)->sum('omzet');
$totalOngkir = collect($penjualanPerKurirFiltered)->sum('total_ongkir');
$totalBiayaAdmin = max(0, $totalPendapatanFiltered - ($totalOmzetKwt + $totalOngkir));

// 🌟 SIAPKAN DATA UNTUK GRAFIK TREN (LINE CHART 12 BULAN) 🌟
$trendLabels = array_values($months);
$trendKwt = [];
$trendKurir = [];
$trendAdmin = [];

foreach ($kwtsFiltered as $kwt) {
$trendKwt[$kwt->id] = ['label' => $kwt->name, 'data' => []];
}

for ($m = 1; $m <= 12; $m++) {
    $totalKwtBulanIni=0;

    // Hitung per KWT
    foreach ($kwtsFiltered as $kwt) {
    $omzetBulanIni=\App\Models\OrderDetail::whereHas('product', function ($q) use ($kwt) {
    $q->where('user_id', $kwt->id);
    })->whereHas('order', function ($q) use ($m, $selectedYear) {
    $q->where('status', 'selesai')->whereMonth('created_at', $m)->whereYear('created_at', $selectedYear)
    ->where(function ($sub) { $sub->where('status_refund', '!=', 'disetujui')->orWhereNull('status_refund'); });
    })->sum(DB::raw('harga_saat_ini * jumlah'));

    $trendKwt[$kwt->id]['data'][] = $omzetBulanIni;
    $totalKwtBulanIni += $omzetBulanIni;
    }

    // Hitung Kurir
    $kurirBulanIni = \App\Models\Order::where('status', 'selesai')
    ->whereMonth('created_at', $m)->whereYear('created_at', $selectedYear)
    ->where(function ($sub) { $sub->where('status_refund', '!=', 'disetujui')->orWhereNull('status_refund'); })
    ->sum('ongkir');
    $trendKurir[] = $kurirBulanIni;

    // Hitung Admin
    $pendapatanTotalBulanIni = \App\Models\Order::where('status', 'selesai')
    ->whereMonth('created_at', $m)->whereYear('created_at', $selectedYear)
    ->where(function ($sub) { $sub->where('status_refund', '!=', 'disetujui')->orWhereNull('status_refund'); })
    ->sum('total_harga');
    $trendAdmin[] = max(0, $pendapatanTotalBulanIni - ($totalKwtBulanIni + $kurirBulanIni));
    }
    @endphp

    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold text-dark mb-1">Dashboard Admin</h2>
                <p class="text-muted">Selamat datang kembali, Admin. Berikut ringkasan operasional dan finansial.</p>
            </div>
            <span class="badge-status"><i class="bi bi-shield-check me-1"></i> Sistem Aktif</span>
        </div>

        {{-- STATISTIK UMUM --}}
        <div class="row g-4 mb-5">
            {{-- KARTU TOTAL KWT --}}
            <div class="col-md-3">
                <div class="card admin-card gradient-green p-4 h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="opacity-75">Total KWT</small>
                            <h2 class="fw-bold mb-0">{{ count($kwtsFiltered) }}</h2>
                        </div>
                        <div class="icon-shape"><i class="bi bi-people"></i></div>
                    </div>
                    <div class="mt-3 pt-2 border-top border-light border-opacity-25">
                        <small class="opacity-75" style="font-size: 0.75rem;">
                            <i class="bi bi-patch-check-fill me-1"></i> 100% Mitra Terverifikasi
                        </small>
                    </div>
                </div>
            </div>

            {{-- KARTU PRODUK --}}
            <div class="col-md-3">
                <div class="card admin-card gradient-blue p-4 h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="opacity-75">Produk</small>
                            <h2 class="fw-bold mb-0">{{ \App\Models\Product::count() }}</h2>
                        </div>
                        <div class="icon-shape"><i class="bi bi-box-seam"></i></div>
                    </div>
                    <div class="mt-3 pt-2 border-top border-light border-opacity-25">
                        <small class="opacity-75" style="font-size: 0.75rem;">
                            <i class="bi bi-tags-fill me-1"></i> Tersebar di berbagai KWT
                        </small>
                    </div>
                </div>
            </div>

            {{-- KARTU PESANAN BULAN INI --}}
            <div class="col-md-3">
                <div class="card admin-card gradient-orange p-4 h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="opacity-75">Pesanan (Bulan Ini)</small>
                            <h2 class="fw-bold mb-0">{{ $totalPesananFiltered }}</h2>
                        </div>
                        <div class="icon-shape"><i class="bi bi-cart3"></i></div>
                    </div>
                    <div class="mt-3 pt-2 border-top border-light border-opacity-25">
                        <small class="opacity-75 d-block" style="font-size: 0.75rem;">
                            Total Seluruh Pesanan
                        </small>
                        <span class="fw-bold fs-6">
                            {{ number_format($totalPesananAllTime, 0, ',', '.') }} Pesanan
                        </span>
                    </div>
                </div>
            </div>

            {{-- KARTU OMZET SPLIT --}}
            <div class="col-md-3">
                <div class="card admin-card gradient-purple p-4 h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="opacity-75">Omzet Bulan Ini</small>
                            <h4 class="fw-bold mb-2">Rp {{ number_format($totalPendapatanFiltered,0,',','.') }}</h4>
                        </div>
                        <div class="icon-shape"><i class="bi bi-wallet2"></i></div>
                    </div>
                    <div class="mt-3 pt-2 border-top border-light border-opacity-25">
                        <small class="opacity-75 d-block" style="font-size: 0.75rem;">Omzet Seluruh (All-Time)</small>
                        <span class="fw-bold fs-6">Rp {{ number_format($totalPendapatanAllTime,0,',','.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION FILTER BULAN & TAHUN --}}
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-end">
                <form action="{{ url()->current() }}" method="GET" class="d-flex align-items-center bg-white py-1 px-2 rounded-pill shadow-sm border" style="max-width: max-content;">
                    <span class="text-muted small ms-2 me-2 fw-bold"><i class="bi bi-calendar3"></i></span>
                    <select name="bulan" class="form-select form-select-sm border-0 bg-transparent text-secondary fw-semibold cursor-pointer py-1" style="box-shadow: none; width: auto; outline: none;">
                        @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                        @endforeach
                    </select>
                    <div class="vr mx-1 text-muted" style="height: 20px; align-self: center;"></div>
                    <select name="tahun" class="form-select form-select-sm border-0 bg-transparent text-secondary fw-semibold cursor-pointer py-1" style="box-shadow: none; width: auto; outline: none;">
                        @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 ms-1 fw-bold">Filter</button>
                </form>
            </div>
        </div>

        {{-- RINCIAN DISTRIBUSI KEUANGAN (Buku Kas Difilter) --}}
        <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-pie-chart-fill me-2 text-success"></i>Buku Kas & Kewajiban Finansial</h5>
        <div class="row g-4 mb-5">
            {{-- KARTU SALDO KWT --}}
            <div class="col-md-4">
                <a href="{{ url('admin/sales') }}" class="card-link-wrapper kwt-link">
                    <div class="card admin-card bg-white p-4 border-bottom border-success border-4 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <small class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem;">Saldo Mitra KWT (Siap Cair)</small>
                                <h3 class="fw-extrabold mb-0 text-success mt-1">Rp {{ number_format($kwtPending, 0, ',', '.') }}</h3>
                            </div>
                            <div class="icon-shape bg-success bg-opacity-10 text-success"><i class="bi bi-shop"></i></div>
                        </div>
                        <div class="pt-3 border-top mt-auto">
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">Disalurkan (Bulan Ini):</small>
                                @php
                                $disalurkanKwt = \App\Models\OrderDetail::whereHas('order', function($q) use($selectedMonth, $selectedYear) {
                                $q->whereMonth('created_at', $selectedMonth)->whereYear('created_at', $selectedYear);
                                })->where('is_cair_kwt', true)->sum(DB::raw('harga_saat_ini * jumlah'));
                                @endphp
                                <small class="fw-bold text-dark">Rp {{ number_format($disalurkanKwt, 0, ',', '.') }}</small>
                            </div>
                            <div class="text-end"><small class="text-success fw-bold">Lihat Semua Penjualan &raquo;</small></div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- KARTU SALDO KURIR --}}
            <div class="col-md-4">
                <a href="{{ route('admin.kurir.pencairan') }}" class="card-link-wrapper kurir-link">
                    <div class="card admin-card bg-white p-4 border-bottom border-primary border-4 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <small class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem;">Saldo Kurir (Siap Cair)</small>
                                <h3 class="fw-extrabold mb-0 text-primary mt-1">Rp {{ number_format($kurirPending, 0, ',', '.') }}</h3>
                            </div>
                            <div class="icon-shape bg-primary bg-opacity-10 text-primary"><i class="bi bi-truck"></i></div>
                        </div>
                        <div class="pt-3 border-top mt-auto">
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">Disalurkan (Bulan Ini):</small>
                                @php
                                $disalurkanKurir = \App\Models\Order::where('is_paid_out', true)
                                ->whereMonth('created_at', $selectedMonth)->whereYear('created_at', $selectedYear)->sum('ongkir');
                                @endphp
                                <small class="fw-bold text-dark">Rp {{ number_format($disalurkanKurir, 0, ',', '.') }}</small>
                            </div>
                            <div class="text-end"><small class="text-primary fw-bold">Kelola Pencairan Kurir &raquo;</small></div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- KARTU PENDAPATAN ADMIN --}}
            <div class="col-md-4">
                <div class="card admin-card bg-white p-4 border-bottom border-warning border-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <small class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem;">Pendapatan Admin</small>
                            <h3 class="fw-extrabold mb-0 text-warning mt-1">Rp {{ number_format($totalBiayaAdmin, 0, ',', '.') }}</h3>
                            <small class="text-muted" style="font-size: 0.65rem;">(Bulan terpilih)</small>
                        </div>
                        <div class="icon-shape bg-warning bg-opacity-10 text-warning"><i class="bi bi-laptop"></i></div>
                    </div>
                    <div class="pt-3 border-top mt-auto">
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">Status:</small>
                            <small class="fw-bold text-success">Hak Milik Platform</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABEL LEADERBOARD --}}
        <div class="row g-4 mb-5">
            <div class="col-lg-4">
                <div class="kwt-table p-3 h-100">
                    <h6 class="p-3 section-title"><i class="bi bi-shop me-2 text-green"></i>KWT Terdaftar</h6>
                    <table class="table mb-0">
                        <tbody>
                            @foreach($kwtsFiltered as $kwt)
                            <tr>
                                <td class="fw-semibold">{{ $kwt->name }}</td>
                                <td class="text-center"><span class="badge bg-success text-white">{{ $kwt->products->count() }} Produk</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="kwt-table p-3 h-100">
                    <h6 class="p-3 section-title"><i class="bi bi-trophy me-2 text-warning"></i>Performa KWT <span class="fs-8 text-muted fw-normal">({{ $months[$selectedMonth] }})</span></h6>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>KWT</th>
                                    <th class="text-end">Omzet</th>
                                    <th class="text-end">Sudah Cair</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penjualanPerKwtFiltered as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item['nama'] }}</td>
                                    <td class="text-end fw-bold text-success">Rp {{ number_format($item['omzet'], 0, ',', '.') }}</td>
                                    <td class="text-end text-muted small">Rp {{ number_format($item['sudah_dicairkan'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="kwt-table p-3 h-100">
                    <h6 class="p-3 section-title"><i class="bi bi-truck me-2 text-primary"></i>Performa Kurir <span class="fs-8 text-muted fw-normal">({{ $months[$selectedMonth] }})</span></h6>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Kurir</th>
                                    <th class="text-end">Total Ongkir</th>
                                    <th class="text-end">Sudah Cair</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penjualanPerKurirFiltered as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item['nama'] }}</td>
                                    <td class="text-end fw-bold text-primary">Rp {{ number_format($item['total_ongkir'], 0, ',', '.') }}</td>
                                    <td class="text-end text-muted small">Rp {{ number_format($item['sudah_dicairkan'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION KEDUA GRAFIK DARI DESAIN AWAL KAMU --}}
        <div class="row g-4 mb-5">
            {{-- DOUGHNUT CHART DISTRIBUSI PENDAPATAN --}}
            <div class="col-lg-4">
                <div class="kwt-table p-4 h-100">
                    <h5 class="section-title mb-4"><i class="bi bi-pie-chart-fill me-2 text-warning"></i>Distribusi Keuangan</h5>

                    {{-- DATA PHP DITITIPKAN KE HTML --}}
                    <div id="doughnutDataContainer" class="d-none"
                        data-kwt="{{ $totalOmzetKwt }}"
                        data-kurir="{{ $totalOngkir }}"
                        data-admin="{{ $totalBiayaAdmin }}">
                    </div>

                    <div style="position: relative; height:320px; display:flex; align-items:center;">
                        <canvas id="revenueDistributionChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- LINE / AREA CHART TREN --}}
            <div class="col-lg-8">
                <div class="kwt-table p-4 h-100">
                    <h5 class="section-title mb-4"><i class="bi bi-graph-up me-2 text-success"></i>Tren Capaian Finansial (Tahun {{ $selectedYear }})</h5>

                    {{-- DATA PHP DITITIPKAN KE HTML --}}
                    <div id="chartTrendData" class="d-none"
                        data-labels="{{ json_encode($trendLabels) }}"
                        data-kwt="{{ json_encode(array_values($trendKwt)) }}"
                        data-kurir="{{ json_encode($trendKurir) }}"
                        data-admin="{{ json_encode($trendAdmin) }}">
                    </div>

                    <div style="position: relative; height:320px;">
                        <canvas id="salesComparisonChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT INTEGRASI CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // ==========================================
            // 1. DOUGHNUT CHART (DISTRIBUSI KEUANGAN)
            // ==========================================
            const doughnutContainer = document.getElementById('doughnutDataContainer');
            const valKwt = parseFloat(doughnutContainer.getAttribute('data-kwt')) || 0;
            const valKurir = parseFloat(doughnutContainer.getAttribute('data-kurir')) || 0;
            const valAdmin = parseFloat(doughnutContainer.getAttribute('data-admin')) || 0;

            const ctxDoughnut = document.getElementById('revenueDistributionChart').getContext('2d');
            new Chart(ctxDoughnut, {
                type: 'doughnut',
                data: {
                    labels: ['Omzet KWT', 'Ongkir Kurir', 'Pendapatan Admin'],
                    datasets: [{
                        data: [valKwt, valKurir, valAdmin],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.85)', // Hijau
                            'rgba(59, 130, 246, 0.85)', // Biru
                            'rgba(245, 158, 11, 0.85)' // Kuning
                        ],
                        borderWidth: 0,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    family: "'Inter', sans-serif",
                                    weight: 'bold'
                                },
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });


            // ==========================================
            // 2. LINE / AREA CHART (TREN FINANSIAL)
            // ==========================================
            const trendContainer = document.getElementById('chartTrendData');
            const trendLabels = JSON.parse(trendContainer.getAttribute('data-labels'));
            const trendKwt = JSON.parse(trendContainer.getAttribute('data-kwt'));
            const trendKurir = JSON.parse(trendContainer.getAttribute('data-kurir'));
            const trendAdmin = JSON.parse(trendContainer.getAttribute('data-admin'));

            const trendDatasets = [];

            // Warna cerah biar gampang bedain
            const colors = ['#22c55e', '#0ea5e9', '#8b5cf6', '#ec4899', '#f43f5e'];

            // Looping KWT yang ada
            trendKwt.forEach((kwt, index) => {
                let color = colors[index % colors.length];
                trendDatasets.push({
                    label: 'Omzet ' + kwt.label,
                    data: kwt.data,
                    borderColor: color,
                    backgroundColor: color + '20', // Tambah transparansi
                    fill: true,
                    tension: 0.4, // Melengkung halus
                    pointRadius: 4,
                    pointBackgroundColor: color
                });
            });

            // Masukkan Kurir
            trendDatasets.push({
                label: 'Ongkir Kurir',
                data: trendKurir,
                borderColor: '#3b82f6', // Biru terang
                backgroundColor: 'rgba(59, 130, 246, 0.15)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#3b82f6'
            });

            // Masukkan Admin
            trendDatasets.push({
                label: 'Pendapatan Admin',
                data: trendAdmin,
                borderColor: '#f59e0b', // Oranye Emas
                backgroundColor: 'rgba(245, 158, 11, 0.15)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#f59e0b'
            });

            const ctxTrend = document.getElementById('salesComparisonChart').getContext('2d');
            new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: trendDatasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    family: "'Inter', sans-serif",
                                    weight: 'bold'
                                },
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#1e293b',
                            bodyColor: '#334155',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', {
                                            style: 'currency',
                                            currency: 'IDR'
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            grid: {
                                borderDash: [5, 5],
                                color: '#f1f5f9'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000).toLocaleString('id-ID') + 'k';
                                }
                            }
                        }
                    }
                }
            });

        });
    </script>
    @endsection