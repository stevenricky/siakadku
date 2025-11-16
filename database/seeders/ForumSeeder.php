<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Forum;
use App\Models\ForumLike;
use App\Models\Siswa;
use App\Models\User;

class ForumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data siswa yang aktif beserta user-nya
        $siswaList = Siswa::with(['user', 'kelas'])
            ->where('status', 'aktif')
            ->get();

        if ($siswaList->isEmpty()) {
            $this->command->error('Tidak ada siswa aktif yang ditemukan. Pastikan SiswaSeeder sudah dijalankan.');
            return;
        }

        // Ambil juga beberapa guru untuk variasi
        $guruUsers = User::where('role', 'guru')->get();
        
        $diskusi = [
            [
                'judul' => 'Tips Belajar Matematika yang Efektif',
                'isi' => 'Halo semuanya! Saya ingin berbagi tips belajar matematika yang menurut saya efektif:

1. Pahami konsep dasar terlebih dahulu sebelum mengerjakan soal
2. Latihan soal secara rutin setiap hari
3. Buat catatan rumus-rumus penting
4. Diskusikan kesulitan dengan teman atau guru

Ada yang punya tips lain? Mari berbagi pengalaman!',
                'kategori' => 'pelajaran',
                'like_count' => 0,
                'view_count' => 89,
                'is_pinned' => true,
            ],
            [
                'judul' => 'Persiapan Ujian Semester Genap',
                'isi' => 'Dear semua siswa,

Ujian semester genap akan segera dimulai. Mari kita persiapkan bersama-sama:

- Buat jadwal belajar yang teratur
- Review materi dari awal semester
- Kerjakan soal-soal latihan
- Jaga kesehatan dan istirahat yang cukup

Semangat belajar! 💪',
                'kategori' => 'umum',
                'like_count' => 0,
                'view_count' => 156,
                'is_pinned' => true,
                'created_by_guru' => true, // Flag untuk diskusi yang dibuat guru
            ],
            [
                'judul' => 'Diskusi tentang Fisika: Hukum Newton',
                'isi' => 'Saya masih bingung dengan perbedaan antara Hukum Newton I, II, dan III. Ada yang bisa menjelaskan dengan contoh sederhana?

Terima kasih!',
                'kategori' => 'pelajaran',
                'like_count' => 0,
                'view_count' => 67,
                'is_pinned' => false,
            ],
            [
                'judul' => 'Kegiatan Ekstrakurikuler Basket',
                'isi' => 'Untuk teman-teman yang berminat mengikuti ekstrakurikuler basket, jadwal latihan:

Hari: Selasa dan Kamis
Waktu: 15.30 - 17.30
Tempat: Lapangan Basket Sekolah

Daftar segera ke pelatih!',
                'kategori' => 'ekstrakurikuler',
                'like_count' => 0,
                'view_count' => 45,
                'is_pinned' => false,
            ],
            [
                'judul' => 'Cara Menghafal Rumus Kimia dengan Mudah',
                'isi' => 'Ada yang punya teknik khusus untuk menghafal rumus kimia? Saya sering kesulitan mengingat rumus-rumus senyawa kimia.

Mohon sharing pengalamannya!',
                'kategori' => 'pelajaran',
                'like_count' => 0,
                'view_count' => 78,
                'is_pinned' => false,
            ],
            [
                'judul' => 'Pentas Seni Akhir Tahun',
                'isi' => 'Dalam rangka menyambut akhir tahun ajaran, sekolah akan mengadakan pentas seni. Bagi yang berminat tampil, silakan daftar:

- Solo vocal
- Band
- Tari tradisional
- Drama
- Puisi

Pendaftaran dibuka sampai tanggal 15 Juni.',
                'kategori' => 'umum',
                'like_count' => 0,
                'view_count' => 112,
                'is_pinned' => false,
                'created_by_guru' => true,
            ],
            [
                'judul' => 'Problem Solving dalam Pemrograman',
                'isi' => 'Untuk teman-teman yang suka pemrograman, mari diskusi tentang teknik problem solving yang efektif.

Apa strategi kalian ketika menghadapi bug atau error dalam coding?',
                'kategori' => 'pelajaran',
                'like_count' => 0,
                'view_count' => 54,
                'is_pinned' => false,
            ],
            [
                'judul' => 'Ekstrakurikuler Paskibra',
                'isi' => 'Latihan rutin Paskibra:

Hari: Senin dan Rabu
Waktu: 16.00 - 18.00
Tempat: Lapangan Upacara

Kita butuh anggota baru untuk regenerasi!',
                'kategori' => 'ekstrakurikuler',
                'like_count' => 0,
                'view_count' => 39,
                'is_pinned' => false,
            ],
            [
                'judul' => 'Diskusi Sejarah: Perang Dunia II',
                'isi' => 'Saya tertarik mempelajari tentang Perang Dunia II. Ada yang punya referensi buku atau film dokumenter yang bagus?

Mari berbagi sumber belajar!',
                'kategori' => 'pelajaran',
                'like_count' => 0,
                'view_count' => 61,
                'is_pinned' => false,
            ],
            [
                'judul' => 'Work-Life Balance untuk Siswa',
                'isi' => 'Bagaimana cara kalian menyeimbangkan antara belajar, ekstrakurikuler, dan waktu istirahat?

Saya sering merasa kelelahan karena jadwal yang padat. Ada saran?',
                'kategori' => 'umum',
                'like_count' => 0,
                'view_count' => 93,
                'is_pinned' => false,
            ]
        ];

        $forums = [];

        foreach ($diskusi as $data) {
            // Tentukan pembuat diskusi
            if (isset($data['created_by_guru']) && $data['created_by_guru'] && !$guruUsers->isEmpty()) {
                // Diskusi dibuat oleh guru
                $user = $guruUsers->random();
                $pembuat = "Guru: {$user->name}";
            } else {
                // Diskusi dibuat oleh siswa
                $siswa = $siswaList->random();
                $user = $siswa->user;
                
                // Perbaikan: gunakan ternary operator sebagai ganti ??
                $kelasNama = ($siswa->kelas && $siswa->kelas->nama) ? $siswa->kelas->nama : 'Belum ada kelas';
                $pembuat = "Siswa: {$siswa->nama} (Kelas: {$kelasNama})";
            }
            
            $forum = Forum::create([
                'user_id' => $user->id,
                'judul' => $data['judul'],
                'isi' => $data['isi'],
                'kategori' => $data['kategori'],
                'like_count' => $data['like_count'],
                'view_count' => $data['view_count'],
                'is_pinned' => $data['is_pinned'],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(0, 29)),
            ]);

            $forums[] = $forum;
            $this->command->info("Diskusi '{$data['judul']}' dibuat oleh {$pembuat}");
        }

        $this->command->info('Seeder Forum berhasil ditambahkan!');

        // Buat like untuk setiap forum
        $this->createLikes($forums, $siswaList, $guruUsers);
    }

    /**
     * Create likes for forums
     */
    private function createLikes($forums, $siswaList, $guruUsers)
    {
        $this->command->info('Membuat data likes...');

        // Gabungkan semua user yang bisa like (siswa + guru)
        $allUsers = collect();
        
        // Ambil user dari siswa
        foreach ($siswaList as $siswa) {
            if ($siswa->user) {
                $allUsers->push($siswa->user);
            }
        }
        
        // Tambahkan user guru
        foreach ($guruUsers as $guru) {
            $allUsers->push($guru);
        }

        if ($allUsers->isEmpty()) {
            $this->command->error('Tidak ada user yang ditemukan untuk membuat likes.');
            return;
        }

        foreach ($forums as $forum) {
            // Buat beberapa like untuk setiap forum (3-10 user yang like)
            $likeCount = rand(3, 10);
            $availableUsers = $allUsers->where('id', '!=', $forum->user_id);
            
            if ($availableUsers->count() > 0) {
                $likeUsers = $availableUsers->random(min($likeCount, $availableUsers->count()));
                
                $actualLikes = 0;
                foreach ($likeUsers as $user) {
                    try {
                        ForumLike::create([
                            'forum_id' => $forum->id,
                            'user_id' => $user->id,
                            'created_at' => $forum->created_at->addHours(rand(1, 24)),
                        ]);
                        $actualLikes++;
                    } catch (\Exception $e) {
                        // Skip jika sudah ada like dari user yang sama
                        continue;
                    }
                }
                
                // Update like_count
                $forum->update(['like_count' => $actualLikes]);
                
                $this->command->info("Forum '{$forum->judul}' memiliki {$actualLikes} likes");
            } else {
                $this->command->info("Forum '{$forum->judul}' tidak memiliki likes (tidak ada user tersedia)");
            }
        }

        $this->command->info('Data likes berhasil ditambahkan!');
    }
}