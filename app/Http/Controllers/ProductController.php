<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 1. Tampilan Katalog untuk Customer
    public function index()
    {
        $products = Product::all();
        return view('customer.katalog', compact('products'));
    }

    // 2. Tampilan List Produk KHUSUS KWT (Ini yang tadi bikin error)
    public function kwtProducts()
    {
        $products = Product::all(); // Mengambil semua produk
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
            $data['foto_produk'] = $request->file('foto_produk')->store('products', 'public');
        }

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
            // Hapus foto lama
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