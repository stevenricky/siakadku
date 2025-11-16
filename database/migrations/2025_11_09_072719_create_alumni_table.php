<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas');
            $table->integer('tahun_lulus');
            $table->string('no_ijazah')->nullable();
            $table->string('status_setelah_lulus'); // kuliah, kerja, wirausaha, lainnya
            $table->string('instansi')->nullable(); // nama kampus/perusahaan
            $table->string('jurusan_kuliah')->nullable();
            $table->string('jabatan_pekerjaan')->nullable();
            $table->string('alamat_instansi')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('email')->nullable();
            $table->text('prestasi_setelah_lulus')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('alumni');
    }
};