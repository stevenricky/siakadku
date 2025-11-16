<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    public function run(): void
    {
        TahunAjaran::firstOrCreate(
            [
                'tahun_awal' => '2026',
                'tahun_akhir' => '2027',
                'semester' => 'Ganjil'
            ],
            [
                'status' => 'aktif',
                'tanggal_awal' => '2026-07-15',
                'tanggal_akhir' => '2026-12-20',
            ]
        );

        $this->command->info('TahunAjaran seeder berhasil dijalankan!');
    }
}