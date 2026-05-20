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
        // 1. Ambil data statistik utama
        $totalKwt = User::where('role', 'kwt')->count();
        $totalProduk = Product::count();
        $totalPesanan = Order::count();
        $totalPendapatan = Order::where('status', 'selesai')->sum('total_harga');

        // 2. Statistik tambahan untuk ringkasan di dashboard
        $stats = [
            'total_customer' => User::where('role', 'customer')->count(),
            'order_aktif'    => Order::whereIn('status', ['menunggu', 'diproses'])->count(),
        ];

        // 3. Ambil data KWT untuk tabel (dengan relasi produk)
        $kwts = User::where('role', 'kwt')->with('products')->latest()->get();

        // 4. Hitung Omzet per KWT untuk tabel peringkat
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
        // PERBAIKAN: Mengambil data order serta memuat list kurir aktif untuk kebutuhan modal terima pesanan
        $sales = Order::with(['user', 'details.product.user.kwt'])
            ->latest()
            ->get();

        $list_kurir = Kurir::where('status', 'aktif')->get();

        return view('admin.sales.index', compact('sales', 'list_kurir'));
    }

    /**
     * MENYAMBUNGKAN METHOD: Update status pesanan dan penugasan armada kurir oleh Admin secara global
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diterima,ditolak,diproses,selesai,dibatalkan',
            'kurir' => 'required|string|max:255',
            'no_hp_kurir' => 'required|string|max:20'
        ]);

        // Mengambil pesanan secara global bebas dari batasan user_id manapun
        $order = Order::findOrFail($id);

        $order->update([
            'status' => $request->status,
            'kurir' => $request->kurir,
            'no_hp_kurir' => $request->no_hp_kurir
        ]);

        return redirect()->back()->with('success', 'Pesanan #' . $id . ' berhasil dikonfirmasi dan diserahkan ke kurir!');
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
    public function adminKurirIndex()
    {
        $kurirs = Kurir::latest()->get();
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
}
