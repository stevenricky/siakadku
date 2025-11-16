<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tracer_study', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni');
            $table->integer('tahun_survey');
            $table->string('status_pekerjaan'); // bekerja, wirausaha, melanjutkan, belum bekerja
            $table->string('nama_perusahaan')->nullable();
            $table->string('bidang_pekerjaan')->nullable();
            $table->string('jabatan')->nullable();
            $table->decimal('gaji', 15, 2)->nullable();
            $table->string('nama_kampus')->nullable();
            $table->string('jurusan_kuliah')->nullable();
            $table->integer('tahun_masuk_kuliah')->nullable();
            $table->text('relevansi_pendidikan')->nullable();
            $table->text('saran_untuk_sekolah')->nullable();
            $table->enum('status_survey', ['terkirim', 'dijawab'])->default('terkirim');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tracer_study');
    }
};