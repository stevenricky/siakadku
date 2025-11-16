<?php

namespace Database\Seeders;

use App\Models\Arsip;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ArsipSeeder extends Seeder
{
    public function run(): void
    {
        $arsips = [
            [
                'nama_dokumen' => 'Kurikulum 2024',
                'kategori_arsip' => 'akademik',
                'deskripsi' => 'Dokumen kurikulum terbaru untuk tahun ajaran 2024/2025',
                'nomor_dokumen' => 'KUR/2024/001',
                'tanggal_dokumen' => now()->subDays(30),
                'file_dokumen' => 'arsip/kurikulum-2024.pdf',
                'akses' => 'publik',
                'tahun_arsip' => 2024,
                'lokasi_fisik' => 'Rak A1',
                'keterangan' => 'Dokumen resmi dari dinas pendidikan',
                'created_by' => 1
            ],
            [
                'nama_dokumen' => 'Laporan Keuangan Semester 1 2024',
                'kategori_arsip' => 'keuangan',
                'deskripsi' => 'Laporan keuangan sekolah untuk semester pertama tahun 2024',
                'nomor_dokumen' => 'LK/2024/S1',
                'tanggal_dokumen' => now()->subDays(15),
                'file_dokumen' => 'arsip/laporan-keuangan-s1-2024.xlsx',
                'akses' => 'terbatas',
                'tahun_arsip' => 2024,
                'lokasi_fisik' => 'Rak B2',
                'keterangan' => 'Hanya dapat diakses oleh bendahara dan kepala sekolah',
                'created_by' => 1
            ],
            [
                'nama_dokumen' => 'Data Siswa Tahun Ajaran 2024/2025',
                'kategori_arsip' => 'kesiswaan',
                'deskripsi' => 'Database lengkap siswa aktif tahun ajaran 2024/2025',
                'nomor_dokumen' => 'DS/2024/001',
                'tanggal_dokumen' => now()->subDays(10),
                'file_dokumen' => 'arsip/data-siswa-2024.xlsx',
                'akses' => 'terbatas',
                'tahun_arsip' => 2024,
                'lokasi_fisik' => 'Rak C3',
                'keterangan' => 'Data sensitif, hanya untuk keperluan administrasi',
                'created_by' => 1
            ],
            [
                'nama_dokumen' => 'Rencana Kegiatan Sekolah 2024',
                'kategori_arsip' => 'administrasi',
                'deskripsi' => 'Rencana kegiatan sekolah untuk satu tahun ke depan',
                'nomor_dokumen' => 'RKS/2024/001',
                'tanggal_dokumen' => now()->subDays(5),
                'file_dokumen' => 'arsip/rencana-kegiatan-2024.docx',
                'akses' => 'publik',
                'tahun_arsip' => 2024,
                'lokasi_fisik' => 'Rak A2',
                'keterangan' => 'Dapat diakses oleh seluruh guru dan staf',
                'created_by' => 1
            ],
            [
                'nama_dokumen' => 'Laporan Bulanan Oktober 2024',
                'kategori_arsip' => 'laporan',
                'deskripsi' => 'Laporan kegiatan bulanan sekolah untuk bulan Oktober 2024',
                'nomor_dokumen' => 'LB/2024/10',
                'tanggal_dokumen' => now()->subDays(2),
                'file_dokumen' => 'arsip/laporan-oktober-2024.pdf',
                'akses' => 'publik',
                'tahun_arsip' => 2024,
                'lokasi_fisik' => 'Rak D1',
                'keterangan' => 'Laporan rutin bulanan',
                'created_by' => 1
            ]
        ];

        foreach ($arsips as $arsip) {
            Arsip::create($arsip);
        }

        $this->command->info('Data arsip berhasil ditambahkan!');
    }
}