<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Kurir;
use App\Models\OrderDetail;
use App\Models\Kwt;
use App\Models\KurirPencairan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;

class AdminController extends Controller
{
    /**
     * Dashboard Admin
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

        $penjualanPerKurir = \App\Models\Kurir::all()->map(function ($kurir) {
        $totalOngkir = \App\Models\Order::where('kurir', $kurir->nama)
                ->where('status', 'selesai')
                ->sum('ongkir');
            return [
                'nama' => $kurir->nama,
                'total_ongkir' => $totalOngkir
            ];
        })->sortByDesc('total_ongkir');

        return view('admin.dashboard', compact(
            'totalKwt', 'totalProduk', 'totalPesanan', 'totalPendapatan', 
            'kwts', 'penjualanPerKwt', 'penjualanPerKurir', 'stats'
        ));
    }

    public function usersIndex()
    {
        $users = User::where('role', 'customer')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function allSales()
    {
        $sales = Order::with(['user', 'details.product.user'])->latest()->get();
        $list_kurir = Kurir::where('status', 'aktif')->get();
        return view('admin.sales.index', compact('sales', 'list_kurir'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
<<<<<<< HEAD
        if ($request->input('status') === 'batal') {
=======
        $status = $request->input('status');

        if ($status === 'batal') {
>>>>>>> 331fc6b73615be611e4252b2c16ffde800b6bb68
            $order->update(['status' => 'batal']);
            return redirect()->back()->with('success', 'Pesanan #' . $id . ' berhasil dibatalkan.');
        }

<<<<<<< HEAD
=======
        if ($status === 'diantar') {
            $order->update(['status' => 'diantar']);
            return redirect()->back()->with('success', 'Pesanan #' . $id . ' statusnya diubah menjadi Pesanan Diantar.');
        }

        // 🌟 PERBAIKAN 1: Gunakan validasi string biasa agar tidak diblokir format browser 🌟
>>>>>>> 331fc6b73615be611e4252b2c16ffde800b6bb68
        $request->validate([
            'kurir' => 'required|string|max:255',
            'no_hp_kurir' => 'required|string|max:20',
            'jadwal_pengiriman' => 'required|date|after_or_equal:today'
        ]);

        $order->status = 'diproses';
        $order->kurir = $request->input('kurir');
        $order->no_hp_kurir = $request->input('no_hp_kurir');
        $order->jadwal_pengiriman = $request->input('jadwal_pengiriman');
        $order->save();

        return redirect()->back()->with('success', 'Pesanan #' . $id . ' berhasil dijadwalkan!');
    }

    public function kwtIndex()
    {
        $kwt = User::where('role', 'kwt')->latest()->get();
        return view('admin.kwt.index', compact('kwt'));
    }

    public function storeKwt(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => 'kwt',
        ]);

        event(new Registered($user));
        return back()->with('success', 'Akun KWT berhasil dibuat!');
    }

    public function updateKwt(Request $request, $id)
    {
        $kwt = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $kwt->id,
            'phone_number' => 'required|string|max:20',
            'password' => 'nullable|string|min:8',
        ]);

        $data = ['name' => $request->name, 'email' => $request->email, 'phone_number' => $request->phone_number];
        if ($request->filled('password')) $data['password'] = Hash::make($request->password);
        $kwt->update($data);

        return back()->with('success', 'Data KWT diperbarui!');
    }

    public function destroyKwt($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Akun KWT dihapus.');
    }

    public function adminKurirIndex()
    {
        $kurirs = Kurir::latest()->get();
        foreach ($kurirs as $kurir) {
            $totalOngkir = Order::where('kurir', $kurir->nama)->where('status', 'selesai')->sum('ongkir');
            $kurir->total_ongkir = $totalOngkir;
            $kurir->potongan_admin = $totalOngkir * 0.15;
            $kurir->pendapatan_bersih = $totalOngkir * 0.85;
        }
        return view('admin.kurir', compact('kurirs'));
    }

    public function storeKurir(Request $request)
    {
        $request->validate(['nama' => 'required', 'no_hp' => 'required', 'status' => 'required']);
        Kurir::create($request->all());
        return redirect()->back()->with('success', 'Kurir ditambahkan!');
    }

    public function updateKurir(Request $request, $id)
    {
        Kurir::findOrFail($id)->update($request->all());
        return redirect()->back()->with('success', 'Kurir diperbarui!');
    }

    public function destroyKurir($id)
    {
        Kurir::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Kurir dihapus!');
    }

    public function printInvoiceKwt($id)
    {
        $sale = Order::with(['user', 'details.product.user'])->findOrFail($id);
        $groupedByKwt = $sale->details->groupBy(fn($d) => $d->product->user->name ?? 'KWT Umum');
        return view('admin.sales.invoice_kwt', compact('sale', 'groupedByKwt'));
    }

    public function printInvoiceKurir($id)
    {
        $sale = Order::with('user')->findOrFail($id);
        return view('admin.sales.invoice_kurir', compact('sale'));
    }

<<<<<<< HEAD
=======
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
>>>>>>> 331fc6b73615be611e4252b2c16ffde800b6bb68
    public function reportKurir(Request $request, $id)
    {
        $kurir = Kurir::findOrFail($id);
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));
        
        $orders = Order::where('kurir', $kurir->nama)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
        $totalOngkir = $orders->where('status', 'selesai')->sum('ongkir');
        
        return view('admin.sales.kurir_laporan', compact('kurir', 'orders', 'totalOngkir', 'month', 'year'));
    }

    public function cairkan(Request $request, $kwt_id)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'nama_penerima' => 'required|string|max:255'
        ]);
        
        Order::whereIn('id', $request->order_ids)->update([
            'is_paid_out' => true,
            'nama_penerima' => $request->nama_penerima
        ]);

        return back()->with('printed_ids', $request->order_ids)
                    ->with('penerima', $request->nama_penerima)
                    ->with('success', 'Transaksi berhasil dicairkan.');
    }
    
    // --- FITUR BARU: Pencairan Kurir ---
    public function riwayatPencairanKurir() {
        $pencairan = KurirPencairan::latest()->get();
        return view('admin.kurir.pencairan', compact('pencairan'));
    }

    public function storePencairanKurir(Request $request) {
        $request->validate(['nama_kurir' => 'required', 'nama_penerima' => 'required', 'total_cair' => 'required']);
        KurirPencairan::create($request->all());
        return back()->with('success', 'Pencairan berhasil dicatat!');
    }

    public function cairkanKurir(Request $request, $id) {
        $kurir = Kurir::findOrFail($id);
        KurirPencairan::create([
            'nama_kurir' => $kurir->nama,
            'nama_penerima' => $request->nama_penerima,
            'total_cair' => $request->total_cair
        ]);
        Order::where('kurir', $kurir->nama)->where('status', 'selesai')->update(['is_paid_out' => true]);
        return back()->with('success', 'Pencairan berhasil!');
    }

    public function reportKwt(Request $request, $id)
    {
        $kwt = User::where('id', $id)->where('role', 'kwt')->firstOrFail();
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));

<<<<<<< HEAD
        $tersediaTahun = Order::whereHas('details.product', fn($q) => $q->where('user_id', $kwt->id))
            ->selectRaw('strftime("%Y", created_at) as year')
            ->distinct()
            ->orderBy('year', 'DESC')
            ->pluck('year')
            ->toArray();
        if (empty($tersediaTahun)) $tersediaTahun = [date('Y')];

=======
        // Query orders containing products from this KWT in that month/year
>>>>>>> 331fc6b73615be611e4252b2c16ffde800b6bb68
        $orders = Order::with(['user', 'details.product'])
            ->whereHas('details.product', fn($q) => $q->where('user_id', $kwt->id))
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
<<<<<<< HEAD
            ->latest()->get();

        $totalPendapatan = OrderDetail::whereHas('product', fn($q) => $q->where('user_id', $kwt->id))
            ->whereHas('order', fn($q) => $q->where('status', 'selesai')->whereMonth('created_at', $month)->whereYear('created_at', $year))
            ->sum(DB::raw('harga_saat_ini * jumlah'));

        $bulanIndo = [
            '01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', 
            '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', 
            '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'
        ];

        return view('admin.sales.laporan_kwt', compact('kwt', 'orders', 'totalPendapatan', 'month', 'year', 'tersediaTahun', 'bulanIndo'));
    }
}
=======
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

>>>>>>> 331fc6b73615be611e4252b2c16ffde800b6bb68
