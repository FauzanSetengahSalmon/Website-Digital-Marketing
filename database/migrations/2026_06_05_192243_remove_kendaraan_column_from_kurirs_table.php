<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('kurirs', function (Blueprint $table) {
            // Menghapus kolom kendaraan yang sudah tidak terpakai
            $table->dropColumn('kendaraan');
        });
    }

    public function down()
    {
        Schema::table('kurirs', function (Blueprint $table) {
            // Mengembalikan kolom jika di-rollback
            $table->string('kendaraan')->nullable();
        });
    }
};
