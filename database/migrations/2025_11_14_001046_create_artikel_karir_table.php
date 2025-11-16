<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('artikel_karir', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('konten');
            $table->string('kategori');
            $table->string('gambar')->nullable();
            $table->string('penulis');
            $table->string('sumber')->nullable();
            $table->json('tags')->nullable();
            $table->enum('status', ['draft', 'publik'])->default('publik');
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('artikel_karir');
    }
};