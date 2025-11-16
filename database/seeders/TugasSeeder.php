<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tugas;
use App\Models\Guru;
use App\Models\Mapel;
use Carbon\Carbon;

class TugasSeeder extends Seeder
{
    public function run(): void
    {
        $gurus = Guru::has('mapel')->with('mapel')->take(5)->get();

        if ($gurus->isEmpty()) {
            $this->command->warn('Tidak ada guru yang memiliki mapel. Silakan jalankan GuruMapelSeeder terlebih dahulu.');
            return;
        }

        $tugasData = [];
        $createdCount = 0;

        foreach ($gurus as $guru) {
            $mapels = $guru->mapel->take(3);

            foreach ($mapels as $mapel) {
                for ($i = 1; $i <= rand(2, 4); $i++) {
                    $tipe = ['tugas', 'kuis', 'ulangan'][rand(0, 2)];
                    $deadline = Carbon::now()->addDays(rand(1, 30))->addHours(rand(1, 23));
                    
                    $tugasData[] = [
                        'mapel_id' => $mapel->id,
                        'guru_id' => $guru->id,
                        'judul' => $this->generateJudulTugas($mapel->nama_mapel, $tipe, $i),
                        'deskripsi' => $this->generateDeskripsi($mapel->nama_mapel, $tipe),
                        'deadline' => $deadline,
                        'tipe' => $tipe,
                        'instruksi' => $this->generateInstruksi($tipe),
                        'file' => null,
                        'max_score' => [100, 90, 80, 70][rand(0, 3)],
                        'is_published' => rand(0, 1),
                        'created_at' => Carbon::now()->subDays(rand(1, 60)),
                        'updated_at' => Carbon::now()->subDays(rand(0, 59)),
                    ];
                    $createdCount++;
                }
            }
        }

        foreach (array_chunk($tugasData, 50) as $chunk) {
            Tugas::insert($chunk);
        }

        $this->command->info("✅ Tugas Seeder berhasil dijalankan!");
        $this->command->info("📚 Total Tugas dibuat: {$createdCount}");
    }

    private function generateJudulTugas($mapel, $tipe, $index): string
    {
        $activities = [
            'tugas' => ['Tugas Individu', 'Tugas Kelompok', 'Tugas Mandiri', 'Tugas Praktikum'],
            'kuis' => ['Kuis Harian', 'Kuis Bab', 'Kuis Singkat', 'Kuis Pemahaman'],
            'ulangan' => ['Ulangan Harian', 'Ulangan Tengah Semester', 'Ulangan Akhir Bab', 'Ulangan Komprehensif']
        ];
        
        $activity = $activities[$tipe][array_rand($activities[$tipe])];
        return "{$activity} {$mapel} - Part {$index}";
    }

    private function generateDeskripsi($mapel, $tipe): string
    {
        return "{$tipe} {$mapel} yang bertujuan untuk mengukur pemahaman siswa tentang materi yang telah dipelajari. Silakan kerjakan dengan sungguh-sungguh dan perhatikan deadline yang telah ditentukan.";
    }

    private function generateInstruksi($tipe): string
    {
        return match($tipe) {
            'tugas' => "Kerjakan tugas ini secara individu. Kumpulkan sebelum deadline. Format file: PDF atau DOC.",
            'kuis' => "Kuis ini bersifat tertutup. Kerjakan sendiri tanpa bantuan orang lain. Waktu pengerjaan: 30 menit.",
            'ulangan' => "Ulangan ini mencakup seluruh materi yang telah dipelajari. Baca soal dengan teliti sebelum menjawab.",
            default => "Silakan kerjakan sesuai dengan petunjuk yang diberikan."
        };
    }
}