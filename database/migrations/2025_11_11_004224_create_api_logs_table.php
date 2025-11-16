<?php
// database/migrations/2024_01_01_000002_create_api_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_key_id')->constrained()->onDelete('cascade');
            $table->string('endpoint');
            $table->string('method'); // GET, POST, PUT, DELETE
            $table->integer('response_code');
            $table->float('response_time');
            $table->ipAddress('ip_address');
            $table->text('user_agent')->nullable();
            $table->text('request_params')->nullable(); // JSON request parameters
            $table->text('response_content')->nullable(); // JSON response (truncated)
            $table->timestamp('requested_at');
            
            // TAMBAHKAN TIMESTAMPS DI SINI
            $table->timestamps();
            
            $table->index(['api_key_id', 'requested_at']);
            $table->index('endpoint');
            $table->index('response_code');
            $table->index('ip_address');
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_logs');
    }
};