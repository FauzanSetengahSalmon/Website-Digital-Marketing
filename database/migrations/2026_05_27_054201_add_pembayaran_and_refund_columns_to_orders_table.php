<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menambahkan kolom log aliran dana setelah kolom status pesanan
            $table->string('status_pembayaran')->default('belum_bayar')->after('status');
            $table->timestamp('waktu_dana_masuk')->nullable()->after('status_pembayaran');
            $table->string('status_refund')->default('tidak_ada')->after('waktu_dana_masuk');
            $table->timestamp('waktu_refund')->nullable()->after('status_refund');
            $table->text('alasan_tolak')->nullable()->after('waktu_refund');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Fungsi rollback jika migration ingin dibatalkan (untuk keamanan sistem)
            $table->dropColumn([
                'status_pembayaran',
                'waktu_dana_masuk',
                'status_refund',
                'waktu_refund',
                'alasan_tolak'
            ]);
        });
    }
};
