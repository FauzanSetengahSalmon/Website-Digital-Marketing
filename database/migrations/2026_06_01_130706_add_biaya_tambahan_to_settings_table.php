<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('batas_jumlah_barang')->default(0)->after('biaya_layanan');
            $table->integer('biaya_tambahan_per_barang')->default(0)->after('batas_jumlah_barang');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['batas_jumlah_barang', 'biaya_tambahan_per_barang']);
        });
    }
};
