<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat');
            $table->string('jenis_surat'); // masuk, keluar
            $table->string('perihal');
            $table->text('isi_singkat');
            $table->string('pengirim');
            $table->string('penerima')->nullable();
            $table->date('tanggal_surat');
            $table->date('tanggal_terima')->nullable();
            $table->string('file_surat')->nullable();
            $table->string('disposisi_ke')->nullable();
            $table->text('catatan_disposisi')->nullable();
            $table->enum('status', ['baru', 'diproses', 'selesai', 'arsip'])->default('baru');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('surat');
    }
};