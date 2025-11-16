<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Guru;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data jadwal yang sudah ada
        Jadwal::query()->delete();

        // Ambil semua data yang diperlukan
        $kelasList = Kelas::all();
        $mapelList = Mapel::all();
        $guruList = Guru::all();

        if ($kelasList->isEmpty() || $mapelList->isEmpty() || $guruList->isEmpty()) {
            $this->command->warn('Data tidak lengkap! Pastikan GuruSeeder, MapelSeeder, dan KelasSeeder sudah dijalankan.');
            return;
        }

        $jadwals = [];

        foreach ($kelasList as $kelas) {
            // Skip kelas laboratorium untuk jadwal reguler
            if (str_contains($kelas->nama_kelas, 'LAB')) {
                continue;
            }

            $this->command->info("Generating jadwal untuk: {$kelas->nama_kelas}");

            // Mapel untuk kelas berdasarkan tingkat dan jurusan
            $mapelKelas = $mapelList->where('tingkat', $kelas->tingkat)
                                   ->where(function($query) use ($kelas) {
                                       return $query->where('jurusan', $kelas->jurusan)
                                                   ->orWhereNull('jurusan');
                                   });

            if ($mapelKelas->isEmpty()) {
                $this->command->warn("  ⚠️ Tidak ada mapel untuk {$kelas->nama_kelas}");
                continue;
            }

            $this->command->info("  ✅ Found {$mapelKelas->count()} mapel for {$kelas->nama_kelas}");

            // Generate jadwal untuk 6 hari (Senin-Sabtu)
            $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            
            foreach ($hariList as $hari) {
                $jamPelajaran = $this->getJamPelajaran($hari);
                
                foreach ($jamPelajaran as $jam) {
                    $mapel = $mapelKelas->random();
                    $guru = $guruList->random();
                    
                    $jadwals[] = [
                        'kelas_id' => $kelas->id,
                        'mapel_id' => $mapel->id,
                        'guru_id' => $guru->id,
                        'hari' => $hari,
                        'jam_mulai' => $jam[0],
                        'jam_selesai' => $jam[1],
                        'ruangan' => $this->getRuangan($mapel->nama_mapel, $kelas->nama_kelas),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Insert data jadwal
        foreach (array_chunk($jadwals, 50) as $chunk) {
            Jadwal::insert($chunk);
        }

        $this->command->info('✅ Jadwal seeder berhasil! Total: ' . count($jadwals) . ' jadwal');
        $this->showSummary($jadwals, $kelasList);
    }

    private function getJamPelajaran(string $hari): array
    {
        if (in_array($hari, ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'])) {
            return [
                ['07:00', '07:45'], ['07:45', '08:30'], ['08:30', '09:15'],
                ['09:15', '10:00'], ['10:15', '11:00'], ['11:00', '11:45'],
                ['11:45', '12:30'],
            ];
        }
        
        // Sabtu: 5 jam pelajaran
        return [
            ['07:00', '07:45'], ['07:45', '08:30'], ['08:30', '09:15'],
            ['09:15', '10:00'], ['10:15', '11:00'],
        ];
    }

    private function getRuangan(string $namaMapel, string $namaKelas): string
    {
        $namaMapel = strtolower($namaMapel);
        
        if (str_contains($namaMapel, 'biologi') || str_contains($namaMapel, 'praktikum biologi')) {
            return 'Lab. Biologi';
        } elseif (str_contains($namaMapel, 'fisika') || str_contains($namaMapel, 'praktikum fisika')) {
            return 'Lab. Fisika';
        } elseif (str_contains($namaMapel, 'kimia') || str_contains($namaMapel, 'praktikum kimia')) {
            return 'Lab. Kimia';
        } elseif (str_contains($namaMapel, 'komputer') || str_contains($namaMapel, 'praktikum komputer')) {
            return 'Lab. Komputer';
        } elseif (str_contains($namaMapel, 'olahraga') || str_contains($namaMapel, 'pjok')) {
            return 'Lapangan Olahraga';
        } elseif (str_contains($namaMapel, 'seni')) {
            return 'Aula';
        } else {
            if (str_contains($namaKelas, '10 IPA')) return 'R.101';
            if (str_contains($namaKelas, '10 IPS')) return 'R.102';
            if (str_contains($namaKelas, '11 IPA')) return 'R.201';
            if (str_contains($namaKelas, '11 IPS')) return 'R.202';
            if (str_contains($namaKelas, '12 IPA')) return 'R.301';
            if (str_contains($namaKelas, '12 IPS')) return 'R.302';
            return 'R.101';
        }
    }

    private function showSummary(array $jadwals, $kelasList): void
    {
        $this->command->info("\n" . str_repeat('=', 50));
        $this->command->info("📊 SUMMARY JADWAL PELAJARAN");
        $this->command->info(str_repeat('=', 50));
        
        $totalJadwal = 0;
        
        foreach ($kelasList as $kelas) {
            if (str_contains($kelas->nama_kelas, 'LAB')) continue;
            
            $jadwalKelas = array_filter($jadwals, function($jadwal) use ($kelas) {
                return $jadwal['kelas_id'] === $kelas->id;
            });
            
            $countJadwal = count($jadwalKelas);
            $totalJadwal += $countJadwal;
            
            $this->command->info("📚 {$kelas->nama_kelas}: {$countJadwal} jadwal");
        }
        
        $this->command->info(str_repeat('=', 50));
        $this->command->info("📈 TOTAL: {$totalJadwal} jadwal untuk 12 kelas reguler");
        $this->command->info("🗓️  PERIODE: Senin - Sabtu");
        $this->command->info("⏰ JAM: 07:00 - 12:30 (Senin-Jumat), 07:00 - 11:00 (Sabtu)");
        $this->command->info(str_repeat('=', 50));
    }
}