<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumni;
use App\Models\Siswa;

class AlumniSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = Siswa::where('status', 'aktif')->take(10)->get();

        if ($siswa->isEmpty()) {
            $this->command->info('Seeder Alumni: Data siswa tidak ditemukan!');
            return;
        }

        $statusOptions = ['kuliah', 'kerja', 'wirausaha', 'lainnya'];
        $universitas = ['Universitas Indonesia', 'Institut Teknologi Bandung', 'Universitas Gadjah Mada', 'Universitas Airlangga', 'Institut Pertanian Bogor'];
        $perusahaan = ['PT. Telkom Indonesia', 'PT. Bank Central Asia', 'PT. Astra International', 'PT. Unilever Indonesia', 'Google Indonesia'];
        $jurusan = ['Teknik Informatika', 'Manajemen', 'Akuntansi', 'Kedokteran', 'Hukum', 'Psikologi'];
        
        foreach ($siswa as $s) {
            $status = $statusOptions[array_rand($statusOptions)];
            $instansi = '';
            $jurusan_kuliah = null;
            $jabatan_pekerjaan = null;

            if ($status === 'kuliah') {
                $instansi = $universitas[array_rand($universitas)];
                $jurusan_kuliah = $jurusan[array_rand($jurusan)];
            } elseif ($status === 'kerja') {
                $instansi = $perusahaan[array_rand($perusahaan)];
                $jabatan_pekerjaan = 'Staff ' . ['IT', 'Marketing', 'Finance', 'HR', 'Operations'][array_rand([0,1,2,3,4])];
            } elseif ($status === 'wirausaha') {
                $instansi = 'Usaha ' . ['Makanan', 'Fashion', 'Teknologi', 'Jasa', 'Retail'][array_rand([0,1,2,3,4])];
            }

            Alumni::create([
                'siswa_id' => $s->id,
                'tahun_lulus' => rand(2018, 2024),
                'no_ijazah' => 'IJZ/' . rand(1000, 9999) . '/2024',
                'status_setelah_lulus' => $status,
                'instansi' => $instansi,
                'jurusan_kuliah' => $jurusan_kuliah,
                'jabatan_pekerjaan' => $jabatan_pekerjaan,
                'alamat_instansi' => 'Jl. Contoh Alamat No. ' . rand(1, 100),
                'no_telepon' => '08' . rand(100000000, 999999999),
                'email' => strtolower(str_replace(' ', '.', $s->nama)) . '@email.com',
                'prestasi_setelah_lulus' => 'Berprestasi dalam bidang ' . ['akademik', 'olahraga', 'seni', 'sosial'][array_rand([0,1,2,3])],
                'status_aktif' => true
            ]);
        }

        $this->command->info('Seeder Alumni berhasil ditambahkan!');
    }
}