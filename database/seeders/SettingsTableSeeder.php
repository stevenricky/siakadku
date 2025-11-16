<?php
// database/seeders/SettingsTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            [
                'key' => 'nama_sekolah',
                'value' => 'SMA Negeri 1 Example',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Nama lengkap sekolah'
            ],
            [
                'key' => 'alamat_sekolah',
                'value' => 'Jl. Contoh No. 123, Jakarta',
                'type' => 'textarea',
                'group' => 'general',
                'description' => 'Alamat lengkap sekolah'
            ],
            [
                'key' => 'telepon_sekolah',
                'value' => '(021) 1234567',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Nomor telepon sekolah'
            ],
            [
                'key' => 'email_sekolah',
                'value' => 'info@sman1example.sch.id',
                'type' => 'email',
                'group' => 'general',
                'description' => 'Email resmi sekolah'
            ],
            [
                'key' => 'website_sekolah',
                'value' => 'https://sman1example.sch.id',
                'type' => 'url',
                'group' => 'general',
                'description' => 'Website resmi sekolah'
            ],
            [
                'key' => 'tahun_ajaran_aktif',
                'value' => date('Y') . '/' . (date('Y') + 1),
                'type' => 'text',
                'group' => 'academic',
                'description' => 'Tahun ajaran yang sedang aktif'
            ],
            [
                'key' => 'kepala_sekolah',
                'value' => 'Dr. John Doe, M.Pd',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Nama kepala sekolah'
            ],
            [
                'key' => 'nip_kepala_sekolah',
                'value' => '196512341987011001',
                'type' => 'text',
                'group' => 'general',
                'description' => 'NIP kepala sekolah'
            ],
            [
                'key' => 'zona_waktu',
                'value' => 'Asia/Jakarta',
                'type' => 'select',
                'group' => 'system',
                'description' => 'Zona waktu sistem'
            ],
            [
                'key' => 'format_tanggal',
                'value' => 'd/m/Y',
                'type' => 'select',
                'group' => 'system',
                'description' => 'Format tanggal yang digunakan'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}