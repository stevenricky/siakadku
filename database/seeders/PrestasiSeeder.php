<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Prestasi;
use App\Models\Siswa;
use Carbon\Carbon;

class PrestasiSeeder extends Seeder
{
    public function run()
    {
        // Pastikan data siswa sudah ada
        $siswaList = Siswa::where('status', 'aktif')->get();

        if ($siswaList->isEmpty()) {
            $this->command->error('Tidak ada data siswa! Jalankan SiswaSeeder terlebih dahulu.');
            return;
        }

        $prestasiData = [
            // Prestasi Akademik
            [
                'siswa_id' => $siswaList->first()->id,
                'jenis_prestasi' => 'akademik',
                'tingkat' => 'provinsi',
                'nama_prestasi' => 'Juara 1 Olimpiade Matematika',
                'penyelenggara' => 'Dinas Pendidikan Provinsi',
                'tanggal_prestasi' => '2024-10-15',
                'peringkat' => 'Juara 1',
                'deskripsi' => 'Meraih juara 1 dalam olimpiade matematika tingkat provinsi',
                'status' => true,
            ],
            [
                'siswa_id' => $siswaList->skip(1)->first()->id,
                'jenis_prestasi' => 'akademik',
                'tingkat' => 'nasional',
                'nama_prestasi' => 'Juara 2 OSN Fisika',
                'penyelenggara' => 'Kementerian Pendidikan',
                'tanggal_prestasi' => '2024-09-20',
                'peringkat' => 'Juara 2',
                'deskripsi' => 'Peraih medali perak Olimpiade Sains Nasional bidang Fisika',
                'status' => true,
            ],

            // Prestasi Olahraga
            [
                'siswa_id' => $siswaList->skip(2)->first()->id,
                'jenis_prestasi' => 'olahraga',
                'tingkat' => 'kabupaten',
                'nama_prestasi' => 'Juara 1 Lomba Lari 100m',
                'penyelenggara' => 'Dinas Pemuda dan Olahraga',
                'tanggal_prestasi' => '2024-11-05',
                'peringkat' => 'Juara 1',
                'deskripsi' => 'Memenangi lomba lari 100 meter putra tingkat kabupaten',
                'status' => true,
            ],
            [
                'siswa_id' => $siswaList->skip(3)->first()->id,
                'jenis_prestasi' => 'olahraga',
                'tingkat' => 'provinsi',
                'nama_prestasi' => 'Juara 3 Turnamen Basket',
                'penyelenggara' => 'Pengprov Basket',
                'tanggal_prestasi' => '2024-08-12',
                'peringkat' => 'Juara 3',
                'deskripsi' => 'Tim basket sekolah meraih juara 3 tingkat provinsi',
                'status' => true,
            ],

            // Prestasi Seni
            [
                'siswa_id' => $siswaList->skip(4)->first()->id,
                'jenis_prestasi' => 'seni',
                'tingkat' => 'nasional',
                'nama_prestasi' => 'Juara 1 Festival Musik Tradisional',
                'penyelenggara' => 'Kementerian Pendidikan dan Kebudayaan',
                'tanggal_prestasi' => '2024-07-25',
                'peringkat' => 'Juara 1',
                'deskripsi' => 'Memenangi festival musik tradisional dengan permainan gamelan',
                'status' => true,
            ],
            [
                'siswa_id' => $siswaList->skip(5)->first()->id,
                'jenis_prestasi' => 'seni',
                'tingkat' => 'kabupaten',
                'nama_prestasi' => 'Juara 2 Lomba Melukis',
                'penyelenggara' => 'Dinas Kebudayaan Kabupaten',
                'tanggal_prestasi' => '2024-06-18',
                'peringkat' => 'Juara 2',
                'deskripsi' => 'Meraih juara 2 lomba melukis bertema lingkungan',
                'status' => true,
            ],

            // Prestasi Non-Akademik
            [
                'siswa_id' => $siswaList->skip(6)->first()->id,
                'jenis_prestasi' => 'non-akademik',
                'tingkat' => 'sekolah',
                'nama_prestasi' => 'Juara 1 Lomba Pidato',
                'penyelenggara' => 'OSIS SMA Negeri 1',
                'tanggal_prestasi' => '2024-11-10',
                'peringkat' => 'Juara 1',
                'deskripsi' => 'Memenangi lomba pidato bahasa Indonesia tingkat sekolah',
                'status' => true,
            ],
            [
                'siswa_id' => $siswaList->skip(7)->first()->id,
                'jenis_prestasi' => 'non-akademik',
                'tingkat' => 'kecamatan',
                'nama_prestasi' => 'Juara 1 Lomba Debat',
                'penyelenggara' => 'Kecamatan Setiabudi',
                'tanggal_prestasi' => '2024-10-28',
                'peringkat' => 'Juara 1',
                'deskripsi' => 'Tim debat sekolah meraih juara 1 tingkat kecamatan',
                'status' => true,
            ],

            // Prestasi Lainnya
            [
                'siswa_id' => $siswaList->skip(8)->first()->id,
                'jenis_prestasi' => 'lainnya',
                'tingkat' => 'provinsi',
                'nama_prestasi' => 'Juara 1 Lomba Karya Tulis Ilmiah',
                'penyelenggara' => 'Dinas Pendidikan Provinsi',
                'tanggal_prestasi' => '2024-09-15',
                'peringkat' => 'Juara 1',
                'deskripsi' => 'Memenangi lomba karya tulis ilmiah dengan penelitian tentang pemanfaatan limbah pertanian',
                'status' => true,
            ],
            [
                'siswa_id' => $siswaList->skip(9)->first()->id,
                'jenis_prestasi' => 'lainnya',
                'tingkat' => 'nasional',
                'nama_prestasi' => 'Juara 3 Kompetisi Robotik',
                'penyelenggara' => 'Kementerian Riset dan Teknologi',
                'tanggal_prestasi' => '2024-08-20',
                'peringkat' => 'Juara 3',
                'deskripsi' => 'Meraih juara 3 dalam kompetisi robotik nasional kategori inovasi',
                'status' => true,
            ],

            // Prestasi Internasional
            [
                'siswa_id' => $siswaList->skip(10)->first()->id ?? $siswaList->first()->id,
                'jenis_prestasi' => 'akademik',
                'tingkat' => 'internasional',
                'nama_prestasi' => 'Medali Perunggu Olimpiade Sains Internasional',
                'penyelenggara' => 'International Science Olympiad',
                'tanggal_prestasi' => '2024-12-05',
                'peringkat' => 'Juara 3',
                'deskripsi' => 'Meraih medali perunggu dalam olimpiade sains internasional di Singapura',
                'status' => true,
            ],
        ];

        // Insert data prestasi
        foreach ($prestasiData as $prestasi) {
            // Cek jika siswa_id tersedia, jika tidak gunakan siswa pertama
            if (!isset($prestasi['siswa_id']) || !Siswa::find($prestasi['siswa_id'])) {
                $prestasi['siswa_id'] = $siswaList->first()->id;
            }

            Prestasi::create($prestasi);
        }

        $this->command->info('Berhasil menambahkan ' . count($prestasiData) . ' data prestasi.');
    }
}