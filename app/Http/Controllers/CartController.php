<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Tampilkan Keranjang
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();
        return view('customer.cart', compact('cartItems'));
    }

    // Add to Cart (Perintah dari tombol di Katalog)
    public function store(Request $request, $id)
    {
        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $id)
            ->first();

        if ($cart) {
            $cart->increment('jumlah');
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $id,
                'jumlah' => 1,
            ]);
        }
        return redirect()->route('cart.index')->with('success', 'Berhasil masuk keranjang!');
    }
    public function updateQuantity(Request $request, $id)
    {
        $cart = Cart::with('product')->findOrFail($id);

        if ($request->jumlah > $cart->product->stok) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi! Maksimal: ' . $cart->product->stok
            ], 400);
        }

        $cart->update(['jumlah' => $request->jumlah]);
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->delete();

        return back()->with('success', 'Produk dihapus dari keranjang');
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);

        if ($request->jumlah > $cart->product->stok) {
            return response()->json(['error' => 'Stok tidak cukup'], 400);
        }

        $cart->update(['jumlah' => $request->jumlah]);
        return response()->json(['success' => true]);
    }

    public function add(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan'
            ]);
        }

        return redirect()->back()->with('success', 'Produk ditambahkan!');
    }
}
