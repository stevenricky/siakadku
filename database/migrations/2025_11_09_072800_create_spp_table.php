<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('spp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('cascade');
            $table->decimal('nominal', 12, 2);
            $table->enum('bulan', [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ]);
            $table->year('tahun');
            $table->date('tanggal_bayar')->nullable();
            $table->enum('status', ['belum_bayar', 'lunas', 'tertunggak'])->default('belum_bayar');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Unique constraint untuk mencegah duplikasi SPP
            $table->unique(['siswa_id', 'tahun_ajaran_id', 'bulan', 'tahun']);

            // Index untuk performa query
            $table->index(['tahun_ajaran_id', 'status']);
            $table->index(['bulan', 'tahun']);
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('spp');
    }
};