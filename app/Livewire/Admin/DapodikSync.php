<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use App\Services\MockDapodikService;
use App\Models\DapodikSyncLog;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;

#[Layout('layouts.app-new')]
class DapodikSync extends Component
{
    public $syncStatus = 'idle'; // idle, testing, syncing, success, error, warning
    public $syncProgress = 0;
    public $currentStep = '';
    public $syncResults = [];
    public $lastSync = null;

    // Config Dapodik dengan data yang Anda berikan
    public $dapodikConfig = [
        'base_url' => 'https://api.dapodik.kemdikbud.go.id',
        'username' => 'rickysilaban384@gmail.com',
        'password' => 'silaban154',
        'npsn' => '43464275'
    ];

    // Sync data statistics
    public $syncData = [
        'siswa' => 0,
        'guru' => 0,
        'kelas' => 0,
    ];

    protected $dapodikService;

    public function boot()
    {
        $this->dapodikService = new MockDapodikService();
    }

    public function mount()
    {
        // Load existing config from database or env
        $this->loadConfig();
        $this->loadSyncData();
        $this->loadLastSync();
    }

    public function render()
    {
        $syncHistory = DapodikSyncLog::latest()->limit(5)->get();
        $syncStats = $this->dapodikService->getSyncStats();

        return view('livewire.admin.dapodik-sync', [
            'syncHistory' => $syncHistory,
            'syncStats' => $syncStats
        ]);
    }

    /**
     * Test koneksi ke Dapodik
     */
    public function testConnection()
    {
        $this->syncStatus = 'testing';
        $this->currentStep = 'Menguji koneksi ke server Dapodik...';
        
        try {
            $result = $this->dapodikService->testConnection($this->dapodikConfig);
            
            if ($result['success']) {
                session()->flash('success', $result['message']);
                $this->logSync('connection_test', 'success', 0, $result['message']);
            } else {
                session()->flash('error', $result['message']);
                $this->logSync('connection_test', 'error', 0, $result['message']);
            }
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
            $this->logSync('connection_test', 'error', 0, 'Error: ' . $e->getMessage());
        }
        
        $this->syncStatus = 'idle';
        $this->currentStep = '';
    }

    /**
     * Sinkronisasi semua data
     */
    public function startSync()
    {
        $this->syncStatus = 'syncing';
        $this->syncProgress = 0;
        $this->syncResults = [];

        try {
            // Validasi konfigurasi
            if (!$this->validateConfig()) {
                throw new \Exception('Konfigurasi Dapodik tidak valid. Silakan periksa username, password, dan NPSN.');
            }

            // Step 1: Sync Siswa
            $this->currentStep = 'Sinkronisasi Data Siswa';
            $this->syncProgress = 25;
            $resultSiswa = $this->dapodikService->syncSiswa($this->dapodikConfig);
            $this->syncResults['siswa'] = $resultSiswa;
            $this->logSync('siswa', $resultSiswa['success'] ? 'success' : 'warning', $resultSiswa['count'], $resultSiswa['message'], $resultSiswa);

            // Step 2: Sync Guru
            $this->currentStep = 'Sinkronisasi Data Guru';
            $this->syncProgress = 50;
            $resultGuru = $this->dapodikService->syncGuru($this->dapodikConfig);
            $this->syncResults['guru'] = $resultGuru;
            $this->logSync('guru', $resultGuru['success'] ? 'success' : 'warning', $resultGuru['count'], $resultGuru['message'], $resultGuru);

            // Step 3: Sync Kelas
            $this->currentStep = 'Sinkronisasi Data Kelas';
            $this->syncProgress = 75;
            $resultKelas = $this->dapodikService->syncKelas($this->dapodikConfig);
            $this->syncResults['kelas'] = $resultKelas;
            $this->logSync('kelas', $resultKelas['success'] ? 'success' : 'warning', $resultKelas['count'], $resultKelas['message'], $resultKelas);

            // Finalize
            $this->currentStep = 'Menyelesaikan Sinkronisasi';
            $this->syncProgress = 100;

            // Check results
            $allSuccess = !in_array(false, array_column($this->syncResults, 'success'));
            $totalCount = array_sum(array_column($this->syncResults, 'count'));
            
            if ($allSuccess) {
                $this->syncStatus = 'success';
                $message = "Sinkronisasi berhasil! {$totalCount} data telah diupdate.";
                session()->flash('success', $message);
                $this->logSync('full', 'success', $totalCount, $message, $this->syncResults);
            } else {
                $this->syncStatus = 'warning';
                $message = 'Sinkronisasi selesai dengan beberapa peringatan.';
                session()->flash('warning', $message);
                $this->logSync('full', 'warning', $totalCount, $message, $this->syncResults);
            }

            // Update sync data
            $this->loadSyncData();
            $this->loadLastSync();

        } catch (\Exception $e) {
            $this->syncStatus = 'error';
            $errorMessage = 'Sinkronisasi gagal: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
            $this->logSync('full', 'error', 0, $errorMessage, ['error' => $e->getMessage()]);
        }

        $this->currentStep = '';
        $this->syncProgress = 0;
    }

    /**
     * Sinkronisasi data siswa saja
     */
    public function syncSiswaOnly()
    {
        $this->syncStatus = 'syncing';
        $this->currentStep = 'Sinkronisasi Data Siswa';

        try {
            if (!$this->validateConfig()) {
                throw new \Exception('Konfigurasi Dapodik tidak valid.');
            }

            $result = $this->dapodikService->syncSiswa($this->dapodikConfig);
            
            $this->logSync('siswa', $result['success'] ? 'success' : 'error', $result['count'], $result['message'], $result);
            
            if ($result['success']) {
                $this->syncStatus = 'success';
                session()->flash('success', $result['message']);
            } else {
                $this->syncStatus = 'error';
                session()->flash('error', $result['message']);
            }

            $this->loadSyncData();
            $this->loadLastSync();

        } catch (\Exception $e) {
            $this->syncStatus = 'error';
            $errorMessage = 'Sync siswa gagal: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
            $this->logSync('siswa', 'error', 0, $errorMessage, ['error' => $e->getMessage()]);
        }

        $this->currentStep = '';
    }

    /**
     * Sinkronisasi data guru saja
     */
    public function syncGuruOnly()
    {
        $this->syncStatus = 'syncing';
        $this->currentStep = 'Sinkronisasi Data Guru';

        try {
            if (!$this->validateConfig()) {
                throw new \Exception('Konfigurasi Dapodik tidak valid.');
            }

            $result = $this->dapodikService->syncGuru($this->dapodikConfig);
            
            $this->logSync('guru', $result['success'] ? 'success' : 'error', $result['count'], $result['message'], $result);
            
            if ($result['success']) {
                $this->syncStatus = 'success';
                session()->flash('success', $result['message']);
            } else {
                $this->syncStatus = 'error';
                session()->flash('error', $result['message']);
            }

            $this->loadSyncData();
            $this->loadLastSync();

        } catch (\Exception $e) {
            $this->syncStatus = 'error';
            $errorMessage = 'Sync guru gagal: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
            $this->logSync('guru', 'error', 0, $errorMessage, ['error' => $e->getMessage()]);
        }

        $this->currentStep = '';
    }

    /**
     * Sinkronisasi data kelas saja
     */
    public function syncKelasOnly()
    {
        $this->syncStatus = 'syncing';
        $this->currentStep = 'Sinkronisasi Data Kelas';

        try {
            if (!$this->validateConfig()) {
                throw new \Exception('Konfigurasi Dapodik tidak valid.');
            }

            $result = $this->dapodikService->syncKelas($this->dapodikConfig);
            
            $this->logSync('kelas', $result['success'] ? 'success' : 'error', $result['count'], $result['message'], $result);
            
            if ($result['success']) {
                $this->syncStatus = 'success';
                session()->flash('success', $result['message']);
            } else {
                $this->syncStatus = 'error';
                session()->flash('error', $result['message']);
            }

            $this->loadSyncData();
            $this->loadLastSync();

        } catch (\Exception $e) {
            $this->syncStatus = 'error';
            $errorMessage = 'Sync kelas gagal: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
            $this->logSync('kelas', 'error', 0, $errorMessage, ['error' => $e->getMessage()]);
        }

        $this->currentStep = '';
    }

    /**
     * Simpan konfigurasi Dapodik
     */
    public function saveConfig()
    {
        // Validasi
        $this->validate([
            'dapodikConfig.username' => 'required|string|email',
            'dapodikConfig.password' => 'required|string|min:6',
            'dapodikConfig.npsn' => 'required|string|size:8',
            'dapodikConfig.base_url' => 'required|url'
        ]);

        try {
            // Simpan ke database atau config
            // Untuk sementara simpan di session
            session([
                'dapodik_config' => $this->dapodikConfig
            ]);

            // Juga simpan ke .env atau database settings
            $this->updateEnvConfig();

            session()->flash('success', 'Konfigurasi Dapodik berhasil disimpan.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan konfigurasi: ' . $e->getMessage());
        }
    }

    /**
     * Load konfigurasi
     */
    private function loadConfig()
    {
        $savedConfig = session('dapodik_config', [
            'base_url' => config('services.dapodik.base_url', 'https://api.dapodik.kemdikbud.go.id'),
            'username' => config('services.dapodik.username', 'rickysilaban384@gmail.com'),
            'password' => config('services.dapodik.password', 'silaban154'),
            'npsn' => config('services.dapodik.npsn', '43464275')
        ]);

        $this->dapodikConfig = array_merge($this->dapodikConfig, $savedConfig);
    }

    /**
     * Load data statistik
     */
    private function loadSyncData()
    {
        $this->syncData = [
            'siswa' => Siswa::count(),
            'guru' => Guru::count(),
            'kelas' => Kelas::count(),
        ];
    }

    /**
     * Load last sync info
     */
    private function loadLastSync()
    {
        $lastSync = DapodikSyncLog::where('status', 'success')
            ->latest()
            ->first();
        $this->lastSync = $lastSync ? $lastSync->sync_date : null;
    }

    /**
     * Reset sync status
     */
    public function resetSync()
    {
        $this->syncStatus = 'idle';
        $this->syncProgress = 0;
        $this->currentStep = '';
        $this->syncResults = [];
    }

    /**
     * Validasi konfigurasi
     */
    private function validateConfig()
    {
        return !empty($this->dapodikConfig['username']) && 
               !empty($this->dapodikConfig['password']) && 
               !empty($this->dapodikConfig['npsn']) &&
               strlen($this->dapodikConfig['npsn']) === 8 &&
               filter_var($this->dapodikConfig['username'], FILTER_VALIDATE_EMAIL);
    }

    /**
     * Log sync activity
     */
    private function logSync($type, $status, $count, $message, $details = null)
    {
        DapodikSyncLog::create([
            'sync_type' => $type,
            'status' => $status,
            'data_count' => $count,
            'message' => $message,
            'details' => $details,
            'sync_date' => now(),
        ]);
    }

    /**
     * Update environment configuration
     */
    private function updateEnvConfig()
    {
        // Untuk production, Anda bisa implementasi update .env file
        // atau simpan ke database settings
        // Ini contoh sederhana untuk development
        try {
            $envPath = base_path('.env');
            if (file_exists($envPath)) {
                $envContent = file_get_contents($envPath);
                
                // Update atau tambah konfigurasi
                $envUpdates = [
                    'DAPODIK_BASE_URL' => $this->dapodikConfig['base_url'],
                    'DAPODIK_USERNAME' => $this->dapodikConfig['username'],
                    'DAPODIK_PASSWORD' => $this->dapodikConfig['password'],
                    'DAPODIK_NPSN' => $this->dapodikConfig['npsn'],
                ];
                
                foreach ($envUpdates as $key => $value) {
                    if (str_contains($envContent, $key . '=')) {
                        $envContent = preg_replace(
                            "/^{$key}=.*/m",
                            "{$key}={$value}",
                            $envContent
                        );
                    } else {
                        $envContent .= "\n{$key}={$value}";
                    }
                }
                
                file_put_contents($envPath, $envContent);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to update env config: ' . $e->getMessage());
        }
    }

    /**
     * Rules untuk validasi
     */
    protected function rules()
    {
        return [
            'dapodikConfig.username' => 'required|string|email',
            'dapodikConfig.password' => 'required|string|min:6',
            'dapodikConfig.npsn' => 'required|string|size:8',
            'dapodikConfig.base_url' => 'required|url'
        ];
    }

    /**
     * Validasi real-time untuk config
     */
    public function updated($propertyName)
    {
        if (str_starts_with($propertyName, 'dapodikConfig.')) {
            $this->validateOnly($propertyName);
        }
    }

    /**
     * Clear all sync logs (untuk development)
     */
    public function clearLogs()
    {
        try {
            DapodikSyncLog::truncate();
            session()->flash('success', 'Riwayat sinkronisasi berhasil dibersihkan.');
            $this->loadLastSync();
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membersihkan riwayat: ' . $e->getMessage());
        }
    }
}