<?php

namespace Database\Seeders;

use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Mapel;
use App\Models\Guru;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NilaiSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Menghapus data nilai lama...');
        DB::table('nilais')->delete();

        $tahunAjaranAktif = TahunAjaran::where('status', 'aktif')->first();

        if (!$tahunAjaranAktif) {
            $this->command->error('Tidak ada tahun ajaran aktif! Buat tahun ajaran aktif terlebih dahulu.');
            return;
        }

        // Ambil data
        $siswas = Siswa::where('status', 'aktif')->get();
        $gurus = Guru::where('status', 'aktif')->get();

        if ($siswas->isEmpty() || $gurus->isEmpty()) {
            $this->command->error('Data siswa atau guru tidak ditemukan!');
            return;
        }

        $totalCreated = 0;
        $semesters = ['ganjil', 'genap'];

        foreach ($siswas as $siswa) {
            $this->command->info("Memproses siswa: {$siswa->nama_lengkap} (Kelas: {$siswa->kelas->nama_kelas})");
            
            // TENTUKAN TINGKAT BERDASARKAN KELAS (10, 11, 12)
            $tingkatSiswa = $this->getTingkatFromKelas($siswa->kelas->nama_kelas);
            
            // AMBIL MAPEL YANG SESUAI TINGKAT SISWA SAJA
            $mapels = Mapel::where('tingkat', $tingkatSiswa)->get();
            
            if ($mapels->isEmpty()) {
                $this->command->warn("  ⚠️  Tidak ada mapel untuk tingkat {$tingkatSiswa}");
                continue;
            }

            $this->command->info("  📚 Mapel untuk tingkat {$tingkatSiswa}: {$mapels->count()} mapel");

            // Mapping guru untuk setiap mapel (1 GURU TETAP PER MAPEL)
            $guruPerMapel = [];
            foreach ($mapels as $mapel) {
                $guruPerMapel[$mapel->id] = $gurus->random()->id;
            }

            foreach ($semesters as $semester) {
                $nilaiData = [];
                
                foreach ($mapels as $mapel) {
                    // Filter mapel berdasarkan jurusan
                    if ($mapel->jurusan && $mapel->jurusan !== 'UMUM') {
                        $kelas = $siswa->kelas;
                        if ($kelas && $kelas->jurusan !== $mapel->jurusan) {
                            continue;
                        }
                    }

                    // GENERATE NILAI REALISTIS
                    $baseAbility = rand(70, 95); // Kemampuan dasar siswa
                    
                    $nilaiUh1 = max(60, min(100, $baseAbility + rand(-10, 10)));
                    $nilaiUh2 = max(60, min(100, $baseAbility + rand(-10, 10))); 
                    $nilaiUts = max(60, min(100, $baseAbility + rand(-15, 5)));
                    $nilaiUas = max(60, min(100, $baseAbility + rand(-15, 5)));
                    
                    $nilaiAkhir = ($nilaiUh1 * 0.2) + ($nilaiUh2 * 0.2) + ($nilaiUts * 0.3) + ($nilaiUas * 0.3);
                    $nilaiAkhir = round($nilaiAkhir, 1);

                    // Gunakan guru yang sudah ditentukan untuk mapel ini
                    $guruId = $guruPerMapel[$mapel->id];

                    $nilaiData[] = [
                        'siswa_id' => $siswa->id,
                        'mapel_id' => $mapel->id,
                        'guru_id' => $guruId,
                        'tahun_ajaran_id' => $tahunAjaranAktif->id,
                        'semester' => $semester,
                        'nilai_uh1' => $nilaiUh1,
                        'nilai_uh2' => $nilaiUh2,
                        'nilai_uts' => $nilaiUts,
                        'nilai_uas' => $nilaiUas,
                        'nilai_akhir' => $nilaiAkhir,
                        'predikat' => $this->getPredikat($nilaiAkhir),
                        'deskripsi' => $this->getDeskripsi($mapel->nama_mapel, $nilaiAkhir),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // INSERT DALAM BATCH KECIL (10 data per batch)
                    if (count($nilaiData) >= 10) {
                        Nilai::insert($nilaiData);
                        $totalCreated += count($nilaiData);
                        $nilaiData = []; // Reset array
                    }
                }

                // Insert sisa data
                if (!empty($nilaiData)) {
                    Nilai::insert($nilaiData);
                    $totalCreated += count($nilaiData);
                }
            }

            $this->command->info("  ✅ {$siswa->nama_lengkap}: {$mapels->count()} mapel × 2 semester = " . ($mapels->count() * 2) . " nilai");
        }

        $this->command->info("\n✅ Nilai seeder selesai! Total {$totalCreated} nilai dibuat.");

        $this->showQuickSummary();
    }

    private function getTingkatFromKelas($namaKelas)
    {
        // Ekstrak tingkat dari nama kelas (contoh: "10 IPA 1" -> "10")
        if (preg_match('/^(\d+)/', $namaKelas, $matches)) {
            return $matches[1];
        }
        return '10'; // Default ke kelas 10
    }

    private function getPredikat($nilai)
    {
        if ($nilai >= 90) return 'A';
        if ($nilai >= 80) return 'B';
        if ($nilai >= 70) return 'C';
        if ($nilai >= 60) return 'D';
        return 'E';
    }

    private function getDeskripsi($namaMapel, $nilai)
    {
        $keterangan = $nilai >= 90 ? 'sangat baik dan konsisten' :
                     ($nilai >= 80 ? 'baik dan memuaskan' :
                     ($nilai >= 70 ? 'cukup dan perlu peningkatan' :
                     ($nilai >= 60 ? 'perlu banyak perbaikan' : 'sangat perlu perhatian khusus')));

        return "Memahami materi {$namaMapel} dengan {$keterangan}.";
    }

    private function showQuickSummary()
    {
        $this->command->info("\n" . str_repeat('=', 50));
        $this->command->info("QUICK SUMMARY NILAI");
        $this->command->info(str_repeat('=', 50));

        $totalSiswa = Siswa::where('status', 'aktif')->count();
        $totalSiswaDenganNilai = Nilai::distinct('siswa_id')->count('siswa_id');
        $totalNilai = Nilai::count();

        $this->command->info("👥 Total Siswa Aktif: {$totalSiswa}");
        $this->command->info("📝 Siswa dengan Nilai: {$totalSiswaDenganNilai}");
        $this->command->info("📊 Total Nilai: {$totalNilai}");

        // CEK DUPLIKAT (SEHARUSNYA 0)
        $duplikat = DB::table('nilais')
            ->select('siswa_id', 'mapel_id', 'semester', DB::raw('COUNT(*) as count'))
            ->groupBy('siswa_id', 'mapel_id', 'semester')
            ->having('count', '>', 1)
            ->count();

        $this->command->info("🚨 Data Duplikat: {$duplikat}");

        if ($duplikat > 0) {
            $this->command->error("❌ MASIH ADA DATA DUPLIKAT! PERBAIKI SEEDER!");
        } else {
            $this->command->info("✅ TIDAK ADA DATA DUPLIKAT!");
        }

        // Cek data per siswa
        $siswaSample = Siswa::withCount(['nilai' => function($q) {
            $q->where('semester', 'ganjil');
        }])->first();

        if ($siswaSample) {
            $this->command->info("📚 Contoh: {$siswaSample->nama_lengkap} punya {$siswaSample->nilai_count} nilai semester ganjil");
        }

        $this->command->info(str_repeat('=', 50));
    }
}