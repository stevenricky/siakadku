<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriBiayaSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah sudah ada data
        $existing = DB::table('kategori_biaya')->count();
        
        if ($existing > 0) {
            $this->command->info("✅ Tabel kategori_biaya sudah memiliki {$existing} data.");
            return;
        }

        $kategoriData = [
            [
                'nama_kategori' => 'SPP Reguler',
                'deskripsi' => 'Biaya SPP bulanan reguler untuk semua siswa',
                'jenis' => 'spp',
                'jumlah_biaya' => 250000,
                'periode' => 'bulanan',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'SPP Kelas 10',
                'deskripsi' => 'Biaya SPP khusus kelas 10',
                'jenis' => 'spp',
                'jumlah_biaya' => 250000,
                'periode' => 'bulanan',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'SPP Kelas 11',
                'deskripsi' => 'Biaya SPP khusus kelas 11',
                'jenis' => 'spp',
                'jumlah_biaya' => 275000,
                'periode' => 'bulanan',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'SPP Kelas 12',
                'deskripsi' => 'Biaya SPP khusus kelas 12',
                'jenis' => 'spp',
                'jumlah_biaya' => 300000,
                'periode' => 'bulanan',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('kategori_biaya')->insert($kategoriData);

        $this->command->info('✅ Berhasil membuat data kategori biaya.');
    }
}