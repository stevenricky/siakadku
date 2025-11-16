<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('agenda_sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('judul_agenda');
            $table->text('deskripsi');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->string('tempat');
            $table->string('penanggung_jawab');
            $table->string('sasaran'); // seluruh sekolah, guru, siswa, kelas tertentu
            $table->enum('jenis_agenda', ['akademik', 'non_akademik', 'sosial', 'lainnya']);
            $table->string('dokumentasi')->nullable();
            $table->enum('status', ['terjadwal', 'berlangsung', 'selesai', 'dibatalkan'])->default('terjadwal');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('agenda_sekolah');
    }
};