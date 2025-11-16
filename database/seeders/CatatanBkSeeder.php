<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CatatanBk;
use App\Models\Siswa;
use App\Models\Guru;

class CatatanBkSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = Siswa::where('status', 'aktif')->take(8)->get();
        $guru = Guru::take(3)->get();

        if ($siswa->isEmpty() || $guru->isEmpty()) {
            $this->command->info('Seeder Catatan BK: Data tidak lengkap!');
            return;
        }

        $jenisCatatan = ['kasus', 'perkembangan', 'khusus'];
        $tingkatKeparahan = ['ringan', 'sedang', 'berat'];
        
        for ($i = 0; $i < 15; $i++) {
            CatatanBk::create([
                'siswa_id' => $siswa->random()->id,
                'guru_id' => $guru->random()->id,
                // Hapus layanan_bk_id
                'tanggal_catatan' => now()->subDays(rand(1, 90)),
                'jenis_catatan' => $jenisCatatan[array_rand($jenisCatatan)],
                'deskripsi' => $this->getRandomDeskripsi(),
                'tingkat_keparahan' => $tingkatKeparahan[array_rand($tingkatKeparahan)],
                'tindak_lanjut' => $this->getRandomTindakLanjut(),
                'status_selesai' => rand(0, 1)
            ]);
        }

        $this->command->info('Seeder Catatan BK berhasil ditambahkan!');
    }

    private function getRandomDeskripsi()
    {
        $deskripsi = [
            'Siswa mengalami kesulitan dalam menyesuaikan diri dengan lingkungan sekolah yang baru.',
            'Permasalahan dalam mengatur waktu belajar dan istirahat yang seimbang.',
            'Kesulitan dalam memahami pelajaran matematika dasar.',
            'Konflik dengan teman sekelas terkait kegiatan kelompok.',
            'Perkembangan positif dalam kemampuan berkomunikasi dan bersosialisasi.',
            'Kebutuhan bimbingan khusus dalam pemilihan jurusan untuk kelanjutan studi.',
            'Masalah konsentrasi selama proses pembelajaran di kelas.',
            'Peningkatan motivasi belajar setelah mengikuti program bimbingan.',
        ];

        return $deskripsi[array_rand($deskripsi)];
    }

    private function getRandomTindakLanjut()
    {
        $tindakLanjut = [
            'Memberikan sesi konseling individu mingguan.',
            'Koordinasi dengan wali kelas untuk monitoring perkembangan.',
            'Memberikan modul belajar mandiri untuk meningkatkan pemahaman.',
            'Mengadakan mediasi dengan pihak yang terkait konflik.',
            'Memberikan bimbingan teknik belajar yang efektif.',
            'Melakukan evaluasi berkala setiap 2 minggu sekali.',
            'Memberikan motivasi dan reinforcement positif.',
        ];

        return $tindakLanjut[array_rand($tindakLanjut)];
    }
}