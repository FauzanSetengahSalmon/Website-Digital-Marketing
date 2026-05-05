<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    /**
     * Tampilkan halaman keranjang belanja
     */
    public function index()
    {
        // Kita ambil data user, produk, dan relasi pemilik produk (KWT)
        $cartItems = Cart::with(['product.user'])
            ->where('user_id', Auth::id())
            ->get();

        return view('customer.cart', compact('cartItems'));
    }

    /**
     * Update jumlah via AJAX (sinkron dengan JavaScript fetch kamu)
     */
    public function update(Request $request, $id): JsonResponse
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        
        // Validasi input
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        // Cek stok produk
        if ($request->jumlah > $cart->product->stok) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok hanya tersedia ' . $cart->product->stok
            ], 400);
        }

        $cart->update(['jumlah' => $request->jumlah]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jumlah berhasil diperbarui'
        ]);
    }

    /**
     * Tambah ke keranjang (dari Katalog)
     */
    public function store(Request $request, $id): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Silakan login dulu'], 401);
        }

        $product = Product::findOrFail($id);
        
        $cart = Cart::where('user_id', Auth::id())->where('product_id', $id)->first();

        if ($cart) {
            if ($cart->jumlah + 1 > $product->stok) {
                return response()->json(['status' => 'error', 'message' => 'Stok habis'], 400);
            }
            $cart->update(['jumlah' => $cart->jumlah + 1]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $id,
                'jumlah' => 1
            ]);
        }

        $cartCount = Cart::where('user_id', Auth::id())->sum('jumlah');

        return response()->json([
            'status' => 'success',
            'cartCount' => $cartCount,
            'message' => 'Berhasil ditambah ke keranjang'
        ]);
    }

    /**
     * Hapus item
     */
    public function destroy($id)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->delete();

        return back()->with('success', 'Item dihapus');
    }
}