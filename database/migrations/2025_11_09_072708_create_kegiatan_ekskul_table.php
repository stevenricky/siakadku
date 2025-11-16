<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kegiatan_ekskul', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ekstrakurikuler_id')->constrained('ekstrakurikulers');
            $table->string('nama_kegiatan');
            $table->text('deskripsi');
            $table->date('tanggal_kegiatan');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('tempat');
            $table->string('pembina');
            $table->text('hasil_kegiatan')->nullable();
            $table->string('dokumentasi')->nullable();
            $table->enum('status', ['terlaksana', 'dibatalkan', 'ditunda'])->default('terlaksana');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kegiatan_ekskul');
    }
};