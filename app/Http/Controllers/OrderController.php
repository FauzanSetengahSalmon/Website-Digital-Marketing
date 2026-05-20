<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\Kurir;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\KwtTransactionExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    /**
     * Menampilkan Dashboard Ringkasan Statistik Internal KWT
     */
    public function kwtDashboard()
    {
        $userId = Auth::id();

        $total_received = OrderDetail::whereHas('product', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->whereHas('order', function ($q) {
                $q->where('status', 'selesai');
            })
            ->sum(DB::raw('harga_saat_ini * jumlah'));

        $sold_count = OrderDetail::whereHas('product', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->whereHas('order', function ($q) {
                $q->where('status', 'selesai');
            })
            ->sum('jumlah');

        $total_products = Product::where('user_id', $userId)->count();

        $pending_orders = OrderDetail::whereHas('product', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->whereHas('order', function ($q) {
                $q->where('status', 'menunggu');
            })
            ->distinct('order_id')
            ->count();

        $total_kurir = Kurir::count();

        $stats = [
            'total_received' => $total_received,
            'sold_count' => $sold_count,
            'total_products' => $total_products,
            'pending_orders' => $pending_orders,
            'total_kurir' => $total_kurir,
        ];

        return view('kwt.dashboard', compact('stats'));
    }

    /**
     * Menampilkan Laporan Penjualan Internal KWT
     */
    public function kwtLaporan()
    {
        $userId = Auth::id();

        $orders = Order::with([
            'user',
            'details' => function ($q) use ($userId) {
                $q->whereHas('product', function ($p) use ($userId) {
                    $p->where('user_id', $userId);
                })->with('product');
            }
        ])
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
     * Menampilkan Daftar Pesanan Masuk (Sisi KWT)
     */
    public function kwtOrders()
    {
        $userId = Auth::id();

        $orders = Order::with([
            'user',
            'details' => function ($q) use ($userId) {
                $q->whereHas('product', function ($p) use ($userId) {
                    $p->where('user_id', $userId);
                })
                    ->with('product');
            }
        ])
            ->whereHas('details.product', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->latest()
            ->get();

        // TOTAL KHUSUS KWT
        foreach ($orders as $order) {
            $subtotalKwt = $order->details->sum(function ($detail) {
                return $detail->harga_saat_ini * $detail->jumlah;
            });
            $order->total_kwt = $subtotalKwt;
        }

        $list_kurir = Kurir::all();

        return view('kwt.orders', compact('orders', 'list_kurir'));
    }

    /**
     * Memantau Proses Pesanan KWT
     */
    public function kwtOrderProcess($id)
    {
        $userId = Auth::id();

        $order = Order::with([
            'user',
            'details' => function ($q) use ($userId) {
                $q->whereHas('product', function ($p) use ($userId) {
                    $p->where('user_id', $userId);
                })
                    ->with('product');
            }
        ])
            ->whereHas('details.product', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->findOrFail($id);

        $list_kurir = Kurir::all();

        return view('kwt.process-orders', compact('order', 'list_kurir'));
    }

    /**
     * Menampilkan Detail Item Pesanan Kelompok KWT
     */
    public function kwtOrderDetail($id)
    {
        $userId = Auth::id();

        $order = Order::with([
            'user',
            'details' => function ($q) use ($userId) {
                $q->whereHas('product', function ($p) use ($userId) {
                    $p->where('user_id', $userId);
                })
                    ->with('product');
            }
        ])
            ->whereHas('details.product', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->findOrFail($id);

        $order->total_kwt = $order->details->sum(function ($detail) {
            return $detail->harga_saat_ini * $detail->jumlah;
        });

        return view('kwt.detail-orders', compact('order'));
    }

    /**
     * Export Excel Penjualan KWT
     */
    public function exportExcel()
    {
        return Excel::download(
            new KwtTransactionExport(Auth::id()),
            'laporan-penjualan-kwt.xlsx'
        );
    }

    /**
     * Riwayat Pembelian Sisi Customer / Pengguna Umum
     */
    public function history()
    {
        $orders = Order::with(['details.product.user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.riwayat-pesanan', compact('orders'));
    }

    /**
     * Detail Nota Belanja Customer
     */
    public function show($id)
    {
        $order = Order::with(['details.product.user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('customer.detail-pesanan', compact('order'));
    }

    /**
     * Konfirmasi Pesanan Diterima Selesai oleh Customer
     */
    public function complete(Request $request, $id)
    {
        $request->validate([
            'bukti_sampai' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status !== 'diproses') {
            return back()->with('error', 'Status pesanan tidak valid.');
        }

        if ($request->hasFile('bukti_sampai')) {
            $path = $request->file('bukti_sampai')
                ->store('bukti_kirim', 'public');

            $order->update([
                'status' => 'selesai',
                'bukti_sampai' => $path
            ]);

            return back()->with('success', 'Pesanan selesai!');
        }

        return back()->with('error', 'Upload gagal.');
    }
    public function storeReport(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'tipe_pengaduan' => 'required|string',
            'pesan' => 'required|string|max:1000',
        ]);

        $product = Product::findOrFail($request->product_id);

        Report::create([
            'order_id'       => $id,
            'product_id'     => $request->product_id,
            'user_id'        => Auth::id(),
            'kwt_id'         => $product->user_id, // KWT pemilik produk
            'tipe_pengaduan' => $request->tipe_pengaduan,
            'pesan'          => $request->pesan,
            'status'         => 'menunggu'
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil dikirim! Tim kami akan segera menindaklanjuti.');
    }
}
