<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('maintenance_mode')->default(false);
            $table->string('maintenance_access_code')->nullable();
            $table->text('maintenance_message')->nullable();
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['maintenance_mode', 'maintenance_access_code', 'maintenance_message']);
        });
    }
};