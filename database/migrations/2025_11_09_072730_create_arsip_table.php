<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('arsip', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dokumen');
            $table->string('kategori_arsip');
            $table->text('deskripsi')->nullable();
            $table->string('nomor_dokumen')->nullable();
            $table->date('tanggal_dokumen');
            $table->string('file_dokumen');
            $table->string('akses'); // publik, terbatas, rahasia
            $table->integer('tahun_arsip');
            $table->string('lokasi_fisik')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('arsip');
    }
};