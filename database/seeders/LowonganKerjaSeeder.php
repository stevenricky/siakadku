<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LowonganKerja;
use Carbon\Carbon;

class LowonganKerjaSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama
        LowonganKerja::truncate();

        $lowongan = [
            [
                'nama_perusahaan' => 'PT. Teknologi Indonesia',
                'posisi' => 'Full Stack Developer',
                'deskripsi_pekerjaan' => 'Mengembangkan dan memelihara aplikasi web menggunakan teknologi terbaru.',
                'kualifikasi' => 'Lulusan D3/S1 Teknik Informatika. Menguasai PHP, Laravel, JavaScript.',
                'lokasi' => 'Jakarta Selatan',
                'gaji' => 8000000,
                'tanggal_buka' => Carbon::now()->subDays(5),
                'tanggal_tutup' => Carbon::now()->addDays(15), // 15 hari dari sekarang
                'kontak_person' => 'Budi Santoso',
                'email' => 'hr@teknologiindonesia.com',
                'no_telepon' => '021-1234567',
                'website' => 'www.teknologiindonesia.com',
                'status' => 'buka'
            ],
            [
                'nama_perusahaan' => 'CV. Kreatif Digital',
                'posisi' => 'UI/UX Designer',
                'deskripsi_pekerjaan' => 'Mendesain antarmuka pengguna yang menarik dan mudah digunakan.',
                'kualifikasi' => 'Lulusan D3/S1 Desain Komunikasi Visual. Menguasai Figma, Adobe XD.',
                'lokasi' => 'Bandung',
                'gaji' => 6500000,
                'tanggal_buka' => Carbon::now()->subDays(3),
                'tanggal_tutup' => Carbon::now()->addDays(10), // 10 hari dari sekarang
                'kontak_person' => 'Sari Dewi',
                'email' => 'career@kreatifdigital.com',
                'no_telepon' => '022-7654321',
                'website' => 'www.kreatifdigital.com',
                'status' => 'buka'
            ],
            [
                'nama_perusahaan' => 'PT. Retail Modern',
                'posisi' => 'Marketing Staff',
                'deskripsi_pekerjaan' => 'Merencanakan dan melaksanakan strategi pemasaran.',
                'kualifikasi' => 'Lulusan D3/S1 Marketing, Komunikasi.',
                'lokasi' => 'Surabaya',
                'gaji' => 5500000,
                'tanggal_buka' => Carbon::now()->subDays(2),
                'tanggal_tutup' => Carbon::now()->addDays(8), // 8 hari dari sekarang
                'kontak_person' => 'Ahmad Rizki',
                'email' => 'hr@retailmodern.com',
                'no_telepon' => '031-9876543',
                'website' => 'www.retailmodern.com',
                'status' => 'buka'
            ],
            [
                'nama_perusahaan' => 'PT. Fintech Nusantara',
                'posisi' => 'Data Analyst',
                'deskripsi_pekerjaan' => 'Menganalisis data keuangan dan transaksi.',
                'kualifikasi' => 'Lulusan S1 Statistika, Matematika, atau Teknik Informatika.',
                'lokasi' => 'Jakarta Pusat',
                'gaji' => 9500000,
                'tanggal_buka' => Carbon::now()->subDays(1),
                'tanggal_tutup' => Carbon::now()->addDays(7), // 7 hari dari sekarang
                'kontak_person' => 'Diana Putri',
                'email' => 'recruitment@fintechnusantara.com',
                'no_telepon' => '021-5558888',
                'website' => 'www.fintechnusantara.com',
                'status' => 'buka'
            ],
            [
                'nama_perusahaan' => 'PT. Logistik Cepat',
                'posisi' => 'Logistics Coordinator',
                'deskripsi_pekerjaan' => 'Mengkoordinasi pengiriman dan distribusi barang.',
                'kualifikasi' => 'Lulusan D3/S1 Logistik, Manajemen.',
                'lokasi' => 'Tangerang',
                'gaji' => 6000000,
                'tanggal_buka' => Carbon::now()->subDays(4),
                'tanggal_tutup' => Carbon::now()->addDays(3), // 3 hari dari sekarang
                'kontak_person' => 'Joko Prasetyo',
                'email' => 'hr@logistikcepat.com',
                'no_telepon' => '021-4443333',
                'website' => 'www.logistikcepat.com',
                'status' => 'buka'
            ],
            [
                'nama_perusahaan' => 'PT. Energi Terbarukan',
                'posisi' => 'Environmental Engineer',
                'deskripsi_pekerjaan' => 'Merancang sistem energi terbarukan.',
                'kualifikasi' => 'Lulusan S1 Teknik Lingkungan, Teknik Kimia.',
                'lokasi' => 'Bali',
                'gaji' => 8500000,
                'tanggal_buka' => Carbon::now()->subDays(6),
                'tanggal_tutup' => Carbon::now()->addDays(5), // 5 hari dari sekarang
                'kontak_person' => 'Wayan Budiarta',
                'email' => 'recruitment@energiterbarukan.com',
                'no_telepon' => '0361-123789',
                'website' => 'www.energiterbarukan.com',
                'status' => 'buka'
            ]
        ];

        foreach ($lowongan as $data) {
            LowonganKerja::create($data);
        }
    }
}