<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with(['product.user'])
            ->where('user_id', Auth::id())
            ->get();

        return view('customer.cart', compact('cartItems'));
    }

    public function update(Request $request, $id): JsonResponse
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

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

    public function store(Request $request, $id)
    {
        if (!Auth::check()) {
            return $request->expectsJson()
                ? response()->json(['status' => 'error', 'message' => 'Silakan login dulu'], 401)
                : redirect()->route('login');
        }

        $product = Product::findOrFail($id);

        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $id)
            ->first();

        if ($cart) {

            if ($cart->jumlah + 1 > $product->stok) {
                return $request->expectsJson()
                    ? response()->json(['status' => 'error', 'message' => 'Stok habis'], 400)
                    : back()->with('error', 'Stok habis');
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

        return $request->expectsJson()
            ? response()->json([
                'status' => 'success',
                'cartCount' => $cartCount,
                'message' => 'Berhasil ditambah ke keranjang'
            ])
            : back();
    }

    public function destroy($id)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->delete();

        return back()->with('success', 'Item dihapus');
    }
}