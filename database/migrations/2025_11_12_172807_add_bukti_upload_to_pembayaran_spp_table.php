<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->string('bukti_upload')->nullable()->after('bukti_bayar');
            $table->timestamp('tanggal_upload')->nullable()->after('bukti_upload');
        });
    }

    public function down()
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->dropColumn(['bukti_upload', 'tanggal_upload']);
        });
    }
};