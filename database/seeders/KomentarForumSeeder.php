<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KomentarForum;
use App\Models\Forum;
use App\Models\Siswa;
use App\Models\User;

class KomentarForumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $forums = Forum::all();
        $siswaList = Siswa::with('user')->where('status', 'aktif')->get();
        $guruUsers = User::where('role', 'guru')->get();

        if ($forums->isEmpty()) {
            $this->command->error('Tidak ada forum yang ditemukan. Pastikan ForumSeeder sudah dijalankan.');
            return;
        }

        // Gabungkan semua user yang bisa berkomentar
        $allUsers = collect();
        
        foreach ($siswaList as $siswa) {
            if ($siswa->user) {
                $allUsers->push($siswa->user);
            }
        }
        
        foreach ($guruUsers as $guru) {
            $allUsers->push($guru);
        }

        if ($allUsers->isEmpty()) {
            $this->command->error('Tidak ada user yang ditemukan untuk membuat komentar.');
            return;
        }

        $komentarSamples = [
            // Untuk diskusi Matematika
            [
                'Wah tips yang bagus! Saya juga suka menggunakan mind mapping untuk memahami konsep matematika.',
                'Terima kasih sharingnya! Saya akan coba tips no.2, latihan rutin setiap hari.',
                'Kalau saya suka belajar kelompok, jadi bisa diskusi kalau ada yang tidak paham.',
                'Saya punya tips tambahan: coba buat rangkuman dengan bahasa sendiri.',
                'Matematika emang butuh konsistensi dalam belajar. Nice tips!',
                'Untuk yang kesulitan dengan aljabar, coba pahami dulu konsep variabel dan konstanta.',
            ],
            // Untuk diskusi Ujian
            [
                'Siap pak/bu! Semoga kita semua dapat nilai yang memuaskan.',
                'Jangan lupa makan yang teratur dan minum air putih yang cukup ya!',
                'Ada yang mau belajar kelompok untuk persiapan ujian?',
                'Tips yang sangat membantu, terima kasih!',
                'Jangan sampai begadang ya, tidur yang cukup penting untuk konsentrasi.',
                'Saya biasanya buat jadwal belajar 2 minggu sebelum ujian.',
            ],
            // Untuk diskusi Fisika
            [
                'Hukum Newton I: benda diam tetap diam, benda bergerak tetap bergerak dengan kecepatan konstan',
                'Hukum Newton II: F = m × a (gaya = massa × percepatan)',
                'Hukum Newton III: aksi-reaksi, contoh: ketika kita mendorong tembok, tembok juga mendorong kita balik',
                'Contoh Hukum I: buku di atas meja tetap diam sampai ada gaya yang menggerakkan',
                'Hukum II menjelaskan kenapa mobil butuh lebih banyak gaya untuk berakselerasi ketika penuh.',
                'Untuk Hukum III, contoh lain: roket yang meluncur karena mendorong gas ke belakang.',
            ],
            // Untuk diskusi Basket
            [
                'Saya mau ikut! Apakah pemula boleh bergabung?',
                'Asik! Sudah lama pengen main basket lagi.',
                'Ada persyaratan khusus untuk bergabung?',
                'Wah seru nih, saya dulu main basket waktu SMP.',
                'Latihan hari apa saja? Saya bisa ikut hari Kamis.',
                'Boleh tahu kontak pelatihnya?',
            ],
            // Untuk diskusi Kimia
            [
                'Saya pakai teknik mnemonik, bikin singkatan yang mudah diingat',
                'Coba hafal pola periodik unsur dulu, nanti lebih mudah',
                'Latihan menulis rumus berulang-ulang juga membantu',
                'Gunakan flashcard untuk menghafal rumus-rumus penting',
                'Saya suka belajar dengan membuat mind map hubungan antar unsur.',
                'Untuk senyawa organik, coba hafal gugus fungsionalnya dulu.',
            ],
            // Untuk diskusi Pentas Seni
            [
                'Saya mau daftar solo vocal!',
                'Ada yang mau buat band bersama? Saya main gitar.',
                'Wah seru nih, pasti ramai acaranya.',
                'Saya bisa ikut tari tradisional, kebetulan bisa tari daerah.',
                'Untuk pendaftaran di mana ya?',
                'Ada batasan jumlah peserta untuk setiap kategori?',
            ],
            // Untuk diskusi Pemrograman
            [
                'Saya biasanya debug dengan print statement dulu',
                'Gunakan rubber duck debugging, ceritakan problem ke teman/boneka',
                'Baca dokumentasi resmi biasanya paling membantu',
                'Coba break problem menjadi bagian-bagian kecil yang lebih mudah',
                'Gunakan version control seperti Git untuk melacak perubahan.',
                'Stack Overflow juga membantu banget untuk mencari solusi error.',
            ],
            // Untuk diskusi Paskibra
            [
                'Latihan Paskibra seru banget! Bisa belajar disiplin dan kerja sama.',
                'Saya dulu anggota Paskibra, pengalaman yang berharga banget.',
                'Mantap! Mari jaga tradisi Paskibra sekolah kita.',
                'Apakah ada tes fisik untuk bergabung?',
                'Saya tertarik, boleh tahu jadwal lengkapnya?',
                'Biasanya latihan berlangsung sampai jam berapa?',
            ],
            // Untuk diskusi Sejarah
            [
                'Coba baca buku "The Second World War" karya Antony Beevor',
                'Film "Band of Brothers" bagus banget buat referensi',
                'Dokumenter "The World at War" juga recommended',
                'Buku "A World at Arms" karya Gerhard L. Weinberg juga bagus',
                'Saya suka baca komik sejarah, membantu visualisasi peristiwa.',
                'Museum Satria Mandala juga punya koleksi yang bagus tentang PD II.',
            ],
            // Untuk diskusi Work-Life Balance
            [
                'Saya pakai teknik pomodoro: 25 menit belajar, 5 menit istirahat',
                'Prioritaskan tugas yang penting dan mendesak dulu',
                'Jangan lupa olahraga ringan setiap hari buat refresh pikiran',
                'Buat jadwal harian yang realistis, jangan terlalu padat',
                'Sisihkan waktu untuk hobi, biar tidak stress.',
                'Istirahat yang cukup juga penting, jangan dipaksakan.',
            ],
            // Untuk diskusi Tips Ujian
            [
                'Saya suka teknik ini, terutama point no.2 tentang mengerjakan yang mudah dulu.',
                'Jangan lupa bawa perlengkapan ujian yang lengkap ya!',
                'Tips yang bagus, saya akan coba terapkan.',
                'Kalau nervous, coba tarik napas dalam-dalam sebelum mulai.',
                'Periksa kembali jawaban sebelum dikumpulkan.',
                'Jaga kesehatan sebelum ujian, jangan sampai sakit.',
            ],
            // Untuk diskusi Komunitas Baca
            [
                'Wah asik nih! Saya suka baca novel.',
                'Buku apa yang akan didiskusikan pertama kali?',
                'Boleh pinjam buku dari perpustakaan sekolah?',
                'Saya punya rekomendasi buku bagus nih.',
                'Apakah ada genre tertentu yang fokus dibahas?',
                'Bisa ajak teman dari luar sekolah?',
            ]
        ];

        $totalKomentar = 0;

        foreach ($forums as $index => $forum) {
            $jumlahKomentar = rand(4, 12); // Setiap forum punya 4-12 komentar
            
            for ($i = 0; $i < $jumlahKomentar; $i++) {
                // Pilih user secara acak (bisa berbeda dengan pembuat forum)
                $availableUsers = $allUsers->where('id', '!=', $forum->user_id);
                if ($availableUsers->isEmpty()) {
                    continue;
                }
                
                $user = $availableUsers->random();
                
                // Ambil sample komentar sesuai index forum
                $sampleKomentar = $komentarSamples[$index % count($komentarSamples)];
                $komentar = $sampleKomentar[array_rand($sampleKomentar)];
                
                try {
                    KomentarForum::create([
                        'forum_id' => $forum->id,
                        'user_id' => $user->id,
                        'komentar' => $komentar,
                        'created_at' => $forum->created_at->addHours(rand(1, 72)),
                        'updated_at' => $forum->created_at->addHours(rand(73, 144)),
                    ]);
                    $totalKomentar++;
                } catch (\Exception $e) {
                    // Skip jika ada error
                    continue;
                }
            }
            
            $this->command->info("Forum '{$forum->judul}' memiliki {$jumlahKomentar} komentar");
        }

        $this->command->info("Total {$totalKomentar} komentar berhasil ditambahkan!");
    }
}