<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\Guru;
use App\Models\Mapel;
use Carbon\Carbon;

class MateriSeeder extends Seeder
{
    public function run(): void
    {
        $gurus = Guru::has('mapel')->with('mapel')->take(5)->get();

        if ($gurus->isEmpty()) {
            $this->command->warn('Tidak ada guru yang memiliki mapel. Silakan jalankan GuruMapelSeeder terlebih dahulu.');
            return;
        }

        $materiData = [];
        $createdCount = 0;

        foreach ($gurus as $guru) {
            $mapels = $guru->mapel->take(3);

            foreach ($mapels as $mapel) {
                for ($i = 1; $i <= rand(2, 4); $i++) {
                    $materiData[] = [
                        'mapel_id' => $mapel->id,
                        'guru_id' => $guru->id,
                        'judul' => $this->generateJudulMateri($mapel->nama_mapel, $i),
                        'deskripsi' => $this->generateDeskripsi($mapel->nama_mapel),
                        'file' => null,
                        'link' => $this->getRandomLink(),
                        'created_at' => Carbon::now()->subDays(rand(1, 60)),
                        'updated_at' => Carbon::now()->subDays(rand(0, 59)),
                    ];
                    $createdCount++;
                }
            }
        }

        foreach (array_chunk($materiData, 50) as $chunk) {
            Materi::insert($chunk);
        }

        $this->command->info("✅ Materi Seeder berhasil dijalankan!");
        $this->command->info("📚 Total Materi dibuat: {$createdCount}");
    }

    private function generateJudulMateri($mapel, $index): string
    {
        $types = [
            'Modul', 'Slide Presentasi', 'Lembar Kerja', 'Video Pembelajaran',
            'Latihan Soal', 'Referensi Bacaan', 'Panduan Praktikum'
        ];
        
        $topics = [
            'Konsep Dasar', 'Teori Utama', 'Aplikasi', 'Analisis', 
            'Evaluasi', 'Problem Solving', 'Studi Kasus'
        ];

        $type = $types[array_rand($types)];
        $topic = $topics[array_rand($topics)];
        
        return "{$type} {$mapel} - {$topic} Part {$index}";
    }

    private function generateDeskripsi($mapel): string
    {
        return "Materi pembelajaran {$mapel} yang berisi penjelasan lengkap tentang topik yang dibahas. Cocok untuk pembelajaran di kelas maupun belajar mandiri.";
    }

    private function getRandomLink(): ?string
    {
        $links = [
            'https://drive.google.com/file/d/example/view',
            'https://youtube.com/watch?v=example',
            'https://slideshare.net/example/presentation',
            null,
            null
        ];
        
        return $links[array_rand($links)];
    }
}