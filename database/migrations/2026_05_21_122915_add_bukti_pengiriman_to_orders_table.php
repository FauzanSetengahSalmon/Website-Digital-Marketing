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
            // Kita buat dua-duanya sekalian setelah kolom nomor_hp agar urutannya rapi
            $table->string('bukti_sampai')->nullable()->after('nomor_hp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus kedua kolom jika di-rollback
            $table->dropColumn(['bukti_sampai']);
        });
    }
};
