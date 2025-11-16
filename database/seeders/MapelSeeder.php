<?php

namespace Database\Seeders;

use App\Models\Mapel;
use Illuminate\Database\Seeder;

class MapelSeeder extends Seeder
{
    public function run(): void
    {
        $mapels = [
            // ==================== KELAS 10 ====================
            // Kelompok A (Umum) - Kelas 10
            ['kode_mapel' => 'PAI10', 'nama_mapel' => 'Pendidikan Agama Islam', 'tingkat' => '10', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'PPKN10', 'nama_mapel' => 'Pendidikan Pancasila dan Kewarganegaraan', 'tingkat' => '10', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'BIN10', 'nama_mapel' => 'Bahasa Indonesia', 'tingkat' => '10', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'BIG10', 'nama_mapel' => 'Bahasa Inggris', 'tingkat' => '10', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'MTK10', 'nama_mapel' => 'Matematika', 'tingkat' => '10', 'jurusan' => null, 'kkm' => 75],
            
            // Kelompok B (Umum) - Kelas 10
            ['kode_mapel' => 'SEJ10', 'nama_mapel' => 'Sejarah Indonesia', 'tingkat' => '10', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'SENI10', 'nama_mapel' => 'Seni Budaya', 'tingkat' => '10', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'PJOK10', 'nama_mapel' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'tingkat' => '10', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'PKWU10', 'nama_mapel' => 'Prakarya dan Kewirausahaan', 'tingkat' => '10', 'jurusan' => null, 'kkm' => 75],
            
            // Kelompok C (Peminatan IPA) - Kelas 10 IPA
            ['kode_mapel' => 'BIO10', 'nama_mapel' => 'Biologi', 'tingkat' => '10', 'jurusan' => 'IPA', 'kkm' => 75],
            ['kode_mapel' => 'FIS10', 'nama_mapel' => 'Fisika', 'tingkat' => '10', 'jurusan' => 'IPA', 'kkm' => 75],
            ['kode_mapel' => 'KIM10', 'nama_mapel' => 'Kimia', 'tingkat' => '10', 'jurusan' => 'IPA', 'kkm' => 75],
            
            // Kelompok C (Peminatan IPS) - Kelas 10 IPS
            ['kode_mapel' => 'EKO10', 'nama_mapel' => 'Ekonomi', 'tingkat' => '10', 'jurusan' => 'IPS', 'kkm' => 75],
            ['kode_mapel' => 'GEO10', 'nama_mapel' => 'Geografi', 'tingkat' => '10', 'jurusan' => 'IPS', 'kkm' => 75],
            ['kode_mapel' => 'SOS10', 'nama_mapel' => 'Sosiologi', 'tingkat' => '10', 'jurusan' => 'IPS', 'kkm' => 75],
            
            // Mata Pelajaran Praktikum Lab - Kelas 10
            ['kode_mapel' => 'LAB-BIO10', 'nama_mapel' => 'Praktikum Biologi', 'tingkat' => '10', 'jurusan' => 'IPA', 'kkm' => 75],
            ['kode_mapel' => 'LAB-FIS10', 'nama_mapel' => 'Praktikum Fisika', 'tingkat' => '10', 'jurusan' => 'IPA', 'kkm' => 75],
            ['kode_mapel' => 'LAB-KIM10', 'nama_mapel' => 'Praktikum Kimia', 'tingkat' => '10', 'jurusan' => 'IPA', 'kkm' => 75],
            ['kode_mapel' => 'LAB-KOM10', 'nama_mapel' => 'Praktikum Komputer', 'tingkat' => '10', 'jurusan' => 'UMUM', 'kkm' => 75],

            // ==================== KELAS 11 ====================
            // Kelompok A (Umum) - Kelas 11
            ['kode_mapel' => 'PAI11', 'nama_mapel' => 'Pendidikan Agama Islam', 'tingkat' => '11', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'PPKN11', 'nama_mapel' => 'Pendidikan Pancasila dan Kewarganegaraan', 'tingkat' => '11', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'BIN11', 'nama_mapel' => 'Bahasa Indonesia', 'tingkat' => '11', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'BIG11', 'nama_mapel' => 'Bahasa Inggris', 'tingkat' => '11', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'MTK11', 'nama_mapel' => 'Matematika', 'tingkat' => '11', 'jurusan' => null, 'kkm' => 75],
            
            // Kelompok B (Umum) - Kelas 11
            ['kode_mapel' => 'SEJ11', 'nama_mapel' => 'Sejarah Indonesia', 'tingkat' => '11', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'SENI11', 'nama_mapel' => 'Seni Budaya', 'tingkat' => '11', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'PJOK11', 'nama_mapel' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'tingkat' => '11', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'PKWU11', 'nama_mapel' => 'Prakarya dan Kewirausahaan', 'tingkat' => '11', 'jurusan' => null, 'kkm' => 75],
            
            // Kelompok C (Peminatan IPA) - Kelas 11 IPA
            ['kode_mapel' => 'BIO11', 'nama_mapel' => 'Biologi', 'tingkat' => '11', 'jurusan' => 'IPA', 'kkm' => 75],
            ['kode_mapel' => 'FIS11', 'nama_mapel' => 'Fisika', 'tingkat' => '11', 'jurusan' => 'IPA', 'kkm' => 75],
            ['kode_mapel' => 'KIM11', 'nama_mapel' => 'Kimia', 'tingkat' => '11', 'jurusan' => 'IPA', 'kkm' => 75],
            
            // Kelompok C (Peminatan IPS) - Kelas 11 IPS
            ['kode_mapel' => 'EKO11', 'nama_mapel' => 'Ekonomi', 'tingkat' => '11', 'jurusan' => 'IPS', 'kkm' => 75],
            ['kode_mapel' => 'GEO11', 'nama_mapel' => 'Geografi', 'tingkat' => '11', 'jurusan' => 'IPS', 'kkm' => 75],
            ['kode_mapel' => 'SOS11', 'nama_mapel' => 'Sosiologi', 'tingkat' => '11', 'jurusan' => 'IPS', 'kkm' => 75],
            
            // Mata Pelajaran Praktikum Lab - Kelas 11
            ['kode_mapel' => 'LAB-BIO11', 'nama_mapel' => 'Praktikum Biologi', 'tingkat' => '11', 'jurusan' => 'IPA', 'kkm' => 75],
            ['kode_mapel' => 'LAB-FIS11', 'nama_mapel' => 'Praktikum Fisika', 'tingkat' => '11', 'jurusan' => 'IPA', 'kkm' => 75],
            ['kode_mapel' => 'LAB-KIM11', 'nama_mapel' => 'Praktikum Kimia', 'tingkat' => '11', 'jurusan' => 'IPA', 'kkm' => 75],
            ['kode_mapel' => 'LAB-KOM11', 'nama_mapel' => 'Praktikum Komputer', 'tingkat' => '11', 'jurusan' => 'UMUM', 'kkm' => 75],

            // ==================== KELAS 12 ====================
            // Kelompok A (Umum) - Kelas 12
            ['kode_mapel' => 'PAI12', 'nama_mapel' => 'Pendidikan Agama Islam', 'tingkat' => '12', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'PPKN12', 'nama_mapel' => 'Pendidikan Pancasila dan Kewarganegaraan', 'tingkat' => '12', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'BIN12', 'nama_mapel' => 'Bahasa Indonesia', 'tingkat' => '12', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'BIG12', 'nama_mapel' => 'Bahasa Inggris', 'tingkat' => '12', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'MTK12', 'nama_mapel' => 'Matematika', 'tingkat' => '12', 'jurusan' => null, 'kkm' => 75],
            
            // Kelompok B (Umum) - Kelas 12
            ['kode_mapel' => 'SEJ12', 'nama_mapel' => 'Sejarah Indonesia', 'tingkat' => '12', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'SENI12', 'nama_mapel' => 'Seni Budaya', 'tingkat' => '12', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'PJOK12', 'nama_mapel' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'tingkat' => '12', 'jurusan' => null, 'kkm' => 75],
            ['kode_mapel' => 'PKWU12', 'nama_mapel' => 'Prakarya dan Kewirausahaan', 'tingkat' => '12', 'jurusan' => null, 'kkm' => 75],
            
            // Kelompok C (Peminatan IPA) - Kelas 12 IPA
            ['kode_mapel' => 'BIO12', 'nama_mapel' => 'Biologi', 'tingkat' => '12', 'jurusan' => 'IPA', 'kkm' => 75],
            ['kode_mapel' => 'FIS12', 'nama_mapel' => 'Fisika', 'tingkat' => '12', 'jurusan' => 'IPA', 'kkm' => 75],
            ['kode_mapel' => 'KIM12', 'nama_mapel' => 'Kimia', 'tingkat' => '12', 'jurusan' => 'IPA', 'kkm' => 75],
            
            // Kelompok C (Peminatan IPS) - Kelas 12 IPS
            ['kode_mapel' => 'EKO12', 'nama_mapel' => 'Ekonomi', 'tingkat' => '12', 'jurusan' => 'IPS', 'kkm' => 75],
            ['kode_mapel' => 'GEO12', 'nama_mapel' => 'Geografi', 'tingkat' => '12', 'jurusan' => 'IPS', 'kkm' => 75],
            ['kode_mapel' => 'SOS12', 'nama_mapel' => 'Sosiologi', 'tingkat' => '12', 'jurusan' => 'IPS', 'kkm' => 75],
            
            // Mata Pelajaran Praktikum Lab - Kelas 12
            ['kode_mapel' => 'LAB-BIO12', 'nama_mapel' => 'Praktikum Biologi', 'tingkat' => '12', 'jurusan' => 'IPA', 'kkm' => 75],
            ['kode_mapel' => 'LAB-FIS12', 'nama_mapel' => 'Praktikum Fisika', 'tingkat' => '12', 'jurusan' => 'IPA', 'kkm' => 75],
            ['kode_mapel' => 'LAB-KIM12', 'nama_mapel' => 'Praktikum Kimia', 'tingkat' => '12', 'jurusan' => 'IPA', 'kkm' => 75],
            ['kode_mapel' => 'LAB-KOM12', 'nama_mapel' => 'Praktikum Komputer', 'tingkat' => '12', 'jurusan' => 'UMUM', 'kkm' => 75],
        ];

        foreach ($mapels as $mapel) {
            Mapel::firstOrCreate(
                ['kode_mapel' => $mapel['kode_mapel']],
                $mapel
            );
        }

        $this->command->info('Mapel seeder berhasil dijalankan! Total: ' . count($mapels) . ' mapel');
        
        // Tampilkan summary
        $this->showSummary();
    }

    private function showSummary(): void
    {
        $count10 = Mapel::where('tingkat', '10')->count();
        $count11 = Mapel::where('tingkat', '11')->count();
        $count12 = Mapel::where('tingkat', '12')->count();
        
        $this->command->info("\n" . str_repeat('=', 50));
        $this->command->info("SUMMARY MAPEL PER TINGKAT");
        $this->command->info(str_repeat('=', 50));
        $this->command->info("📚 Kelas 10: {$count10} mapel");
        $this->command->info("📚 Kelas 11: {$count11} mapel");
        $this->command->info("📚 Kelas 12: {$count12} mapel");
        $this->command->info("📊 TOTAL: " . ($count10 + $count11 + $count12) . " mapel");
        $this->command->info(str_repeat('=', 50));
    }
}