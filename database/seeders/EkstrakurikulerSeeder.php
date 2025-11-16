<?php

namespace Database\Seeders;

use App\Models\Ekstrakurikuler;
use App\Models\Guru;
use Illuminate\Database\Seeder;

class EkstrakurikulerSeeder extends Seeder
{
    public function run(): void
    {
        $guru = Guru::first();

        $ekstrakurikulers = [
            [
                'nama_ekstra' => 'Pramuka',
                'deskripsi' => 'Kegiatan kepramukaan untuk melatih kedisiplinan dan kepemimpinan',
                'pembina' => $guru->nama_lengkap,
                'hari' => 'Sabtu',
                'jam_mulai' => '07:00',
                'jam_selesai' => '10:00',
                'tempat' => 'Lapangan Sekolah',
                'status' => 1,
            ],
            [
                'nama_ekstra' => 'Paskibra',
                'deskripsi' => 'Pasukan Pengibar Bendera untuk melatih kedisiplinan dan nasionalisme',
                'pembina' => $guru->nama_lengkap,
                'hari' => 'Jumat',
                'jam_mulai' => '15:00',
                'jam_selesai' => '17:00',
                'tempat' => 'Lapangan Sekolah',
                'status' => 1,
            ],
            [
                'nama_ekstra' => 'Basket',
                'deskripsi' => 'Olahraga bola basket untuk melatih kerjasama tim dan kesehatan',
                'pembina' => $guru->nama_lengkap,
                'hari' => 'Selasa',
                'jam_mulai' => '15:00',
                'jam_selesai' => '17:00',
                'tempat' => 'Lapangan Basket',
                'status' => 1,
            ],
            [
                'nama_ekstra' => 'Futsal',
                'deskripsi' => 'Olahraga futsal untuk melatih kerjasama tim dan kesehatan',
                'pembina' => $guru->nama_lengkap,
                'hari' => 'Kamis',
                'jam_mulai' => '15:00',
                'jam_selesai' => '17:00',
                'tempat' => 'Lapangan Futsal',
                'status' => 1,
            ],
            [
                'nama_ekstra' => 'Paduan Suara',
                'deskripsi' => 'Kegiatan menyanyi bersama untuk mengembangkan bakat seni',
                'pembina' => $guru->nama_lengkap,
                'hari' => 'Rabu',
                'jam_mulai' => '15:00',
                'jam_selesai' => '17:00',
                'tempat' => 'Aula Sekolah',
                'status' => 1,
            ],
            [
                'nama_ekstra' => 'Silat',
                'deskripsi' => 'Olahraga silat tradisional untuk melatih keterampilan dan kepercayaan diri',
                'pembina' => $guru->nama_lengkap,
                'hari' => 'Senin',
                'jam_mulai' => '15:00',
                'jam_selesai' => '17:00',
                'tempat' => 'Ruang Olahraga',
                'status' => 1,
            ],
            [
                'nama_ekstra' => 'Robotik',
                'deskripsi' => 'Kegiatan robotik dan pemrograman untuk mengembangkan kemampuan STEM',
                'pembina' => $guru->nama_lengkap,
                'hari' => 'Rabu',
                'jam_mulai' => '16:00',
                'jam_selesai' => '18:00',
                'tempat' => 'Lab Komputer',
                'status' => 1,
            ],
            [
                'nama_ekstra' => 'KIR',
                'deskripsi' => 'Karya Ilmiah Remaja untuk mengembangkan minat sains',
                'pembina' => $guru->nama_lengkap,
                'hari' => 'Jumat',
                'jam_mulai' => '16:00',
                'jam_selesai' => '18:00',
                'tempat' => 'Lab Sains',
                'status' => 1,
            ],
            [
                'nama_ekstra' => 'Voli',
                'deskripsi' => 'Olahraga bola voli untuk melatih kerjasama tim',
                'pembina' => $guru->nama_lengkap,
                'hari' => 'Selasa',
                'jam_mulai' => '16:00',
                'jam_selesai' => '18:00',
                'tempat' => 'Lapangan Voli',
                'status' => 1,
            ],
            [
                'nama_ekstra' => 'Musik',
                'deskripsi' => 'Kegiatan musik untuk mengembangkan bakat seni musik',
                'pembina' => $guru->nama_lengkap,
                'hari' => 'Kamis',
                'jam_mulai' => '16:00',
                'jam_selesai' => '18:00',
                'tempat' => 'Ruang Musik',
                'status' => 1,
            ],
        ];

        foreach ($ekstrakurikulers as $ekstra) {
            Ekstrakurikuler::create($ekstra);
        }

        $this->command->info('Data ekstrakurikuler berhasil ditambahkan!');
        $this->command->info('Total: ' . Ekstrakurikuler::count() . ' ekstrakurikuler');
    }
}