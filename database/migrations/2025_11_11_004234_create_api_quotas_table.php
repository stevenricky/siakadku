<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('api_quotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_key_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('request_count')->default(0);
            $table->integer('limit_exceeded')->default(0);
            $table->timestamps(); // Tambahkan ini
            
            $table->unique(['api_key_id', 'date']);
            $table->index('date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_quotas');
    }
};