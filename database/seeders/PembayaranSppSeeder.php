<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PembayaranSpp;
use App\Models\TagihanSpp;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;

class PembayaranSppSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil siswa aktif
        $siswaAktif = Siswa::aktif()->with('kelas')->get();
        $admin = User::where('role', 'admin')->first();

        $metodeBayar = ['tunai', 'transfer', 'qris'];
        $statusVerifikasi = ['pending', 'diterima', 'ditolak'];

        foreach ($siswaAktif as $siswa) {
            // Buat beberapa pembayaran untuk setiap siswa
            $tagihanSiswa = TagihanSpp::where('siswa_id', $siswa->id)
                ->where('status', 'belum_bayar')
                ->get();

            foreach ($tagihanSiswa->take(3) as $tagihan) {
                $status = $statusVerifikasi[array_rand($statusVerifikasi)];
                
                PembayaranSpp::create([
                    'tagihan_spp_id' => $tagihan->id,
                    'siswa_id' => $siswa->id,
                    'jumlah_bayar' => $tagihan->jumlah - rand(0, 50000),
                    'tanggal_bayar' => Carbon::now()->subDays(rand(1, 90)),
                    'metode_bayar' => $metodeBayar[array_rand($metodeBayar)],
                    'bukti_bayar' => rand(0, 1) ? 'bukti/bukti-' . rand(1, 5) . '.jpg' : null,
                    'status_verifikasi' => $status,
                    'catatan' => $status === 'ditolak' ? 'Bukti transfer tidak jelas' : null,
                    'verified_by' => $status !== 'pending' ? $admin->id : null,
                    'verified_at' => $status !== 'pending' ? Carbon::now() : null,
                ]);

                // Update status tagihan jika pembayaran diterima
                if ($status === 'diterima') {
                    $tagihan->update(['status' => 'lunas']);
                }
            }
        }

        $this->command->info('Seeder Pembayaran SPP berhasil dijalankan!');
        $this->command->info('Total Pembayaran: ' . PembayaranSpp::count());
        $this->command->info('Siswa dengan pembayaran: ' . $siswaAktif->count());
    }
}