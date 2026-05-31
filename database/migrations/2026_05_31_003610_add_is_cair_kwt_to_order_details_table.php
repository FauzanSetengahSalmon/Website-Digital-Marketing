<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            // Tambah kolom is_cair_kwt dengan default false (0)
            $table->boolean('is_cair_kwt')->default(false)->after('status_kwt');
        });
    }

    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn('is_cair_kwt');
        });
    }
};
