<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key checks sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Gunakan delete() instead of truncate() untuk menghindari foreign key constraint
        Ruangan::query()->delete();
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $ruangans = [
            ['kode_ruangan' => 'R001', 'nama_ruangan' => 'Lab Komputer 1', 'gedung' => 'A', 'kapasitas' => 30, 'fasilitas' => 'Komputer, Proyektor'],
            ['kode_ruangan' => 'R002', 'nama_ruangan' => 'Lab Komputer 2', 'gedung' => 'A', 'kapasitas' => 30, 'fasilitas' => 'Komputer, Proyektor'],
            ['kode_ruangan' => 'R003', 'nama_ruangan' => 'Kelas X-1', 'gedung' => 'B', 'kapasitas' => 35, 'fasilitas' => 'AC, Proyektor'],
            ['kode_ruangan' => 'R004', 'nama_ruangan' => 'Kelas X-2', 'gedung' => 'B', 'kapasitas' => 35, 'fasilitas' => 'AC, Proyektor'],
            ['kode_ruangan' => 'R005', 'nama_ruangan' => 'Kelas XI-1', 'gedung' => 'B', 'kapasitas' => 35, 'fasilitas' => 'AC, Proyektor'],
            ['kode_ruangan' => 'R006', 'nama_ruangan' => 'Kelas XI-2', 'gedung' => 'B', 'kapasitas' => 35, 'fasilitas' => 'AC, Proyektor'],
            ['kode_ruangan' => 'R007', 'nama_ruangan' => 'Kelas XII-1', 'gedung' => 'C', 'kapasitas' => 35, 'fasilitas' => 'AC, Proyektor'],
            ['kode_ruangan' => 'R008', 'nama_ruangan' => 'Kelas XII-2', 'gedung' => 'C', 'kapasitas' => 35, 'fasilitas' => 'AC, Proyektor'],
            ['kode_ruangan' => 'R009', 'nama_ruangan' => 'Aula', 'gedung' => 'D', 'kapasitas' => 200, 'fasilitas' => 'Panggung, Sound System'],
            ['kode_ruangan' => 'R010', 'nama_ruangan' => 'Perpustakaan', 'gedung' => 'D', 'kapasitas' => 50, 'fasilitas' => 'Rak Buku, Meja Baca'],
        ];

        foreach ($ruangans as $ruangan) {
            Ruangan::create($ruangan);
        }

        $this->command->info('Ruangan seeder berhasil dijalankan! Total: ' . count($ruangans) . ' ruangan');
    }
}