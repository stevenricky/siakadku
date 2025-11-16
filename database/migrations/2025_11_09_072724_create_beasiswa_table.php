<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('beasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('nama_beasiswa');
            $table->string('penyelenggara');
            $table->text('deskripsi');
            $table->text('persyaratan');
            $table->decimal('nilai_beasiswa', 15, 2)->nullable();
            $table->date('tanggal_buka');
            $table->date('tanggal_tutup');
            $table->string('kontak');
            $table->string('website')->nullable();
            $table->string('dokumen')->nullable();
            $table->enum('status', ['buka', 'tutup'])->default('buka');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('beasiswa');
    }
};