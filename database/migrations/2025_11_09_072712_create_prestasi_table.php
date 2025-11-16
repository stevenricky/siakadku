<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('prestasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas');
            $table->string('jenis_prestasi'); // akademik, non-akademik, olahraga, seni
            $table->string('tingkat'); // sekolah, kecamatan, kabupaten, provinsi, nasional, internasional
            $table->string('nama_prestasi');
            $table->string('penyelenggara');
            $table->date('tanggal_prestasi');
            $table->string('peringkat')->nullable(); // juara 1, 2, 3, harapan, dll
            $table->text('deskripsi')->nullable();
            $table->string('sertifikat')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prestasi');
    }
};