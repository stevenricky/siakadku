<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop table jika exists
        Schema::dropIfExists('komentar_forum');
    }

    public function down()
    {
        // Tidak perlu rollback untuk drop table
    }
};