<?php
// database/migrations/2025_11_14_xxxxxx_recreate_notifications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Cek jika tabel notifications ada, backup dan hapus
        if (Schema::hasTable('notifications')) {
            // Backup data notifications jika ada (opsional)
            $this->backupNotifications();
            
            // Hapus tabel
            Schema::dropIfExists('notifications');
        }
        
        // Buat ulang tabel notifications dengan struktur yang benar
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable'); // ini akan membuat notifiable_type dan notifiable_id
            $table->json('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }

    /**
     * Backup notifications data (opsional)
     */
    private function backupNotifications()
    {
        // Jika ingin backup data, bisa ditambahkan di sini
        // Contoh: export ke file JSON atau tabel temporary
    }
};