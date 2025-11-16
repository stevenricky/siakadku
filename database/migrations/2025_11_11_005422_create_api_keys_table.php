<?php
// database/migrations/2025_11_11_004300_create_api_keys_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('api_key')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('permissions')->nullable();
            $table->string('status')->default('active');
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->integer('rate_limit')->default(100);
            $table->text('allowed_ips')->nullable();
            $table->text('webhook_url')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'expires_at']);
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_keys');
    }
};