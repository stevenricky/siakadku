<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BarangInventaris;
use App\Models\KategoriInventaris;
use App\Models\Ruangan;
use Carbon\Carbon;

class BarangInventarisSeeder extends Seeder
{
    public function run()
    {
        // Pastikan kategori dan ruangan sudah ada
        $kategori = KategoriInventaris::all();
        $ruangan = Ruangan::all();

        if ($kategori->isEmpty() || $ruangan->isEmpty()) {
            $this->command->error('Pastikan KategoriInventarisSeeder dan RuanganSeeder sudah dijalankan!');
            return;
        }

        $barangData = [
            // Elektronik
            [
                'kode_barang' => 'INV-2024-001',
                'nama_barang' => 'Laptop Dell Latitude',
                'kategori_id' => $kategori->where('nama_kategori', 'Elektronik')->first()->id,
                'ruangan_id' => $ruangan->where('nama_ruangan', 'Lab Komputer 1')->first()->id ?? $ruangan->first()->id,
                'merk' => 'Dell',
                'tipe' => 'Latitude 5420',
                'jumlah' => 25,
                'satuan' => 'unit',
                'harga' => 12500000,
                'tanggal_pembelian' => '2024-01-15',
                'sumber_dana' => 'BOS',
                'spesifikasi' => 'Intel Core i5, 8GB RAM, 256GB SSD, 14 inch',
                'kondisi' => 'baik',
                'keterangan' => 'Untuk praktikum siswa'
            ],
            [
                'kode_barang' => 'INV-2024-002',
                'nama_barang' => 'Proyektor Epson',
                'kategori_id' => $kategori->where('nama_kategori', 'Elektronik')->first()->id,
                'ruangan_id' => $ruangan->where('nama_ruangan', 'Kelas X IPA 1')->first()->id ?? $ruangan->first()->id,
                'merk' => 'Epson',
                'tipe' => 'EB-X06',
                'jumlah' => 3,
                'satuan' => 'unit',
                'harga' => 4500000,
                'tanggal_pembelian' => '2024-02-20',
                'sumber_dana' => 'BOS',
                'spesifikasi' => '3000 lumens, XGA, HDMI',
                'kondisi' => 'baik',
                'keterangan' => 'Untuk pembelajaran'
            ],
            [
                'kode_barang' => 'INV-2024-003',
                'nama_barang' => 'Printer Canon',
                'kategori_id' => $kategori->where('nama_kategori', 'Elektronik')->first()->id,
                'ruangan_id' => $ruangan->where('nama_ruangan', 'Tata Usaha')->first()->id ?? $ruangan->first()->id,
                'merk' => 'Canon',
                'tipe' => 'PIXMA G2710',
                'jumlah' => 2,
                'satuan' => 'unit',
                'harga' => 3200000,
                'tanggal_pembelian' => '2024-03-10',
                'sumber_dana' => 'BOS',
                'spesifikasi' => 'All-in-One, WiFi, Tank System',
                'kondisi' => 'rusak_ringan',
                'keterangan' => 'Tinta sering macet'
            ],

            // Furniture
            [
                'kode_barang' => 'INV-2024-004',
                'nama_barang' => 'Meja Guru',
                'kategori_id' => $kategori->where('nama_kategori', 'Furniture')->first()->id,
                'ruangan_id' => $ruangan->where('nama_ruangan', 'Kelas X IPA 1')->first()->id ?? $ruangan->first()->id,
                'merk' => 'Olympic',
                'tipe' => 'MG-2024',
                'jumlah' => 1,
                'satuan' => 'unit',
                'harga' => 850000,
                'tanggal_pembelian' => '2024-01-10',
                'sumber_dana' => 'BOS',
                'spesifikasi' => 'Kayu jati, ukuran 120x60x75 cm',
                'kondisi' => 'baik',
                'keterangan' => 'Meja guru utama'
            ],
            [
                'kode_barang' => 'INV-2024-005',
                'nama_barang' => 'Kursi Siswa',
                'kategori_id' => $kategori->where('nama_kategori', 'Furniture')->first()->id,
                'ruangan_id' => $ruangan->where('nama_ruangan', 'Kelas X IPA 1')->first()->id ?? $ruangan->first()->id,
                'merk' => 'Olympic',
                'tipe' => 'KS-2024',
                'jumlah' => 36,
                'satuan' => 'unit',
                'harga' => 250000,
                'tanggal_pembelian' => '2024-01-10',
                'sumber_dana' => 'BOS',
                'spesifikasi' => 'Plastik injeksi, besi hollow',
                'kondisi' => 'rusak_ringan',
                'keterangan' => '2 unit perlu perbaikan'
            ],
            [
                'kode_barang' => 'INV-2024-006',
                'nama_barang' => 'Papan Tulis',
                'kategori_id' => $kategori->where('nama_kategori', 'Furniture')->first()->id,
                'ruangan_id' => $ruangan->where('nama_ruangan', 'Kelas X IPA 1')->first()->id ?? $ruangan->first()->id,
                'merk' => 'Whiteboard',
                'tipe' => 'WB-200',
                'jumlah' => 2,
                'satuan' => 'buah',
                'harga' => 1200000,
                'tanggal_pembelian' => '2024-01-10',
                'sumber_dana' => 'BOS',
                'spesifikasi' => 'Alumunium frame, magnetik',
                'kondisi' => 'baik',
                'keterangan' => 'Papan tulis putih'
            ],

            // Alat Tulis
            [
                'kode_barang' => 'INV-2024-007',
                'nama_barang' => 'Spidol Whiteboard',
                'kategori_id' => $kategori->where('nama_kategori', 'Alat Tulis')->first()->id,
                'ruangan_id' => $ruangan->where('nama_ruangan', 'Gudang')->first()->id ?? $ruangan->first()->id,
                'merk' => 'Snowman',
                'tipe' => 'WB-100',
                'jumlah' => 50,
                'satuan' => 'pack',
                'harga' => 250000,
                'tanggal_pembelian' => '2024-06-15',
                'sumber_dana' => 'BOS',
                'spesifikasi' => 'Isi 12 warna, tinta mudah dihapus',
                'kondisi' => 'baik',
                'keterangan' => 'Stok gudang'
            ],
            [
                'kode_barang' => 'INV-2024-008',
                'nama_barang' => 'Kertas A4',
                'kategori_id' => $kategori->where('nama_kategori', 'Alat Tulis')->first()->id,
                'ruangan_id' => $ruangan->where('nama_ruangan', 'Gudang')->first()->id ?? $ruangan->first()->id,
                'merk' => 'Paperline',
                'tipe' => 'A4-80gr',
                'jumlah' => 10,
                'satuan' => 'rim',
                'harga' => 450000,
                'tanggal_pembelian' => '2024-07-01',
                'sumber_dana' => 'BOS',
                'spesifikasi' => '80 gram, 500 lembar per rim',
                'kondisi' => 'baik',
                'keterangan' => 'Untuk keperluan administrasi'
            ],

            // Alat Laboratorium
            [
                'kode_barang' => 'INV-2024-009',
                'nama_barang' => 'Mikroskop Binokuler',
                'kategori_id' => $kategori->where('nama_kategori', 'Alat Laboratorium')->first()->id,
                'ruangan_id' => $ruangan->where('nama_ruangan', 'Lab Biologi')->first()->id ?? $ruangan->first()->id,
                'merk' => 'Olympus',
                'tipe' => 'CX23',
                'jumlah' => 10,
                'satuan' => 'unit',
                'harga' => 8500000,
                'tanggal_pembelian' => '2024-03-25',
                'sumber_dana' => 'BOS',
                'spesifikasi' => 'Perbesaran 40x-1000x, LED illumination',
                'kondisi' => 'baik',
                'keterangan' => 'Untuk praktikum biologi'
            ],
            [
                'kode_barang' => 'INV-2024-010',
                'nama_barang' => 'Set Alat Kimia',
                'kategori_id' => $kategori->where('nama_kategori', 'Alat Laboratorium')->first()->id,
                'ruangan_id' => $ruangan->where('nama_ruangan', 'Lab Kimia')->first()->id ?? $ruangan->first()->id,
                'merk' => 'Pyrex',
                'tipe' => 'Basic Set',
                'jumlah' => 15,
                'satuan' => 'set',
                'harga' => 1250000,
                'tanggal_pembelian' => '2024-04-10',
                'sumber_dana' => 'BOS',
                'spesifikasi' => 'Tabung reaksi, gelas ukur, pipet, dll',
                'kondisi' => 'rusak_berat',
                'keterangan' => '5 set pecah, perlu penggantian'
            ],

            // Alat Olahraga
            [
                'kode_barang' => 'INV-2024-011',
                'nama_barang' => 'Bola Basket',
                'kategori_id' => $kategori->where('nama_kategori', 'Alat Olahraga')->first()->id,
                'ruangan_id' => $ruangan->where('nama_ruangan', 'Gudang Olahraga')->first()->id ?? $ruangan->first()->id,
                'merk' => 'Mikasa',
                'tipe' => 'Basket Pro',
                'jumlah' => 8,
                'satuan' => 'buah',
                'harga' => 350000,
                'tanggal_pembelian' => '2024-02-15',
                'sumber_dana' => 'BOS',
                'spesifikasi' => 'Size 7, karet sintetis',
                'kondisi' => 'baik',
                'keterangan' => 'Untuk ekstrakurikuler basket'
            ],
            [
                'kode_barang' => 'INV-2024-012',
                'nama_barang' => 'Jaring Voli',
                'kategori_id' => $kategori->where('nama_kategori', 'Alat Olahraga')->first()->id,
                'ruangan_id' => $ruangan->where('nama_ruangan', 'Gudang Olahraga')->first()->id ?? $ruangan->first()->id,
                'merk' => 'Senoh',
                'tipe' => 'Volley Net',
                'jumlah' => 2,
                'satuan' => 'buah',
                'harga' => 650000,
                'tanggal_pembelian' => '2024-02-15',
                'sumber_dana' => 'BOS',
                'spesifikasi' => 'Tinggi 2.43m, nylon reinforced',
                'kondisi' => 'rusak_ringan',
                'keterangan' => 'Perlu penggantian tali'
            ],

            // Alat Musik
            [
                'kode_barang' => 'INV-2024-013',
                'nama_barang' => 'Gitar Akustik',
                'kategori_id' => $kategori->where('nama_kategori', 'Alat Musik')->first()->id,
                'ruangan_id' => $ruangan->where('nama_ruangan', 'Ruang Kesenian')->first()->id ?? $ruangan->first()->id,
                'merk' => 'Yamaha',
                'tipe' => 'F310',
                'jumlah' => 5,
                'satuan' => 'unit',
                'harga' => 1850000,
                'tanggal_pembelian' => '2024-05-20',
                'sumber_dana' => 'BOS',
                'spesifikasi' => 'Spruce top, nato back & sides',
                'kondisi' => 'baik',
                'keterangan' => 'Untuk ekstrakurikuler musik'
            ],
            [
                'kode_barang' => 'INV-2024-014',
                'nama_barang' => 'Drum Set',
                'kategori_id' => $kategori->where('nama_kategori', 'Alat Musik')->first()->id,
                'ruangan_id' => $ruangan->where('nama_ruangan', 'Ruang Kesenian')->first()->id ?? $ruangan->first()->id,
                'merk' => 'Pearl',
                'tipe' => 'Export Series',
                'jumlah' => 1,
                'satuan' => 'set',
                'harga' => 12500000,
                'tanggal_pembelian' => '2024-05-20',
                'sumber_dana' => 'BOS',
                'spesifikasi' => '5 piece, termasuk cymbal',
                'kondisi' => 'hilang',
                'keterangan' => 'Hilang saat peminjaman'
            ],

            // Kendaraan
            [
                'kode_barang' => 'INV-2024-015',
                'nama_barang' => 'Mobil Operasional',
                'kategori_id' => $kategori->where('nama_kategori', 'Kendaraan')->first()->id,
                'ruangan_id' => $ruangan->where('nama_ruangan', 'Parkiran')->first()->id ?? $ruangan->first()->id,
                'merk' => 'Toyota',
                'tipe' => 'Avanza 1.5',
                'jumlah' => 1,
                'satuan' => 'unit',
                'harga' => 215000000,
                'tanggal_pembelian' => '2024-01-05',
                'sumber_dana' => 'APBD',
                'spesifikasi' => '7-seater, manual transmission',
                'kondisi' => 'baik',
                'keterangan' => 'Untuk operasional sekolah'
            ]
        ];

        foreach ($barangData as $data) {
            BarangInventaris::create($data);
        }

        $this->command->info('Data barang inventaris berhasil ditambahkan!');
        $this->command->info('Total barang: ' . BarangInventaris::count());
        
        // Tampilkan statistik
        $this->command->info('Statistik Kondisi:');
        $this->command->info('- Baik: ' . BarangInventaris::where('kondisi', 'baik')->count());
        $this->command->info('- Rusak Ringan: ' . BarangInventaris::where('kondisi', 'rusak_ringan')->count());
        $this->command->info('- Rusak Berat: ' . BarangInventaris::where('kondisi', 'rusak_berat')->count());
        $this->command->info('- Hilang: ' . BarangInventaris::where('kondisi', 'hilang')->count());
    }
}