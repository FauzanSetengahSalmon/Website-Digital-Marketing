<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller {
    public function kwtDashboard() {
        if (Auth::user()->role !== 'kwt') return redirect('/');

        $stats = [
            'total_received' => Order::where('status', 'selesai')->sum('total_harga'),
            'sold_count'     => Order::where('status', 'selesai')->sum('jumlah'),
            'total_products' => Product::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
        ];

        return view('kwt.dashboard', compact('stats'));
    }

    public function kwtOrders() {
        if (Auth::user()->role !== 'kwt') return redirect('/');
        $orders = Order::whereIn('status', ['pending', 'proses'])->orderBy('created_at', 'desc')->get();
        return view('kwt.list-pesanan', compact('orders'));
    }

    public function markAsDone($id) {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'selesai']);
        
        $product = Product::where('nama_produk', $order->nama_produk)->first();
        if ($product) {
            $product->decrement('stok', $order->jumlah);
        }

        return back()->with('success', 'Pesanan selesai! Pendapatan diperbarui.');
    }
}