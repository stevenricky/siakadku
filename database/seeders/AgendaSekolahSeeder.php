<?php

namespace Database\Seeders;

use App\Models\AgendaSekolah;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AgendaSekolahSeeder extends Seeder
{
    public function run(): void
    {
        $agendas = [
            [
                'judul_agenda' => 'Ujian Semester Ganjil',
                'deskripsi' => 'Ujian semester untuk semua mata pelajaran kelas X, XI, dan XII',
                'tanggal_mulai' => now()->addDays(5),
                'tanggal_selesai' => now()->addDays(10),
                'waktu_mulai' => '08:00',
                'waktu_selesai' => '12:00',
                'tempat' => 'Ruang Kelas',
                'penanggung_jawab' => 'Wakil Kepala Sekolah Bidang Kurikulum',
                'sasaran' => 'seluruh_sekolah',
                'jenis_agenda' => 'akademik',
                'status' => 'terjadwal'
            ],
            [
                'judul_agenda' => 'Lomba Basket Antar Kelas',
                'deskripsi' => 'Final lomba basket tahunan antar kelas X, XI, dan XII',
                'tanggal_mulai' => now()->addDays(3),
                'tanggal_selesai' => now()->addDays(3),
                'waktu_mulai' => '14:00',
                'waktu_selesai' => '17:00',
                'tempat' => 'Lapangan Basket',
                'penanggung_jawab' => 'Guru Olahraga',
                'sasaran' => 'siswa',
                'jenis_agenda' => 'non_akademik',
                'status' => 'terjadwal'
            ],
            [
                'judul_agenda' => 'Workshop Kreativitas Siswa',
                'deskripsi' => 'Workshop pengembangan kreativitas dan inovasi siswa dalam bidang seni dan teknologi',
                'tanggal_mulai' => now()->addDays(7),
                'tanggal_selesai' => now()->addDays(7),
                'waktu_mulai' => '09:00',
                'waktu_selesai' => '12:00',
                'tempat' => 'Aula Sekolah',
                'penanggung_jawab' => 'Ketua OSIS',
                'sasaran' => 'siswa',
                'jenis_agenda' => 'sosial',
                'status' => 'terjadwal'
            ],
            [
                'judul_agenda' => 'Rapat Koordinasi Guru',
                'deskripsi' => 'Rapat koordinasi bulanan untuk membahas program akademik dan non-akademik',
                'tanggal_mulai' => now()->subDays(1),
                'tanggal_selesai' => now()->subDays(1),
                'waktu_mulai' => '13:00',
                'waktu_selesai' => '15:00',
                'tempat' => 'Ruang Guru',
                'penanggung_jawab' => 'Kepala Sekolah',
                'sasaran' => 'guru',
                'jenis_agenda' => 'akademik',
                'status' => 'selesai'
            ],
            [
                'judul_agenda' => 'Pertemuan Orang Tua Siswa Kelas XII',
                'deskripsi' => 'Pertemuan dengan orang tua siswa kelas XII untuk membahas persiapan ujian nasional',
                'tanggal_mulai' => now(),
                'tanggal_selesai' => now(),
                'waktu_mulai' => '10:00',
                'waktu_selesai' => '12:00',
                'tempat' => 'Aula Sekolah',
                'penanggung_jawab' => 'Wali Kelas XII',
                'sasaran' => 'kelas_xii',
                'jenis_agenda' => 'akademik',
                'status' => 'berlangsung'
            ],
            [
                'judul_agenda' => 'Bakti Sosial ke Panti Asuhan',
                'deskripsi' => 'Kegiatan bakti sosial siswa ke panti asuhan dalam rangka bulan peduli sosial',
                'tanggal_mulai' => now()->addDays(15),
                'tanggal_selesai' => now()->addDays(15),
                'waktu_mulai' => '08:00',
                'waktu_selesai' => '14:00',
                'tempat' => 'Panti Asuhan Sejahtera',
                'penanggung_jawab' => 'Pembina OSIS',
                'sasaran' => 'siswa',
                'jenis_agenda' => 'sosial',
                'status' => 'terjadwal'
            ]
        ];

        foreach ($agendas as $agenda) {
            AgendaSekolah::create($agenda);
        }

        $this->command->info('Agenda sekolah berhasil ditambahkan!');
    }
}