<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('kendaraan_kurirs', function (Blueprint $table) {
            $table->id();
            // Menyambungkan kendaraan ini ke tabel kurirs
            $table->unsignedBigInteger('kurir_id');
            $table->foreign('kurir_id')->references('id')->on('kurirs')->onDelete('cascade');

            $table->string('jenis_kendaraan'); // Contoh: Motor / Mobil
            $table->string('merk_kendaraan'); // Contoh: Honda Beat
            $table->string('plat_nomor'); // Contoh: D 1234 ABC
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('kendaraan_kurirs');
    }
};
