<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID Customer yang ngadu
            $table->foreignId('kwt_id')->constrained('users')->onDelete('cascade');  // ID KWT pemilik produk
            $table->string('tipe_pengaduan'); // Contoh: 'Produk Rusak', 'Terlambat Datang', 'Porsi Kurang', dll
            $table->text('pesan');
            $table->string('foto_bukti')->nullable();
            $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};