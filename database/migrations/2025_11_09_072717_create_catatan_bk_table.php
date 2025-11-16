<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('catatan_bk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas');
            $table->foreignId('guru_id')->constrained('gurus');
            $table->string('jenis_catatan'); // kasus, perkembangan, khusus
            $table->text('deskripsi');
            $table->date('tanggal_catatan');
            $table->enum('tingkat_keparahan', ['ringan', 'sedang', 'berat'])->default('ringan');
            $table->text('tindak_lanjut')->nullable();
            $table->boolean('status_selesai')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('catatan_bk');
    }
};