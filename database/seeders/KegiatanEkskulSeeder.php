<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KegiatanEkskul;
use App\Models\Ekstrakurikuler;
use Carbon\Carbon;

class KegiatanEkskulSeeder extends Seeder
{
    public function run()
    {
        // Pastikan data ekstrakurikuler sudah ada
        $ekskulList = Ekstrakurikuler::all();

        if ($ekskulList->isEmpty()) {
            $this->command->error('Tidak ada data ekstrakurikuler! Jalankan EkstrakurikulerSeeder terlebih dahulu.');
            return;
        }

        // Ambil beberapa ekskul yang ada
        $basket = $ekskulList->firstWhere('nama_ekskul', 'Basket');
        $pramuka = $ekskulList->firstWhere('nama_ekskul', 'Pramuka');
        $robotik = $ekskulList->firstWhere('nama_ekskul', 'Robotik');
        $pmr = $ekskulList->firstWhere('nama_ekskul', 'PMR');
        $seniTari = $ekskulList->firstWhere('nama_ekskul', 'Seni Tari');

        // Jika tidak ada data dengan nama spesifik, gunakan yang tersedia
        $basket = $basket ?: $ekskulList->first();
        $pramuka = $pramuka ?: $ekskulList->skip(1)->first() ?: $ekskulList->first();
        $robotik = $robotik ?: $ekskulList->skip(2)->first() ?: $ekskulList->first();
        $pmr = $pmr ?: $ekskulList->skip(3)->first() ?: $ekskulList->first();
        $seniTari = $seniTari ?: $ekskulList->skip(4)->first() ?: $ekskulList->first();

        $kegiatanData = [
            [
                'ekstrakurikuler_id' => $basket->id,
                'nama_kegiatan' => 'Latihan Basket Mingguan',
                'deskripsi' => 'Latihan rutin teknik dasar basket dan strategi permainan',
                'tanggal_kegiatan' => '2024-11-15',
                'waktu_mulai' => '15:00',
                'waktu_selesai' => '17:00',
                'tempat' => 'Lapangan Basket',
                'pembina' => 'Pak Rudi',
                'hasil_kegiatan' => 'Peningkatan skill dribbling dan shooting',
                'status' => 'terlaksana',
            ],
            [
                'ekstrakurikuler_id' => $pramuka->id,
                'nama_kegiatan' => 'Persiapan Lomba Pramuka',
                'deskripsi' => 'Latihan intensif untuk persiapan lomba pramuka tingkat kabupaten',
                'tanggal_kegiatan' => '2024-11-18',
                'waktu_mulai' => '14:00',
                'waktu_selesai' => '16:00',
                'tempat' => 'Aula Sekolah',
                'pembina' => 'Bu Sari',
                'hasil_kegiatan' => 'Peningkatan kemampuan pionering dan semaphore',
                'status' => 'terlaksana',
            ],
            [
                'ekstrakurikuler_id' => $robotik->id,
                'nama_kegiatan' => 'Workshop Robotik',
                'deskripsi' => 'Workshop pembuatan robot sederhana untuk pemula',
                'tanggal_kegiatan' => '2024-11-22',
                'waktu_mulai' => '13:00',
                'waktu_selesai' => '15:00',
                'tempat' => 'Lab Komputer',
                'pembina' => 'Pak Andi',
                'hasil_kegiatan' => null,
                'status' => 'ditunda',
            ],
            [
                'ekstrakurikuler_id' => $pmr->id,
                'nama_kegiatan' => 'Pelatihan P3K',
                'deskripsi' => 'Pelatihan pertolongan pertama pada kecelakaan',
                'tanggal_kegiatan' => '2024-11-25',
                'waktu_mulai' => '10:00',
                'waktu_selesai' => '12:00',
                'tempat' => 'Ruang Kesehatan',
                'pembina' => 'Bu Dian',
                'hasil_kegiatan' => 'Siswa mampu melakukan pertolongan pertama dasar',
                'status' => 'terlaksana',
            ],
            [
                'ekstrakurikuler_id' => $seniTari->id,
                'nama_kegiatan' => 'Latihan Tari Tradisional',
                'deskripsi' => 'Latihan tari tradisional untuk persiapan pentas seni',
                'tanggal_kegiatan' => '2024-11-28',
                'waktu_mulai' => '16:00',
                'waktu_selesai' => '18:00',
                'tempat' => 'Aula Seni',
                'pembina' => 'Bu Maya',
                'hasil_kegiatan' => 'Koreografi tari lengkap',
                'status' => 'terlaksana',
            ],
            [
                'ekstrakurikuler_id' => $basket->id,
                'nama_kegiatan' => 'Turnamen Basket Antar Kelas',
                'deskripsi' => 'Turnamen basket antar kelas untuk menjaring bakat baru',
                'tanggal_kegiatan' => '2024-12-05',
                'waktu_mulai' => '08:00',
                'waktu_selesai' => '16:00',
                'tempat' => 'Lapangan Basket Utama',
                'pembina' => 'Pak Rudi',
                'hasil_kegiatan' => null,
                'status' => 'dibatalkan',
            ],
        ];

        foreach ($kegiatanData as $kegiatan) {
            KegiatanEkskul::create($kegiatan);
        }

        $this->command->info('Berhasil menambahkan ' . count($kegiatanData) . ' data kegiatan ekskul.');
    }
}