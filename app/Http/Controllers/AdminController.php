<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Kurir;
use App\Models\OrderDetail;
use App\Models\Kwt;
use App\Models\KurirPencairan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;

class AdminController extends Controller
{
    /**
     * Dashboard Admin
     */
    public function dashboard()
    {
        $totalKwt = User::where('role', 'kwt')->count();
        $totalProduk = Product::count();
        $totalPesanan = Order::count();
        $totalPendapatan = Order::where('status', 'selesai')->sum('total_harga');

        $stats = [
            'total_customer' => User::where('role', 'customer')->count(),
            'order_aktif'    => Order::whereIn('status', ['menunggu', 'diproses', 'diantar'])->count(),
        ];

        $kwts = User::where('role', 'kwt')->with('products')->latest()->get();

        $penjualanPerKwt = User::where('role', 'kwt')
            ->get()
            ->map(function ($kwt) {
                $omzet = OrderDetail::whereHas('product', function ($q) use ($kwt) {
                    $q->where('user_id', $kwt->id);
                })
                    ->whereHas('order', function ($q) {
                        $q->where('status', 'selesai');
                    })
                    ->sum(DB::raw('harga_saat_ini * jumlah'));

                return [
                    'nama' => $kwt->name,
                    'omzet' => $omzet,
                    'produk_count' => $kwt->products()->count()
                ];
            })->sortByDesc('omzet');

        $penjualanPerKurir = \App\Models\Kurir::all()->map(function ($kurir) {
            $totalOngkir = \App\Models\Order::where('kurir', $kurir->nama)
                ->where('status', 'selesai')
                ->sum('ongkir');
            return [
                'nama' => $kurir->nama,
                'total_ongkir' => $totalOngkir
            ];
        })->sortByDesc('total_ongkir');

        return view('admin.dashboard', compact(
            'totalKwt',
            'totalProduk',
            'totalPesanan',
            'totalPendapatan',
            'kwts',
            'penjualanPerKwt',
            'penjualanPerKurir',
            'stats'
        ));
    }

    public function usersIndex()
    {
        $users = User::where('role', 'customer')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function allSales()
    {
        $sales = Order::with(['user', 'details.product.user'])->latest()->get();
        $list_kurir = Kurir::where('status', 'aktif')->get();
        return view('admin.sales.index', compact('sales', 'list_kurir'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        // PERBAIKAN: Load relasi product sekalian untuk proses kembalikan stok
        $order = Order::with('details.product')->findOrFail($id);
        $status = $request->input('status');

        if ($status === 'batal') {
            // PERBAIKAN: Cek agar tidak double-return stok jika halaman direfresh
            if ($order->status !== 'batal') {
                // PERBAIKAN: Kembalikan stok saat admin yang membatalkan pesanan
                foreach ($order->details as $detail) {
                    if ($detail->product) {
                        $detail->product->increment('stok', $detail->jumlah);
                    }
                }
                $order->update(['status' => 'batal']);
            }
            return redirect()->back()->with('success', 'Pesanan #' . $id . ' berhasil dibatalkan dan stok dikembalikan.');
        }

        if ($status === 'diantar') {
            $order->update(['status' => 'diantar']);
            return redirect()->back()->with('success', 'Pesanan #' . $id . ' statusnya diubah menjadi Pesanan Diantar.');
        }

        if ($status === 'diproses') {
            $request->validate([
                'kurir' => 'required|string|max:255',
                'no_hp_kurir' => 'required|string|max:20',
                'jadwal_pengiriman' => 'required|date|after_or_equal:today'
            ]);

            $order->update([
                'status' => 'diproses',
                'kurir' => $request->input('kurir'),
                'no_hp_kurir' => $request->input('no_hp_kurir'),
                'jadwal_pengiriman' => $request->input('jadwal_pengiriman')
            ]);

            return redirect()->back()->with('success', 'Pesanan #' . $id . ' berhasil dijadwalkan!');
        }

        return redirect()->back()->with('error', 'Status tidak dikenali.');
    }

    public function kwtIndex()
    {
        $kwt = User::where('role', 'kwt')->latest()->get();
        return view('admin.kwt.index', compact('kwt'));
    }

    public function storeKwt(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => 'kwt',
        ]);

        event(new Registered($user));
        return back()->with('success', 'Akun KWT berhasil dibuat dan otomatis terverifikasi!');
    }

    public function verifyKwt($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'email_verified_at' => now()
        ]);

        return back()->with('success', 'Akun KWT ' . $user->name . ' berhasil diverifikasi!');
    }

    public function updateKwt(Request $request, $id)
    {
        $kwt = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $kwt->id,
            'phone_number' => 'required|string|max:20',
            'password' => 'nullable|string|min:8',
        ]);

        $data = ['name' => $request->name, 'email' => $request->email, 'phone_number' => $request->phone_number];
        if ($request->filled('password')) $data['password'] = Hash::make($request->password);
        $kwt->update($data);

        return back()->with('success', 'Data KWT diperbarui!');
    }

    public function destroyKwt($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Akun KWT dihapus.');
    }

    public function adminKurirIndex()
    {
        $kurirs = Kurir::latest()->get();
        foreach ($kurirs as $kurir) {
            $totalOngkir = Order::where('kurir', $kurir->nama)->where('status', 'selesai')->sum('ongkir');
            $kurir->total_ongkir = $totalOngkir;

            // 🌟 PERBAIKAN: Potongan 15% dihapuskan. Pendapatan bersih = 100% Ongkir
            $kurir->potongan_admin = 0;
            $kurir->pendapatan_bersih = $totalOngkir;
        }
        return view('admin.kurir', compact('kurirs'));
    }

    public function storeKurir(Request $request)
    {
        $request->validate(['nama' => 'required', 'no_hp' => 'required', 'status' => 'required']);
        Kurir::create($request->all());
        return redirect()->back()->with('success', 'Kurir ditambahkan!');
    }

    public function updateKurir(Request $request, $id)
    {
        Kurir::findOrFail($id)->update($request->all());
        return redirect()->back()->with('success', 'Kurir diperbarui!');
    }

    public function destroyKurir($id)
    {
        Kurir::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Kurir dihapus!');
    }

    public function printInvoiceKwt($id)
    {
        $sale = Order::with(['user', 'details.product.user'])->findOrFail($id);
        $groupedByKwt = $sale->details->groupBy(fn($d) => $d->product->user->name ?? 'KWT Umum');
        return view('admin.sales.invoice_kwt', compact('sale', 'groupedByKwt'));
    }

    public function printInvoiceKurir($id)
    {
        $sale = Order::with('user')->findOrFail($id);
        return view('admin.sales.invoice_kurir', compact('sale'));
    }

    public function printInvoiceKwtBatch(Request $request)
    {
        $ids = explode(',', $request->query('ids', ''));
        $sales = Order::with(['user', 'details.product.user'])->whereIn('id', $ids)->get();

        $allGrouped = [];
        foreach ($sales as $sale) {
            $allGrouped[$sale->id] = [
                'sale' => $sale,
                'grouped' => $sale->details->groupBy(fn($d) => $d->product->user->name ?? 'KWT Umum')
            ];
        }

        return view('admin.sales.invoice_kwt_batch', compact('allGrouped'));
    }

    public function printInvoiceKurirBatch(Request $request)
    {
        $ids = explode(',', $request->query('ids', ''));
        $sales = Order::with('user')->whereIn('id', $ids)->get();

        return view('admin.sales.invoice_kurir_batch', compact('sales'));
    }

    public function reportKurir(Request $request, $id)
    {
        $kurir = Kurir::findOrFail($id);
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));

        $orders = Order::where('kurir', $kurir->nama)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();

        $totalOngkir = $orders->where('status', 'selesai')->sum('ongkir');

        // 🌟 PERBAIKAN: Potongan 15% dihapuskan. Pendapatan bersih = 100% Ongkir
        $potonganAdmin = 0;
        $pendapatanBersih = $totalOngkir;

        return view('admin.sales.kurir_laporan', compact(
            'kurir',
            'orders',
            'totalOngkir',
            'potonganAdmin',
            'pendapatanBersih',
            'month',
            'year'
        ));
    }

    public function cairkan(Request $request, $kwt_id)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'nama_penerima' => 'required|string|max:255'
        ]);

        Order::whereIn('id', $request->order_ids)->update([
            'is_paid_out' => true,
            'nama_penerima' => $request->nama_penerima
        ]);

        return back()->with('printed_ids', $request->order_ids)
            ->with('penerima', $request->nama_penerima)
            ->with('success', 'Transaksi berhasil dicairkan.');
    }

    public function riwayatPencairanKurir()
    {
        $pencairan = KurirPencairan::latest()->get();
        $list_kurir = Kurir::where('status', 'aktif')->get();

        return view('admin.kurir.pencairan', compact(
            'pencairan',
            'list_kurir'
        ));
    }

    public function storePencairanKurir(Request $request)
    {
        $request->validate(['nama_kurir' => 'required', 'nama_penerima' => 'required', 'total_cair' => 'required']);
        KurirPencairan::create($request->all());
        return back()->with('success', 'Pencairan berhasil dicatat!');
    }

    public function cairkanKurir(Request $request, $id)
    {
        $kurir = Kurir::findOrFail($id);
        KurirPencairan::create([
            'nama_kurir' => $kurir->nama,
            'nama_penerima' => $request->nama_penerima,
            'total_cair' => $request->total_cair
        ]);
        Order::where('kurir', $kurir->nama)->where('status', 'selesai')->update(['is_paid_out' => true]);
        return back()->with('success', 'Pencairan berhasil!');
    }

    public function reportKwt(Request $request, $id)
    {
        $kwt = User::where('id', $id)->where('role', 'kwt')->firstOrFail();
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));

        $orders = Order::with(['user', 'details.product'])
            ->whereHas('details.product', fn($q) => $q->where('user_id', $kwt->id))
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->latest()
            ->get();

        $totalPendapatan = OrderDetail::whereHas('product', fn($q) => $q->where('user_id', $kwt->id))
            ->whereHas('order', function ($q) use ($month, $year) {
                $q->where('status', 'selesai')
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year);
            })
            ->sum(DB::raw('harga_saat_ini * jumlah'));

        return view('admin.kwt.laporan', compact('kwt', 'orders', 'totalPendapatan', 'month', 'year'));
    }

    /**
     * SISI ADMIN: Verifikasi (Terima/Tolak) Pengajuan Refund Customer
     */
    public function prosesRefund(Request $request, $id)
    {
        $request->validate([
            'keputusan' => 'required|in:disetujui,ditolak',
            'catatan_admin_refund' => 'nullable|string|max:1000'
        ]);

        $order = Order::with(['user', 'details.product'])->findOrFail($id);

        if ($request->keputusan == 'disetujui') {
            // PERBAIKAN: Cek dulu agar stok tidak dobel balik kalau disubmit berkali-kali
            if ($order->status_refund !== 'disetujui') {
                foreach ($order->details as $detail) {
                    if ($detail->product) {
                        $detail->product->increment('stok', $detail->jumlah);
                    }
                }

                $order->update([
                    'status_refund' => 'disetujui',
                    'status' => 'batal',
                    'catatan_admin_refund' => $request->catatan_admin_refund
                ]);

                try {
                    if ($order->user && $order->user->email) {
                        $total_rp = number_format($order->total_harga, 0, ',', '.');
                        $catatan = $request->catatan_admin_refund ?? 'Sesuai dengan pengajuan komplain Anda.';

                        Mail::raw(
                            "Halo {$order->user->name},\n\nPengajuan Refund (Pengembalian Dana) Anda untuk pesanan #ORD-{$order->id} telah DISETUJUI oleh Admin.\n\nDetail Pengajuan Anda:\n- Alasan Komplain: \"{$order->alasan_refund}\"\n\nTotal Dana yang Dikembalikan: Rp {$total_rp}\nCatatan Admin: \"{$catatan}\"\n\nDana Anda akan segera diproses. Terima kasih.",
                            function ($message) use ($order) {
                                $message->to($order->user->email)
                                    ->subject("Refund Disetujui: Pesanan #ORD-{$order->id}");
                            }
                        );
                    }
                } catch (\Exception $e) {
                    // Abaikan jika email gagal terkirim
                }
            }
            return back()->with('success', 'Refund disetujui. Dana dikembalikan ke pelanggan dan stok dipulihkan.');
        } else {
            $order->update([
                'status_refund' => 'ditolak',
                'catatan_admin_refund' => $request->catatan_admin_refund
            ]);

            try {
                if ($order->user && $order->user->email) {
                    $catatan = $request->catatan_admin_refund ?? 'Bukti yang dilampirkan tidak memenuhi syarat pengembalian.';

                    Mail::raw(
                        "Halo {$order->user->name},\n\nMohon maaf, Pengajuan Refund (Pengembalian Dana) Anda untuk pesanan #ORD-{$order->id} DITOLAK oleh Admin.\n\nDetail Pengajuan Anda:\n- Alasan Komplain: \"{$order->alasan_refund}\"\n\nCatatan Admin: \"{$catatan}\"\n\nJika ada pertanyaan lebih lanjut, silakan hubungi layanan pelanggan kami.",
                        function ($message) use ($order) {
                            $message->to($order->user->email)
                                ->subject("Refund Ditolak: Pesanan #ORD-{$order->id}");
                        }
                    );
                }
            } catch (\Exception $e) {
                // Abaikan error email
            }

            return back()->with('success', 'Pengajuan refund ditolak. Pesanan dilanjutkan.');
        }
    }
}
