<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriBuku;
use Illuminate\Support\Facades\DB;

class KategoriBukuSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key checks sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        KategoriBuku::query()->delete();
        
        $kategori = [
            [
                'nama_kategori' => 'Pendidikan', 
                'kode_kategori' => 'PEN',
                'deskripsi' => 'Buku-buku pendidikan dan pembelajaran', 
                'status' => true
            ],
            [
                'nama_kategori' => 'Fiksi', 
                'kode_kategori' => 'FIK',
                'deskripsi' => 'Novel, cerpen, dan karya fiksi lainnya', 
                'status' => true
            ],
            [
                'nama_kategori' => 'Sains & Teknologi', 
                'kode_kategori' => 'SNS',
                'deskripsi' => 'Buku ilmu pengetahuan dan teknologi', 
                'status' => true
            ],
            [
                'nama_kategori' => 'Sejarah & Biografi', 
                'kode_kategori' => 'SEJ',
                'deskripsi' => 'Buku sejarah dan biografi tokoh', 
                'status' => true
            ],
            [
                'nama_kategori' => 'Agama & Spiritual', 
                'kode_kategori' => 'AGM',
                'deskripsi' => 'Buku keagamaan dan spiritualitas', 
                'status' => true
            ],
            [
                'nama_kategori' => 'Bisnis & Ekonomi', 
                'kode_kategori' => 'BIS',
                'deskripsi' => 'Buku bisnis, ekonomi, dan manajemen', 
                'status' => true
            ],
            [
                'nama_kategori' => 'Seni & Budaya', 
                'kode_kategori' => 'SEN',
                'deskripsi' => 'Buku seni, budaya, dan kesenian', 
                'status' => true
            ],
        ];

        foreach ($kategori as $kat) {
            KategoriBuku::create($kat);
        }

        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('KategoriBuku seeder berhasil dijalankan! Total: ' . count($kategori) . ' kategori');
    }
}