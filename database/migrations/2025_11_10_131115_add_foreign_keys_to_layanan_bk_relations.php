<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Cek dan perbaiki tabel konseling
        if (Schema::hasTable('konseling')) {
            Schema::table('konseling', function (Blueprint $table) {
                // Tambahkan kolom layanan_bk_id setelah id (bukan setelah guru_bk_id)
                if (!Schema::hasColumn('konseling', 'layanan_bk_id')) {
                    $table->foreignId('layanan_bk_id')->after('id')->nullable();
                }
                
                // Tambahkan foreign key constraint jika tabel layanan_bk ada
                if (Schema::hasTable('layanan_bk')) {
                    $table->foreign('layanan_bk_id')->references('id')->on('layanan_bk')->onDelete('set null');
                }
            });
        }

        // Cek dan perbaiki tabel catatan_bk
        if (Schema::hasTable('catatan_bk')) {
            Schema::table('catatan_bk', function (Blueprint $table) {
                // Tambahkan kolom layanan_bk_id setelah id (bukan setelah guru_bk_id)
                if (!Schema::hasColumn('catatan_bk', 'layanan_bk_id')) {
                    $table->foreignId('layanan_bk_id')->after('id')->nullable();
                }
                
                // Tambahkan foreign key constraint jika tabel layanan_bk ada
                if (Schema::hasTable('layanan_bk')) {
                    $table->foreign('layanan_bk_id')->references('id')->on('layanan_bk')->onDelete('set null');
                }
            });
        }
    }

    public function down()
    {
        // Drop foreign keys dan kolom dari konseling
        if (Schema::hasTable('konseling') && Schema::hasColumn('konseling', 'layanan_bk_id')) {
            Schema::table('konseling', function (Blueprint $table) {
                $table->dropForeign(['layanan_bk_id']);
                $table->dropColumn('layanan_bk_id');
            });
        }

        // Drop foreign keys dan kolom dari catatan_bk
        if (Schema::hasTable('catatan_bk') && Schema::hasColumn('catatan_bk', 'layanan_bk_id')) {
            Schema::table('catatan_bk', function (Blueprint $table) {
                $table->dropForeign(['layanan_bk_id']);
                $table->dropColumn('layanan_bk_id');
            });
        }
    }
};