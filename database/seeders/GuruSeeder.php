<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $gurus = [
            // Wali Kelas untuk Kelas 10
            [
                'user_id' => 2,
                'nip' => '196512151990031001',
                'nama_lengkap' => 'Dr. Maruli Tua Sitorus, M.Pd',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '1965-12-15',
                'alamat' => 'Jl. Sisingamangaraja No. 123, Medan',
                'no_telp' => '081234567890',
                'status' => 'aktif',
            ],
            [
                'user_id' => 3,
                'nip' => '196803201992031002',
                'nama_lengkap' => 'Drs. Binsar Situmorang',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Pematang Siantar',
                'tanggal_lahir' => '1968-03-20',
                'alamat' => 'Jl. Diponegoro No. 45, Pematang Siantar',
                'no_telp' => '081234567891',
                'status' => 'aktif',
            ],
            [
                'user_id' => 4,
                'nip' => '197506101995032003',
                'nama_lengkap' => 'Sinta Ria Hutagalung, S.Pd',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Tebing Tinggi',
                'tanggal_lahir' => '1975-06-10',
                'alamat' => 'Jl. Sutomo No. 67, Tebing Tinggi',
                'no_telp' => '081234567892',
                'status' => 'aktif',
            ],
            [
                'user_id' => 5,
                'nip' => '198002152000032004',
                'nama_lengkap' => 'Maya Sarah Simbolon, M.Pd',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Balige',
                'tanggal_lahir' => '1980-02-15',
                'alamat' => 'Jl. Pagar Batu No. 89, Balige',
                'no_telp' => '081234567893',
                'status' => 'aktif',
            ],
            [
                'user_id' => 6,
                'nip' => '198510252005031005',
                'nama_lengkap' => 'Rizky Pratama Nainggolan, S.Pd',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Tarutung',
                'tanggal_lahir' => '1985-10-25',
                'alamat' => 'Jl. Sibolga No. 12, Tarutung',
                'no_telp' => '081234567894',
                'status' => 'aktif',
            ],
            [
                'user_id' => 7,
                'nip' => '197812121998031006',
                'nama_lengkap' => 'Hotman Paris Siahaan, S.Pd',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Sidikalang',
                'tanggal_lahir' => '1978-12-12',
                'alamat' => 'Jl. Karya No. 34, Sidikalang',
                'no_telp' => '081234567895',
                'status' => 'aktif',
            ],
            [
                'user_id' => 8,
                'nip' => '198304181999032007',
                'nama_lengkap' => 'Debora Rotua Sinaga, M.Pd',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Dolok Sanggul',
                'tanggal_lahir' => '1983-04-18',
                'alamat' => 'Jl. Merdeka No. 56, Dolok Sanggul',
                'no_telp' => '081234567896',
                'status' => 'aktif',
            ],
            [
                'user_id' => 9,
                'nip' => '197911052001032008',
                'nama_lengkap' => 'Lina Marlina Pardede, S.Pd',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Porsea',
                'tanggal_lahir' => '1979-11-05',
                'alamat' => 'Jl. Asahan No. 78, Porsea',
                'no_telp' => '081234567897',
                'status' => 'aktif',
            ],
            [
                'user_id' => 10,
                'nip' => '198206252003031009',
                'nama_lengkap' => 'Jhonni Marbun, S.Pd',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Sibolga',
                'tanggal_lahir' => '1982-06-25',
                'alamat' => 'Jl. Barus No. 23, Sibolga',
                'no_telp' => '081234567898',
                'status' => 'aktif',
            ],
            [
                'user_id' => 11,
                'nip' => '198709152006032010',
                'nama_lengkap' => 'Rina Julianti Sihombing, M.Pd',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Pangururan',
                'tanggal_lahir' => '1987-09-15',
                'alamat' => 'Jl. Samosir No. 45, Pangururan',
                'no_telp' => '081234567899',
                'status' => 'aktif',
            ],
            [
                'user_id' => 12,
                'nip' => '198412302004031011',
                'nama_lengkap' => 'Roy Martua Simanjuntak, S.Pd',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Kabanjahe',
                'tanggal_lahir' => '1984-12-30',
                'alamat' => 'Jl. Gundaling No. 67, Kabanjahe',
                'no_telp' => '081234567800',
                'status' => 'aktif',
            ],
            [
                'user_id' => 13,
                'nip' => '197703221997031012',
                'nama_lengkap' => 'Erika Margaretha Sihotang, S.Pd',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Parapat',
                'tanggal_lahir' => '1977-03-22',
                'alamat' => 'Jl. Danau Toba No. 89, Parapat',
                'no_telp' => '081234567801',
                'status' => 'aktif',
            ],

            // Kepala Laboratorium
            [
                'user_id' => 14,
                'nip' => '198101152002031013',
                'nama_lengkap' => 'Dr. Robert Manurung, M.Si',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '1981-01-15',
                'alamat' => 'Jl. Pendidikan No. 34, Medan',
                'no_telp' => '081234567802',
                'status' => 'aktif',
            ],
            [
                'user_id' => 15,
                'nip' => '198508102007031014',
                'nama_lengkap' => 'Diana Rosdiana Siregar, M.Si',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Pematang Siantar',
                'tanggal_lahir' => '1985-08-10',
                'alamat' => 'Jl. Ilmiah No. 56, Pematang Siantar',
                'no_telp' => '081234567803',
                'status' => 'aktif',
            ],
            [
                'user_id' => 16,
                'nip' => '198210052005031015',
                'nama_lengkap' => 'Ferdinand Tampubolon, S.Si',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Balige',
                'tanggal_lahir' => '1982-10-05',
                'alamat' => 'Jl. Teknologi No. 78, Balige',
                'no_telp' => '081234567804',
                'status' => 'aktif',
            ],
            [
                'user_id' => 17,
                'nip' => '198912152010031016',
                'nama_lengkap' => 'Sarah Debora Panjaitan, S.Kom',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Tarutung',
                'tanggal_lahir' => '1989-12-15',
                'alamat' => 'Jl. Digital No. 12, Tarutung',
                'no_telp' => '081234567805',
                'status' => 'aktif',
            ],
        ];

        foreach ($gurus as $guru) {
            Guru::firstOrCreate(
                ['nip' => $guru['nip']], // Cari berdasarkan NIP
                $guru
            );
        }

        $this->command->info('Seeder guru (wali kelas & kepala lab) berhasil dijalankan!');
        $this->command->info('Total guru: ' . Guru::count());
    }
}