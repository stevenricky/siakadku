<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ekstrakurikulers', function (Blueprint $table) {
            $table->integer('kuota')->default(30)->after('tempat');
            $table->integer('jumlah_peserta')->default(0)->after('kuota');
        });
    }

    public function down()
    {
        Schema::table('ekstrakurikulers', function (Blueprint $table) {
            $table->dropColumn(['kuota', 'jumlah_peserta']);
        });
    }
};