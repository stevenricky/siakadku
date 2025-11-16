<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('barang_inventaris', function (Blueprint $table) {
            $table->integer('jumlah_tersedia')->after('jumlah')->default(0);
            $table->enum('status', ['tersedia', 'dipinjam', 'rusak', 'hilang'])->after('kondisi')->default('tersedia');
        });

        // Update data existing
        \App\Models\BarangInventaris::query()->update([
            'jumlah_tersedia' => \DB::raw('jumlah'),
            'status' => 'tersedia'
        ]);
    }

    public function down()
    {
        Schema::table('barang_inventaris', function (Blueprint $table) {
            $table->dropColumn(['jumlah_tersedia', 'status']);
        });
    }
};