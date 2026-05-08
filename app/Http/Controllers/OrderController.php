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
     * DASHBOARD KWT
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
     * CHECKOUT
     */
    public function checkout(Request $request)
    {
        if (!$request->has('items')) {
            return redirect()->route('cart.index')
                ->with('error', 'Pilih produk terlebih dahulu!');
        }

        $ids = explode(',', $request->query('items'));

        $cartItems = Cart::with('product.user')
            ->whereIn('id', $ids)
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong!');
        }

        $subtotal = $cartItems->sum(fn($item) =>
            $item->jumlah * $item->product->harga
        );

        $jarak = 2;
        $ongkir = $jarak * 6500;
        $totalBayar = $subtotal + $ongkir;

        return view('customer.checkout', compact(
            'cartItems',
            'subtotal',
            'ongkir',
            'totalBayar',
            'jarak'
        ));
    }

    /**
     * PROCESS ORDER
     */
    public function process(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'item_ids' => 'required',
            ]);

            $ids = explode(',', $request->item_ids);

            $cartItems = Cart::with('product')
                ->whereIn('id', $ids)
                ->where('user_id', Auth::id())
                ->get();

            if ($cartItems->isEmpty()) {
                throw new \Exception('Keranjang kosong.');
            }

            $subtotal = $cartItems->sum(fn($item) =>
                $item->jumlah * $item->product->harga
            );

            $ongkir = 2 * 6500;

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_harga' => $subtotal + $ongkir,
                'ongkir' => $ongkir,
                'status' => 'menunggu',
                'catatan' => $request->catatan,
            ]);

            foreach ($cartItems as $item) {

                // VALIDASI STOK
                if ($item->jumlah > $item->product->stok) {
                    throw new \Exception(
                        'Stok produk ' .
                        $item->product->nama_produk .
                        ' tidak mencukupi.'
                    );
                }

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'jumlah' => $item->jumlah,
                    'harga_saat_ini' => $item->product->harga,
                ]);

                // KURANGI STOK
                $item->product->decrement('stok', $item->jumlah);

                // HAPUS CART
                $item->delete();
            }

            DB::commit();

            return redirect()
                ->route('orders.history')
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->route('cart.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * HISTORY CUSTOMER
     */
    public function history()
    {
        $orders = Order::with([
            'details.product',
            'user'
        ])
        ->where('user_id', Auth::id())
        ->latest()
        ->get();

        return view('customer.riwayat-pesanan', compact('orders'));
    }

    /**
     * DETAIL HISTORY CUSTOMER
     */
    public function show($id)
    {
        $order = Order::with([
            'details.product',
            'user'
        ])
        ->where('user_id', Auth::id())
        ->findOrFail($id);

        return view('customer.detail-pesanan', compact('order'));
    }

    /**
     * LIST PESANAN KWT
     */
    public function kwtOrders()
    {
        $orders = Order::with([
            'user',
            'details.product'
        ])
        ->whereHas('details.product', function ($q) {
            $q->where('user_id', Auth::id());
        })
        ->latest()
        ->get();

        return view('kwt.orders', compact('orders'));
    }

    /**
     * DETAIL PESANAN KWT
     */
    public function kwtOrderDetail($id)
    {
        $order = Order::with([
            'user',
            'details.product'
        ])
        ->whereHas('details.product', function ($q) {
            $q->where('user_id', Auth::id());
        })
        ->findOrFail($id);

        return view('kwt.detail-order', compact('order'));
    }

    /**
     * UPDATE STATUS
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai,dibatalkan'
        ]);

        $order = Order::whereHas('details.product', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        $order->update([
            'status' => $request->status
        ]);

        return back()->with(
            'success',
            'Status pesanan berhasil diperbarui!'
        );
    }
}