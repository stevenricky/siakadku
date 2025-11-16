<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->timestamp('maintenance_start')->nullable();
            $table->timestamp('maintenance_end')->nullable();
            $table->string('maintenance_title')->nullable();
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['maintenance_start', 'maintenance_end', 'maintenance_title']);
        });
    }
};