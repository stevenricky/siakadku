<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LayananBk;

class LayananBkSeeder extends Seeder
{
    public function run(): void
    {
        $layananBk = [
            // Bimbingan Pribadi - Individu
            [
                'nama_layanan' => 'Konseling Individu',
                'deskripsi' => 'Layanan konseling untuk masalah pribadi siswa seperti kepercayaan diri, masalah keluarga, dan perkembangan diri',
                'jenis_layanan' => 'bimbingan pribadi',
                'sasaran' => 'individu',
                'status' => true,
            ],
            [
                'nama_layanan' => 'Bimbingan Pengembangan Diri',
                'deskripsi' => 'Membantu siswa dalam pengembangan potensi diri dan pembentukan karakter',
                'jenis_layanan' => 'bimbingan pribadi',
                'sasaran' => 'individu',
                'status' => true,
            ],
            [
                'nama_layanan' => 'Konseling Masalah Keluarga',
                'deskripsi' => 'Layanan konseling untuk mengatasi masalah yang berkaitan dengan keluarga siswa',
                'jenis_layanan' => 'bimbingan pribadi',
                'sasaran' => 'individu',
                'status' => true,
            ],

            // Bimbingan Sosial - Kelompok
            [
                'nama_layanan' => 'Bimbingan Sosial Kelompok',
                'deskripsi' => 'Mengembangkan kemampuan sosial dan interaksi dengan teman sebaya dalam kelompok',
                'jenis_layanan' => 'bimbingan sosial',
                'sasaran' => 'kelompok',
                'status' => true,
            ],
            [
                'nama_layanan' => 'Konseling Hubungan Teman Sebaya',
                'deskripsi' => 'Membantu siswa dalam membina hubungan yang sehat dengan teman sebaya',
                'jenis_layanan' => 'bimbingan sosial',
                'sasaran' => 'individu',
                'status' => true,
            ],

            // Bimbingan Belajar - Kelas
            [
                'nama_layanan' => 'Bimbingan Teknik Belajar Efektif',
                'deskripsi' => 'Melatih siswa dalam teknik belajar yang efektif dan efisien',
                'jenis_layanan' => 'bimbingan belajar',
                'sasaran' => 'kelompok',
                'status' => true,
            ],
            [
                'nama_layanan' => 'Konseling Kesulitan Belajar',
                'deskripsi' => 'Membantu siswa yang mengalami kesulitan dalam proses belajar',
                'jenis_layanan' => 'bimbingan belajar',
                'sasaran' => 'individu',
                'status' => true,
            ],
            [
                'nama_layanan' => 'Bimbingan Persiapan Ujian',
                'deskripsi' => 'Mempersiapkan siswa secara mental dan teknis dalam menghadapi ujian',
                'jenis_layanan' => 'bimbingan belajar',
                'sasaran' => 'kelas',
                'status' => true,
            ],

            // Bimbingan Karir - Berbagai Sasaran
            [
                'nama_layanan' => 'Konseling Pemilihan Jurusan',
                'deskripsi' => 'Membantu siswa dalam memilih jurusan yang sesuai dengan minat dan bakat',
                'jenis_layanan' => 'bimbingan karir',
                'sasaran' => 'individu',
                'status' => true,
            ],
            [
                'nama_layanan' => 'Bimbingan Perencanaan Karir',
                'deskripsi' => 'Membantu siswa dalam merencanakan karir masa depan',
                'jenis_layanan' => 'bimbingan karir',
                'sasaran' => 'kelompok',
                'status' => true,
            ],
            [
                'nama_layanan' => 'Workshop Pengembangan Karir',
                'deskripsi' => 'Workshop untuk pengembangan keterampilan yang dibutuhkan dalam dunia kerja',
                'jenis_layanan' => 'bimbingan karir',
                'sasaran' => 'kelas',
                'status' => true,
            ],

            // Layanan Khusus
            [
                'nama_layanan' => 'Konseling Krisis',
                'deskripsi' => 'Layanan darurat untuk menangani masalah krisis yang dialami siswa',
                'jenis_layanan' => 'bimbingan pribadi',
                'sasaran' => 'individu',
                'status' => true,
            ],
            [
                'nama_layanan' => 'Bimbingan Anti Bullying',
                'deskripsi' => 'Layanan pencegahan dan penanganan kasus bullying di sekolah',
                'jenis_layanan' => 'bimbingan sosial',
                'sasaran' => 'kelas',
                'status' => true,
            ],
            [
                'nama_layanan' => 'Tes Bakat dan Minat',
                'deskripsi' => 'Layanan tes untuk mengetahui bakat dan minat siswa sebagai dasar pemilihan karir',
                'jenis_layanan' => 'bimbingan karir',
                'sasaran' => 'individu',
                'status' => true,
            ],
            [
                'nama_layanan' => 'Bimbingan Life Skills',
                'deskripsi' => 'Pengembangan keterampilan hidup untuk bekal masa depan siswa',
                'jenis_layanan' => 'bimbingan sosial',
                'sasaran' => 'kelompok',
                'status' => true,
            ]
        ];

        foreach ($layananBk as $layanan) {
            LayananBk::create($layanan);
        }

        $this->command->info('Seeder Layanan BK berhasil ditambahkan!');
        $this->command->info('Total: ' . count($layananBk) . ' layanan BK');
    }
}