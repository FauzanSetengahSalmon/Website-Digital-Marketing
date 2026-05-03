<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // 1. Tampilan Katalog untuk Customer (DIPERBARUI)
    public function index(Request $request)
    {
        // Gunakan query builder agar bisa difilter
        $query = Product::query();

        // Fitur Pencarian Dinamis
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        // Fitur Sorting/Filter
        if ($request->sort == 'cheap') {
            $query->orderBy('harga', 'asc'); // Termurah
        } elseif ($request->sort == 'stock') {
            $query->orderBy('stok', 'desc'); // Stok Terbanyak[cite: 2]
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->get(); // Ambil data hasil filter[cite: 2]

        return view('customer.katalog', compact('products'));
    }

    public function home()
    {
        // Mengambil maksimal 6 produk dari tabel products
        $products = Product::limit(8)->get();

        return view('home', compact('products'));
    }

    // 2. Tampilan List Produk KHUSUS KWT (Halaman Admin KWT)
    public function kwtProducts()
    {
        // Menampilkan semua produk milik KWT[cite: 2]
        $products = Product::all();
        return view('kwt.list-produk', compact('products'));
    }

    // 3. Simpan Produk Baru (Tambah)
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
            'satuan' => 'required',
            'foto_produk' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto_produk')) {
            // Simpan foto ke folder public/products[cite: 2]
            $data['foto_produk'] = $request->file('foto_produk')->store('products', 'public');
        }

        $data['user_id'] = Auth::id();

        Product::create($data);

        return redirect()->route('kwt.products')->with('success', 'Produk berhasil ditambahkan!');
    }

    // 4. Tampilkan Halaman Edit
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('kwt.edit-produk', compact('product'));
    }

    // 5. Update Data Produk
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
            'satuan' => 'required',
            'foto_produk' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto_produk')) {
            // Hapus foto lama jika ada[cite: 2]
            if ($product->foto_produk) {
                Storage::disk('public')->delete($product->foto_produk);
            }
            $data['foto_produk'] = $request->file('foto_produk')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('kwt.products')->with('success', 'Produk berhasil diperbarui!');
    }

    // 6. Hapus Produk
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->foto_produk) {
            Storage::disk('public')->delete($product->foto_produk);
        }

        $product->delete();

        return redirect()->route('kwt.products')->with('success', 'Produk berhasil dihapus!');
    }
}
