<?php

namespace Database\Seeders;

use App\Models\BiayaSpp;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BiayaSppSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🔄 Memulai seeding data Biaya SPP...');

        // Kosongkan tabel
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        BiayaSpp::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Ambil data yang diperlukan
        $tahunAjarans = TahunAjaran::all();
        $kelas = Kelas::all();

        if ($tahunAjarans->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada data tahun ajaran, menggunakan default...');
            $tahunAjarans = collect([(object)['id' => 1]]);
        }

        if ($kelas->isEmpty()) {
            $this->command->error('❌ Tidak ada data kelas!');
            return;
        }

        // Ambil kategori biaya SPP
        $kategoriSpp = DB::table('kategori_biaya')
            ->where('jenis', 'spp')
            ->where('status', 1)
            ->get();

        if ($kategoriSpp->isEmpty()) {
            $this->command->error('❌ Tidak ada kategori biaya SPP! Jalankan KategoriBiayaSeeder dulu.');
            return;
        }

        $biayaData = [];
        $currentDate = now();
        $createdCount = 0;

        foreach ($tahunAjarans as $tahunAjaran) {
            foreach ($kelas as $kelasItem) {
                // Tentukan kategori biaya berdasarkan tingkat kelas
                $kategori = $this->getKategoriByTingkat($kelasItem->nama_kelas, $kategoriSpp);
                
                if (!$kategori) {
                    $this->command->warn("⚠️ Tidak ada kategori untuk kelas {$kelasItem->nama_kelas}");
                    continue;
                }

                $biayaData[] = [
                    'tahun_ajaran_id' => $tahunAjaran->id,
                    'kelas_id' => $kelasItem->id,
                    'kategori_biaya_id' => $kategori->id,
                    'jumlah' => $kategori->jumlah_biaya,
                    'tanggal_mulai' => $currentDate->format('Y-m-d'),
                    'tanggal_selesai' => $currentDate->copy()->addYear()->format('Y-m-d'),
                    'status' => 1,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ];

                $createdCount++;
            }
        }

        // Insert data
        BiayaSpp::insert($biayaData);

        $this->command->info("✅ Berhasil membuat {$createdCount} data biaya SPP.");
    }

    /**
     * Tentukan kategori biaya berdasarkan tingkat kelas
     */
    private function getKategoriByTingkat($namaKelas, $kategoriSpp)
    {
        if (str_contains($namaKelas, 'XII') || str_contains($namaKelas, '12')) {
            return $kategoriSpp->firstWhere('nama_kategori', 'SPP Kelas 12') ?? $kategoriSpp->first();
        } elseif (str_contains($namaKelas, 'XI') || str_contains($namaKelas, '11')) {
            return $kategoriSpp->firstWhere('nama_kategori', 'SPP Kelas 11') ?? $kategoriSpp->first();
        } elseif (str_contains($namaKelas, 'X') || str_contains($namaKelas, '10')) {
            return $kategoriSpp->firstWhere('nama_kategori', 'SPP Kelas 10') ?? $kategoriSpp->first();
        } else {
            return $kategoriSpp->firstWhere('nama_kategori', 'SPP Reguler') ?? $kategoriSpp->first();
        }
    }
}