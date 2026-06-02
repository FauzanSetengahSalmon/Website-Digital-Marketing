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
use Illuminate\Support\Facades\Mail;
use App\Exports\KwtTransactionExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon; // 🌟 TAMBAHAN: Untuk mengecek waktu 1x24 Jam

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
                $q->whereIn('status', ['menunggu', 'diproses', 'diantar']);
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
    public function kwtLaporan(Request $request)
    {
        $userId = Auth::id();
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));

        $orders = Order::with(['user', 'details.product'])
            ->whereHas('details.product', fn($q) => $q->where('user_id', $userId))
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->latest()
            ->get();

        $totalPendapatan = OrderDetail::whereHas('product', fn($q) => $q->where('user_id', $userId))
            ->whereHas('order', fn($q) => $q->where('status', 'selesai')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year))
            ->sum(DB::raw('harga_saat_ini * jumlah'));

        return view('kwt.laporan', compact('orders', 'totalPendapatan', 'month', 'year'));
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
                })->with('product');
            }
        ])
            ->whereHas('details.product', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->latest()
            ->get();

        foreach ($orders as $order) {
            $order->total_kwt = $order->details->sum(function ($detail) {
                return $detail->harga_saat_ini * $detail->jumlah;
            });
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
                })->with('product');
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
                })->with('product');
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
     * SISI KWT: Konfirmasi Ketersediaan Stok (Tersimpan Permanen)
     */
    public function acceptOrder(Request $request, $id)
    {
        $userId = Auth::id();
        $order = Order::with('details.product')->findOrFail($id);

        // 1. Tangkap data checkbox dari Javascript Blade (bentuknya array)
        $stokReadyData = $request->input('stok_ready', []);

        // 2. Ambil detail pesanan HANYA milik KWT yang sedang login
        $details = $order->details->filter(function ($detail) use ($userId) {
            return $detail->product && $detail->product->user_id == $userId;
        });

        // 3. Update database (Pasti berhasil karena sudah ada di $fillable)
        foreach ($details as $detail) {
            // Jika ada data toggle yang dikirim untuk produk ini
            if (isset($stokReadyData[$detail->id])) {
                $isReady = $stokReadyData[$detail->id] == '1' || $stokReadyData[$detail->id] == 1;

                $detail->update([
                    'stok_ready' => $isReady ? 1 : 0,
                    'stok_ready_at' => $isReady ? now() : null,
                ]);
            }
        }
        $belumReady = \App\Models\OrderDetail::where('order_id', $order->id)
            ->where('stok_ready', 0)
            ->exists();

        if (!$belumReady && $order->status === 'menunggu') {
            $order->update(['status' => 'diproses']);
            $message = 'Stok berhasil dikonfirmasi. Pesanan sekarang Diproses Admin!';
        } else {
            $message = 'Ketersediaan stok berhasil dikunci di database.';
        }

        // 5. Response khusus untuk Javascript (AJAX) agar halaman TIDAK REFRESH
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        // Fallback jika Javascript di browser error/mati
        return back()->with('success', $message);
    }

    /**
     * KWT: Tolak / Batalkan Pesanan
     */
    public function rejectOrder(Request $request, $id)
    {
        $request->validate([
            'alasan_tolak' => 'required|string|max:1000'
        ]);

        $userId = Auth::id();
        $order = Order::with(['user', 'details.product'])->whereHas('details.product', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->findOrFail($id);

        // PERBAIKAN: Proteksi agar jika direfresh, stok tidak bertambah ganda
        if ($order->status !== 'batal') {
            foreach ($order->details as $detail) {
                if ($detail->product) {
                    $detail->product->increment('stok', $detail->jumlah);
                }
            }

            $order->update([
                'status' => 'batal',
                'alasan_tolak' => $request->alasan_tolak
            ]);

            try {
                if ($order->user && $order->user->email) {
                    Mail::raw(
                        "Halo {$order->user->name},\n\nMohon maaf, pesanan Anda dengan ID #ORD-{$order->id} terpaksa kami TOLAK dengan alasan: \"{$request->alasan_tolak}\".\n\nDana Anda akan segera diproses untuk pengembalian (Refund).",
                        function ($message) use ($order) {
                            $message->to($order->user->email)
                                ->subject("Pemberitahuan Pembatalan & Refund Pesanan #ORD-{$order->id}");
                        }
                    );
                }
            } catch (\Exception $e) {
                // Abaikan jika error
            }
        }

        return back()->with('success', 'Pesanan berhasil ditolak, stok dikembalikan, dan notifikasi email telah dikirim ke pelanggan.');
    }

    /**
     * SISI KWT / KURIR: Mengunggah Bukti Pengiriman (Barang Mulai Dikirim)
     */
    public function kirimPesanan(Request $request, $id)
    {
        $request->validate([
            'bukti_pengiriman' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $order = Order::findOrFail($id);

        if ($request->hasFile('bukti_pengiriman')) {
            $path = $request->file('bukti_pengiriman')->store('bukti_kirim', 'public');

            // 🌟 PERBAIKAN: Gunakan nama kolom 'bukti_sampai' (sesuai database)
            // Dan ubah status jadi 'diantar' agar tombol selesaikan muncul di customer
            $order->update([
                'bukti_sampai' => $path,
                'status' => 'diantar'
            ]);

            return back()->with('success', 'Bukti pengiriman berhasil diunggah, kurir siap mengantar hasil panen KWT!');
        }

        return back()->with('error', 'Gagal mengunggah gambar.');
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
        // 🌟 SISTEM OTOMATIS: ACC PESANAN 1 HARI (24 JAM) JIKA LUPA DIKLIK
        Order::where('status', 'diantar')
            ->whereNotNull('bukti_sampai')
            ->where('updated_at', '<=', Carbon::now()->subDay()) // Lewat 1x24 Jam
            ->update(['status' => 'selesai']);
        // ==============================================================

        $orders = Order::with(['details.product.user', 'reports'])
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
        // 🌟 SISTEM OTOMATIS: ACC PESANAN 1 HARI (24 JAM) JIKA LUPA DIKLIK
        Order::where('status', 'diantar')
            ->whereNotNull('bukti_sampai')
            ->where('updated_at', '<=', Carbon::now()->subDay()) // Lewat 1x24 Jam
            ->update(['status' => 'selesai']);
        // ==============================================================

        $order = Order::with(['details.product.user', 'reports'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('customer.detail-pesanan', compact('order'));
    }

    /**
     * SISI CUSTOMER: Konfirmasi Pesanan Diterima Selesai oleh Customer
     */
    public function complete(Request $request, $id)
    {
        // Validasi foto (bukti_sampai) dihapus karena itu tugas kurir
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status !== 'diantar') {
            return back()->with('error', 'Status pesanan tidak valid.');
        }

        $order->update([
            'status' => 'selesai',
        ]);

        return back()->with('success', 'Pesanan berhasil diselesaikan! Terima kasih.');
    }

    /**
     * Menyimpan Laporan Komplain Pengaduan Pelanggan
     */
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
            'kwt_id'         => $product->user_id,
            'tipe_pengaduan' => $request->tipe_pengaduan,
            'pesan'          => $request->pesan,
            'status'         => 'menunggu'
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil dikirim! Tim kami akan segera menindaklanjuti.');
    }

    /**
     * SISI CUSTOMER: Mengajukan Refund
     */
    public function ajukanRefund(Request $request, $id)
    {
        $request->validate([
            'alasan_refund' => 'required|string|max:1000',
            'bukti_refund'  => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if (!in_array($order->status, ['diantar', 'selesai']) || $order->status_refund !== 'tidak_ada') {
            return back()->with('error', 'Pesanan ini tidak memenuhi syarat untuk direfund.');
        }

        if ($request->hasFile('bukti_refund')) {
            $path = $request->file('bukti_refund')->store('bukti_refunds', 'public');

            $order->update([
                'status_refund' => 'diajukan',
                'alasan_refund' => $request->alasan_refund,
                'bukti_refund'  => $path,
            ]);

            return back()->with('success', 'Pengajuan pengembalian dana (Refund) berhasil dikirim. Menunggu verifikasi Admin.');
        }

        return back()->with('error', 'Gagal mengunggah bukti foto.');
    }
}
