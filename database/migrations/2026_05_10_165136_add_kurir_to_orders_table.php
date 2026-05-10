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
            // Tambahkan kolom kurir dan no_hp_kurir setelah kolom catatan
            $table->string('kurir')->nullable()->after('catatan');
            $table->string('no_hp_kurir')->nullable()->after('kurir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus kolom jika migration di-rollback
            $table->dropColumn(['kurir', 'no_hp_kurir']);
        });
    }
};