<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ArtikelKarir;
use Carbon\Carbon;

class ArtikelKarirSeeder extends Seeder
{
    public function run()
    {
        $artikel = [
            [
                'judul' => 'Tips Memilih Jurusan Kuliah yang Tepat untuk Masa Depan',
                'slug' => 'tips-memilih-jurusan-kuliah',
                'konten' => 'Memilih jurusan kuliah adalah keputusan penting yang akan mempengaruhi masa depan karir Anda. Berikut beberapa tips yang dapat membantu...',
                'kategori' => 'Pendidikan',
                'penulis' => 'Bimbingan Konseling',
                'sumber' => 'Tim BK SMA Negeri 1',
                'tags' => ['jurusan', 'kuliah', 'karir', 'pendidikan'],
                'status' => 'publik',
                'views' => 150,
                'created_at' => Carbon::now()->subDays(5)
            ],
            [
                'judul' => 'Mengembangkan Soft Skills untuk Dunia Kerja',
                'slug' => 'mengembangkan-soft-skills',
                'konten' => 'Soft skills menjadi faktor penentu kesuksesan dalam dunia kerja. Kemampuan komunikasi, kerja tim, dan leadership sangat dibutuhkan...',
                'kategori' => 'Pengembangan Diri',
                'penulis' => 'Guru BK',
                'sumber' => 'Workshop Karir 2024',
                'tags' => ['soft skills', 'karir', 'pengembangan', 'kerja'],
                'status' => 'publik',
                'views' => 89,
                'created_at' => Carbon::now()->subDays(3)
            ],
            // Tambahkan lebih banyak artikel...
        ];

        foreach ($artikel as $data) {
            ArtikelKarir::create($data);
        }
    }
}