<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = [
            // Kelas 10 - Wali Kelas dari user_id 2-5
            ['nama_kelas' => '10 IPA 1', 'tingkat' => '10', 'jurusan' => 'IPA', 'wali_kelas_id' => 1, 'kapasitas' => 36],
            ['nama_kelas' => '10 IPA 2', 'tingkat' => '10', 'jurusan' => 'IPA', 'wali_kelas_id' => 2, 'kapasitas' => 36],
            ['nama_kelas' => '10 IPS 1', 'tingkat' => '10', 'jurusan' => 'IPS', 'wali_kelas_id' => 3, 'kapasitas' => 36],
            ['nama_kelas' => '10 IPS 2', 'tingkat' => '10', 'jurusan' => 'IPS', 'wali_kelas_id' => 4, 'kapasitas' => 36],
            
            // Kelas 11 - Wali Kelas dari user_id 5-8
            ['nama_kelas' => '11 IPA 1', 'tingkat' => '11', 'jurusan' => 'IPA', 'wali_kelas_id' => 5, 'kapasitas' => 36],
            ['nama_kelas' => '11 IPA 2', 'tingkat' => '11', 'jurusan' => 'IPA', 'wali_kelas_id' => 6, 'kapasitas' => 36],
            ['nama_kelas' => '11 IPS 1', 'tingkat' => '11', 'jurusan' => 'IPS', 'wali_kelas_id' => 7, 'kapasitas' => 36],
            ['nama_kelas' => '11 IPS 2', 'tingkat' => '11', 'jurusan' => 'IPS', 'wali_kelas_id' => 8, 'kapasitas' => 36],
            
            // Kelas 12 - Wali Kelas dari user_id 9-12
            ['nama_kelas' => '12 IPA 1', 'tingkat' => '12', 'jurusan' => 'IPA', 'wali_kelas_id' => 9, 'kapasitas' => 36],
            ['nama_kelas' => '12 IPA 2', 'tingkat' => '12', 'jurusan' => 'IPA', 'wali_kelas_id' => 10, 'kapasitas' => 36],
            ['nama_kelas' => '12 IPS 1', 'tingkat' => '12', 'jurusan' => 'IPS', 'wali_kelas_id' => 11, 'kapasitas' => 36],
            ['nama_kelas' => '12 IPS 2', 'tingkat' => '12', 'jurusan' => 'IPS', 'wali_kelas_id' => 12, 'kapasitas' => 36],
            
            // Ruang Laboratorium - Kepala Lab dari user_id 13-16
            ['nama_kelas' => 'LAB BIOLOGI', 'tingkat' => 'LAB', 'jurusan' => 'UMUM', 'wali_kelas_id' => 13, 'kapasitas' => 24],
            ['nama_kelas' => 'LAB FISIKA', 'tingkat' => 'LAB', 'jurusan' => 'UMUM', 'wali_kelas_id' => 14, 'kapasitas' => 24],
            ['nama_kelas' => 'LAB KIMIA', 'tingkat' => 'LAB', 'jurusan' => 'UMUM', 'wali_kelas_id' => 15, 'kapasitas' => 24],
            ['nama_kelas' => 'LAB KOMPUTER', 'tingkat' => 'LAB', 'jurusan' => 'UMUM', 'wali_kelas_id' => 16, 'kapasitas' => 30],
        ];

        foreach ($kelas as $data) {
            Kelas::firstOrCreate(
                ['nama_kelas' => $data['nama_kelas']],
                $data
            );
        }

        $this->command->info('Kelas seeder berhasil dijalankan dengan wali kelas!');
    }
}