<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->string('channel_pembayaran')->nullable()->after('metode_bayar');
            $table->string('kode_referensi')->nullable()->after('bukti_bayar');
            $table->enum('status_pembayaran', ['pending', 'paid', 'failed', 'expired'])->default('pending')->after('kode_referensi');
            $table->string('url_pembayaran')->nullable()->after('status_pembayaran');
            $table->timestamp('waktu_kadaluarsa')->nullable()->after('url_pembayaran');
            $table->decimal('biaya_admin', 15, 2)->default(0)->after('jumlah_bayar');
        });
    }

    public function down()
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->dropColumn([
                'channel_pembayaran',
                'kode_referensi', 
                'status_pembayaran',
                'url_pembayaran',
                'waktu_kadaluarsa',
                'biaya_admin'
            ]);
        });
    }
};