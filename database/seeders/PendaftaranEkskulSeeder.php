<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PendaftaranEkskul;
use App\Models\Ekstrakurikuler;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;

class PendaftaranEkskulSeeder extends Seeder
{
    public function run()
    {
        // Pastikan data ekstrakurikuler, siswa, dan user sudah ada
        $ekskulList = Ekstrakurikuler::where('status', 1)->get();
        $siswaList = Siswa::where('status', 'aktif')->get();
        $users = User::all();

        if ($ekskulList->isEmpty()) {
            $this->command->error('Tidak ada data ekstrakurikuler! Jalankan EkstrakurikulerSeeder terlebih dahulu.');
            return;
        }

        if ($siswaList->isEmpty()) {
            $this->command->error('Tidak ada data siswa! Jalankan SiswaSeeder terlebih dahulu.');
            return;
        }

        if ($users->isEmpty()) {
            $this->command->error('Tidak ada data user! Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        $adminUser = $users->first();
        $tahunAjaran = date('Y');

        $pendaftaranData = [
            // Pendaftaran dengan status pending
            [
                'siswa_id' => $siswaList->where('nama', 'like', '%Ahmad%')->first()->id ?? $siswaList->first()->id,
                'ekstrakurikuler_id' => $ekskulList->where('nama_ekstra', 'like', '%Basket%')->first()->id ?? $ekskulList->first()->id,
                'tahun_ajaran' => $tahunAjaran,
                'status_pendaftaran' => 'pending',
                'alasan_ditolak' => null,
                'disetujui_oleh' => null,
                'disetujui_pada' => null,
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'siswa_id' => $siswaList->where('nama', 'like', '%Sari%')->first()->id ?? $siswaList->skip(1)->first()->id,
                'ekstrakurikuler_id' => $ekskulList->where('nama_ekstra', 'like', '%Musik%')->first()->id ?? $ekskulList->skip(1)->first()->id,
                'tahun_ajaran' => $tahunAjaran,
                'status_pendaftaran' => 'pending',
                'alasan_ditolak' => null,
                'disetujui_oleh' => null,
                'disetujui_pada' => null,
                'created_at' => Carbon::now()->subDays(1),
            ],

            // Pendaftaran dengan status diterima
            [
                'siswa_id' => $siswaList->where('nama', 'like', '%Budi%')->first()->id ?? $siswaList->skip(2)->first()->id,
                'ekstrakurikuler_id' => $ekskulList->where('nama_ekstra', 'like', '%Futsal%')->first()->id ?? $ekskulList->skip(2)->first()->id,
                'tahun_ajaran' => $tahunAjaran,
                'status_pendaftaran' => 'diterima',
                'alasan_ditolak' => null,
                'disetujui_oleh' => $adminUser->id,
                'disetujui_pada' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'siswa_id' => $siswaList->where('nama', 'like', '%Citra%')->first()->id ?? $siswaList->skip(3)->first()->id,
                'ekstrakurikuler_id' => $ekskulList->where('nama_ekstra', 'like', '%Pramuka%')->first()->id ?? $ekskulList->skip(3)->first()->id,
                'tahun_ajaran' => $tahunAjaran,
                'status_pendaftaran' => 'diterima',
                'alasan_ditolak' => null,
                'disetujui_oleh' => $adminUser->id,
                'disetujui_pada' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(4),
            ],
            [
                'siswa_id' => $siswaList->where('nama', 'like', '%Dewi%')->first()->id ?? $siswaList->skip(4)->first()->id,
                'ekstrakurikuler_id' => $ekskulList->where('nama_ekstra', 'like', '%Paskibra%')->first()->id ?? $ekskulList->skip(4)->first()->id,
                'tahun_ajaran' => $tahunAjaran,
                'status_pendaftaran' => 'diterima',
                'alasan_ditolak' => null,
                'disetujui_oleh' => $adminUser->id,
                'disetujui_pada' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(3),
            ],

            // Pendaftaran dengan status ditolak
            [
                'siswa_id' => $siswaList->where('nama', 'like', '%Eko%')->first()->id ?? $siswaList->skip(5)->first()->id,
                'ekstrakurikuler_id' => $ekskulList->where('nama_ekstra', 'like', '%Basket%')->first()->id ?? $ekskulList->first()->id,
                'tahun_ajaran' => $tahunAjaran,
                'status_pendaftaran' => 'ditolak',
                'alasan_ditolak' => 'Kuota sudah penuh untuk ekstrakurikuler ini',
                'disetujui_oleh' => $adminUser->id,
                'disetujui_pada' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(4),
            ],
            [
                'siswa_id' => $siswaList->where('nama', 'like', '%Fitri%')->first()->id ?? $siswaList->skip(6)->first()->id,
                'ekstrakurikuler_id' => $ekskulList->where('nama_ekstra', 'like', '%Musik%')->first()->id ?? $ekskulList->skip(1)->first()->id,
                'tahun_ajaran' => $tahunAjaran,
                'status_pendaftaran' => 'ditolak',
                'alasan_ditolak' => 'Tidak memenuhi persyaratan kemampuan dasar',
                'disetujui_oleh' => $adminUser->id,
                'disetujui_pada' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(3),
            ],

            // Pendaftaran untuk berbagai ekskul
            [
                'siswa_id' => $siswaList->skip(7)->first()->id,
                'ekstrakurikuler_id' => $ekskulList->where('nama_ekstra', 'like', '%Voli%')->first()->id ?? $ekskulList->skip(5)->first()->id,
                'tahun_ajaran' => $tahunAjaran,
                'status_pendaftaran' => 'diterima',
                'alasan_ditolak' => null,
                'disetujui_oleh' => $adminUser->id,
                'disetujui_pada' => Carbon::now()->subDays(4),
                'created_at' => Carbon::now()->subDays(6),
            ],
            [
                'siswa_id' => $siswaList->skip(8)->first()->id,
                'ekstrakurikuler_id' => $ekskulList->where('nama_ekstra', 'like', '%Silat%')->first()->id ?? $ekskulList->skip(6)->first()->id,
                'tahun_ajaran' => $tahunAjaran,
                'status_pendaftaran' => 'pending',
                'alasan_ditolak' => null,
                'disetujui_oleh' => null,
                'disetujui_pada' => null,
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'siswa_id' => $siswaList->skip(9)->first()->id,
                'ekstrakurikuler_id' => $ekskulList->where('nama_ekstra', 'like', '%Robotik%')->first()->id ?? $ekskulList->skip(7)->first()->id,
                'tahun_ajaran' => $tahunAjaran,
                'status_pendaftaran' => 'diterima',
                'alasan_ditolak' => null,
                'disetujui_oleh' => $adminUser->id,
                'disetujui_pada' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(4),
            ],
            [
                'siswa_id' => $siswaList->skip(10)->first()->id,
                'ekstrakurikuler_id' => $ekskulList->where('nama_ekstra', 'like', '%KIR%')->first()->id ?? $ekskulList->skip(8)->first()->id,
                'tahun_ajaran' => $tahunAjaran,
                'status_pendaftaran' => 'ditolak',
                'alasan_ditolak' => 'Nilai akademik tidak memenuhi syarat',
                'disetujui_oleh' => $adminUser->id,
                'disetujui_pada' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'siswa_id' => $siswaList->skip(11)->first()->id,
                'ekstrakurikuler_id' => $ekskulList->where('nama_ekstra', 'like', '%Paduan Suara%')->first()->id ?? $ekskulList->skip(9)->first()->id,
                'tahun_ajaran' => $tahunAjaran,
                'status_pendaftaran' => 'pending',
                'alasan_ditolak' => null,
                'disetujui_oleh' => null,
                'disetujui_pada' => null,
                'created_at' => Carbon::now()->subHours(6),
            ],
        ];

        foreach ($pendaftaranData as $data) {
            // Cek apakah sudah ada pendaftaran yang sama
            $existing = PendaftaranEkskul::where('siswa_id', $data['siswa_id'])
                ->where('ekstrakurikuler_id', $data['ekstrakurikuler_id'])
                ->where('tahun_ajaran', $data['tahun_ajaran'])
                ->exists();

            if (!$existing) {
                PendaftaranEkskul::create($data);
            }
        }

        $this->command->info('Data pendaftaran ekstrakurikuler berhasil ditambahkan!');
        $this->command->info('Total pendaftaran: ' . PendaftaranEkskul::count());
        
        // Tampilkan statistik
        $this->command->info('Statistik Status:');
        $this->command->info('- Pending: ' . PendaftaranEkskul::where('status_pendaftaran', 'pending')->count());
        $this->command->info('- Diterima: ' . PendaftaranEkskul::where('status_pendaftaran', 'diterima')->count());
        $this->command->info('- Ditolak: ' . PendaftaranEkskul::where('status_pendaftaran', 'ditolak')->count());
    }
}