<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class KwtSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Produk Contoh sesuai gambar kamu
        Product::create([
            'nama_produk' => 'Kangkung',
            'harga' => 15000,
            'stok' => 100,
            'foto_produk' => 'kangkung.jpg' // Pastikan ada file ini nanti
        ]);

        Product::create([
            'nama_produk' => 'Bayam',
            'harga' => 15000,
            'stok' => 50,
            'foto_produk' => 'bayam.jpg'
        ]);

        // 2. Buat Pesanan Contoh untuk Statistik
        Order::create([
            'user_id' => 1, // Asumsi user ID 1 adalah customer
            'nama_produk' => 'Kangkung',
            'jumlah' => 2,
            'total_harga' => 30000,
            'status' => 'pending'
        ]);
    }
}