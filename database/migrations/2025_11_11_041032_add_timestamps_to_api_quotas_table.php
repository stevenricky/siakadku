<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('api_quotas', function (Blueprint $table) {
            $table->timestamps(); // Tambah created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::table('api_quotas', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};