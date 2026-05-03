<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Produk: Tempat KWT menyimpan barang jualannya
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->integer('harga');
            $table->integer('stok');
            $table->string('satuan')->default('kg'); // Kolom satuan (kg, ikat, buah, dll)
            $table->string('foto_produk')->nullable();
            $table->timestamps();
        });

        // Tabel Pesanan: Tempat mencatat belanjaan customer
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk'); 
            $table->integer('jumlah');
            $table->integer('total_harga');
            $table->string('status')->default('pending'); // pending, proses, selesai
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
    }
};