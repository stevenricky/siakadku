<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rpp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mapel_id')->constrained()->onDelete('cascade');
            $table->foreignId('guru_id')->constrained()->onDelete('cascade');
            $table->string('judul');
            $table->text('kompetensi_dasar');
            $table->text('tujuan_pembelajaran');
            $table->text('materi');
            $table->string('metode')->nullable();
            $table->string('media')->nullable();
            $table->text('langkah_kegiatan');
            $table->text('penilaian')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rpp');
    }
};