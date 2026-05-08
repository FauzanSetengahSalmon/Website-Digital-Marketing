<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
            'foto_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'nama_produk',
            'harga',
            'stok',
            'satuan'
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
            'foto_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'nama_produk',
            'harga',
            'stok',
            'satuan'
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
}