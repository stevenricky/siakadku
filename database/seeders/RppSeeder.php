<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rpp;
use App\Models\Guru;
use App\Models\Mapel;
use Carbon\Carbon;

class RppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil beberapa guru yang memiliki mapel
        $gurus = Guru::has('mapel')->with('mapel')->take(5)->get();

        if ($gurus->isEmpty()) {
            $this->command->warn('Tidak ada guru yang memiliki mapel. Silakan jalankan GuruMapelSeeder terlebih dahulu.');
            return;
        }

        $rppData = [];
        $createdCount = 0;

        foreach ($gurus as $guru) {
            // Ambil 3-5 mapel pertama yang diampu oleh guru
            $mapels = $guru->mapel->take(5);

            foreach ($mapels as $mapel) {
                // Buat 2-3 RPP untuk setiap mapel
                for ($i = 1; $i <= rand(2, 3); $i++) {
                    $rppData[] = [
                        'mapel_id' => $mapel->id,
                        'guru_id' => $guru->id,
                        'judul' => $this->generateJudulRpp($mapel->nama_mapel, $mapel->tingkat, $i),
                        'kompetensi_dasar' => $this->generateKompetensiDasar($mapel->nama_mapel),
                        'tujuan_pembelajaran' => $this->generateTujuanPembelajaran($mapel->nama_mapel),
                        'materi' => $this->generateMateri($mapel->nama_mapel),
                        'metode' => $this->getRandomMetode(),
                        'media' => $this->getRandomMedia(),
                        'langkah_kegiatan' => $this->generateLangkahKegiatan(),
                        'penilaian' => $this->generatePenilaian(),
                        'created_at' => Carbon::now()->subDays(rand(1, 30)),
                        'updated_at' => Carbon::now()->subDays(rand(0, 29)),
                    ];
                    $createdCount++;
                }
            }
        }

        // Insert data RPP
        foreach (array_chunk($rppData, 50) as $chunk) {
            Rpp::insert($chunk);
        }

        $this->command->info("✅ RPP Seeder berhasil dijalankan!");
        $this->command->info("📚 Total RPP dibuat: {$createdCount}");
        $this->command->info("👨‍🏫 Guru yang memiliki RPP: " . $gurus->count());
        
        $this->showSummary();
    }

    private function generateJudulRpp($mapel, $tingkat, $index): string
    {
        $topics = [
            'Pengenalan', 'Konsep Dasar', 'Aplikasi', 'Analisis', 'Evaluasi',
            'Pemahaman', 'Implementasi', 'Strategi', 'Teknik', 'Metode'
        ];
        
        $subTopics = [
            'Materi Pembelajaran', 'Konsep Utama', 'Prinsip Dasar', 'Teknik Penyelesaian',
            'Analisis Kasus', 'Problem Solving', 'Kreativitas', 'Inovasi'
        ];

        $topic = $topics[array_rand($topics)];
        $subTopic = $subTopics[array_rand($subTopics)];
        
        return "RPP {$mapel} Kelas {$tingkat} - {$topic} {$subTopic} " . ($index > 1 ? "Part {$index}" : "");
    }

    private function generateKompetensiDasar($mapel): string
    {
        $kds = [
            "Memahami konsep dasar {$mapel} dan penerapannya dalam kehidupan sehari-hari.",
            "Menganalisis permasalahan terkait {$mapel} dan menyelesaikannya dengan metode yang tepat.",
            "Menerapkan prinsip-prinsip {$mapel} dalam konteks yang berbeda-beda.",
            "Mengembangkan kemampuan berpikir kritis melalui pembelajaran {$mapel}.",
            "Menunjukkan sikap ilmiah dalam mempelajari {$mapel}."
        ];
        
        return $kds[array_rand($kds)] . "\n\n" . implode("\n", [
            "- Memahami teori dasar",
            "- Menerapkan konsep dalam praktik", 
            "- Menganalisis data dan informasi",
            "- Menyajikan hasil pembelajaran"
        ]);
    }

    private function generateTujuanPembelajaran($mapel): string
    {
        return implode("\n", [
            "Siswa mampu memahami konsep dasar {$mapel}",
            "Siswa dapat menerapkan pengetahuan {$mapel} dalam situasi nyata",
            "Siswa mampu menganalisis permasalahan terkait {$mapel}",
            "Siswa dapat berkomunikasi efektif tentang topik {$mapel}",
            "Siswa mengembangkan sikap positif terhadap pembelajaran {$mapel}"
        ]);
    }

    private function generateMateri($mapel): string
    {
        return implode("\n\n", [
            "Konsep Dasar {$mapel}:",
            "- Pengertian dan ruang lingkup",
            "- Prinsip-prinsip utama", 
            "- Aplikasi dalam kehidupan",
            "",
            "Metode Pembelajaran:",
            "- Teori dan praktik",
            "- Studi kasus",
            "- Diskusi kelompok"
        ]);
    }

    private function getRandomMetode(): string
    {
        $metodes = [
            'Ceramah interaktif, diskusi kelompok, presentasi',
            'Problem Based Learning, diskusi, demonstrasi',
            'Project Based Learning, cooperative learning',
            'Inkuiri, eksperimen, observasi',
            'Discovery Learning, tanya jawab, latihan'
        ];
        
        return $metodes[array_rand($metodes)];
    }

    private function getRandomMedia(): string
    {
        $media = [
            'LCD Projector, papan tulis, LKS, video pembelajaran',
            'PowerPoint, gambar, chart, alat peraga',
            'Google Classroom, quiz interaktif, video',
            'Papan tulis, modul, worksheet, alat praktikum',
            'Canva presentasi, YouTube, aplikasi edukasi'
        ];
        
        return $media[array_rand($media)];
    }

    private function generateLangkahKegiatan(): string
    {
        return implode("\n\n", [
            "Pendahuluan (15 menit):",
            "- Apersepsi dan motivasi",
            "- Menyampaikan tujuan pembelajaran",
            "- Brainstorming topik",
            "",
            "Kegiatan Inti (60 menit):", 
            "- Eksplorasi konsep",
            "- Elaborasi melalui diskusi",
            "- Konfirmasi pemahaman",
            "",
            "Penutup (15 menit):",
            "- Refleksi pembelajaran",
            "- Evaluasi formatif",
            "- Tindak lanjut"
        ]);
    }

    private function generatePenilaian(): string
    {
        return implode("\n", [
            "Penilaian Sikap: Observasi selama proses pembelajaran",
            "Penilaian Pengetahuan: Tes tertulis, quiz, ulangan harian",
            "Penilaian Keterampilan: Praktik, presentasi, proyek",
            "Rubrik Penilaian:",
            "- Ketepatan konsep: 40%",
            "- Kreativitas: 30%", 
            "- Kerjasama: 20%",
            "- Presentasi: 10%"
        ]);
    }

    private function showSummary(): void
    {
        $totalRpp = Rpp::count();
        $guruWithRpp = Guru::has('rpp')->count();
        $mapelWithRpp = Mapel::has('rpp')->count();
        
        $this->command->info("\n" . str_repeat('=', 50));
        $this->command->info("📊 SUMMARY RPP SEEDER");
        $this->command->info(str_repeat('=', 50));
        $this->command->info("📚 Total RPP: {$totalRpp}");
        $this->command->info("👨‍🏫 Guru dengan RPP: {$guruWithRpp}");
        $this->command->info("📖 Mapel dengan RPP: {$mapelWithRpp}");
        $this->command->info(str_repeat('=', 50));
        
        // Tampilkan contoh RPP
        $sampleRpp = Rpp::with(['guru', 'mapel'])->first();
        if ($sampleRpp) {
            $this->command->info("\n📝 CONTOH RPP:");
            $this->command->info("Judul: {$sampleRpp->judul}");
            $this->command->info("Guru: {$sampleRpp->guru->nama_lengkap}");
            $this->command->info("Mapel: {$sampleRpp->mapel->nama_mapel}");
        }
    }
}