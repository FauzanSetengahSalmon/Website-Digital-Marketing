<?php
namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;

class KwtTransactionExport implements FromCollection
{
    public function collection()
    {
        $orders = Order::with(['details.product','user'])
            ->whereHas('details.product', function($q){
                $q->where('user_id', Auth::id());
            })
            ->where('status','selesai')
            ->get();

        $data = [];

        foreach($orders as $order){
            foreach($order->details as $detail){
                if($detail->product->user_id == Auth::id()){
                    $data[] = [
                        'Order ID' => $order->id,
                        'Tanggal' => $order->created_at->format('d-m-Y'),
                        'Pembeli' => $order->user->name,
                        'Produk' => $detail->product->nama_produk,
                        'Jumlah' => $detail->jumlah,
                        'Total' => $detail->harga_saat_ini * $detail->jumlah,
                    ];
                }
            }
        }

        return collect($data);
    }
}