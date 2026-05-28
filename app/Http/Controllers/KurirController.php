<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class KurirController extends Controller
{
    public function showUpload($id, $token)
    {
        $order = Order::where('id', $id)->where('delivery_token', $token)->firstOrFail();
        return view('kurir.upload', compact('order'));
    }

    public function storeUpload(Request $request, $id, $token)
    {
        $order = Order::where('id', $id)->where('delivery_token', $token)->firstOrFail();
        $request->validate(['bukti_sampai' => 'required|image|max:5000']);

        $path = $request->file('bukti_sampai')->store('bukti_pesanan', 'public');

        $order->update([
            'status' => 'selesai',
            'bukti_sampai' => $path
        ]);

        return "Pesanan #$id berhasil diselesaikan. Terima kasih!";
    }
}
