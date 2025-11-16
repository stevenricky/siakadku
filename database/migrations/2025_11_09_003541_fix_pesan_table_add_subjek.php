<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tambah kolom subjek ke pesan jika belum ada
        if (!Schema::hasColumn('pesan', 'subjek')) {
            Schema::table('pesan', function (Blueprint $table) {
                $table->string('subjek')->nullable()->after('penerima_id');
            });
        }
    }

    public function down()
    {
        // Hapus kolom subjek jika ada
        if (Schema::hasColumn('pesan', 'subjek')) {
            Schema::table('pesan', function (Blueprint $table) {
                $table->dropColumn('subjek');
            });
        }
    }
};