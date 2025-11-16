<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tagihan_spp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas');
            $table->foreignId('biaya_spp_id')->constrained('biaya_spp');
            $table->string('bulan');
            $table->integer('tahun');
            $table->decimal('jumlah_tagihan', 15, 2);
            $table->decimal('denda', 15, 2)->default(0);
            $table->enum('status', ['belum_bayar', 'lunas', 'tertunggak'])->default('belum_bayar');
            $table->date('tanggal_jatuh_tempo');
            $table->date('tanggal_bayar')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tagihan_spp');
    }
};