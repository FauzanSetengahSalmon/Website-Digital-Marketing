<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // HAPUS ->after('id') karena ini Schema::create
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('nama_produk');
            $table->integer('harga');
            $table->integer('stok');
            $table->string('satuan')->default('kg');
            $table->string('foto_produk')->nullable();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->integer('jumlah');
            $table->integer('total_harga');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
    }
};
