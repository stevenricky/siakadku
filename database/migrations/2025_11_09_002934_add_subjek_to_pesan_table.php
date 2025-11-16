<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pesan', function (Blueprint $table) {
            // Cek apakah kolom subjek sudah ada, jika belum tambahkan
            if (!Schema::hasColumn('pesan', 'subjek')) {
                $table->string('subjek')->nullable()->after('penerima_id');
            }
        });
    }

    public function down()
    {
        Schema::table('pesan', function (Blueprint $table) {
            // Hapus kolom subjek jika ada
            if (Schema::hasColumn('pesan', 'subjek')) {
                $table->dropColumn('subjek');
            }
        });
    }
};