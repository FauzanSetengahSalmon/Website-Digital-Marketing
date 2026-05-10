<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
            ->map(function($kwt) {
                $omzet = OrderDetail::whereHas('product', function($q) use ($kwt) {
                        $q->where('user_id', $kwt->id);
                    })
                    ->whereHas('order', function($q) {
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
     * Menampilkan riwayat transaksi global
     */
    public function allSales()
    {
        $sales = Order::with(['user', 'details.product.user'])
            ->latest()
            ->get();
        return view('admin.sales.index', compact('sales'));
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'kwt',
        ]);

        return back()->with('success', 'Akun KWT berhasil dibuat!');
    }
}