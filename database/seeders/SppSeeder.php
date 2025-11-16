<?php

namespace Database\Seeders;

use App\Models\Spp;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SppSeeder extends Seeder
{
    public function run()
    {
        // Nonaktifkan foreign key checks untuk menghindari error
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Spp::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Ambil data siswa aktif
        $siswas = Siswa::where('status', 'aktif')->get();
        
        // Ambil tahun ajaran aktif
        $tahunAjarans = TahunAjaran::where('status', 'aktif')->get();

        if ($siswas->isEmpty()) {
            $this->command->info('❌ Tidak ada siswa aktif! Skipping SPP seeder.');
            return;
        }

        if ($tahunAjarans->isEmpty()) {
            $this->command->info('❌ Tidak ada tahun ajaran aktif! Skipping SPP seeder.');
            return;
        }

        $this->command->info('🎯 Memulai seeding data SPP...');

        $bulanList = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $currentYear = date('Y');
        $totalData = 0;

        // Data untuk tahun berjalan
        foreach ($siswas as $siswa) {
            foreach ($tahunAjarans as $tahunAjaran) {
                foreach ($bulanList as $bulan) {
                    // Skip jika sudah ada data untuk kombinasi yang sama
                    $exists = Spp::where('siswa_id', $siswa->id)
                        ->where('tahun_ajaran_id', $tahunAjaran->id)
                        ->where('bulan', $bulan)
                        ->where('tahun', $currentYear)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    // Tentukan status berdasarkan probabilitas
                    $status = $this->getRandomStatus();
                    
                    // Tentukan tanggal bayar berdasarkan status
                    $tanggalBayar = null;
                    if ($status === 'lunas') {
                        $bulanNumber = array_search($bulan, $bulanList) + 1;
                        $currentMonth = date('n');
                        
                        if ($bulanNumber < $currentMonth) {
                            // Bulan sudah lewat, set tanggal di bulan tersebut
                            $tanggalBayar = $this->generateRandomDate($bulanNumber, $currentYear);
                        } else {
                            // Bulan berjalan atau mendatang, set tanggal acak
                            $tanggalBayar = now()->subDays(rand(1, 30));
                        }
                    }

                    // Tentukan nominal berdasarkan tingkat kelas
                    $nominal = $this->getNominalByKelas($siswa->kelas_id);

                    Spp::create([
                        'siswa_id' => $siswa->id,
                        'tahun_ajaran_id' => $tahunAjaran->id,
                        'nominal' => $nominal,
                        'bulan' => $bulan,
                        'tahun' => $currentYear,
                        'tanggal_bayar' => $tanggalBayar,
                        'status' => $status,
                        'keterangan' => $this->getKeterangan($status),
                    ]);

                    $totalData++;
                }
            }
        }

        // Data untuk tahun sebelumnya (opsional)
        $this->createPreviousYearData($siswas, $tahunAjarans);

        $this->command->info("✅ Berhasil membuat {$totalData} data SPP untuk {$siswas->count()} siswa aktif.");
    }

    /**
     * Generate random status dengan probabilitas
     */
    private function getRandomStatus(): string
    {
        $random = rand(1, 100);
        
        // Probabilitas:
        // - 70% lunas
        // - 20% belum bayar  
        // - 10% tertunggak
        if ($random <= 70) {
            return 'lunas';
        } elseif ($random <= 90) {
            return 'belum_bayar';
        } else {
            return 'tertunggak';
        }
    }

    /**
     * Generate random date dalam bulan tertentu
     */
    private function generateRandomDate($month, $year): string
    {
        // Tentukan hari terakhir dalam bulan
        $lastDay = date('t', mktime(0, 0, 0, $month, 1, $year));
        
        // Generate random day antara 1 dan hari terakhir
        $day = rand(1, $lastDay);
        
        return sprintf('%d-%02d-%02d', $year, $month, $day);
    }

    /**
     * Tentukan nominal berdasarkan tingkat kelas
     */
    private function getNominalByKelas($kelasId): float
    {
        // Default nominal jika tidak ada kelas
        $defaultNominal = 250000;

        if (!$kelasId) {
            return $defaultNominal;
        }

        // Ambil data kelas
        $kelas = DB::table('kelas')->where('id', $kelasId)->first();
        
        if (!$kelas) {
            return $defaultNominal;
        }

        // Tentukan nominal berdasarkan tingkat
        $namaKelas = $kelas->nama_kelas ?? '';
        
        if (str_contains($namaKelas, 'XII') || str_contains($namaKelas, '12')) {
            return 300000; // Kelas 12 lebih mahal
        } elseif (str_contains($namaKelas, 'XI') || str_contains($namaKelas, '11')) {
            return 275000; // Kelas 11
        } elseif (str_contains($namaKelas, 'X') || str_contains($namaKelas, '10')) {
            return 250000; // Kelas 10
        } else {
            return $defaultNominal;
        }
    }

    /**
     * Generate keterangan berdasarkan status
     */
    private function getKeterangan($status): ?string
    {
        return match($status) {
            'lunas' => 'Pembayaran lunas sesuai jadwal',
            'tertunggak' => 'Menunggu konfirmasi pembayaran',
            'belum_bayar' => 'Belum melakukan pembayaran',
            default => null
        };
    }

    /**
     * Buat data untuk tahun sebelumnya
     */
    private function createPreviousYearData($siswas, $tahunAjarans)
    {
        $bulanList = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $previousYear = date('Y') - 1;
        $created = 0;

        foreach ($siswas as $siswa) {
            foreach ($tahunAjarans as $tahunAjaran) {
                // Untuk tahun sebelumnya, buat data untuk 6 bulan pertama
                foreach (array_slice($bulanList, 0, 6) as $bulan) {
                    $exists = Spp::where('siswa_id', $siswa->id)
                        ->where('tahun_ajaran_id', $tahunAjaran->id)
                        ->where('bulan', $bulan)
                        ->where('tahun', $previousYear)
                        ->exists();

                    if (!$exists) {
                        Spp::create([
                            'siswa_id' => $siswa->id,
                            'tahun_ajaran_id' => $tahunAjaran->id,
                            'nominal' => 240000, // Nominal tahun lalu
                            'bulan' => $bulan,
                            'tahun' => $previousYear,
                            'tanggal_bayar' => $this->generateRandomDate(
                                array_search($bulan, $bulanList) + 1, 
                                $previousYear
                            ),
                            'status' => 'lunas',
                            'keterangan' => 'Pembayaran tahun sebelumnya',
                        ]);
                        $created++;
                    }
                }
            }
        }

        if ($created > 0) {
            $this->command->info("📅 Berhasil membuat {$created} data SPP untuk tahun sebelumnya.");
        }
    }
}