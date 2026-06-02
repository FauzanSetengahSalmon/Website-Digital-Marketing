<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class AutoCompleteOrders extends Command
{
    // Nama untuk memanggil robot ini
    protected $signature = 'orders:autocomplete';

    // Deskripsi kerja robot
    protected $description = 'Otomatis menyelesaikan pesanan yang sudah 1x24 jam sejak difoto kurir';

    public function handle()
    {
        // Cari pesanan yang statusnya 'diantar', sudah ada 'bukti_sampai',
        // dan terakhir di-update lebih dari 1 hari (24 jam) yang lalu
        $affectedRows = Order::where('status', 'diantar')
            ->whereNotNull('bukti_sampai')
            ->where('updated_at', '<=', Carbon::now()->subDay())
            ->update(['status' => 'selesai']);

        if ($affectedRows > 0) {
            $this->info("Berhasil! {$affectedRows} pesanan telah otomatis di-set menjadi Selesai.");
        } else {
            $this->info("Aman. Tidak ada pesanan yang melewati batas 1x24 jam saat ini.");
        }
    }
}