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
use App\Exports\KwtTransactionExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function kwtDashboard()
    {
        $userId = Auth::id();

        $total_received = OrderDetail::whereHas('product', fn($q) => $q->where('user_id', $userId))
            ->whereHas('order', fn($q) => $q->where('status', 'selesai'))
            ->sum(DB::raw('harga_saat_ini * jumlah'));

        $sold_count = OrderDetail::whereHas('product', fn($q) => $q->where('user_id', $userId))
            ->whereHas('order', fn($q) => $q->where('status', 'selesai'))
            ->sum('jumlah');

        $total_products = Product::where('user_id', $userId)->count();

        $pending_orders = Order::whereHas('details.product', fn($q) => $q->where('user_id', $userId))
            ->where('status', 'menunggu')
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

    public function kwtKurirIndex()
    {
        $kurirs = Kurir::latest()->get();
        return view('kwt.kurir', compact('kurirs'));
    }

    public function storeKurir(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'kendaraan' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Kurir::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'kendaraan' => $request->kendaraan,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data kurir berhasil ditambahkan!');
    }

    public function updateKurir(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'kendaraan' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $kurir = Kurir::findOrFail($id);
        $kurir->update([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'kendaraan' => $request->kendaraan,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data kurir berhasil diperbarui!');
    }

    public function destroyKurir($id)
    {
        $kurir = Kurir::findOrFail($id);
        $kurir->delete();

        return redirect()->back()->with('success', 'Data kurir berhasil dihapus!');
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

    public function exportExcel()
    {
        return Excel::download(
            new KwtTransactionExport(Auth::id()),
            'laporan-penjualan-kwt.xlsx'
        );
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

    // 🟢 REVISI TOTAL: FORM WAJIB ATTACH FOTO & ANTI-GAGAL SIMPAN
    public function complete(Request $request, $id)
    {
        // 1. Validasi Ketat: File wajib ada, wajib gambar, dan maksimal 2MB
        $request->validate([
            'bukti_sampai' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'bukti_sampai.required' => 'Gagal! Kamu wajib melampirkan foto bukti penerimaan terlebih dahulu.',
            'bukti_sampai.image'    => 'Berkas harus berupa gambar foto (JPG, PNG, JPEG, WEBP).',
            'bukti_sampai.max'      => 'Ukuran berkas terlalu besar. Maksimal ukuran foto adalah 2 MB.'
        ]);

        // 2. Ambil data order milik customer yang bersangkutan
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // 3. Kunci eksekusi: Hanya bisa diproses jika status saat ini adalah 'diproses'
        if ($order->status !== 'diproses') {
            return redirect()->back()->with('error', 'Pesanan gagal diselesaikan karena status transaksi saat ini tidak valid.');
        }

        // 4. Pengecekan file secara fisik sebelum database diubah
        if ($request->hasFile('bukti_sampai') && $request->file('bukti_sampai')->isValid()) {
            
            // Simpan gambar fisik ke folder: storage/app/public/bukti_kirim
            $path = $request->file('bukti_sampai')->store('bukti_kirim', 'public');
            
            // JIKA FILE BERHASIL DISIMPAN, BARU UPDATE STATUS DATABASE
            $order->update([
                'status' => 'selesai',
                'bukti_sampai' => $path
            ]);

            return redirect()->back()->with('success', 'Pesanan berhasil diselesaikan! Foto bukti Anda telah aman disimpan dalam sistem.');
        }

        // Antisipasi jika file corrupt/rusak saat dikirim dari device user
        return redirect()->back()->with('error', 'Gagal memproses berkas foto. Silakan coba unggah foto bukti lainnya.');
    }
}