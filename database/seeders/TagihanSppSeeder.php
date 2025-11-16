<?php

namespace Database\Seeders;

use App\Models\TagihanSpp;
use App\Models\Siswa;
use App\Models\BiayaSpp;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagihanSppSeeder extends Seeder
{
    public function run()
    {
        // Nonaktifkan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        TagihanSpp::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Ambil data siswa aktif
        $siswas = Siswa::where('status', 'aktif')->get();
        
        if ($siswas->isEmpty()) {
            $this->command->info('❌ Tidak ada siswa aktif! Skipping Tagihan SPP seeder.');
            return;
        }

        $this->command->info('🎯 Memulai seeding data Tagihan SPP...');

        $bulanList = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $currentYear = date('Y');
        $currentMonth = date('n');
        $totalData = 0;

        foreach ($siswas as $siswa) {
            // Cari biaya SPP yang sesuai dengan kelas siswa
            $biayaSpp = BiayaSpp::where('kelas_id', $siswa->kelas_id)
                ->where('status', 1)
                ->first();

            // Jika tidak ditemukan, cari biaya SPP default
            if (!$biayaSpp) {
                $biayaSpp = BiayaSpp::where('status', 1)->first();
                
                if (!$biayaSpp) {
                    $this->command->warn("⚠️ Tidak ada biaya SPP aktif untuk siswa {$siswa->nama}");
                    continue;
                }
            }

            // Buat tagihan untuk 3 bulan terakhir dan bulan berjalan
            $startMonth = max(0, $currentMonth - 4);
            $monthsToCreate = array_slice($bulanList, $startMonth, 4);

            foreach ($monthsToCreate as $bulan) {
                $bulanNumber = array_search($bulan, $bulanList) + 1;

                // Skip jika sudah ada data
                $exists = TagihanSpp::where('siswa_id', $siswa->id)
                    ->where('bulan', $bulan)
                    ->where('tahun', $currentYear)
                    ->exists();

                if ($exists) {
                    continue;
                }

                // Tentukan status: bulan lalu lunas, bulan ini 50/50
                if ($bulanNumber < $currentMonth) {
                    $status = 'lunas';
                } else {
                    $status = rand(0, 1) ? 'lunas' : 'belum_bayar';
                }

                // Hitung tanggal jatuh tempo (tanggal 10 bulan tersebut)
                $tanggalJatuhTempo = $currentYear . '-' . str_pad($bulanNumber, 2, '0', STR_PAD_LEFT) . '-10';

                // Tentukan tanggal bayar jika status lunas
                $tanggalBayar = null;
                if ($status === 'lunas') {
                    $tanggalBayar = $currentYear . '-' . str_pad($bulanNumber, 2, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1, 9), 2, '0', STR_PAD_LEFT);
                }

                // Tentukan denda jika status tertunggak
                $denda = 0;
                $isOverdue = strtotime($tanggalJatuhTempo) < time() && $status === 'belum_bayar';
                
                if ($isOverdue) {
                    $status = 'tertunggak';
                    $daysLate = floor((time() - strtotime($tanggalJatuhTempo)) / (60 * 60 * 24));
                    $denda = min(50000, $daysLate * 5000);
                }

                TagihanSpp::create([
                    'siswa_id' => $siswa->id,
                    'biaya_spp_id' => $biayaSpp->id,
                    'bulan' => $bulan,
                    'tahun' => $currentYear,
                    'jumlah_tagihan' => $biayaSpp->jumlah,
                    'denda' => $denda,
                    'status' => $status,
                    'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                    'tanggal_bayar' => $tanggalBayar,
                    'keterangan' => $this->getKeterangan($status),
                ]);

                $totalData++;
            }
        }

        $this->command->info("✅ Berhasil membuat {$totalData} data Tagihan SPP untuk {$siswas->count()} siswa aktif.");
    }

    /**
     * Generate keterangan berdasarkan status
     */
    private function getKeterangan($status): string
    {
        return match($status) {
            'lunas' => 'Pembayaran lunas tepat waktu',
            'belum_bayar' => 'Menunggu pembayaran',
            'tertunggak' => 'Terlambat bayar, dikenakan denda',
            default => 'Tagihan bulanan'
        };
    }
}