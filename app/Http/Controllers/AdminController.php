<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * DASHBOARD ADMIN
     */
    public function dashboard()
    {
        $totalKwt = User::where('role', 'kwt')->count();

        $totalPendapatan = Order::where('status', 'selesai')
            ->sum('total_harga');

        $totalProduk = Product::count();

        $totalPesanan = Order::count();

        // WAJIB: biar blade tidak error $kwts undefined
        $kwts = User::where('role', 'kwt')
            ->with('products') // biar $kwt->products->count() aman
            ->latest()
            ->get();

        return view('admin.dashboard', compact(
            'totalKwt',
            'totalPendapatan',
            'totalProduk',
            'totalPesanan',
            'kwts'
        ));
    }

    /**
     * HALAMAN LIST KWT
     */
    public function kwtIndex()
    {
        $kwts = User::where('role', 'kwt')
            ->with('products')
            ->latest()
            ->get();

        return view('admin.kwt', compact('kwts'));
    }

    /**
     * SIMPAN KWT BARU
     */
    public function storeKwt(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'kwt',
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Akun KWT berhasil dibuat');
    }
}