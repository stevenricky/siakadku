<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tagihan_spp', function (Blueprint $table) {
            // Tambah kolom yang diperlukan untuk online payment
            $table->string('metode_pembayaran')->nullable()->after('keterangan');
            $table->string('bukti_pembayaran')->nullable()->after('metode_pembayaran');
            $table->foreignId('verified_by')->nullable()->after('bukti_pembayaran')->constrained('users');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
        });
    }

    public function down()
    {
        Schema::table('tagihan_spp', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'metode_pembayaran',
                'bukti_pembayaran',
                'verified_by',
                'verified_at'
            ]);
        });
    }
};