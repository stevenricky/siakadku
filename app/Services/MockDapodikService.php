<?php

namespace App\Services;

use App\Models\DapodikSyncLog;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use Exception;
use Illuminate\Support\Facades\Log;

class MockDapodikService
{
    private $isMockMode = true;

    public function __construct()
    {
        $this->isMockMode = config('app.env') === 'local' || config('app.env') === 'development';
    }

    /**
     * Test koneksi ke Dapodik (Mock)
     */
    public function testConnection($config = null)
    {
        // Simulasi delay seperti koneksi real
        sleep(2);
        
        return [
            'success' => true,
            'message' => 'Koneksi ke Dapodik berhasil (MOCK MODE - Development)',
            'data' => [
                'school_name' => 'SMA NEGERI 1 CONTOH',
                'npsn' => $config['npsn'] ?? '12345678',
                'status' => 'active',
                'alamat' => 'Jl. Contoh No. 123, Kota Contoh',
                'telepon' => '(021) 1234567'
            ]
        ];
    }

    /**
     * Sinkronisasi data siswa (Mock)
     */
    public function syncSiswa($config = null)
    {
        try {
            // Simulasi proses sync
            sleep(3);
            
            $mockData = $this->generateMockSiswa();
            $count = 0;

            foreach ($mockData as $siswa) {
                $count += $this->updateOrCreateSiswa($siswa);
            }

            // Log sync
            $this->logSync('siswa', 'success', $count, 'Sinkronisasi siswa berhasil (MOCK DATA)', $mockData);

            return [
                'success' => true,
                'count' => $count,
                'message' => "Berhasil sync {$count} data siswa (MOCK DATA - Development)"
            ];

        } catch (Exception $e) {
            $this->logSync('siswa', 'error', 0, $e->getMessage());
            return [
                'success' => false,
                'count' => 0,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Sinkronisasi data guru (Mock)
     */
    public function syncGuru($config = null)
    {
        try {
            sleep(2);
            
            $mockData = $this->generateMockGuru();
            $count = 0;

            foreach ($mockData as $guru) {
                $count += $this->updateOrCreateGuru($guru);
            }

            $this->logSync('guru', 'success', $count, 'Sinkronisasi guru berhasil (MOCK DATA)', $mockData);

            return [
                'success' => true,
                'count' => $count,
                'message' => "Berhasil sync {$count} data guru (MOCK DATA - Development)"
            ];

        } catch (Exception $e) {
            $this->logSync('guru', 'error', 0, $e->getMessage());
            return [
                'success' => false,
                'count' => 0,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Sinkronisasi data kelas (Mock)
     */
    public function syncKelas($config = null)
    {
        try {
            sleep(1);
            
            $mockData = $this->generateMockKelas();
            $count = 0;

            foreach ($mockData as $kelas) {
                $count += $this->updateOrCreateKelas($kelas);
            }

            $this->logSync('kelas', 'success', $count, 'Sinkronisasi kelas berhasil (MOCK DATA)', $mockData);

            return [
                'success' => true,
                'count' => $count,
                'message' => "Berhasil sync {$count} data kelas (MOCK DATA - Development)"
            ];

        } catch (Exception $e) {
            $this->logSync('kelas', 'error', 0, $e->getMessage());
            return [
                'success' => false,
                'count' => 0,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate mock data siswa
     */
    private function generateMockSiswa()
    {
        $siswas = [];
        $kelas = ['X IPA 1', 'X IPA 2', 'XI IPA 1', 'XI IPA 2', 'XII IPA 1', 'XII IPA 2'];
        
        for ($i = 1; $i <= 25; $i++) {
            $gender = $i % 2 == 0 ? 'L' : 'P';
            $siswas[] = [
                'nisn' => '00' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'nama' => $gender == 'L' ? 'Ahmad Student ' . $i : 'Siti Student ' . $i,
                'jenis_kelamin' => $gender,
                'tempat_lahir' => 'Kota Contoh',
                'tanggal_lahir' => '200' . (6 + ($i % 3)) . '-' . str_pad(($i % 12) + 1, 2, '0', STR_PAD_LEFT) . '-10',
                'agama' => ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha'][$i % 5],
                'alamat' => 'Jl. Student No. ' . $i . ', Kota Contoh',
                'nama_ibu_kandung' => $gender == 'L' ? 'Ibu Ahmad ' . $i : 'Ibu Siti ' . $i,
                'kelas' => $kelas[array_rand($kelas)],
                'status' => 'aktif'
            ];
        }
        
        return $siswas;
    }

    /**
     * Generate mock data guru
     */
    private function generateMockGuru()
    {
        $gurus = [];
        $mapel = ['Matematika', 'Fisika', 'Kimia', 'Biologi', 'Bahasa Indonesia', 'Bahasa Inggris', 'Sejarah', 'PKN', 'Seni Budaya', 'PJOK'];
        
        for ($i = 1; $i <= 12; $i++) {
            $gender = $i % 2 == 0 ? 'L' : 'P';
            $gurus[] = [
                'nik' => '1970' . str_pad($i, 12, '0', STR_PAD_LEFT),
                'nama' => $gender == 'L' ? 'Dr. Teacher ' . $i : 'Dra. Teacher ' . $i,
                'jenis_kelamin' => $gender,
                'tempat_lahir' => 'Kota Contoh',
                'tanggal_lahir' => '197' . ($i % 10) . '-' . str_pad(($i % 12) + 1, 2, '0', STR_PAD_LEFT) . '-15',
                'agama' => ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha'][$i % 5],
                'alamat' => 'Jl. Teacher No. ' . $i . ', Kota Contoh',
                'telepon' => '081234567' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'email' => 'teacher' . $i . '@example.sch.id',
                'jenis_ptk' => 'Guru Mapel',
                'mapel' => $mapel[$i % count($mapel)],
                'status' => 'aktif'
            ];
        }
        
        return $gurus;
    }

    /**
     * Generate mock data kelas
     */
    private function generateMockKelas()
    {
        return [
            [
                'id_rombel' => 'ROMBEL001',
                'nama_rombel' => 'X IPA 1',
                'tingkat_pendidikan' => 10,
                'jurusan' => 'IPA',
                'nama_guru' => 'Dr. Teacher 1',
                'kapasitas' => 36
            ],
            [
                'id_rombel' => 'ROMBEL002', 
                'nama_rombel' => 'X IPA 2',
                'tingkat_pendidikan' => 10,
                'jurusan' => 'IPA',
                'nama_guru' => 'Dra. Teacher 2',
                'kapasitas' => 38
            ],
            [
                'id_rombel' => 'ROMBEL003',
                'nama_rombel' => 'XI IPA 1',
                'tingkat_pendidikan' => 11,
                'jurusan' => 'IPA',
                'nama_guru' => 'Dr. Teacher 3',
                'kapasitas' => 35
            ],
            [
                'id_rombel' => 'ROMBEL004',
                'nama_rombel' => 'XI IPA 2',
                'tingkat_pendidikan' => 11,
                'jurusan' => 'IPA',
                'nama_guru' => 'Dra. Teacher 4',
                'kapasitas' => 37
            ],
            [
                'id_rombel' => 'ROMBEL005',
                'nama_rombel' => 'XII IPA 1',
                'tingkat_pendidikan' => 12,
                'jurusan' => 'IPA',
                'nama_guru' => 'Dr. Teacher 5',
                'kapasitas' => 36
            ],
            [
                'id_rombel' => 'ROMBEL006',
                'nama_rombel' => 'XII IPA 2',
                'tingkat_pendidikan' => 12,
                'jurusan' => 'IPA',
                'nama_guru' => 'Dra. Teacher 6',
                'kapasitas' => 34
            ]
        ];
    }

    /**
     * Update atau create data siswa
     */
    private function updateOrCreateSiswa($data)
    {
        try {
            Siswa::updateOrCreate(
                ['nisn' => $data['nisn']],
                [
                    'nama' => $data['nama'],
                    'jenis_kelamin' => $data['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan',
                    'tempat_lahir' => $data['tempat_lahir'],
                    'tanggal_lahir' => $data['tanggal_lahir'],
                    'agama' => $data['agama'],
                    'alamat' => $data['alamat'],
                    'nama_ibu' => $data['nama_ibu_kandung'],
                    'status' => 'Aktif'
                ]
            );
            return 1;
        } catch (Exception $e) {
            Log::error('Error sync siswa: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Update atau create data guru
     */
    private function updateOrCreateGuru($data)
    {
        try {
            Guru::updateOrCreate(
                ['nik' => $data['nik']],
                [
                    'nama' => $data['nama'],
                    'jenis_kelamin' => $data['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan',
                    'tempat_lahir' => $data['tempat_lahir'],
                    'tanggal_lahir' => $data['tanggal_lahir'],
                    'agama' => $data['agama'],
                    'alamat' => $data['alamat'],
                    'telepon' => $data['telepon'],
                    'email' => $data['email'],
                    'jabatan' => $data['jenis_ptk'],
                    'status' => 'Aktif'
                ]
            );
            return 1;
        } catch (Exception $e) {
            Log::error('Error sync guru: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Update atau create data kelas
     */
    private function updateOrCreateKelas($data)
    {
        try {
            Kelas::updateOrCreate(
                ['kode_kelas' => $data['id_rombel']],
                [
                    'nama_kelas' => $data['nama_rombel'],
                    'tingkat' => $data['tingkat_pendidikan'],
                    'jurusan' => $data['jurusan'] ?? 'Umum',
                    'wali_kelas' => $data['nama_guru'] ?? null,
                    'kapasitas' => $data['kapasitas'] ?? 40,
                    'status' => 'Aktif'
                ]
            );
            return 1;
        } catch (Exception $e) {
            Log::error('Error sync kelas: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Log sinkronisasi
     */
    private function logSync($type, $status, $count, $message, $details = null)
    {
        DapodikSyncLog::create([
            'sync_type' => $type,
            'status' => $status,
            'data_count' => $count,
            'message' => $message,
            'details' => $details,
            'sync_date' => now()
        ]);
    }

    /**
     * Get sync statistics
     */
    public function getSyncStats()
    {
        $totalSyncs = DapodikSyncLog::count();
        $successfulSyncs = DapodikSyncLog::where('status', 'success')->count();
        $lastSync = DapodikSyncLog::latest()->first();

        return [
            'total_syncs' => $totalSyncs,
            'success_rate' => $totalSyncs > 0 ? round(($successfulSyncs / $totalSyncs) * 100, 2) : 0,
            'last_sync' => $lastSync ? $lastSync->sync_date : null,
            'last_status' => $lastSync ? $lastSync->status : 'never'
        ];
    }
}