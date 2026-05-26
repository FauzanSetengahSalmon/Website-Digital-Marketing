<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Kurir;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;

class AdminController extends Controller
{
    /**
     * Dashboard Admin: Menampilkan Ringkasan Statistik
     */
    public function dashboard()
    {
        $totalKwt = User::where('role', 'kwt')->count();
        $totalProduk = Product::count();
        $totalPesanan = Order::count();
        $totalPendapatan = Order::where('status', 'selesai')->sum('total_harga');

        $stats = [
            'total_customer' => User::where('role', 'customer')->count(),
            'order_aktif'    => Order::whereIn('status', ['menunggu', 'diproses', 'diantar'])->count(),
        ];

        $kwts = User::where('role', 'kwt')->with('products')->latest()->get();

        $penjualanPerKwt = User::where('role', 'kwt')
            ->get()
            ->map(function ($kwt) {
                $omzet = OrderDetail::whereHas('product', function ($q) use ($kwt) {
                    $q->where('user_id', $kwt->id);
                })
                    ->whereHas('order', function ($q) {
                        $q->where('status', 'selesai');
                    })
                    ->sum(DB::raw('harga_saat_ini * jumlah'));

                return [
                    'nama' => $kwt->name,
                    'omzet' => $omzet,
                    'produk_count' => $kwt->products()->count()
                ];
            })->sortByDesc('omzet');

        return view('admin.dashboard', compact(
            'totalKwt',
            'totalProduk',
            'totalPesanan',
            'totalPendapatan',
            'kwts',
            'penjualanPerKwt',
            'stats'
        ));
    }

    /**
     * Menampilkan daftar Customer
     */
    public function usersIndex()
    {
        $users = User::where('role', 'customer')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan riwayat transaksi global (Halaman Sales Admin)
     */
    public function allSales()
    {
        $sales = Order::with(['user', 'details.product.user'])
            ->latest()
            ->get();

        $list_kurir = Kurir::where('status', 'aktif')->get();

        return view('admin.sales.index', compact('sales', 'list_kurir'));
    }

    /**
     * 🌟 FIX SAKTI: Memaksa Data Masuk Nyata ke Database 🌟
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $status = $request->input('status');

        if ($status === 'batal') {
            $order->update(['status' => 'batal']);
            return redirect()->back()->with('success', 'Pesanan #' . $id . ' berhasil dibatalkan/ditolak.');
        }

        if ($status === 'diantar') {
            $order->update(['status' => 'diantar']);
            return redirect()->back()->with('success', 'Pesanan #' . $id . ' statusnya diubah menjadi Pesanan Diantar.');
        }

        // 🌟 PERBAIKAN 1: Gunakan validasi string biasa agar tidak diblokir format browser 🌟
        $request->validate([
            'kurir' => 'required|string|max:255',
            'no_hp_kurir' => 'required|string|max:20',
            'jadwal_pengiriman' => 'required|date|after_or_equal:today'
        ]);

        // 🌟 PERBAIKAN 2: Gunakan penugasan langsung (Direct Assignment) untuk menembus proteksi fillable 🌟
        $order->status = 'diproses';
        $order->kurir = $request->input('kurir');
        $order->no_hp_kurir = $request->input('no_hp_kurir');
        $order->jadwal_pengiriman = $request->input('jadwal_pengiriman');

        // Simpan paksa ke database
        $order->save();

        return redirect()->back()->with('success', 'Pesanan #' . $id . ' sukses diverifikasi dan dijadwalkan pengirimannya!');
    }

    /**
     * Menampilkan daftar akun KWT
     */
    public function kwtIndex()
    {
        $kwt = User::where('role', 'kwt')->latest()->get();
        return view('admin.kwt.index', compact('kwt'));
    }

    /**
     * Menyimpan akun KWT baru
     */
    public function storeKwt(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
            'password'     => 'required|string|min:8',
        ]);

        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'password'     => Hash::make($request->password),
            'role'         => 'kwt',
        ]);

        event(new Registered($user));

        return back()->with('success', 'Akun KWT baru berhasil dibuat!');
    }

    /**
     * Memperbarui data akun KWT yang sudah ada
     */
    public function updateKwt(Request $request, $id)
    {
        $kwt = User::findOrFail($id);

        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users,email,' . $kwt->id,
            'phone_number' => 'required|string|max:20',
            'password'     => 'nullable|string|min:8',
        ]);

        $data = [
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $kwt->update($data);

        return back()->with('success', 'Data akun KWT berhasil diperbarui!');
    }

    /**
     * Menghapus akun KWT secara permanen
     */
    public function destroyKwt($id)
    {
        $kwt = User::findOrFail($id);
        $kwt->delete();

        return back()->with('success', 'Akun KWT berhasil dihapus dari sistem.');
    }

    /**
     * Menampilkan halaman manajemen Kurir
     */
    /**
     * Menampilkan halaman manajemen Kurir dengan perhitungan pendapatan
     */
    public function adminKurirIndex()
    {
        // Mengambil semua kurir
        $kurirs = Kurir::latest()->get();

        foreach ($kurirs as $kurir) {
            // Menghitung total ongkir dari order yang statusnya 'selesai' dan kurirnya cocok
            $totalOngkir = Order::where('kurir', $kurir->nama)
                ->where('status', 'selesai')
                ->sum('ongkir');

            // Kalkulasi 15% untuk Admin dan sisanya untuk Kurir
            $kurir->total_ongkir = $totalOngkir;
            $kurir->potongan_admin = $totalOngkir * 0.15;
            $kurir->pendapatan_bersih = $totalOngkir * 0.85;
        }

        return view('admin.kurir', compact('kurirs'));
    }

    /**
     * Menyimpan data kurir baru
     */
    public function storeKurir(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'kendaraan' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Kurir::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'kendaraan' => $request->kendaraan,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data kurir berhasil ditambahkan!');
    }

    /**
     * Memperbarui data kurir
     */
    public function updateKurir(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'kendaraan' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $kurir = Kurir::findOrFail($id);
        $kurir->update([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'kendaraan' => $request->kendaraan,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data kurir berhasil diperbarui!');
    }

    /**
     * Menghapus data kurir
     */
    public function destroyKurir($id)
    {
        $kurir = Kurir::findOrFail($id);
        $kurir->delete();

        return redirect()->back()->with('success', 'Data kurir berhasil dihapus!');
    }

    public function detailPesananKwt($id)
    {
        $orders = Order::with(['details.product'])
            ->whereHas('details.product', function ($q) use ($id) {
                $q->where('user_id', $id);
            })->get();

        // Hitung total untuk setiap order agar bisa ditampilkan
        foreach ($orders as $order) {
            $order->subtotal = $order->details->sum(fn($d) => $d->harga_saat_ini * $d->jumlah);
        }

        return view('admin.partials.kwt-orders-table', compact('orders'))->render();
    }

    /**
     * Cetak Invoice Khusus KWT (Pisah per KWT)
     */
    public function printInvoiceKwt($id)
    {
        $sale = Order::with(['user', 'details.product.user'])->findOrFail($id);
        
        // Mengelompokkan detail pesanan berdasarkan Nama Pemilik KWT
        $groupedByKwt = $sale->details->groupBy(function ($detail) {
            return $detail->product->user->name ?? 'KWT Umum';
        });

        return view('admin.sales.invoice_kwt', compact('sale', 'groupedByKwt'));
    }

    /**
     * Cetak Invoice/Surat Jalan Khusus Kurir
     */
    public function printInvoiceKurir($id)
    {
        $sale = Order::with('user')->findOrFail($id);
        
        return view('admin.sales.invoice_kurir', compact('sale'));
    }

    /**
     * Cetak Invoice KWT Batch (Multi-select)
     */
    public function printInvoiceKwtBatch(Request $request)
    {
        $ids = explode(',', $request->query('ids', ''));
        $sales = Order::with(['user', 'details.product.user'])->whereIn('id', $ids)->get();
        
        $allGrouped = [];
        foreach ($sales as $sale) {
            $allGrouped[$sale->id] = [
                'sale' => $sale,
                'grouped' => $sale->details->groupBy(fn($d) => $d->product->user->name ?? 'KWT Umum')
            ];
        }
        
        return view('admin.sales.invoice_kwt_batch', compact('allGrouped'));
    }

    /**
     * Cetak Invoice Kurir Batch (Multi-select)
     */
    public function printInvoiceKurirBatch(Request $request)
    {
        $ids = explode(',', $request->query('ids', ''));
        $sales = Order::with('user')->whereIn('id', $ids)->get();
        
        return view('admin.sales.invoice_kurir_batch', compact('sales'));
    }

    /**
     * Cetak Laporan & Invoice Penghasilan Kurir
     */
    public function reportKurir(Request $request, $id)
    {
        $kurir = Kurir::findOrFail($id);

        // Ambil filter dari request, default ke bulan & tahun sekarang
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));

        // Query orders that this courier handled in that month/year
        $orders = Order::with('user')
            ->where('kurir', $kurir->nama)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->latest()
            ->get();

        // Calculate completed courier earnings (only status == selesai)
        $totalOngkir = Order::where('kurir', $kurir->nama)
            ->where('status', 'selesai')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('ongkir');

        $potonganAdmin = $totalOngkir * 0.15;
        $pendapatanBersih = $totalOngkir * 0.85;

        return view('admin.sales.kurir_laporan', compact('kurir', 'orders', 'totalOngkir', 'potonganAdmin', 'pendapatanBersih', 'month', 'year'));
    }

    /**
     * Cetak Laporan & Invoice Penghasilan KWT
     */
    public function reportKwt(Request $request, $id)
    {
        $kwt = User::where('id', $id)->where('role', 'kwt')->firstOrFail();

        // Ambil filter dari request, default ke bulan & tahun sekarang
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));

        // Query orders containing products from this KWT in that month/year
        $orders = Order::with(['user', 'details.product'])
            ->whereHas('details.product', fn($q) => $q->where('user_id', $kwt->id))
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->latest()
            ->get();

        // Calculate KWT's completed earnings for these orders (only status == selesai)
        $totalPendapatan = OrderDetail::whereHas('product', fn($q) => $q->where('user_id', $kwt->id))
            ->whereHas('order', function ($q) use ($month, $year) {
                $q->where('status', 'selesai')
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year);
            })
            ->sum(DB::raw('harga_saat_ini * jumlah'));

        return view('admin.kwt.laporan', compact('kwt', 'orders', 'totalPendapatan', 'month', 'year'));
    }
}

