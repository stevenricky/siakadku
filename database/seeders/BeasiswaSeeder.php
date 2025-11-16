<?php

namespace Database\Seeders;

use App\Models\Beasiswa;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BeasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $beasiswas = [
            [
                'nama_beasiswa' => 'Beasiswa Prestasi Akademik',
                'penyelenggara' => 'Kementerian Pendidikan dan Kebudayaan',
                'deskripsi' => 'Beasiswa bagi mahasiswa berprestasi akademik dengan IPK minimal 3.5. Beasiswa ini bertujuan untuk mendukung mahasiswa yang memiliki prestasi akademik yang luar biasa dalam melanjutkan studi mereka.',
                'persyaratan' => "1. IPK minimal 3.5\n2. Aktif sebagai mahasiswa S1\n3. Tidak sedang menerima beasiswa lain\n4. Mengisi formulir pendaftaran\n5. Melampirkan transkrip nilai\n6. Surat rekomendasi dari dosen",
                'nilai_beasiswa' => 5000000,
                'tanggal_buka' => now()->subDays(10),
                'tanggal_tutup' => now()->addDays(20),
                'kontak' => 'beasiswa@kemdikbud.go.id',
                'website' => 'https://beasiswa.kemdikbud.go.id',
                'status' => 'buka'
            ],
            [
                'nama_beasiswa' => 'Beasiswa KIP Kuliah',
                'penyelenggara' => 'Pemerintah Indonesia',
                'deskripsi' => 'Beasiswa Kartu Indonesia Pintar Kuliah untuk mahasiswa dari keluarga kurang mampu secara ekonomi. Program ini merupakan bagian dari upaya pemerintah dalam meningkatkan akses pendidikan tinggi.',
                'persyaratan' => "1. Dari keluarga kurang mampu\n2. Lulusan SMA/SMK/MA\n3. Memiliki Kartu Indonesia Pintar\n4. Diterima di perguruan tinggi\n5. Mengisi formulir online\n6. Melampirkan dokumen ekonomi keluarga",
                'nilai_beasiswa' => 4800000,
                'tanggal_buka' => now()->subDays(5),
                'tanggal_tutup' => now()->addDays(30),
                'kontak' => 'kipkuliah@kemdikbud.go.id',
                'website' => 'https://kip-kuliah.kemdikbud.go.id',
                'status' => 'buka'
            ],
            [
                'nama_beasiswa' => 'Beasiswa Bank Indonesia',
                'penyelenggara' => 'Bank Indonesia',
                'deskripsi' => 'Beasiswa untuk mahasiswa jurusan ekonomi, perbankan, dan keuangan yang memiliki prestasi akademik dan non-akademik. Program ini merupakan bentuk komitmen BI dalam mendukung pendidikan di Indonesia.',
                'persyaratan' => "1. Jurusan Ekonomi/Perbankan/Keuangan\n2. IPK minimal 3.3\n3. Aktif dalam organisasi\n4. Essay tentang ekonomi Indonesia\n5. Wawancara\n6. Surat rekomendasi",
                'nilai_beasiswa' => 7500000,
                'tanggal_buka' => now()->subDays(15),
                'tanggal_tutup' => now()->addDays(15),
                'kontak' => 'beasiswa@bi.go.id',
                'website' => 'https://www.bi.go.id',
                'status' => 'buka'
            ],
            [
                'nama_beasiswa' => 'Beasiswa BCA Finance',
                'penyelenggara' => 'PT BCA Finance',
                'deskripsi' => 'Beasiswa corporate social responsibility dari BCA Finance untuk mahasiswa jurusan teknik, ekonomi, dan bisnis. Program ini bertujuan mencetak calon pemimpin masa depan.',
                'persyaratan' => "1. Semester 4-6\n2. IPK minimal 3.0\n3. Jurusan terkait\n4. Essay motivasi\n5. CV dan portofolio\n6. Wawancara",
                'nilai_beasiswa' => 6000000,
                'tanggal_buka' => now()->subDays(20),
                'tanggal_tutup' => now()->addDays(5),
                'kontak' => 'csr@bcafinance.co.id',
                'website' => 'https://www.bcafinance.co.id',
                'status' => 'tutup'
            ],
            [
                'nama_beasiswa' => 'Beasiswa Djarum Plus',
                'penyelenggara' => 'Djarum Foundation',
                'deskripsi' => 'Beasiswa prestise dari Djarum Foundation untuk mahasiswa berprestasi dari berbagai jurusan. Program ini tidak hanya memberikan bantuan finansial tetapi juga leadership training.',
                'persyaratan' => "1. IPK minimal 3.3\n2. Aktif organisasi\n3. Prestasi non-akademik\n4. Essay leadership\n5. Wawancara intensif\n6. Komitmen mengikuti program",
                'nilai_beasiswa' => 12000000,
                'tanggal_buka' => now()->addDays(10),
                'tanggal_tutup' => now()->addDays(60),
                'kontak' => 'djarumplus@djarum.com',
                'website' => 'https://djarumbeasiswaplus.org',
                'status' => 'tutup'
            ],
            [
                'nama_beasiswa' => 'Beasiswa Teknik Pertamina',
                'penyelenggara' => 'PT Pertamina',
                'deskripsi' => 'Beasiswa khusus untuk mahasiswa jurusan teknik dari berbagai disiplin ilmu. Program ini merupakan bagian dari pengembangan SDM di sektor energi.',
                'persyaratan' => "1. Jurusan Teknik\n2. IPK minimal 3.2\n3. Semester 3-6\n4. Surat rekomendasi\n5. Essay energi nasional\n6. Tes kemampuan teknik",
                'nilai_beasiswa' => 8000000,
                'tanggal_buka' => now()->subDays(3),
                'tanggal_tutup' => now()->addDays(25),
                'kontak' => 'beasiswa@pertamina.com',
                'website' => 'https://www.pertamina.com',
                'status' => 'buka'
            ]
        ];

        foreach ($beasiswas as $beasiswa) {
            Beasiswa::create($beasiswa);
        }

        $this->command->info('Beasiswa berhasil ditambahkan!');
    }
}