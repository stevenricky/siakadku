<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // database/migrations/xxxx_xx_xx_xxxxxx_create_ruangans_table.php
public function up()
{
    Schema::create('ruangans', function (Blueprint $table) {
        $table->id();
        $table->string('kode_ruangan')->unique();
        $table->string('nama_ruangan');
        $table->string('gedung');
        $table->integer('kapasitas');
        $table->text('fasilitas')->nullable();
        $table->boolean('status')->default(true);
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('ruangans');
    }
};