<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_activities', function (Blueprint $table) {
            $table->id();
            
            // User yang melakukan aksi (bisa null untuk aktivitas sistem)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Jenis aksi (login, create, update, delete, dll)
            $table->string('action', 50);
            
            // Deskripsi detail aksi
            $table->text('description');
            
            // Informasi teknis
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('method', 10)->nullable(); // GET, POST, PUT, DELETE
            $table->text('url')->nullable();
            $table->text('referrer')->nullable();
            
            // Data tambahan dalam format JSON
            $table->json('extra_data')->nullable();
            
            // Model terkait (opsional)
            $table->string('subject_type')->nullable(); // Model class
            $table->unsignedBigInteger('subject_id')->nullable(); // Model ID
            
            $table->timestamps();

            // Index untuk performa query
            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index(['subject_type', 'subject_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_activities');
    }
};