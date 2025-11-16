<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Skip migrasi yang bermasalah dengan menandainya sebagai sudah dijalankan
        \DB::table('migrations')->insert([
            'migration' => '2025_11_10_131115_add_foreign_keys_to_layanan_bk_relations',
            'batch' => 1
        ]);
    }

    public function down()
    {
        // Optional: remove the record if rolling back
        \DB::table('migrations')
            ->where('migration', '2025_11_10_131115_add_foreign_keys_to_layanan_bk_relations')
            ->delete();
    }
};