<?php

namespace Database\Seeders;

use App\Models\Surat;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SuratSeeder extends Seeder
{
    public function run(): void
    {
        $surats = [
            [
                'nomor_surat' => '421.5/102/SMA/XI/2024',
                'jenis_surat' => 'keluar',
                'perihal' => 'Undangan Rapat Orang Tua',
                'isi_singkat' => 'Undangan rapat orang tua/wali siswa kelas XII dalam rangka persiapan ujian nasional',
                'pengirim' => 'Kepala Sekolah',
                'penerima' => 'Orang Tua/Wali Siswa Kelas XII',
                'tanggal_surat' => now()->subDays(5),
                'tanggal_terima' => null,
                'disposisi_ke' => 'wakil_kepala_sekolah',
                'catatan_disposisi' => 'Mohon koordinasi dengan wali kelas untuk persiapan rapat',
                'status' => 'diproses',
                'created_by' => 1
            ],
            [
                'nomor_surat' => '005/DPK/SMA/XI/2024',
                'jenis_surat' => 'masuk',
                'perihal' => 'Undangan Workshop Pendidikan',
                'isi_singkat' => 'Undangan workshop peningkatan kompetensi guru dalam pembelajaran abad 21',
                'pengirim' => 'Dinas Pendidikan Kabupaten',
                'penerima' => 'Kepala SMA Negeri 1 Contoh',
                'tanggal_surat' => now()->subDays(3),
                'tanggal_terima' => now()->subDays(2),
                'disposisi_ke' => 'guru',
                'catatan_disposisi' => 'Diteruskan kepada guru yang bersangkutan',
                'status' => 'selesai',
                'created_by' => 1
            ],
            [
                'nomor_surat' => '421.5/103/SMA/XI/2024',
                'jenis_surat' => 'keluar',
                'perihal' => 'Permohonan Izin Studi Banding',
                'isi_singkat' => 'Permohonan izin studi banding siswa OSIS ke sekolah mitra',
                'pengirim' => 'Pembina OSIS',
                'penerima' => 'Kepala Dinas Pendidikan',
                'tanggal_surat' => now()->subDays(2),
                'tanggal_terima' => null,
                'disposisi_ke' => 'kepala_sekolah',
                'catatan_disposisi' => 'Menunggu persetujuan',
                'status' => 'baru',
                'created_by' => 1
            ],
            [
                'nomor_surat' => '008/BANK/SMA/XI/2024',
                'jenis_surat' => 'masuk',
                'perihal' => 'Penawaran Program Tabungan Siswa',
                'isi_singkat' => 'Penawaran kerjasama program tabungan pendidikan untuk siswa',
                'pengirim' => 'Bank Contoh Indonesia',
                'penerima' => 'Kepala SMA Negeri 1 Contoh',
                'tanggal_surat' => now()->subDays(1),
                'tanggal_terima' => now(),
                'disposisi_ke' => 'tata_usaha',
                'catatan_disposisi' => 'Untuk ditindaklanjuti bagian keuangan',
                'status' => 'diproses',
                'created_by' => 1
            ],
            [
                'nomor_surat' => '421.5/099/SMA/X/2024',
                'jenis_surat' => 'keluar',
                'perihal' => 'Laporan Kegiatan Porseni',
                'isi_singkat' => 'Laporan pelaksanaan kegiatan Pekan Olahraga dan Seni antar kelas',
                'pengirim' => 'Ketua Panitia Porseni',
                'penerima' => 'Kepala Dinas Pendidikan',
                'tanggal_surat' => now()->subDays(30),
                'tanggal_terima' => null,
                'disposisi_ke' => null,
                'catatan_disposisi' => null,
                'status' => 'arsip',
                'created_by' => 1
            ]
        ];

        foreach ($surats as $surat) {
            Surat::create($surat);
        }

        $this->command->info('Data surat berhasil ditambahkan!');
    }
}