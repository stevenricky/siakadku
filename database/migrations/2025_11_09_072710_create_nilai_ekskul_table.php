<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nilai_ekskul', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas');
            $table->foreignId('ekstrakurikuler_id')->constrained('ekstrakurikulers');
            $table->integer('tahun_ajaran');
            $table->integer('semester');
            $table->integer('nilai_keaktifan')->default(0);
            $table->integer('nilai_keterampilan')->default(0);
            $table->integer('nilai_sikap')->default(0);
            $table->integer('nilai_rata_rata')->default(0);
            $table->text('catatan')->nullable();
            $table->foreignId('penilai_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nilai_ekskul');
    }
};