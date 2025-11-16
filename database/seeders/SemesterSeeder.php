<?php

namespace Database\Seeders;

use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAjaran = TahunAjaran::first();

        Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran_id' => $tahunAjaran->id,
            'tanggal_mulai' => '2024-07-15',
            'tanggal_selesai' => '2024-12-20',
            'is_aktif' => true,
        ]);

        Semester::create([
            'nama_semester' => 'Genap',
            'tahun_ajaran_id' => $tahunAjaran->id,
            'tanggal_mulai' => '2025-01-06',
            'tanggal_selesai' => '2025-06-20',
            'is_aktif' => false,
        ]);
    }
}