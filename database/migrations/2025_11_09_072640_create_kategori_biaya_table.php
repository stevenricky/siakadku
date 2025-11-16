<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kategori_biaya', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->text('deskripsi')->nullable();
            $table->enum('jenis', ['spp', 'dana_siswa', 'lainnya']);
            $table->decimal('jumlah_biaya', 15, 2);
            $table->enum('periode', ['bulanan', 'semester', 'tahunan']);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_biaya');
    }
};