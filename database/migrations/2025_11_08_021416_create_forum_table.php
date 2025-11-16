<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('forum', function (Blueprint $table) { // Pastikan nama tabel 'forum' bukan 'forums'
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('judul');
            $table->text('isi');
            $table->enum('kategori', ['pelajaran', 'umum', 'ekstrakurikuler'])->default('umum');
            $table->integer('like_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('forum'); // Pastikan nama tabel 'forum'
    }
};