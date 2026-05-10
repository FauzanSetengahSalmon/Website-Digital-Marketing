<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\Kurir; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Dashboard untuk KWT
     */
    public function kwtDashboard()
    {
        $userId = Auth::id();

        $totalReceived = OrderDetail::whereHas('product', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->whereHas('order', function ($q) {
                $q->where('status', 'selesai');
            })
            ->sum(DB::raw('harga_saat_ini * jumlah'));

        $soldCount = OrderDetail::whereHas('product', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->whereHas('order', function ($q) {
                $q->where('status', 'selesai');
            })
            ->sum('jumlah');

        $totalProducts = Product::where('user_id', $userId)->count();

        $pendingOrders = Order::whereHas('details.product', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->where('status', 'menunggu')
            ->count();

        $stats = [
            'total_received' => $totalReceived,
            'sold_count' => $soldCount,
            'total_products' => $totalProducts,
            'pending_orders' => $pendingOrders,
        ];

        return view('kwt.dashboard', compact('stats'));
    }

    /**
     * Laporan Penjualan KWT
     */
    public function kwtLaporan()
    {
        $userId = Auth::id();

        $orders = Order::with(['user', 'details.product'])
            ->whereHas('details.product', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->latest()
            ->get();

        $totalPendapatan = OrderDetail::whereHas('product', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->whereHas('order', function ($q) {
                $q->where('status', 'selesai');
            })
            ->sum(DB::raw('harga_saat_ini * jumlah'));

        return view('kwt.laporan', compact('orders', 'totalPendapatan'));
    }

    /**
     * Daftar Pesanan Masuk (Route: kwt.orders)
     */
    public function kwtOrders()
    {
        $orders = Order::with(['user', 'details.product'])
            ->whereHas('details.product', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->latest()
            ->get();

        $list_kurir = Kurir::all(); 

        return view('kwt.orders', compact('orders', 'list_kurir'));
    }

    /**
     * Halaman Proses Pesanan (Route: kwt.orders.process)
     * INI FUNGSI YANG TADI BIKIN ERROR KARENA HILANG
     */
    public function kwtOrderProcess($id)
    {
        $order = Order::with(['user', 'details.product'])
            ->whereHas('details.product', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->findOrFail($id);

        $list_kurir = Kurir::all();

        return view('kwt.process-orders', compact('order', 'list_kurir'));
    }

    /**
     * Detail Pesanan KWT (Route: kwt.orders.detail)
     */
    public function kwtOrderDetail($id)
    {
        $order = Order::with(['user', 'details.product'])
            ->whereHas('details.product', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->findOrFail($id);

        return view('kwt.detail-orders', compact('order'));
    }

    /**
     * Update Status & Kurir (Route: kwt.order.status)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diterima,ditolak,diproses,selesai,dibatalkan',
            'kurir' => 'nullable',
            'no_hp_kurir' => 'nullable'
        ]);

        $order = Order::whereHas('details.product', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        $order->update([
            'status' => $request->status,
            'kurir' => $request->kurir,
            'no_hp_kurir' => $request->no_hp_kurir
        ]);

        return redirect()->route('kwt.orders')->with('success', 'Pesanan berhasil diperbarui!');
    }

    /**
     * Reset Laporan KWT
     */
    public function resetLaporan()
    {
        $userId = Auth::id();
        OrderDetail::whereHas('product', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->delete();

        return back()->with('success', 'Data laporan berhasil direset.');
    }

    /* |--------------------------------------------------------------------------
    | CUSTOMER METHODS
    |--------------------------------------------------------------------------
    */

    public function checkout(Request $request)
    {
        if (!$request->has('items')) {
            return redirect()->route('cart.index')->with('error', 'Pilih produk!');
        }
        
        $ids = explode(',', $request->query('items'));
        $cartItems = Cart::with('product.user')->whereIn('id', $ids)->where('user_id', Auth::id())->get();
        
        $subtotal = $cartItems->sum(fn($item) => $item->jumlah * $item->product->harga);
        $jarak = 2; 
        $ongkir = $jarak * 6500;

        return view('customer.checkout', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'ongkir' => $ongkir,
            'totalBayar' => $subtotal + $ongkir,
            'jarak' => $jarak
        ]);
    }

    public function process(Request $request)
    {
        DB::beginTransaction();
        try {
            $ids = explode(',', $request->item_ids);
            $cartItems = Cart::with('product')->whereIn('id', $ids)->where('user_id', Auth::id())->get();
            
            if ($cartItems->isEmpty()) throw new \Exception("Item tidak ditemukan.");

            $subtotal = $cartItems->sum(fn($item) => $item->jumlah * $item->product->harga);
            $jarak = 2;
            $ongkir = $jarak * 6500;

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_harga' => $subtotal + $ongkir,
                'ongkir' => $ongkir,
                'status' => 'menunggu',
                'catatan' => $request->catatan,
            ]);

            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'jumlah' => $item->jumlah,
                    'harga_saat_ini' => $item->product->harga,
                ]);

                $item->product->decrement('stok', $item->jumlah);
                $item->delete();
            }

            DB::commit();
            return redirect()->route('orders.history');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function history()
    {
        $orders = Order::with(['details.product.user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        return view('customer.riwayat-pesanan', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['details.product.user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        return view('customer.detail-pesanan', compact('order'));
    }
}