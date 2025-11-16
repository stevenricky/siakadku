<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tambahkan index dengan cara yang aman
        Schema::table('nilais', function (Blueprint $table) {
            $table->index('nilai_akhir', 'idx_nilais_nilai_akhir');
            $table->index('predikat', 'idx_nilais_predikat');
            $table->index('semester', 'idx_nilais_semester');
            // Skip tahun_ajaran_id karena sudah ada index dari foreign key
            $table->index('mapel_id', 'idx_nilais_mapel');
            $table->index('siswa_id', 'idx_nilais_siswa');
            $table->index('guru_id', 'idx_nilais_guru');
        });
    }

    public function down()
    {
        Schema::table('nilais', function (Blueprint $table) {
            $table->dropIndex('idx_nilais_nilai_akhir');
            $table->dropIndex('idx_nilais_predikat');
            $table->dropIndex('idx_nilais_semester');
            $table->dropIndex('idx_nilais_mapel');
            $table->dropIndex('idx_nilais_siswa');
            $table->dropIndex('idx_nilais_guru');
        });
    }
};