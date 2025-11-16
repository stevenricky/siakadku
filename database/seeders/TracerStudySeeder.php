<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TracerStudy;
use App\Models\Alumni;

class TracerStudySeeder extends Seeder
{
    public function run(): void
    {
        $alumni = Alumni::take(15)->get();

        if ($alumni->isEmpty()) {
            $this->command->info('Seeder Tracer Study: Data alumni tidak ditemukan!');
            return;
        }

        $statusPekerjaan = ['bekerja', 'wirausaha', 'melanjutkan', 'belum_bekerja'];
        $perusahaan = ['PT. Telkom Indonesia', 'PT. Bank Central Asia', 'PT. Astra International', 'PT. Unilever Indonesia', 'Google Indonesia'];
        $kampus = ['Universitas Indonesia', 'Institut Teknologi Bandung', 'Universitas Gadjah Mada', 'Universitas Airlangga', 'Institut Pertanian Bogor'];
        $jurusan = ['Teknik Informatika', 'Manajemen', 'Akuntansi', 'Kedokteran', 'Hukum', 'Psikologi'];
        
        foreach ($alumni as $a) {
            $status = $statusPekerjaan[array_rand($statusPekerjaan)];
            $nama_perusahaan = null;
            $bidang_pekerjaan = null;
            $jabatan = null;
            $gaji = null;
            $nama_kampus = null;
            $jurusan_kuliah = null;
            $tahun_masuk_kuliah = null;

            if ($status === 'bekerja') {
                $nama_perusahaan = $perusahaan[array_rand($perusahaan)];
                $bidang_pekerjaan = ['IT', 'Finance', 'Marketing', 'HR', 'Operations'][array_rand([0,1,2,3,4])];
                $jabatan = ['Staff', 'Supervisor', 'Manager', 'Analyst'][array_rand([0,1,2,3])] . ' ' . $bidang_pekerjaan;
                $gaji = rand(5000000, 15000000);
            } elseif ($status === 'wirausaha') {
                $nama_perusahaan = 'Usaha ' . ['Makanan', 'Fashion', 'Teknologi', 'Jasa', 'Retail'][array_rand([0,1,2,3,4])];
                $bidang_pekerjaan = 'Wirausaha';
                $jabatan = 'Owner/Pemilik';
            } elseif ($status === 'melanjutkan') {
                $nama_kampus = $kampus[array_rand($kampus)];
                $jurusan_kuliah = $jurusan[array_rand($jurusan)];
                $tahun_masuk_kuliah = $a->tahun_lulus + 1;
            }

            TracerStudy::create([
                'alumni_id' => $a->id,
                'tahun_survey' => date('Y'),
                'status_pekerjaan' => $status,
                'nama_perusahaan' => $nama_perusahaan,
                'bidang_pekerjaan' => $bidang_pekerjaan,
                'jabatan' => $jabatan,
                'gaji' => $gaji,
                'nama_kampus' => $nama_kampus,
                'jurusan_kuliah' => $jurusan_kuliah,
                'tahun_masuk_kuliah' => $tahun_masuk_kuliah,
                'relevansi_pendidikan' => 'Pendidikan di sekolah sangat membantu dalam ' . ['pekerjaan', 'perkuliahan', 'berwirausaha'][array_rand([0,1,2])] . ' saat ini.',
                'saran_untuk_sekolah' => 'Terus tingkatkan kualitas pembelajaran dan fasilitas untuk siswa.',
                'status_survey' => rand(0, 1) ? 'terkirim' : 'dijawab'
            ]);
        }

        $this->command->info('Seeder Tracer Study berhasil ditambahkan!');
    }
}