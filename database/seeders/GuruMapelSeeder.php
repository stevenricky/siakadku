<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Guru;
use App\Models\Mapel;

class GuruMapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada data guru dan mapel terlebih dahulu
        $gurus = Guru::all();
        $mapels = Mapel::all();

        if ($gurus->isEmpty()) {
            $this->command->warn('Data guru masih kosong. Silakan jalankan GuruSeeder terlebih dahulu.');
            return;
        }

        if ($mapels->isEmpty()) {
            $this->command->warn('Data mapel masih kosong. Silakan jalankan MapelSeeder terlebih dahulu.');
            return;
        }

        $this->command->info('Memulai proses attaching mapel ke guru...');
        $this->command->info('Total Guru: ' . $gurus->count());
        $this->command->info('Total Mapel: ' . $mapels->count());

        // Data contoh relasi guru-mapel berdasarkan kode mapel yang benar
        $guruMapelData = [
            // Guru 1 (Ahmad Santoso) - Mengampu Matematika dan IPA
            [
                'guru_nip' => $gurus->first()->nip ?? '196501011987031001',
                'mapel_kode' => ['MTK10', 'MTK11', 'MTK12', 'FIS10', 'FIS11']
            ],
            // Guru 2 (Siti Rahayu) - Mengampu Bahasa
            [
                'guru_nip' => $gurus->skip(1)->first()->nip ?? '196802151989032002',
                'mapel_kode' => ['BIN10', 'BIN11', 'BIN12', 'BIG10', 'BIG11']
            ],
            // Guru 3 (Budi Prasetyo) - Mengampu IPA
            [
                'guru_nip' => $gurus->skip(2)->first()->nip ?? '197003201991031003',
                'mapel_kode' => ['BIO10', 'BIO11', 'BIO12', 'KIM10', 'KIM11']
            ],
            // Guru 4 (Joko Widodo) - Mengampu IPS
            [
                'guru_nip' => $gurus->skip(3)->first()->nip ?? '197104251993032004',
                'mapel_kode' => ['SEJ10', 'SEJ11', 'SEJ12', 'GEO10', 'GEO11']
            ],
            // Guru 5 (Maria Ulfa) - Mengampu Agama dan PKN
            [
                'guru_nip' => $gurus->skip(4)->first()->nip ?? '197205301995031005',
                'mapel_kode' => ['PAI10', 'PAI11', 'PAI12', 'PPKN10', 'PPKN11']
            ]
        ];

        $attachedCount = 0;
        $errorCount = 0;

        foreach ($guruMapelData as $data) {
            $guru = Guru::where('nip', $data['guru_nip'])->first();
            
            if (!$guru) {
                $this->command->error("❌ Guru dengan NIP {$data['guru_nip']} tidak ditemukan.");
                $errorCount++;
                continue;
            }

            $this->command->info("\n🔹 Memproses Guru: {$guru->nama_lengkap}");

            foreach ($data['mapel_kode'] as $mapelKode) {
                $mapel = Mapel::where('kode_mapel', $mapelKode)->first();
                
                if ($mapel) {
                    // Attach mapel ke guru tanpa duplikasi
                    if (!$guru->mapel()->where('mapel_id', $mapel->id)->exists()) {
                        $guru->mapel()->attach($mapel->id);
                        $attachedCount++;
                        $this->command->info("   ✅ Mapel {$mapel->nama_mapel} ({$mapelKode}) berhasil diattach");
                    } else {
                        $this->command->info("   ⚡ Mapel {$mapel->nama_mapel} ({$mapelKode}) sudah diattach sebelumnya");
                    }
                } else {
                    $this->command->error("   ❌ Mapel dengan kode {$mapelKode} tidak ditemukan.");
                    $errorCount++;
                }
            }
        }

        // Tampilkan summary
        $this->command->info("\n" . str_repeat('=', 50));
        $this->command->info("📊 SUMMARY GURU MAPEL SEEDER");
        $this->command->info(str_repeat('=', 50));
        $this->command->info("✅ Berhasil attach: {$attachedCount} relasi");
        $this->command->info("❌ Error: {$errorCount} data");
        $this->command->info(str_repeat('=', 50));

        // Tampilkan data hasil
        $this->showResultSummary();
    }

    private function showResultSummary(): void
    {
        $this->command->info("\n📋 DATA HASIL RELASI GURU-MAPEL:");
        $this->command->info(str_repeat('-', 50));

        $gurus = Guru::with('mapel')->get();

        foreach ($gurus as $guru) {
            $mapelCount = $guru->mapel->count();
            $mapelNames = $guru->mapel->pluck('nama_mapel')->join(', ');
            
            $this->command->info("👨‍🏫 {$guru->nama_lengkap}: {$mapelCount} mapel");
            if ($mapelCount > 0) {
                $this->command->info("   📚 {$mapelNames}");
            }
            $this->command->info("");
        }

        $totalRelations = $gurus->sum(function($guru) {
            return $guru->mapel->count();
        });

        $this->command->info("🎯 TOTAL RELASI: {$totalRelations} relasi guru-mapel");
    }
}