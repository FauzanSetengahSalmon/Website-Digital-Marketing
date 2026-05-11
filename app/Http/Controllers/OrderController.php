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

// ⭐ WAJIB UNTUK EXPORT EXCEL
use App\Exports\KwtTransactionExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    /* =====================================================
     | KWT AREA
     ===================================================== */

    public function kwtDashboard()
    {
        $userId = Auth::id();

        $totalReceived = OrderDetail::whereHas('product', fn($q) => $q->where('user_id', $userId))
            ->whereHas('order', fn($q) => $q->where('status', 'selesai'))
            ->sum(DB::raw('harga_saat_ini * jumlah'));

        $soldCount = OrderDetail::whereHas('product', fn($q) => $q->where('user_id', $userId))
            ->whereHas('order', fn($q) => $q->where('status', 'selesai'))
            ->sum('jumlah');

        $totalProducts = Product::where('user_id', $userId)->count();

        $pendingOrders = Order::whereHas('details.product', fn($q) => $q->where('user_id', $userId))
            ->where('status', 'menunggu')
            ->count();

        $stats = compact('totalReceived', 'soldCount', 'totalProducts', 'pendingOrders');

        return view('kwt.dashboard', compact('stats'));
    }

    public function kwtLaporan()
    {
        $userId = Auth::id();

        $orders = Order::with(['user', 'details.product'])
            ->whereHas('details.product', fn($q) => $q->where('user_id', $userId))
            ->latest()->get();

        $totalPendapatan = OrderDetail::whereHas('product', fn($q) => $q->where('user_id', $userId))
            ->whereHas('order', fn($q) => $q->where('status', 'selesai'))
            ->sum(DB::raw('harga_saat_ini * jumlah'));

        return view('kwt.laporan', compact('orders', 'totalPendapatan'));
    }

    public function kwtOrders()
    {
        $orders = Order::with(['user', 'details.product'])
            ->whereHas('details.product', fn($q) => $q->where('user_id', Auth::id()))
            ->latest()->get();

        $list_kurir = Kurir::all();

        return view('kwt.orders', compact('orders', 'list_kurir'));
    }

    public function kwtOrderProcess($id)
    {
        $order = Order::with(['user', 'details.product'])
            ->whereHas('details.product', fn($q) => $q->where('user_id', Auth::id()))
            ->findOrFail($id);

        $list_kurir = Kurir::all();

        return view('kwt.process-orders', compact('order', 'list_kurir'));
    }

    public function kwtOrderDetail($id)
    {
        $order = Order::with(['user', 'details.product'])
            ->whereHas('details.product', fn($q) => $q->where('user_id', Auth::id()))
            ->findOrFail($id);

        return view('kwt.detail-orders', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diterima,ditolak,diproses,selesai,dibatalkan',
            'kurir' => 'nullable',
            'no_hp_kurir' => 'nullable'
        ]);

        $order = Order::whereHas('details.product', fn($q) => $q->where('user_id', Auth::id()))
            ->findOrFail($id);

        $order->update([
            'status' => $request->status,
            'kurir' => $request->kurir,
            'no_hp_kurir' => $request->no_hp_kurir
        ]);

        return redirect()->route('kwt.orders')->with('success', 'Pesanan diperbarui!');
    }

    /* ================= EXPORT EXCEL ================= */

    public function exportExcel()
    {
        return Excel::download(
            new KwtTransactionExport(Auth::id()),
            'laporan-penjualan-kwt.xlsx'
        );
    }

    /* =====================================================
     | CUSTOMER AREA
     ===================================================== */

    public function checkout(Request $request)
    {
        if (!$request->has('items')) {
            return redirect()->route('cart.index')->with('error', 'Pilih produk!');
        }

        $ids = explode(',', $request->query('items'));

        $cartItems = Cart::with('product.user')
            ->whereIn('id', $ids)
            ->where('user_id', Auth::id())
            ->get();

        $subtotal = $cartItems->sum(fn($item) => $item->jumlah * $item->product->harga);
        $ongkir = 2 * 6500;

        return view('customer.checkout', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'ongkir' => $ongkir,
            'totalBayar' => $subtotal + $ongkir,
            'jarak' => 2
        ]);
    }

    public function process(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();

            // 🔥 WAJIB ADA ALAMAT + HP
            if (!$user->address || !$user->phone_number) {
                return back()->with('error', 'Lengkapi alamat & nomor HP di profil!');
            }

            $ids = explode(',', $request->item_ids);

            $cartItems = Cart::with('product')
                ->whereIn('id', $ids)
                ->where('user_id', $user->id)
                ->get();

            if ($cartItems->isEmpty()) throw new \Exception("Cart kosong");

            $subtotal = $cartItems->sum(fn($i) => $i->jumlah * $i->product->harga);
            $ongkir = 2 * 6500;

            /* 🔥 SNAPSHOT DATA CUSTOMER KE ORDER 🔥 */
            $order = Order::create([
                'user_id' => $user->id,
                'total_harga' => $subtotal + $ongkir,
                'ongkir' => $ongkir,
                'status' => 'menunggu',
                'catatan' => $request->catatan,

                // ⭐ INI YANG BIKIN HP CUSTOMER MUNCUL
                'alamat' => $user->address,
                'nomor_hp' => $user->phone_number,

                // default kurir
                'kurir' => 'Menunggu penugasan',
                'no_hp_kurir' => '-'
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

            return redirect()->route('orders.history')
                ->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function history()
    {
        $orders = Order::with(['details.product.user'])
            ->where('user_id', Auth::id())
            ->latest()->get();

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
