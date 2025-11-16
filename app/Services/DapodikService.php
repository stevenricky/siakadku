<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\DapodikSyncLog;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use Exception;

class DapodikService
{
    private $baseUrl;
    private $username;
    private $password;
    private $npsn;
    private $token;

    public function __construct()
    {
        // Ambil dari config atau database settings
        $this->baseUrl = config('services.dapodik.base_url', 'https://api.dapodik.kemdikbud.go.id');
        $this->username = config('services.dapodik.username');
        $this->password = config('services.dapodik.password');
        $this->npsn = config('services.dapodik.npsn');
    }

    /**
     * Test koneksi ke API Dapodik
     */
    public function testConnection()
    {
        try {
            if (!$this->authenticate()) {
                return [
                    'success' => false,
                    'message' => 'Gagal melakukan autentikasi ke Dapodik'
                ];
            }

            // Test endpoint sederhana
            $response = Http::withToken($this->token)
                ->timeout(30)
                ->get($this->baseUrl . '/sekolah/' . $this->npsn . '/info');

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Koneksi ke Dapodik berhasil',
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal mengambil data dari Dapodik: ' . $response->status()
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error koneksi: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Sinkronisasi data siswa dari Dapodik
     */
    public function syncSiswa()
    {
        try {
            if (!$this->authenticate()) {
                throw new Exception('Autentikasi gagal');
            }

            $response = Http::withToken($this->token)
                ->timeout(60)
                ->get($this->baseUrl . '/sekolah/' . $this->npsn . '/peserta-didik');

            if (!$response->successful()) {
                throw new Exception('Gagal mengambil data siswa: ' . $response->status());
            }

            $siswaData = $response->json();
            $count = 0;

            foreach ($siswaData['data'] ?? [] as $siswa) {
                $count += $this->updateOrCreateSiswa($siswa);
            }

            // Log sync
            $this->logSync('siswa', 'success', $count, 'Sinkronisasi siswa berhasil', $siswaData);

            return [
                'success' => true,
                'count' => $count,
                'message' => "Berhasil sync {$count} data siswa"
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
     * Sinkronisasi data guru dari Dapodik
     */
    public function syncGuru()
    {
        try {
            if (!$this->authenticate()) {
                throw new Exception('Autentikasi gagal');
            }

            $response = Http::withToken($this->token)
                ->timeout(60)
                ->get($this->baseUrl . '/sekolah/' . $this->npsn . '/ptk');

            if (!$response->successful()) {
                throw new Exception('Gagal mengambil data guru: ' . $response->status());
            }

            $guruData = $response->json();
            $count = 0;

            foreach ($guruData['data'] ?? [] as $guru) {
                $count += $this->updateOrCreateGuru($guru);
            }

            $this->logSync('guru', 'success', $count, 'Sinkronisasi guru berhasil', $guruData);

            return [
                'success' => true,
                'count' => $count,
                'message' => "Berhasil sync {$count} data guru"
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
     * Sinkronisasi data kelas dari Dapodik
     */
    public function syncKelas()
    {
        try {
            if (!$this->authenticate()) {
                throw new Exception('Autentikasi gagal');
            }

            $response = Http::withToken($this->token)
                ->timeout(60)
                ->get($this->baseUrl . '/sekolah/' . $this->npsn . '/rombel');

            if (!$response->successful()) {
                throw new Exception('Gagal mengambil data kelas: ' . $response->status());
            }

            $kelasData = $response->json();
            $count = 0;

            foreach ($kelasData['data'] ?? [] as $kelas) {
                $count += $this->updateOrCreateKelas($kelas);
            }

            $this->logSync('kelas', 'success', $count, 'Sinkronisasi kelas berhasil', $kelasData);

            return [
                'success' => true,
                'count' => $count,
                'message' => "Berhasil sync {$count} data kelas"
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
     * Sinkronisasi semua data sekaligus
     */
    public function syncAll()
    {
        $results = [];
        
        $results['siswa'] = $this->syncSiswa();
        $results['guru'] = $this->syncGuru();
        $results['kelas'] = $this->syncKelas();

        // Log sync all
        $totalCount = array_sum(array_column($results, 'count'));
        $allSuccess = !in_array(false, array_column($results, 'success'));

        $this->logSync(
            'full', 
            $allSuccess ? 'success' : 'warning',
            $totalCount,
            'Sinkronisasi lengkap selesai',
            $results
        );

        return $results;
    }

    /**
     * Autentikasi ke API Dapodik
     */
    private function authenticate()
    {
        try {
            // Jika token masih valid, gunakan yang ada
            if ($this->token && $this->validateToken()) {
                return true;
            }

            $response = Http::timeout(30)
                ->post($this->baseUrl . '/auth/login', [
                    'username' => $this->username,
                    'password' => $this->password
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->token = $data['data']['token'] ?? $data['token'] ?? null;
                return !empty($this->token);
            }

            return false;

        } catch (Exception $e) {
            Log::error('Dapodik auth failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Validasi token
     */
    private function validateToken()
    {
        try {
            $response = Http::withToken($this->token)
                ->get($this->baseUrl . '/auth/validate');

            return $response->successful();
        } catch (Exception $e) {
            return false;
        }
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
     * Get sync history
     */
    public function getSyncHistory($limit = 10)
    {
        return DapodikSyncLog::latestFirst()->limit($limit)->get();
    }

    /**
     * Get sync statistics
     */
    public function getSyncStats()
    {
        $totalSyncs = DapodikSyncLog::count();
        $successfulSyncs = DapodikSyncLog::successful()->count();
        $lastSync = DapodikSyncLog::latestFirst()->first();

        return [
            'total_syncs' => $totalSyncs,
            'success_rate' => $totalSyncs > 0 ? round(($successfulSyncs / $totalSyncs) * 100, 2) : 0,
            'last_sync' => $lastSync ? $lastSync->sync_date : null,
            'last_status' => $lastSync ? $lastSync->status : 'never'
        ];
    }
}