<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('tarif_per_km')->default(2000);
            $table->integer('minimal_km')->default(1);
            $table->integer('maksimal_km')->default(15);
            $table->integer('biaya_layanan')->default(2000);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
