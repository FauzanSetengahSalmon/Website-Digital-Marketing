<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $totalKwt = User::where('role', 'kwt')->count();
        $totalPembeli = User::where('role', 'customer')->count();
        $totalTransaksi = Order::count();

        $pemasukanPerKwt = User::where('role', 'kwt')
            ->leftJoin('products', 'users.id', '=', 'products.user_id')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'irder_items.order_id', '=', 'orders.id')
            ->select(
                'users.name as nama_kwt',
                DB::raw('SUM(order_items.quantity * order_items.price) as total_pemasukan')
            )
            ->where('orders.status', 'completed') // Hanya hitung yang pembayarannya selesai
            ->groupBy('users.id', 'users.name')
            ->get();

        return view('admin.dashboard', compact(
            'totalKwt', 
            'totalPembeli', 
            'totalTransaksi', 
            'pemasukanPerKwt'
        ));
    }

    public function manageUsers()
    {
        $users = User::latest()->get();
        return view('admin.users', compact('users'));
    }
}
