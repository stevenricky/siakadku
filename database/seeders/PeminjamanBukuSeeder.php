<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PeminjamanBuku;
use App\Models\Buku;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;

class PeminjamanBukuSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil data yang diperlukan
        $bukuList = Buku::where('stok', '>', 0)->get();
        $siswaList = Siswa::where('status', 'aktif')->get();
        $petugasList = User::whereIn('role', ['admin', 'petugas'])->get();

        if ($bukuList->isEmpty()) {
            $this->command->error('Data buku tidak ditemukan! Jalankan BukuSeeder terlebih dahulu.');
            return;
        }

        if ($siswaList->isEmpty()) {
            $this->command->error('Data siswa tidak ditemukan! Jalankan SiswaSeeder terlebih dahulu.');
            return;
        }

        if ($petugasList->isEmpty()) {
            $this->command->error('Data petugas tidak ditemukan! Pastikan ada user dengan role admin/petugas.');
            return;
        }

        $peminjamanData = [];

        // Reset counter untuk kode peminjaman
        $counter = 1;

        // Generate 40 data peminjaman dengan status berbeda-beda
        for ($i = 1; $i <= 40; $i++) {
            $buku = $bukuList->random();
            $siswa = $siswaList->random();
            $petugas = $petugasList->random();

            // Pastikan stok buku mencukupi
            if ($buku->stok <= $buku->dipinjam) {
                continue;
            }

            $tanggalPinjam = Carbon::now()->subDays(rand(1, 90));
            $tanggalKembali = $tanggalPinjam->copy()->addDays(14); // Masa pinjam 14 hari
            
            // Tentukan status secara proporsional
            $statusRand = rand(1, 100);
            if ($statusRand <= 35) {
                // 35% masih dipinjam
                $status = 'dipinjam';
                $tanggalDikembalikan = null;
                $denda = 0;
            } elseif ($statusRand <= 70) {
                // 35% sudah dikembalikan
                $status = 'dikembalikan';
                $tanggalDikembalikan = $tanggalKembali->copy()->subDays(rand(0, 5));
                $denda = 0;
            } elseif ($statusRand <= 85) {
                // 15% terlambat
                $status = 'terlambat';
                $tanggalDikembalikan = null;
                // Hitung denda untuk yang terlambat (Rp 2.000 per hari)
                $hariTerlambat = max(0, Carbon::now()->diffInDays($tanggalKembali));
                $denda = $hariTerlambat * 2000;
            } else {
                // 15% hilang
                $status = 'hilang';
                $tanggalDikembalikan = null;
                $denda = 50000; // Denda tetap untuk buku hilang
            }

            // Untuk yang sudah dikembalikan, pastikan tanggal dikembalikan realistis
            if ($status === 'dikembalikan') {
                $maxHariPinjam = $tanggalPinjam->copy()->addDays(21); // Maksimal 3 minggu
                $tanggalDikembalikan = $tanggalPinjam->copy()->addDays(rand(5, 21));
                if ($tanggalDikembalikan->gt($tanggalKembali)) {
                    // Jika terlambat mengembalikan, hitung denda
                    $hariTerlambat = $tanggalDikembalikan->diffInDays($tanggalKembali);
                    $denda = $hariTerlambat * 2000;
                }
            }

            $kodePeminjaman = 'PJN-' . $tanggalPinjam->format('Ymd') . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
            $counter++;

            $peminjamanData[] = [
                'kode_peminjaman' => $kodePeminjaman,
                'siswa_id' => $siswa->id,
                'buku_id' => $buku->id,
                'tanggal_pinjam' => $tanggalPinjam,
                'tanggal_kembali' => $tanggalKembali,
                'tanggal_dikembalikan' => $tanggalDikembalikan,
                'status' => $status,
                'denda' => $denda,
                'keterangan' => $this->generateKeterangan($status, $buku->judul),
                'petugas_id' => $petugas->id,
                'created_at' => $tanggalPinjam,
                'updated_at' => $tanggalDikembalikan ?? $tanggalPinjam,
            ];
        }

        // Insert data peminjaman
        foreach ($peminjamanData as $data) {
            try {
                PeminjamanBuku::create($data);
            } catch (\Exception $e) {
                $this->command->error("Gagal membuat peminjaman: " . $e->getMessage());
            }
        }

        // Update stok buku berdasarkan peminjaman aktif
        $this->updateStokBuku();

        $this->command->info('Seeder Peminjaman Buku berhasil dijalankan!');
        $this->command->info('Total data peminjaman: ' . count($peminjamanData));
        
        // Tampilkan statistik
        $this->showStatistics();
    }

    private function generateKeterangan(string $status, string $judulBuku): string
    {
        $keterangan = [
            'dipinjam' => [
                "Peminjaman buku \"{$judulBuku}\" untuk keperluan belajar",
                "Pinjam buku \"{$judulBuku}\" sebagai referensi tugas sekolah",
                "Peminjaman rutin buku \"{$judulBuku}\" dari perpustakaan",
                "Buku \"{$judulBuku}\" dipinjam untuk bacaan tambahan",
                "Pinjam buku \"{$judulBuku}\" untuk persiapan ujian"
            ],
            'dikembalikan' => [
                "Buku \"{$judulBuku}\" telah dikembalikan dalam kondisi baik",
                "Pengembalian buku \"{$judulBuku}\" tepat waktu",
                "Buku \"{$judulBuku}\" selesai dibaca dan dikembalikan",
                "Pengembalian buku \"{$judulBuku}\" setelah menyelesaikan tugas",
                "Buku \"{$judulBuku}\" dikembalikan dengan kondisi lengkap"
            ],
            'terlambat' => [
                "Peminjaman buku \"{$judulBuku}\" melebihi batas waktu",
                "Perpanjangan buku \"{$judulBuku}\" tidak dilakukan tepat waktu",
                "Terlambat mengembalikan buku \"{$judulBuku}\" karena kesibukan",
                "Buku \"{$judulBuku}\" belum dikembalikan setelah batas waktu",
                "Perlu diingatkan untuk pengembalian buku \"{$judulBuku}\""
            ],
            'hilang' => [
                "Buku \"{$judulBuku}\" tidak dapat ditemukan",
                "Laporan kehilangan buku \"{$judulBuku}\" dari peminjam",
                "Buku \"{$judulBuku}\" hilang selama masa peminjaman",
                "Tidak dapat mengembalikan buku \"{$judulBuku}\" karena hilang",
                "Proses pencarian buku \"{$judulBuku}\" belum membuahkan hasil"
            ]
        ];

        $pilihan = $keterangan[$status] ?? ['Tidak ada keterangan'];
        return $pilihan[array_rand($pilihan)];
    }

    private function updateStokBuku(): void
    {
        // Hitung jumlah buku yang sedang dipinjam per buku
        $peminjamanAktif = PeminjamanBuku::whereIn('status', ['dipinjam', 'terlambat'])
            ->selectRaw('buku_id, COUNT(*) as total_dipinjam')
            ->groupBy('buku_id')
            ->get();

        foreach ($peminjamanAktif as $p) {
            Buku::where('id', $p->buku_id)->update(['dipinjam' => $p->total_dipinjam]);
        }

        // Update status buku yang stoknya habis
        Buku::whereRaw('stok <= dipinjam')->update(['status' => 'dipinjam']);
        
        // Update status buku yang tersedia
        Buku::whereRaw('stok > dipinjam')->where('status', 'dipinjam')->update(['status' => 'tersedia']);

        $this->command->info('Stok buku berhasil diupdate berdasarkan data peminjaman!');
    }

    private function showStatistics(): void
    {
        $totalPeminjaman = PeminjamanBuku::count();
        $dipinjam = PeminjamanBuku::where('status', 'dipinjam')->count();
        $dikembalikan = PeminjamanBuku::where('status', 'dikembalikan')->count();
        $terlambat = PeminjamanBuku::where('status', 'terlambat')->count();
        $hilang = PeminjamanBuku::where('status', 'hilang')->count();
        $totalDenda = PeminjamanBuku::sum('denda');

        $this->command->info("\n=== STATISTIK PEMINJAMAN ===");
        $this->command->info("Total Peminjaman: " . $totalPeminjaman);
        $this->command->info("Sedang Dipinjam: " . $dipinjam);
        $this->command->info("Sudah Dikembalikan: " . $dikembalikan);
        $this->command->info("Terlambat: " . $terlambat);
        $this->command->info("Hilang: " . $hilang);
        $this->command->info("Total Denda: Rp " . number_format($totalDenda, 0, ',', '.'));
    }
}