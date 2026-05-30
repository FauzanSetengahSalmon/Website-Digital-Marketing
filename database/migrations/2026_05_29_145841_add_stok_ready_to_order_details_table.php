<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_details', function (Blueprint $table) {

            $table->boolean('stok_ready')
                ->default(false)
                ->after('harga_saat_ini');

            $table->timestamp('stok_ready_at')
                ->nullable()
                ->after('stok_ready');
        });
    }

    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {

            $table->dropColumn([
                'stok_ready',
                'stok_ready_at'
            ]);
        });
    }
};
