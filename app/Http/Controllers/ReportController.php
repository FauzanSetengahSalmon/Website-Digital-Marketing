<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Report;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // SISI CUSTOMER: Menyimpan pengaduan ke database
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'tipe_pengaduan' => 'required|string',
            'pesan' => 'required|string|max:1000',
            'foto_bukti' => 'nullable|image|max:2048',
        ]);

        $order = Order::findOrFail($orderId);

        // Cari detail order untuk mengambil user_id (KWT) si pemilik produk
        $orderDetail = OrderDetail::where('order_id', $orderId)
            ->where('product_id', $request->product_id)
            ->firstOrFail();

        $pemilikProdukId = $orderDetail->product->user_id;

        $fotoPath = null;
        if ($request->hasFile('foto_bukti')) {
            $fotoPath = $request->file('foto_bukti')->store('reports', 'public');
        }

        Report::create([
            'order_id' => $orderId,
            'product_id' => $request->product_id,
            'user_id' => Auth::id(), // ID Pembeli
            'kwt_id' => $pemilikProdukId, // ID KWT (user_id dari tabel products)
            'tipe_pengaduan' => $request->tipe_pengaduan,
            'pesan' => $request->pesan,
            'foto_bukti' => $fotoPath,
            'status' => 'menunggu'
        ]);

        return redirect()->back()->with('success', 'Pengaduan Anda berhasil dikirim ke KWT terkait.');
    }

    // SISI KWT: Menampilkan daftar pengaduan yang masuk ke produk milik mereka
    public function kwtIndex()
    {
        // Mengambil laporan yang produknya dimiliki oleh KWT yang sedang login
        $reports = \App\Models\Report::whereHas('product', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with(['product', 'order', 'customer'])
            ->latest()
            ->get();

        return view('kwt.reports', compact('reports'));
    }

    // SISI KWT: Mengubah status pengaduan (misal dari menunggu -> diproses -> selesai)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai'
        ]);

        $report = Report::where('id', $id)->where('kwt_id', Auth::id())->firstOrFail();
        $report->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    // SISI KWT: Mengisi atau memperbarui tanggapan untuk laporan/pengaduan
    public function updateTanggapan(Request $request, $id)
    {
        $request->validate([
            'tanggapan_kwt' => 'required|string|max:2000',
        ]);

        $report = Report::where('id', $id)->where('kwt_id', Auth::id())->firstOrFail();
        $report->update(['tanggapan_kwt' => $request->tanggapan_kwt]);

        return redirect()->back()->with('success', 'Tanggapan berhasil dikirim.');
    }
}
