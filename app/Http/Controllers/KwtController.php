<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kwt;
use App\Models\Order;
use Illuminate\Http\Request;

class KwtController extends Controller
{
    public function index()
    {
        $kwts = Kwt::with('user')->get();
        return response()->json($kwts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kwt'  => 'required|string|max:255',
            'alamat'  => 'required|string',
            'kontak'  => 'required|string',
            'user_id'  => 'required|exists:users,id',
        ]);

        $kwt = Kwt::create($validated);
        return response()->json(['message' => 'KWT berhasil didaftarkan', 'data' => $kwt], 201);
    }

    // public function cairkan(Request $request, $id)
    // {
    //     $request->validate([
    //         'order_ids' => 'required|array',
    //         'nama_penerima' => 'required|string'
    //     ]);

    //     // Update HANYA produk milik KWT ini di dalam order yang dicentang
    //     \App\Models\OrderDetail::whereIn('order_id', $request->order_ids)
    //         ->whereHas('product', function ($query) use ($id) {
    //             $query->where('user_id', $id);
    //         })
    //         ->update(['is_cair_kwt' => true]);

    //     return back()->with([
    //         'success' => 'Dana berhasil dicairkan!',
    //         'printed_ids' => $request->order_ids,
    //         'penerima' => $request->nama_penerima
    //     ]);
    // }
}
