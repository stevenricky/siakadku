<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // HANYA untuk catatan_bk (konseling sudah lengkap)
        if (Schema::hasTable('catatan_bk')) {
            Schema::table('catatan_bk', function (Blueprint $table) {
                if (!Schema::hasColumn('catatan_bk', 'layanan_bk_id')) {
                    $table->foreignId('layanan_bk_id')->nullable();
                }

                if (Schema::hasTable('layanan_bk')) {
                    $table->foreign('layanan_bk_id')
                          ->references('id')
                          ->on('layanan_bk')
                          ->nullOnDelete();
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('catatan_bk') && Schema::hasColumn('catatan_bk', 'layanan_bk_id')) {
            Schema::table('catatan_bk', function (Blueprint $table) {
                $table->dropForeign(['layanan_bk_id']);
                $table->dropColumn('layanan_bk_id');
            });
        }
    }
};
