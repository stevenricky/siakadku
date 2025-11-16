<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lowongan_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan');
            $table->string('posisi');
            $table->text('deskripsi_pekerjaan');
            $table->text('kualifikasi');
            $table->string('lokasi');
            $table->decimal('gaji', 15, 2)->nullable();
            $table->date('tanggal_buka');
            $table->date('tanggal_tutup');
            $table->string('kontak_person');
            $table->string('email');
            $table->string('no_telepon');
            $table->string('website')->nullable();
            $table->string('logo_perusahaan')->nullable();
            $table->enum('status', ['buka', 'tutup'])->default('buka');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lowongan_kerja');
    }
};