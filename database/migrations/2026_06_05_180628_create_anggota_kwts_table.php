<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('anggota_kwts', function (Blueprint $table) {
            $table->id();
            // Menyambungkan data ibu-ibu ini ke tabel users (akun KWT)
            $table->unsignedBigInteger('kwt_id');
            $table->foreign('kwt_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('nama_anggota');
            $table->string('no_hp')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('anggota_kwts');
    }
};