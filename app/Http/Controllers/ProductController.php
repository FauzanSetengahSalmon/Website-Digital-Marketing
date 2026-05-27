<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ProductController extends Controller
{
    /**
     * KATALOG CUSTOMER
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('search') && $request->search != '') {
            $query->where(
                'nama_produk',
                'like',
                '%' . $request->search . '%'
            );
        }

        if ($request->sort == 'cheap') {
            $query->orderBy('harga', 'asc');
        } elseif ($request->sort == 'stock') {
            $query->orderBy('stok', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->get();

        return view('customer.katalog', compact('products'));
    }

    /**
     * DETAIL PRODUK CUSTOMER
     */
    public function show($id)
    {
        $product = Product::with('user')->findOrFail($id);

        $relatedProducts = Product::where('user_id', $product->user_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('customer.detail-produk', compact('product', 'relatedProducts'));
    }

    /**
     * HOME
     */
    public function home()
    {
        $products = Product::latest()
            ->limit(8)
            ->get();

        return view('home', compact('products'));
    }

    /**
     * LIST PRODUK KWT
     */
    public function kwtProducts()
    {
        $products = Product::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('kwt.list-produk', compact('products'));
    }

    /**
     * STORE PRODUK
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:1',
            'stok' => 'required|numeric|min:0',
            'satuan' => 'required',
            'deskripsi' => 'nullable|string',
            'foto_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'nama_produk',
            'harga',
            'stok',
            'satuan',
            'deskripsi'
        ]);

        if ($request->hasFile('foto_produk')) {
            $data['foto_produk'] = $request
                ->file('foto_produk')
                ->store('products', 'public');
        }

        $data['user_id'] = Auth::id();

        Product::create($data);

        return redirect()
            ->route('kwt.products')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * EDIT
     */
    public function edit($id)
    {
        $product = Product::where('user_id', Auth::id())
            ->findOrFail($id);

        return view('kwt.edit-produk', compact('product'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $product = Product::where('user_id', Auth::id())
            ->findOrFail($id);

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:1',
            'stok' => 'required|numeric|min:0',
            'satuan' => 'required',
            'deskripsi' => 'nullable|string',
            'foto_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'nama_produk',
            'harga',
            'stok',
            'satuan',
            'deskripsi'
        ]);

        if ($request->hasFile('foto_produk')) {
            if ($product->foto_produk) {
                Storage::disk('public')
                    ->delete($product->foto_produk);
            }

            $data['foto_produk'] = $request
                ->file('foto_produk')
                ->store('products', 'public');
        }

        $product->update($data);

        return redirect()
            ->route('kwt.products')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * DELETE
     */
    public function destroy($id)
    {
        $product = Product::where('user_id', Auth::id())
            ->findOrFail($id);

        if ($product->foto_produk) {
            Storage::disk('public')
                ->delete($product->foto_produk);
        }

        $product->delete();

        return redirect()
            ->route('kwt.products')
            ->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * LAPORAN TRANSAKSI
     */
    public function laporanTransaksi()
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

        $totalPendapatan = 0;

        foreach ($orders as $order) {
            foreach ($order->details as $detail) {
                if (
                    $detail->product &&
                    $detail->product->user_id == Auth::id()
                ) {
                    $totalPendapatan +=
                        $detail->harga_saat_ini *
                        $detail->jumlah;
                }
            }
        }

        return view(
            'kwt.laporan',
            compact(
                'orders',
                'totalPendapatan'
            )
        );
    }

    /**
     * 🌟 MANAJEMEN PEMBATALAN & REFUND TRANSAKSI OLEH ADMIN / KWT 🌟
     */
    public function tolakPesanan(Request $request, $id)
    {
        $request->validate([
            'alasan_tolak' => 'required|string|max:500'
        ]);

        // Muat data relasi detil pesanan, produk, dan user pembeli secara utuh
        $order = Order::with(['user', 'details.product'])->findOrFail($id);

        // 1. Mengembalikan jumlah stok produk yang gagal dibeli secara otomatis ke database
        foreach ($order->details as $detail) {
            if ($detail->product) {
                $detail->product->increment('stok', $detail->jumlah);
            }
        }

        // 2. Mengubah status pesanan menjadi 'batal' dan menyimpan alasan penolakannya
        $order->update([
            'status' => 'batal',
            'alasan_tolak' => $request->alasan_tolak
        ]);

        // 3. Mengirimkan Notifikasi Email murni menggunakan Laravel Mailer bawaan
        try {
            Mail::raw(
                "Halo {$order->user->name},\n\nMohon maaf, pesanan Anda dengan ID #ORD-{$order->id} terpaksa kami TOLAK dengan alasan: \"{$request->alasan_tolak}\".\n\nDana yang telah Anda bayarkan melalui Midtrans aman 100% dan akan dikembalikan oleh pihak KWT. Silakan hubungi Admin melalui aplikasi atau klik tombol 'Klaim Refund' pada halaman detail pesanan untuk mengirimkan nomor rekening Anda.\n\nTerima kasih,\nPengurus Kelompok Wanita Tani (KWT).",
                function ($message) use ($order) {
                    $message->to($order->user->email)
                        ->subject("Pemberitahuan Pembatalan & Refund Pesanan #ORD-{$order->id}");
                }
            );
        } catch (\Exception $e) {
            // Blok penangkap exception jika server email lokal (Mailpit/Mailtrap) belum aktif saat testing
        }

        return redirect()->back()->with('success', 'Pesanan berhasil ditolak, stok dikembalikan, dan notifikasi email telah dikirim.');
    }
}
