<?php

namespace Database\Seeders;

use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AbsensiSeeder extends Seeder
{
    public function run(): void
    {
        $startDate = Carbon::create(2024, 7, 1); // Tanggal awal semester
        $endDate = Carbon::create(2024, 12, 31); // Tanggal akhir semester
        
        $siswaIds = [1, 2, 3, 4]; // 4 siswa pertama
        $jadwalIds = [1, 2, 3]; // 3 jadwal pertama

        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            // Hanya hari Senin, Selasa, Rabu (contoh)
            if (in_array($currentDate->englishDayOfWeek, ['Monday', 'Tuesday', 'Wednesday'])) {
                foreach ($jadwalIds as $jadwalId) {
                    foreach ($siswaIds as $siswaId) {
                        // Random status kehadiran (80% hadir, 20% lainnya)
                        $random = rand(1, 100);
                        if ($random <= 80) {
                            $status = 'hadir';
                            $keterangan = null;
                        } elseif ($random <= 90) {
                            $status = 'sakit';
                            $keterangan = 'Sakit';
                        } elseif ($random <= 95) {
                            $status = 'izin';
                            $keterangan = 'Ijin keluarga';
                        } else {
                            $status = 'alpha';
                            $keterangan = 'Tanpa keterangan';
                        }

                        Absensi::create([
                            'siswa_id' => $siswaId,
                            'jadwal_id' => $jadwalId,
                            'tanggal' => $currentDate->format('Y-m-d'),
                            'status' => $status,
                            'keterangan' => $keterangan,
                        ]);
                    }
                }
            }
            
            $currentDate->addDay();
        }
    }
}