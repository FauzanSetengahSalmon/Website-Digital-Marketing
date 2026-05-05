<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Dashboard Statistik KWT
     */
    public function kwtDashboard()
    {
        $userId = Auth::id();
        $totalReceived = Order::where('status', 'selesai')
            ->whereHas('details.product', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->sum('total_harga');

        $soldCount = OrderDetail::whereHas('product', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->whereHas('order', function($q) {
                $q->where('status', 'selesai');
            })->sum('jumlah');

        $totalProducts = Product::where('user_id', $userId)->count();

        $pendingOrders = Order::where('status', 'menunggu')
            ->whereHas('details.product', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count();

        $stats = [
            'total_received' => $totalReceived,
            'sold_count'     => $soldCount,
            'total_products' => $totalProducts,
            'pending_orders' => $pendingOrders,
        ];

        return view('kwt.dashboard', compact('stats'));
    }

    /**
     * Halaman Checkout (Setelah pilih barang di keranjang)
     */
    public function checkout(Request $request)
    {
        if (!$request->has('items')) {
            return redirect()->route('cart.index')->with('error', 'Pilih produk di keranjang terlebih dahulu!');
        }

        $ids = explode(',', $request->query('items'));
        $cartItems = Cart::with('product.user')->whereIn('id', $ids)->where('user_id', Auth::id())->get();
        
        if($cartItems->isEmpty()) return redirect()->route('cart.index');

        $subtotal = $cartItems->sum(fn($item) => $item->jumlah * $item->product->harga);
        $jarak = 2; // Default jarak (bisa dinamis nantinya)
        $ongkir = $jarak * 6500;
        $totalBayar = $subtotal + $ongkir;

        return view('customer.checkout', compact('cartItems', 'subtotal', 'ongkir', 'totalBayar', 'jarak'));
    }

    /**
     * Proses Simpan Pesanan ke Database
     */
    public function process(Request $request)
    {
        DB::beginTransaction();
        try {
            $ids = explode(',', $request->item_ids);
            $cartItems = Cart::with('product')->whereIn('id', $ids)->where('user_id', Auth::id())->get();
            
            if($cartItems->isEmpty()) throw new \Exception("Keranjang kosong atau item tidak ditemukan.");

            $subtotal = $cartItems->sum(fn($item) => $item->jumlah * $item->product->harga);
            $ongkir = 2 * 6500; // Samakan dengan logic di checkout

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_harga' => $subtotal + $ongkir,
                'ongkir' => $ongkir,
                'status' => 'menunggu'
            ]);

            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'jumlah' => $item->jumlah,
                    'harga_saat_ini' => $item->product->harga, // Sesuai field DB kamu
                ]);
                $item->delete(); 
            }

            DB::commit();
            return redirect()->route('orders.history')->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Halaman Riwayat Pesanan (Sisi Customer)
     */
    public function history() 
    {
        $orders = Order::with('details.product')
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();
        
        // SUDAH DIBENERIN: Memanggil riwayat-pesanan.blade.php
        return view('customer.riwayat-pesanan', compact('orders'));
    }

    /**
     * List Pesanan Masuk (Sisi KWT/Penjual)
     */
    public function kwtOrders() 
    {
        $orders = Order::with(['user', 'details.product'])
                  ->whereHas('details.product', function($q) {
                      $q->where('user_id', Auth::id());
                  })->latest()->get();

        return view('kwt.orders', compact('orders'));
    }

    /**
     * Update Status oleh KWT (Terima/Tolak/Selesai)
     */
    public function updateStatus(Request $request, $id) 
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan diperbarui!');
    }
}