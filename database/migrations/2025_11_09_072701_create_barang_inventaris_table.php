<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('barang_inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->foreignId('kategori_id')->constrained('kategori_inventaris');
            $table->foreignId('ruangan_id')->constrained('ruangans');
            $table->string('merk')->nullable();
            $table->string('tipe')->nullable();
            $table->integer('jumlah');
            $table->string('satuan');
            $table->decimal('harga', 15, 2)->nullable();
            $table->date('tanggal_pembelian')->nullable();
            $table->string('sumber_dana')->nullable();
            $table->text('spesifikasi')->nullable();
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->default('baik');
            $table->text('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('barang_inventaris');
    }
};