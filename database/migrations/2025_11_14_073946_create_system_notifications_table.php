<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('system_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // info, warning, success, danger
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Data tambahan
            $table->string('icon')->nullable(); // Icon notifikasi
            $table->string('action_url')->nullable(); // URL ketika diklik
            $table->string('action_text')->nullable(); // Teks action
            $table->boolean('is_public')->default(true); // Notifikasi publik
            $table->json('target_roles')->nullable(); // Role spesifik (null = semua role)
            $table->json('read_by')->nullable(); // User yang sudah baca
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('system_notifications');
    }
};