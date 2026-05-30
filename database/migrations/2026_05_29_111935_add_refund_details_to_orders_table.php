<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('alasan_refund')->nullable()->after('status_refund');
            $table->string('bukti_refund')->nullable()->after('alasan_refund');
            $table->text('catatan_admin_refund')->nullable()->after('bukti_refund');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['alasan_refund', 'bukti_refund', 'catatan_admin_refund']);
        });
    }
};
