<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pembayaran_spp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagihan_spp_id')->constrained('tagihan_spp');
            $table->foreignId('siswa_id')->constrained('siswas');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->date('tanggal_bayar');
            $table->string('metode_bayar')->default('tunai');
            $table->string('channel_pembayaran')->nullable(); // Tambah field
            $table->string('bukti_bayar')->nullable();
            $table->enum('status_verifikasi', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->string('kode_referensi')->nullable(); // Tambah field
            $table->enum('status_pembayaran', ['pending', 'paid', 'failed', 'expired'])->default('pending'); // Tambah field
            $table->string('url_pembayaran')->nullable(); // Tambah field
            $table->timestamp('waktu_kadaluarsa')->nullable(); // Tambah field
            $table->text('catatan')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayaran_spp');
    }
};