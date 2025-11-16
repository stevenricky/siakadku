<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pemeliharaan;
use App\Models\BarangInventaris;
use App\Models\User;
use Carbon\Carbon;

class PemeliharaanSeeder extends Seeder
{
    public function run()
    {
        // Pastikan data barang dan user sudah ada
        $barangList = BarangInventaris::all();
        $users = User::all();

        if ($barangList->isEmpty()) {
            $this->command->error('Tidak ada data barang! Jalankan BarangInventarisSeeder terlebih dahulu.');
            return;
        }

        if ($users->isEmpty()) {
            $this->command->error('Tidak ada data user! Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        $pelaporId = $users->first()->id;

        // Ambil barang dengan cara yang lebih aman
        $laptop = $barangList->firstWhere('nama_barang', 'like', '%Laptop%') ?? $barangList->first();
        $proyektor = $barangList->firstWhere('nama_barang', 'like', '%Proyektor%') ?? $barangList->skip(1)->first();
        $printer = $barangList->firstWhere('nama_barang', 'like', '%Printer%') ?? $barangList->skip(2)->first();
        $kursi = $barangList->firstWhere('nama_barang', 'like', '%Kursi%') ?? $barangList->skip(3)->first();
        $meja = $barangList->firstWhere('nama_barang', 'like', '%Meja%') ?? $barangList->skip(4)->first();
        $jaring = $barangList->firstWhere('nama_barang', 'like', '%Jaring%') ?? $barangList->skip(5)->first();
        $mikroskop = $barangList->firstWhere('nama_barang', 'like', '%Mikroskop%') ?? $barangList->skip(6)->first();
        $alatKimia = $barangList->firstWhere('nama_barang', 'like', '%Alat Kimia%') ?? $barangList->skip(7)->first();
        $bola = $barangList->firstWhere('nama_barang', 'like', '%Bola%') ?? $barangList->skip(8)->first();
        $gitar = $barangList->firstWhere('nama_barang', 'like', '%Gitar%') ?? $barangList->skip(9)->first();
        $mobil = $barangList->firstWhere('nama_barang', 'like', '%Mobil%') ?? $barangList->skip(10)->first();
        $papan = $barangList->firstWhere('nama_barang', 'like', '%Papan%') ?? $barangList->skip(11)->first();

        $pemeliharaanData = [
            // Pemeliharaan dengan status dilaporkan
            [
                'barang_id' => $laptop->id,
                'tanggal_pemeliharaan' => '2024-11-15',
                'jenis_pemeliharaan' => 'perbaikan',
                'deskripsi_kerusakan' => 'Laptop tidak bisa menyala, kemungkinan masalah pada power supply',
                'tindakan' => 'Periksa kabel power dan adaptor, ganti adaptor jika diperlukan',
                'biaya' => 0,
                'teknisi' => null,
                'status' => 'dilaporkan',
                'catatan' => 'Dilaporkan oleh guru TIK',
                'pelapor_id' => $pelaporId,
            ],
            [
                'barang_id' => $proyektor->id,
                'tanggal_pemeliharaan' => '2024-11-14',
                'jenis_pemeliharaan' => 'perbaikan',
                'deskripsi_kerusakan' => 'Gambar proyektor buram dan berwarna kuning',
                'tindakan' => 'Bersihkan lensa dan ganti lampu proyektor',
                'biaya' => 0,
                'teknisi' => null,
                'status' => 'dilaporkan',
                'catatan' => 'Proyektor di ruang kelas',
                'pelapor_id' => $pelaporId,
            ],

            // Pemeliharaan dengan status diproses
            [
                'barang_id' => $printer->id,
                'tanggal_pemeliharaan' => '2024-11-12',
                'jenis_pemeliharaan' => 'perbaikan',
                'deskripsi_kerusakan' => 'Printer sering macet dan kertas tersangkut',
                'tindakan' => 'Bersihkan roller dan ganti cartridge',
                'biaya' => 350000,
                'teknisi' => 'Teknisi A',
                'status' => 'diproses',
                'catatan' => 'Menunggu sparepart dari vendor',
                'pelapor_id' => $pelaporId,
            ],
            [
                'barang_id' => $kursi->id,
                'tanggal_pemeliharaan' => '2024-11-10',
                'jenis_pemeliharaan' => 'perbaikan',
                'deskripsi_kerusakan' => 'Kaki kursi patah',
                'tindakan' => 'Las dan perbaiki kaki kursi',
                'biaya' => 150000,
                'teknisi' => 'Teknisi B',
                'status' => 'diproses',
                'catatan' => 'Perbaikan di workshop sekolah',
                'pelapor_id' => $pelaporId,
            ],

            // Pemeliharaan dengan status selesai
            [
                'barang_id' => $meja->id,
                'tanggal_pemeliharaan' => '2024-11-08',
                'jenis_pemeliharaan' => 'perbaikan',
                'deskripsi_kerusakan' => 'Laci meja macet tidak bisa dibuka',
                'tindakan' => 'Bersihkan rel laci dan beri pelumas',
                'biaya' => 75000,
                'teknisi' => 'Teknisi A',
                'status' => 'selesai',
                'catatan' => 'Sudah bisa berfungsi normal',
                'pelapor_id' => $pelaporId,
            ],
            [
                'barang_id' => $jaring->id,
                'tanggal_pemeliharaan' => '2024-11-05',
                'jenis_pemeliharaan' => 'pemeliharaan_rutin',
                'deskripsi_kerusakan' => 'Tali jaring putus dan longgar',
                'tindakan' => 'Ganti tali dan kencangkan jaring',
                'biaya' => 120000,
                'teknisi' => 'Teknisi C',
                'status' => 'selesai',
                'catatan' => 'Siap digunakan untuk latihan',
                'pelapor_id' => $pelaporId,
            ],
            [
                'barang_id' => $mikroskop->id,
                'tanggal_pemeliharaan' => '2024-11-03',
                'jenis_pemeliharaan' => 'kalibrasi',
                'deskripsi_kerusakan' => 'Perbesaran tidak akurat',
                'tindakan' => 'Kalibrasi lensa dan pembersihan optik',
                'biaya' => 450000,
                'teknisi' => 'Vendor Eksternal',
                'status' => 'selesai',
                'catatan' => 'Sudah dikalibrasi dan berfungsi optimal',
                'pelapor_id' => $pelaporId,
            ],

            // Pemeliharaan dengan status batal
            [
                'barang_id' => $alatKimia->id,
                'tanggal_pemeliharaan' => '2024-11-01',
                'jenis_pemeliharaan' => 'perbaikan',
                'deskripsi_kerusakan' => 'Gelas ukur pecah',
                'tindakan' => 'Penggantian gelas ukur',
                'biaya' => 0,
                'teknisi' => null,
                'status' => 'batal',
                'catatan' => 'Dibatalkan karena akan diganti dengan set baru',
                'pelapor_id' => $pelaporId,
            ],

            // Pemeliharaan rutin lainnya
            [
                'barang_id' => $bola->id,
                'tanggal_pemeliharaan' => '2024-10-28',
                'jenis_pemeliharaan' => 'pemeliharaan_rutin',
                'deskripsi_kerusakan' => 'Tekanan bola kurang optimal',
                'tindakan' => 'Isi ulang tekanan udara dan bersihkan permukaan',
                'biaya' => 0,
                'teknisi' => 'Teknisi B',
                'status' => 'selesai',
                'catatan' => 'Pemeliharaan rutin sebelum pertandingan',
                'pelapor_id' => $pelaporId,
            ],
            [
                'barang_id' => $gitar->id,
                'tanggal_pemeliharaan' => '2024-10-25',
                'jenis_pemeliharaan' => 'pemeliharaan_rutin',
                'deskripsi_kerusakan' => 'Senar berkarat dan perlu disetel',
                'tindakan' => 'Ganti senar dan setel nada',
                'biaya' => 85000,
                'teknisi' => 'Teknisi A',
                'status' => 'selesai',
                'catatan' => 'Siap digunakan untuk ekstrakurikuler',
                'pelapor_id' => $pelaporId,
            ],
        ];

        // Jika ada mobil, tambahkan data pemeliharaan mobil
        if ($mobil) {
            $pemeliharaanData[] = [
                'barang_id' => $mobil->id,
                'tanggal_pemeliharaan' => '2024-10-20',
                'jenis_pemeliharaan' => 'pemeliharaan_rutin',
                'deskripsi_kerusakan' => 'Service rutin 10.000 km',
                'tindakan' => 'Ganti oli, filter, dan check komponen',
                'biaya' => 1250000,
                'teknisi' => 'Bengkel Resmi',
                'status' => 'selesai',
                'catatan' => 'Service di bengkel resmi',
                'pelapor_id' => $pelaporId,
            ];
        }

        // Jika ada papan tulis, tambahkan data
        if ($papan) {
            $pemeliharaanData[] = [
                'barang_id' => $papan->id,
                'tanggal_pemeliharaan' => '2024-10-10',
                'jenis_pemeliharaan' => 'lainnya',
                'deskripsi_kerusakan' => 'Permukaan papan kotor dan sulit dibersihkan',
                'tindakan' => 'Pembersihan khusus dan perawatan permukaan',
                'biaya' => 95000,
                'teknisi' => 'Teknisi C',
                'status' => 'selesai',
                'catatan' => 'Permukaan sudah bersih dan mudah ditulis',
                'pelapor_id' => $pelaporId,
            ];
        }

        foreach ($pemeliharaanData as $data) {
            Pemeliharaan::create($data);
        }

        $this->command->info('Data pemeliharaan berhasil ditambahkan!');
        $this->command->info('Total pemeliharaan: ' . Pemeliharaan::count());
        
        // Tampilkan statistik
        $this->command->info('Statistik Status:');
        $this->command->info('- Dilaporkan: ' . Pemeliharaan::where('status', 'dilaporkan')->count());
        $this->command->info('- Diproses: ' . Pemeliharaan::where('status', 'diproses')->count());
        $this->command->info('- Selesai: ' . Pemeliharaan::where('status', 'selesai')->count());
        $this->command->info('- Batal: ' . Pemeliharaan::where('status', 'batal')->count());
        
        $totalBiaya = Pemeliharaan::sum('biaya');
        $this->command->info('Total biaya pemeliharaan: Rp ' . number_format($totalBiaya, 0, ',', '.'));
    }
}