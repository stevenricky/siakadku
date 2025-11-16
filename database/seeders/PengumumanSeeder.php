<?php

namespace Database\Seeders;

use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengumumanSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $pengumumen = [
            [
                'judul' => 'Penerimaan Siswa Baru Tahun Ajaran 2024/2025',
                'isi' => 'Diberitahukan kepada seluruh calon siswa bahwa pendaftaran siswa baru untuk tahun ajaran 2024/2025 akan dibuka mulai tanggal 1 Juni 2024. Persyaratan dapat diunduh di website sekolah.',
                'target' => 'semua',
                'user_id' => $admin->id,
                'is_urgent' => true,
                'tanggal_berlaku' => '2024-06-30 23:59:59',
            ],
            [
                'judul' => 'Ujian Semester Ganjil',
                'isi' => 'Ujian Semester Ganjil akan dilaksanakan pada tanggal 15-20 Desember 2024. Seluruh siswa diharapkan mempersiapkan diri dengan baik.',
                'target' => 'siswa',
                'user_id' => $admin->id,
                'is_urgent' => false,
                'tanggal_berlaku' => '2024-12-20 23:59:59',
            ],
            [
                'judul' => 'Rapat Dewan Guru',
                'isi' => 'Akan diadakan rapat dewan guru pada hari Jumat, 10 November 2024 pukul 13.00 di ruang guru. Kehadiran diwajibkan.',
                'target' => 'guru',
                'user_id' => $admin->id,
                'is_urgent' => true,
                'tanggal_berlaku' => '2024-11-10 23:59:59',
            ],
            [
                'judul' => 'Perbaikan Nilai',
                'isi' => 'Bagi siswa yang ingin melakukan perbaikan nilai, dapat mendaftar di bagian administrasi paling lambat tanggal 25 November 2024.',
                'target' => 'siswa',
                'user_id' => $admin->id,
                'is_urgent' => false,
                'tanggal_berlaku' => '2024-11-25 23:59:59',
            ],
            [
                'judul' => 'Libur Semester',
                'isi' => 'Libur semester ganjil akan dimulai tanggal 21 Desember 2024 sampai dengan 5 Januari 2025. Selamat berlibur!',
                'target' => 'semua',
                'user_id' => $admin->id,
                'is_urgent' => false,
                'tanggal_berlaku' => '2025-01-05 23:59:59',
            ],
        ];

        foreach ($pengumumen as $pengumuman) {
            Pengumuman::create($pengumuman);
        }
    }
}