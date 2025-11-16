<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pemeliharaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang_inventaris');
            $table->date('tanggal_pemeliharaan');
            $table->string('jenis_pemeliharaan');
            $table->text('deskripsi_kerusakan')->nullable();
            $table->text('tindakan');
            $table->decimal('biaya', 15, 2)->default(0);
            $table->string('teknisi')->nullable();
            $table->enum('status', ['dilaporkan', 'diproses', 'selesai', 'batal'])->default('dilaporkan');
            $table->text('catatan')->nullable();
            $table->foreignId('pelapor_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemeliharaan');
    }
};