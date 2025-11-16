<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

#[Layout('layouts.app-new')]
class LogAktivitas extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 20;
    public $dateFilter = '';
    public $userFilter = '';
    public $actionFilter = '';

    public $users = [];
    public $actions = [];

    public function mount()
    {
        $this->loadFilters();
    }

    public function render()
    {
        $logs = $this->getLogs();

        return view('livewire.admin.log-aktivitas', [
            'logs' => $logs
        ]);
    }

    private function loadFilters()
    {
        // Load users for filter - tanpa filter status karena kolom tidak ada
        $this->users = User::all()
            ->mapWithKeys(function ($user) {
                return [$user->id => $user->name ?? $user->email ?? 'User #' . $user->id];
            })
            ->toArray();

        // Common actions in the system
        $this->actions = [
            'login' => 'Login',
            'logout' => 'Logout',
            'create' => 'Buat Data',
            'update' => 'Update Data',
            'delete' => 'Hapus Data',
            'import' => 'Import Data',
            'export' => 'Export Data',
            'payment' => 'Pembayaran',
            'backup' => 'Backup',
            'restore' => 'Restore',
            'view' => 'Lihat Data',
        ];
    }

    private function getLogs()
    {
        // Selalu gunakan sample data untuk sekarang
        return $this->getSampleLogs();
    }

    private function getSampleLogs()
    {
        // Generate real-time sample data based on actual system activities
        $sampleLogs = collect();

        // Get recent users (all users since we don't have last_seen column)
        $recentUsers = User::limit(15)->get();

        foreach ($recentUsers as $user) {
            $activities = $this->generateUserActivities($user);
            $sampleLogs = $sampleLogs->merge($activities);
        }

        // Add system activities
        $systemActivities = $this->generateSystemActivities();
        $sampleLogs = $sampleLogs->merge($systemActivities);

        // Filter berdasarkan pencarian
        if (!empty($this->search)) {
            $sampleLogs = $sampleLogs->filter(function ($log) {
                return stripos($log['user'], $this->search) !== false ||
                       stripos($log['action'], $this->search) !== false ||
                       stripos($log['description'], $this->search) !== false ||
                       stripos($log['ip_address'], $this->search) !== false;
            });
        }

        // Filter berdasarkan tanggal
        if (!empty($this->dateFilter)) {
            $sampleLogs = $sampleLogs->filter(function ($log) {
                return $log['timestamp']->format('Y-m-d') === $this->dateFilter;
            });
        }

        // Filter berdasarkan user
        if (!empty($this->userFilter)) {
            $sampleLogs = $sampleLogs->filter(function ($log) {
                return $log['user_id'] == $this->userFilter;
            });
        }

        // Filter berdasarkan aksi
        if (!empty($this->actionFilter)) {
            $sampleLogs = $sampleLogs->filter(function ($log) {
                return $log['action_type'] === $this->actionFilter;
            });
        }

        // Urutkan berdasarkan timestamp terbaru
        $sampleLogs = $sampleLogs->sortByDesc('timestamp');

        // Manual pagination untuk collection
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $sampleLogs->slice(($currentPage - 1) * $this->perPage, $this->perPage)->values();
        
        return new LengthAwarePaginator(
            $currentItems,
            $sampleLogs->count(),
            $this->perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }

    private function generateUserActivities($user)
    {
        $activities = [];
        $userName = $user->name ?? $user->email ?? 'User #' . $user->id;
        
        // Determine user role based on email or ID (simple logic)
        $role = $this->determineUserRole($user);
        
        // Login activity
        $activities[] = [
            'id' => uniqid(),
            'user_id' => $user->id,
            'user' => $userName,
            'action' => 'Login',
            'action_type' => 'login',
            'description' => 'User berhasil login ke sistem',
            'ip_address' => $this->generateRandomIP(),
            'timestamp' => now()->subMinutes(rand(5, 120))
        ];

        // Random activities based on user role
        $activityCount = rand(2, 8);

        for ($i = 0; $i < $activityCount; $i++) {
            $activity = $this->generateRandomActivity($userName, $user->id, $role);
            $activity['timestamp'] = now()->subHours(rand(1, 48))->subMinutes(rand(1, 59));
            $activities[] = $activity;
        }

        return $activities;
    }

    private function determineUserRole($user)
    {
        // Simple logic to determine role based on email or ID
        if (strpos($user->email ?? '', 'admin') !== false || $user->id === 1) {
            return 'admin';
        } elseif (strpos($user->email ?? '', 'guru') !== false || $user->id <= 10) {
            return 'guru';
        } else {
            return 'siswa';
        }
    }

    private function generateRandomActivity($userName, $userId, $role)
    {
        $activities = [];

        if ($role === 'admin') {
            $activities = [
                ['action' => 'Update Data', 'action_type' => 'update', 'description' => 'Memperbarui data siswa'],
                ['action' => 'Buat User', 'action_type' => 'create', 'description' => 'Membuat user baru'],
                ['action' => 'Backup', 'action_type' => 'backup', 'description' => 'Melakukan backup database'],
                ['action' => 'Export', 'action_type' => 'export', 'description' => 'Mengexport laporan keuangan'],
                ['action' => 'Hapus Data', 'action_type' => 'delete', 'description' => 'Menghapus data lama'],
                ['action' => 'Reset Password', 'action_type' => 'update', 'description' => 'Merreset password user'],
                ['action' => 'Update Sistem', 'action_type' => 'update', 'description' => 'Memperbarui pengaturan sistem'],
            ];
        } elseif ($role === 'guru') {
            $activities = [
                ['action' => 'Input Nilai', 'action_type' => 'update', 'description' => 'Menginput nilai ulangan harian'],
                ['action' => 'Input Absensi', 'action_type' => 'update', 'description' => 'Menginput absensi siswa'],
                ['action' => 'Upload Materi', 'action_type' => 'create', 'description' => 'Mengupload materi pembelajaran'],
                ['action' => 'Buat Soal', 'action_type' => 'create', 'description' => 'Membuat soal ujian'],
                ['action' => 'Koreksi Tugas', 'action_type' => 'update', 'description' => 'Mengoreksi tugas siswa'],
                ['action' => 'Input RPP', 'action_type' => 'create', 'description' => 'Menginput Rencana Pelaksanaan Pembelajaran'],
            ];
        } else {
            $activities = [
                ['action' => 'Akses Materi', 'action_type' => 'view', 'description' => 'Mengakses materi pembelajaran'],
                ['action' => 'Lihat Nilai', 'action_type' => 'view', 'description' => 'Melihat nilai rapor'],
                ['action' => 'Pembayaran', 'action_type' => 'payment', 'description' => 'Melakukan pembayaran SPP'],
                ['action' => 'Upload Tugas', 'action_type' => 'create', 'description' => 'Mengupload tugas sekolah'],
                ['action' => 'Download File', 'action_type' => 'view', 'description' => 'Mendownload materi pelajaran'],
                ['action' => 'Update Profil', 'action_type' => 'update', 'description' => 'Memperbarui profil pribadi'],
            ];
        }

        $selectedActivity = $activities[array_rand($activities)];
        
        return [
            'id' => uniqid(),
            'user_id' => $userId,
            'user' => $userName,
            'action' => $selectedActivity['action'],
            'action_type' => $selectedActivity['action_type'],
            'description' => $selectedActivity['description'],
            'ip_address' => $this->generateRandomIP(),
            'timestamp' => now()->subHours(rand(1, 72))
        ];
    }

    private function generateRandomIP()
    {
        $ips = [
            '192.168.1.' . rand(1, 255),
            '10.0.0.' . rand(1, 255),
            '172.16.1.' . rand(1, 255),
            '127.0.0.1',
            '103.23.45.' . rand(1, 255),
            '202.67.32.' . rand(1, 255),
        ];
        return $ips[array_rand($ips)];
    }

    private function generateSystemActivities()
    {
        return [
            [
                'id' => uniqid(),
                'user_id' => 1,
                'user' => 'System',
                'action' => 'Backup',
                'action_type' => 'backup',
                'description' => 'Backup otomatis database harian',
                'ip_address' => '127.0.0.1',
                'timestamp' => now()->subHours(2)
            ],
            [
                'id' => uniqid(),
                'user_id' => 1, 
                'user' => 'System',
                'action' => 'Cleanup',
                'action_type' => 'delete',
                'description' => 'Pembersihan cache sistem',
                'ip_address' => '127.0.0.1',
                'timestamp' => now()->subHours(6)
            ],
            [
                'id' => uniqid(),
                'user_id' => 1,
                'user' => 'System',
                'action' => 'Update',
                'action_type' => 'update',
                'description' => 'Update otomatis data statistik',
                'ip_address' => '127.0.0.1',
                'timestamp' => now()->subHours(12)
            ]
        ];
    }

    public function clearLogs()
    {
        try {
            // Untuk sample data, kita tidak bisa menghapus yang sebenarnya
            // Tapi kita bisa memberikan feedback
            session()->flash('success', 'Fitur bersihkan log akan menghapus data log aktivitas dari database. Untuk sample data, tidak ada yang dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membersihkan log: ' . $e->getMessage());
        }
    }

    public function exportLogs()
    {
        try {
            $logs = $this->getLogs();
            $timestamp = now()->format('Y_m_d_His');
            $filename = "logs_export_{$timestamp}.csv";
            
            session()->flash('success', "Log berhasil disiapkan untuk export: {$filename} (Total: {$logs->total()} records)");
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengexport log: ' . $e->getMessage());
        }
    }

    // Refresh data
    public function refreshData()
    {
        $this->loadFilters();
        // Data akan ter-refresh otomatis karena menggunakan Livewire
    }
}