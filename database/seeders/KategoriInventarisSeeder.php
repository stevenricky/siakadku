<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KategoriInventaris;

class KategoriInventarisSeeder extends Seeder
{
    public function run()
    {
        $kategori = [
            [
                'nama_kategori' => 'Elektronik',
                'kode_kategori' => 'ELK',
                'deskripsi' => 'Perangkat elektronik dan komputer',
                'status' => true
            ],
            [
                'nama_kategori' => 'Furniture',
                'kode_kategori' => 'FRN',
                'deskripsi' => 'Perabot dan furniture sekolah',
                'status' => true
            ],
            [
                'nama_kategori' => 'Alat Tulis',
                'kode_kategori' => 'ATK',
                'deskripsi' => 'Alat tulis kantor dan perlengkapan',
                'status' => true
            ],
            [
                'nama_kategori' => 'Alat Laboratorium',
                'kode_kategori' => 'LAB',
                'deskripsi' => 'Peralatan laboratorium IPA',
                'status' => true
            ],
            [
                'nama_kategori' => 'Alat Olahraga',
                'kode_kategori' => 'OLG',
                'deskripsi' => 'Peralatan olahraga',
                'status' => true
            ],
            [
                'nama_kategori' => 'Alat Musik',
                'kode_kategori' => 'MSK',
                'deskripsi' => 'Peralatan musik dan kesenian',
                'status' => true
            ],
            [
                'nama_kategori' => 'Kendaraan',
                'kode_kategori' => 'KND',
                'deskripsi' => 'Kendaraan operasional sekolah',
                'status' => true
            ],
            [
                'nama_kategori' => 'Lainnya',
                'kode_kategori' => 'OTH',
                'deskripsi' => 'Kategori lainnya',
                'status' => true
            ],
        ];

        foreach ($kategori as $item) {
            KategoriInventaris::create($item);
        }

        $this->command->info('Data kategori inventaris berhasil ditambahkan!');
    }
}