<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\Nilai;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    private $tahunAktifId;

    public function run(): void
    {
        // Dapatkan tahun ajaran aktif
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
        $this->tahunAktifId = $tahunAktif ? $tahunAktif->id : null;

        $this->command->info('Menghapus data siswa lama...');
        
        // Hapus data yang terkait terlebih dahulu
        Absensi::query()->delete();
        Nilai::query()->delete();
        
        // Hapus siswa dan user siswa
        $siswaUsers = User::where('role', 'siswa')->pluck('id');
        Siswa::query()->delete();
        User::where('role', 'siswa')->delete();

        $this->command->info('Data lama berhasil dihapus!');

        $namaBatakLaki = [
            // Batch 1
            'Mangara Sitorus', 'Jhonni Situmorang', 'Roy Martua Sihombing', 'Hotman Pardede',
            'Binsar Nainggolan', 'Rizky Simanjuntak', 'Ferdinand Siregar', 'Robert Manurung',
            'Maruli Tua Siahaan', 'Jekson Sinaga', 'Roymond Simbolon', 'Victor Panjaitan',
            'Alberth Hutagalung', 'Richard Tampubolon', 'Steven Marbun', 'Kevin Sihotang',
            'Wilson Pasaribu', 'Hendrik Gultom', 'Daniel Sirait', 'Michael Sipahutar',
            'Christoper Silitonga', 'Jonathan Sianipar', 'Andi Rajagukguk', 'Bobby Sidauruk',
            'Charles Sijabat', 'David Simalango', 'Erick Sinurat', 'Franky Sipayung',
            'Gilbert Sitohang', 'Henry Sitorus', 'Ivan Situmorang', 'Jack Sihombing',
            'Kenny Pardede', 'Leo Nainggolan', 'Mario Simanjuntak', 'Nelson Siregar',
            'Ricky Silaban', 'Donny Sihotang', 'Herman Sirait', 'Jefri Sitorus',
            
            // Batch 2
            'Kristopher Sihombing', 'Lucas Pardede', 'Martin Nainggolan', 'Nando Simanjuntak',
            'Oscar Siregar', 'Paulus Manurung', 'Ronald Sinaga', 'Steven Simbolon',
            'Tommy Panjaitan', 'Ucok Hutagalung', 'Vicky Tampubolon', 'William Marbun',
            'Xavier Sihotang', 'Yosua Pasaribu', 'Zacky Gultom', 'Adrian Sitorus',
            'Brian Situmorang', 'Cakra Sihombing', 'Dedi Pardede', 'Eko Nainggolan',
            'Fajar Simanjuntak', 'Guntur Siregar', 'Hadi Manurung', 'Iwan Siahaan',
            'Joko Sinaga', 'Kurniawan Simbolon', 'Lukas Panjaitan', 'Mulyadi Hutagalung',
            
            // Batch 3
            'Nurdin Tampubolon', 'Oki Marbun', 'Pandu Sihotang', 'Rico Sitorus',
            'Sandy Situmorang', 'Toni Sihombing', 'Umar Pardede', 'Vino Nainggolan',
            'Wawan Simanjuntak', 'Xavier Siregar', 'Yoga Manurung', 'Zaki Siahaan',
            'Aldo Sinaga', 'Budi Simbolon', 'Caca Panjaitan', 'Doni Hutagalung',
            'Eri Tampubolon', 'Fikri Marbun', 'Genta Sihotang', 'Hilman Pasaribu',
            'Irfan Gultom', 'Juni Sirait', 'Koko Sipahutar', 'Lance Sitorus',
            
            // Batch 4
            'Miko Situmorang', 'Niko Sihombing', 'Odi Pardede', 'Panca Nainggolan',
            'Qori Simanjuntak', 'Rafi Siregar', 'Sandi Manurung', 'Teguh Siahaan',
            'Ujang Sinaga', 'Vito Simbolon', 'Wira Panjaitan', 'Yuda Hutagalung',
            'Zulfikar Tampubolon', 'Ari Marbun', 'Bayu Sihotang', 'Cepi Pasaribu',
            'Dafa Gultom', 'Ega Sirait', 'Farhan Sipahutar', 'Gading Silitonga',
            'Hendra Sianipar', 'Ical Rajagukguk', 'Jaya Sidauruk', 'Kris Sijabat',
            
            // Batch 5
            'Luthfi Simalango', 'Maman Sinurat', 'Nando Sipayung', 'Opik Sitohang',
            'Pratama Sitorus', 'Qashmal Situmorang', 'Rangga Sihombing', 'Samsul Pardede',
            'Taufik Nainggolan', 'Usman Simanjuntak', 'Vallen Siregar', 'Wahyu Manurung',
            'Yoga Siahaan', 'Zainal Sinaga', 'Ahmad Simbolon', 'Bambang Panjaitan',
            'Cahyo Hutagalung', 'Dodi Tampubolon', 'Eko Marbun', 'Fajar Sihotang',
            'Gunawan Pasaribu', 'Hasan Gultom', 'Ibrahim Sirait', 'Joni Sipahutar'
        ];

        $namaBatakPerempuan = [
            // Batch 1
            'Sinta Ria Sitorus', 'Debora Situmorang', 'Maya Sarah Sihombing', 'Lina Marlina Pardede',
            'Rina Julianti Nainggolan', 'Erika Margaretha Simanjuntak', 'Diana Rosdiana Siregar',
            'Sarah Debora Manurung', 'Martha Siahaan', 'Jenny Sinaga', 'Cindy Simbolon',
            'Grace Panjaitan', 'Lisa Hutagalung', 'Maria Tampubolon', 'Susan Marbun',
            'Jessica Sihotang', 'Nina Pasaribu', 'Olivia Gultom', 'Patricia Sirait',
            'Ruth Silitonga', 'Sofia Sianipar', 'Tina Rajagukguk', 'Umi Sidauruk',
            'Vina Sijabat', 'Winda Simalango', 'Yuni Sinurat', 'Zaskia Sipayung',
            'Anita Sitohang', 'Bella Sitorus', 'Catherine Situmorang', 'Dina Sihombing',
            'Eva Pardede', 'Fanny Nainggolan', 'Gita Simanjuntak', 'Hana Siregar',
            
            // Batch 2
            'Indah Manurung', 'Juli Siahaan', 'Karin Sinaga', 'Lia Simbolon',
            'Mona Panjaitan', 'Nadia Hutagalung', 'Oline Tampubolon', 'Putri Marbun',
            'Riri Pasaribu', 'Sari Gultom', 'Tania Sirait', 'Ulya Sipahutar',
            'Vania Silitonga', 'Wulan Sianipar', 'Yulia Sidauruk', 'Zahra Sijabat',
            'Amelia Sitorus', 'Bunga Situmorang', 'Cantika Sihombing', 'Dewi Pardede',
            'Elsa Nainggolan', 'Fitri Simanjuntak', 'Gadis Siregar', 'Hesti Manurung',
            
            // Batch 3
            'Intan Siahaan', 'Jihan Sinaga', 'Kezia Simbolon', 'Lestari Panjaitan',
            'Mira Hutagalung', 'Nia Tampubolon', 'Opi Marbun', 'Putri Sihotang',
            'Queen Sitorus', 'Rara Situmorang', 'Sari Sihombing', 'Tari Pardede',
            'Umi Nainggolan', 'Vivi Simanjuntak', 'Winda Siregar', 'Xena Manurung',
            'Yuni Siahaan', 'Zara Sinaga', 'Anisa Simbolon', 'Bella Panjaitan',
            'Citra Hutagalung', 'Dewi Tampubolon', 'Eka Marbun', 'Fira Sihotang',
            
            // Batch 4
            'Gita Pasaribu', 'Hani Gultom', 'Indah Sirait', 'Jihan Sipahutar',
            'Kanya Silitonga', 'Lia Sianipar', 'Maya Rajagukguk', 'Nia Sidauruk',
            'Ovi Sijabat', 'Putri Simalango', 'Queency Sinurat', 'Rara Sipayung',
            'Sisi Sitohang', 'Tia Sitorus', 'Umi Situmorang', 'Vina Sihombing',
            'Widi Pardede', 'Xena Nainggolan', 'Yani Simanjuntak', 'Zahra Siregar',
            'Alya Manurung', 'Bela Siahaan', 'Cinta Sinaga', 'Dinda Simbolon',
            
            // Batch 5
            'Elsa Panjaitan', 'Fira Hutagalung', 'Gita Tampubolon', 'Hana Marbun',
            'Ina Sihotang', 'Jihan Pasaribu', 'Kania Gultom', 'Lia Sirait',
            'Maya Sipahutar', 'Nia Silitonga', 'Opi Sianipar', 'Putri Rajagukguk',
            'Queena Sidauruk', 'Rani Sijabat', 'Sari Simalango', 'Titi Sinurat',
            'Uci Sipayung', 'Vina Sitohang', 'Winda Sitorus', 'Xia Situmorang',
            'Yuni Sihombing', 'Zara Pardede', 'Aisyah Nainggolan', 'Bunga Simanjuntak',
            'Cindy Siregar', 'Dewi Manurung', 'Eva Siahaan', 'Fitri Sinaga',
            'Giselle Simbolon', 'Hilda Panjaitan', 'Intan Hutagalung', 'Jasmine Tampubolon'
        ];

        // Acak urutan nama
        shuffle($namaBatakLaki);
        shuffle($namaBatakPerempuan);

        // Ambil kelas berdasarkan tingkat
        $kelasX = Kelas::where('tingkat', '10')->get();
        $kelasXI = Kelas::where('tingkat', '11')->get();
        $kelasXII = Kelas::where('tingkat', '12')->get();

        $userCounter = 1000;

        $this->command->info("Membuat siswa untuk Kelas X...");
$createdX = $this->createSiswaForKelas($kelasX, $namaBatakLaki, $namaBatakPerempuan, $userCounter, '2008', 15, 15); // 15 laki + 15 perempuan per kelas

$this->command->info("Membuat siswa untuk Kelas XI...");
$createdXI = $this->createSiswaForKelas($kelasXI, $namaBatakLaki, $namaBatakPerempuan, $userCounter, '2007', 12, 12); // 12 laki + 12 perempuan per kelas

$this->command->info("Membuat siswa untuk Kelas XII...");
$createdXII = $this->createSiswaForKelas($kelasXII, $namaBatakLaki, $namaBatakPerempuan, $userCounter, '2006', 10, 10); // 10 laki + 10 perempuan per kelas
    }

    private function createSiswaForKelas($kelasList, &$namaLaki, &$namaPerempuan, &$userCounter, $tahunLahir, $jumlahLaki, $jumlahPerempuan)
    {
        $totalCreated = 0;

        foreach ($kelasList as $kelas) {
            $this->command->info("Membuat siswa untuk kelas: {$kelas->nama}");

            // Buat siswa laki-laki
            for ($i = 0; $i < $jumlahLaki; $i++) {
                if (empty($namaLaki)) {
                    $nama = "Siswa Laki " . $userCounter;
                } else {
                    $nama = array_shift($namaLaki);
                }
                
                $email = strtolower(str_replace(' ', '.', $nama)) . $userCounter . '@siakad.sch.id';
                $nis = 'S' . $userCounter;

                $user = User::create([
                    'name' => $nama,
                    'email' => $email,
                    'password' => Hash::make($nis),
                    'role' => 'siswa',
                    'is_active' => true,
                ]);

                // BUAT DATA SISWA DENGAN ARRAY YANG BENAR
                $siswaData = [
                    'user_id' => $user->id,
                    'nis' => $nis,
                    'nisn' => '00' . $userCounter . '001',
                    'nama' => $nama,
                    'nama_lengkap' => $nama,
                    'jenis_kelamin' => 'L',
                    'tempat_lahir' => 'Medan',
                    'tanggal_lahir' => $tahunLahir . '-' . sprintf('%02d', rand(1, 12)) . '-' . sprintf('%02d', rand(1, 28)),
                    'alamat' => 'Jl. Pendidikan No. ' . rand(1, 100) . ', Medan',
                    'no_telp' => '0812' . rand(1000000, 9999999),
                    'kelas_id' => $kelas->id,
                    'tahun_ajaran_id' => $this->tahunAktifId,
                    'status' => 'aktif',
                ];

                Siswa::create($siswaData);

                $userCounter++;
                $totalCreated++;
            }

            // Buat siswa perempuan
            for ($i = 0; $i < $jumlahPerempuan; $i++) {
                if (empty($namaPerempuan)) {
                    $nama = "Siswa Perempuan " . $userCounter;
                } else {
                    $nama = array_shift($namaPerempuan);
                }

                $email = strtolower(str_replace(' ', '.', $nama)) . $userCounter . '@siakad.sch.id';
                $nis = 'S' . $userCounter;

                $user = User::create([
                    'name' => $nama,
                    'email' => $email,
                    'password' => Hash::make($nis),
                    'role' => 'siswa',
                    'is_active' => true,
                ]);

                // BUAT DATA SISWA DENGAN ARRAY YANG BENAR
                $siswaData = [
                    'user_id' => $user->id,
                    'nis' => $nis,
                    'nisn' => '00' . $userCounter . '002',
                    'nama' => $nama,
                    'nama_lengkap' => $nama,
                    'jenis_kelamin' => 'P',
                    'tempat_lahir' => 'Medan',
                    'tanggal_lahir' => $tahunLahir . '-' . sprintf('%02d', rand(1, 12)) . '-' . sprintf('%02d', rand(1, 28)),
                    'alamat' => 'Jl. Pendidikan No. ' . rand(1, 100) . ', Medan',
                    'no_telp' => '0813' . rand(1000000, 9999999),
                    'kelas_id' => $kelas->id,
                    'tahun_ajaran_id' => $this->tahunAktifId,
                    'status' => 'aktif',
                ];

                Siswa::create($siswaData);

                $userCounter++;
                $totalCreated++;
            }
        }

        return $totalCreated;
    }
}