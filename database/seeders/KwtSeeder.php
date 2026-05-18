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
        $kwtUser = User::where('role', 'kwt')->first();

        Product::create([
            'user_id' => $kwtUser->id,
            'nama_produk' => 'Kangkung',
            'harga' => 15000,
            'stok' => 100,
            'foto_produk' => 'kangkung.jpg' 
        ]);

        Product::create([
            'user_id' => $kwtUser->id,
            'nama_produk' => 'Bayam',
            'harga' => 15000,
            'stok' => 50,
            'foto_produk' => 'bayam.jpg'
        ]);
    }
}