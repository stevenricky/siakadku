<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengembalian_buku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman_buku');
            $table->date('tanggal_dikembalikan');
            $table->integer('terlambat_hari')->default(0);
            $table->decimal('denda', 15, 2)->default(0);
            $table->text('kondisi_buku')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('petugas_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengembalian_buku');
    }
};