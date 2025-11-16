<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nilais', function (Blueprint $table) {
            $table->enum('status', ['Lulus', 'Tidak Lulus'])->nullable()->after('predikat');
        });

        // Update data existing
        \App\Models\Nilai::whereIn('predikat', ['A', 'B', 'C'])->update(['status' => 'Lulus']);
        \App\Models\Nilai::where('predikat', 'D')->update(['status' => 'Tidak Lulus']);
    }

    public function down(): void
    {
        Schema::table('nilais', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};