<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Konseling;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\LayananBk;

class KonselingSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = Siswa::where('status', 'aktif')->take(10)->get();
        $guruBk = Guru::take(3)->get(); // Ambil 3 guru pertama
        $layananBk = LayananBk::where('status', true)->get();

        if ($siswa->isEmpty() || $guruBk->isEmpty() || $layananBk->isEmpty()) {
            $this->command->info('Seeder Konseling: Data siswa, guru, atau layanan BK tidak ditemukan!');
            return;
        }

        $statuses = ['terjadwal', 'selesai', 'dibatalkan'];
        $tempat = ['Ruangan BK 1', 'Ruangan BK 2', 'Ruang Meeting', 'Ruang Guru'];
        
        for ($i = 0; $i < 15; $i++) {
            Konseling::create([
                'siswa_id' => $siswa->random()->id,
                'guru_id' => $guruBk->random()->id, // Sesuaikan
                'layanan_bk_id' => $layananBk->random()->id,
                'tanggal_konseling' => now()->addDays(rand(-30, 30)),
                'waktu_mulai' => sprintf('%02d:00', rand(8, 16)),
                'waktu_selesai' => sprintf('%02d:00', rand(9, 17)),
                'tempat' => $tempat[array_rand($tempat)],
                'permasalahan' => $this->getRandomPermasalahan(),
                'tindakan' => 'Memberikan konseling dan bimbingan sesuai kebutuhan siswa',
                'hasil' => 'Siswa menunjukkan perkembangan positif',
                'status' => $statuses[array_rand($statuses)],
                'catatan' => 'Perlu follow up dalam 2 minggu ke depan'
            ]);
        }

        $this->command->info('Seeder Konseling berhasil ditambahkan!');
    }

    private function getRandomPermasalahan()
    {
        $permasalahan = [
            'Kesulitan dalam mengatur waktu belajar',
            'Masalah konsentrasi selama pembelajaran',
            'Kesulitan beradaptasi dengan lingkungan sekolah',
            'Konflik dengan teman sekelas',
            'Tekanan dalam menghadapi ujian',
            'Minat belajar yang menurun',
            'Kesulitan dalam pemilihan jurusan',
            'Masalah komunikasi dengan orang tua',
            'Kecemasan dalam presentasi di kelas',
            'Kesulitan memahami pelajaran matematika'
        ];

        return $permasalahan[array_rand($permasalahan)];
    }
}