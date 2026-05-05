<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // ... (Fungsi index, home, kwtProducts, store, edit, update, destroy tetap sama seperti kodinganmu)

    public function index(Request $request)
    {
        $query = Product::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }
        if ($request->sort == 'cheap') {
            $query->orderBy('harga', 'asc');
        } elseif ($request->sort == 'stock') {
            $query->orderBy('stok', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        $products = $query->get();
        return view('customer.katalog', compact('products'));
    }

    public function home()
    {
        $products = Product::limit(8)->get();
        return view('home', compact('products'));
    }

    public function kwtProducts()
    {
        $products = Product::all();
        return view('kwt.list-produk', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga'       => 'required|numeric',
            'stok'        => 'required|numeric',
            'satuan'      => 'required',
            'foto_produk' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);
        $data = $request->only(['nama_produk', 'harga', 'stok', 'satuan']);
        if ($request->hasFile('foto_produk')) {
            $data['foto_produk'] = $request->file('foto_produk')->store('products', 'public');
        }
        $data['user_id'] = Auth::id();
        Product::create($data);
        return redirect()->route('kwt.products')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('kwt.edit-produk', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga'       => 'required|numeric',
            'stok'        => 'required|numeric',
            'satuan'      => 'required',
            'foto_produk' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);
        $data = $request->only(['nama_produk', 'harga', 'stok', 'satuan']);
        if ($request->hasFile('foto_produk')) {
            if ($product->foto_produk) {
                Storage::disk('public')->delete($product->foto_produk);
            }
            $data['foto_produk'] = $request->file('foto_produk')->store('products', 'public');
        }
        $product->update($data);
        return redirect()->route('kwt.products')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->foto_produk) {
            Storage::disk('public')->delete($product->foto_produk);
        }
        $product->delete();
        return redirect()->route('kwt.products')->with('success', 'Produk berhasil dihapus!');
    }

    // --- 7. FUNGSI LAPORAN TRANSAKSI (DIPERBAIKI) ---
    public function laporanTransaksi()
    {
        // Ambil semua data order yang statusnya 'success' atau 'selesai' 
        // Jika ingin semua status muncul, hapus bagian ->where(...)
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
        
        // Total pendapatan dari semua transaksi yang ada di list
        $totalPendapatan = $orders->sum('total_harga');

        return view('kwt.laporan', compact('orders', 'totalPendapatan'));
    }
}