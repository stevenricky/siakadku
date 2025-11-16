<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dapodik_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->string('sync_type'); // full, siswa, guru, kelas
            $table->string('status'); // success, error, warning
            $table->integer('data_count')->default(0);
            $table->text('message');
            $table->json('details')->nullable();
            $table->timestamp('sync_date');
            $table->timestamps();
            
            $table->index(['sync_type', 'status']);
            $table->index('sync_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dapodik_sync_logs');
    }
};