<?php

namespace Database\Seeders;

use App\Models\Pemeliharaan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // 0. Role dan Permission harus dijalankan pertama
            RolePermissionSeeder::class,
            
            // 1. User dan role dasar
            UserSeeder::class,
            
            // 2. Data master
            TahunAjaranSeeder::class,
            GuruSeeder::class,
            MapelSeeder::class,
            KelasSeeder::class,
            
            // 3. Data master tambahan
            SemesterSeeder::class,
            RuanganSeeder::class,
            EkstrakurikulerSeeder::class,
            KategoriBiayaSeeder::class, 
            
            // 4. Data transaksi
            SiswaSeeder::class,
            JadwalSeeder::class,
            NilaiSeeder::class,
            AbsensiSeeder::class,
            
            // 5. Data keuangan 
            BiayaSppSeeder::class,
            SppSeeder::class,
            TagihanSppSeeder::class, 
            PembayaranSppSeeder::class,

            // 6. Data perpustakaan
            BukuSeeder::class,
            KategoriBukuSeeder::class,
            PeminjamanBukuSeeder::class,

            // 7. Data inventaris
            KategoriInventarisSeeder::class,
            BarangInventarisSeeder::class,
            PemeliharaanSeeder::class,


            // 8. Data ekstrakurikuler
            PendaftaranEkskulSeeder::class,
            PrestasiSeeder::class,
            KegiatanEkskulSeeder::class,

            // 9. Layanan Bk
            LayananBkSeeder::class,
            KonselingSeeder::class,
            CatatanBkSeeder::class,

            // 10. Alumni & Karrier
            AlumniSeeder::class,
            TracerStudySeeder::class,
            BeasiswaSeeder::class,

            // 11. Sistem Pendukung
            AgendaSekolahSeeder::class,
            SuratSeeder::class,
            ArsipSeeder::class,


            // 12. Guru Perencanaan
            GuruMapelSeeder::class,
            RppSeeder::class,
            MateriSeeder::class,
            TugasSeeder::class,


            // 13. Siswa Lowongan kerja
            LowonganKerjaSeeder::class,
            ArtikelKarirSeeder::class,

            // 14. Interaksi Siswa
            ForumSeeder::class,


            // 6. Data komunikasi
            PengumumanSeeder::class,
        ]);
    }
}