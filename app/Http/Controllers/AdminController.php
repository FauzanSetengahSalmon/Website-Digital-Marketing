<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalKwt = User::where('role', 'kwt')->count();

        $totalProduk = Product::count();

        $totalPesanan = Order::count();

        $totalPendapatan = Order::where('status', 'selesai')
            ->sum('total_harga');

        $stats = [
            'total_customer' => User::where('role', 'customer')->count(),

            'order_aktif' => Order::whereIn('status', [
                'menunggu',
                'diproses'
            ])->count(),
        ];

        $kwts = User::where('role', 'kwt')
            ->withCount('products')
            ->latest()
            ->get();

        $penjualanPerKwt = User::where('role', 'kwt')
            ->get()
            ->map(function ($kwt) {

                $omzet = DB::table('order_details')
                    ->join('products', 'order_details.product_id', '=', 'products.id')
                    ->join('orders', 'order_details.order_id', '=', 'orders.id')
                    ->where('products.user_id', $kwt->id)
                    ->where('orders.status', 'selesai')
                    ->select(DB::raw('SUM(order_details.harga_saat_ini * order_details.jumlah) as total'))
                    ->value('total') ?? 0;

                return [
                    'nama' => $kwt->name,

                    'omzet' => $omzet,

                    'produk_count' => Product::where('user_id', $kwt->id)->count()
                ];
            })
            ->sortByDesc('omzet');

        $sales = Order::with([
            'user',
            'details.product'
        ])->latest()->get();

        return view('admin.dashboard', compact(
            'totalKwt',
            'totalProduk',
            'totalPesanan',
            'totalPendapatan',
            'kwts',
            'penjualanPerKwt',
            'stats',
            'sales'
        ));
    }

    public function usersIndex()
    {
        $users = User::where('role', 'customer')
            ->latest()
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function allSales()
    {
        $sales = Order::with([
            'user',
            'details.product'
        ])->latest()->get();

        return view('admin.sales.index', compact('sales'));
    }

    public function kwtIndex()
    {
        $kwt = User::where('role', 'kwt')
            ->latest()
            ->get();

        return view('admin.kwt.index', compact('kwt'));
    }

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

            'phone_number' => '-',
        ]);

        return back()->with(
            'success',
            'Akun KWT berhasil dibuat!'
        );
    }

    public function updateKwt(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',

            'email' => 'required|string|email|max:255|unique:users,email,' . $id,

            'password' => 'nullable|string|min:8',
        ]);

        $user->name = $request->name;

        $user->email = $request->email;

        if ($request->filled('password')) {

            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with(
            'success',
            'Akun KWT berhasil diperbarui!'
        );
    }

    public function destroyKwt($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return back()->with(
            'success',
            'Akun KWT berhasil dihapus!'
        );
    }
}