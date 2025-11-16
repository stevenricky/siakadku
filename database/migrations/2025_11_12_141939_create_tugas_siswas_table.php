<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tugas_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained()->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained()->onDelete('cascade');
            $table->text('jawaban')->nullable();
            $table->string('file_jawaban')->nullable();
            $table->integer('nilai')->nullable();
            $table->text('komentar')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('dinilai_at')->nullable();
            $table->enum('status', ['belum', 'dikumpulkan', 'dinilai', 'terlambat'])->default('belum');
            $table->timestamps();

            $table->unique(['tugas_id', 'siswa_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tugas_siswa');
    }
};