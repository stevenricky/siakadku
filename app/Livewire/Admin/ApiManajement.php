<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ApiKey;
use App\Models\ApiLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class ApiManajement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    
    // Create modal properties
    public $showCreateModal = false;
    public $showLogsModal = false;
    public $selectedApiKey = null;
    public $newKeyName = '';
    public $newKeyDescription = '';
    public $permissions = [];
    public $rateLimit = 100;
    public $expiresAt = '';
    public $allowedIps = '';
    public $webhookUrl = '';

    // Available endpoints and permissions
    public $availableEndpoints = [
        'siswa' => ['read' => 'Melihat data siswa', 'write' => 'Mengubah data siswa'],
        'guru' => ['read' => 'Melihat data guru', 'write' => 'Mengubah data guru'],
        'kelas' => ['read' => 'Melihat data kelas', 'write' => 'Mengubah data kelas'],
        'nilai' => ['read' => 'Melihat nilai', 'write' => 'Input/edit nilai'],
        'absensi' => ['read' => 'Melihat absensi', 'write' => 'Input absensi'],
        'keuangan' => ['read' => 'Melihat data keuangan', 'write' => 'Input transaksi'],
        'jadwal' => ['read' => 'Melihat jadwal'],
        'mapel' => ['read' => 'Melihat mata pelajaran'],
        'inventaris' => ['read' => 'Melihat inventaris', 'write' => 'Update inventaris'],
    ];

    protected $rules = [
        'newKeyName' => 'required|string|max:255',
        'newKeyDescription' => 'nullable|string|max:500',
        'rateLimit' => 'required|integer|min:1|max:1000',
        'expiresAt' => 'nullable|date|after:today',
        'allowedIps' => 'nullable|string',
        'webhookUrl' => 'nullable|url',
    ];

    public function mount()
    {
        $this->resetPermissions();
    }

    public function render()
{
    // Pastikan availableEndpoints selalu array
    if (!is_array($this->availableEndpoints)) {
        $this->availableEndpoints = [];
    }

    $apiKeys = ApiKey::with(['user', 'logs' => function($query) {
        $query->orderBy('requested_at', 'desc')->take(5);
    }])
    ->when($this->search, function($query) {
        $query->where('name', 'like', '%' . $this->search . '%')
              ->orWhere('api_key', 'like', '%' . $this->search . '%');
    })
    ->when($this->statusFilter, function($query) {
        $query->where('status', $this->statusFilter);
    })
    ->latest()
    ->paginate(10);

    $stats = [
        'total' => ApiKey::count(),
        'active' => ApiKey::where('status', 'active')->count(),
        'inactive' => ApiKey::where('status', 'inactive')->count(),
        'today_requests' => ApiLog::whereDate('requested_at', today())->count(),
        'total_requests' => ApiLog::count(),
    ];

    return view('livewire.admin.api-manajement', [
        'apiKeys' => $apiKeys,
        'stats' => $stats
    ]);
}

    public function resetPermissions()
    {
        foreach (array_keys($this->availableEndpoints) as $endpoint) {
            $this->permissions[$endpoint] = ['read' => false, 'write' => false];
        }
    }

  public function createApiKey()
{
    $this->validate();

    try {
        // Generate API key
        $fullApiKey = 'sk_' . Str::random(32);
        
        // Format permissions
        $formattedPermissions = [];
        foreach ($this->permissions as $endpoint => $actions) {
            if (is_array($actions)) {
                $formattedPermissions[$endpoint] = [
                    'read' => (bool)($actions['read'] ?? false),
                    'write' => (bool)($actions['write'] ?? false)
                ];
            }
        }

        // Create API key
        ApiKey::create([
            'name' => $this->newKeyName,
            'api_key' => $fullApiKey,
            'user_id' => auth()->id(),
            'permissions' => $formattedPermissions,
            'rate_limit' => $this->rateLimit,
            'expires_at' => $this->expiresAt ?: null,
            'allowed_ips' => $this->allowedIps ? array_map('trim', explode(',', $this->allowedIps)) : null,
            'webhook_url' => $this->webhookUrl,
            'description' => $this->newKeyDescription,
            'status' => 'active'
        ]);

        // Close modal dan reset
        $this->showCreateModal = false;
        $this->resetModal();
        
        session()->flash('success', 'API Key berhasil dibuat!');
        
    } catch (\Exception $e) {
        session()->flash('error', 'Gagal membuat API Key: ' . $e->getMessage());
    }
}

    public function toggleKeyStatus(ApiKey $apiKey)
    {
        $apiKey->update([
            'status' => $apiKey->status === 'active' ? 'inactive' : 'active'
        ]);
        
        session()->flash('success', 'Status API Key berhasil diubah');
    }

   public function regenerateKey(ApiKey $apiKey)
{
    $newKey = 'sk_' . Str::random(32);
    $apiKey->update(['api_key' => $newKey]);
    
    session()->flash('api_key_success', true);
    session()->flash('api_key_message', "API Key berhasil diregenerasi!");
    session()->flash('api_key_full', $newKey);
    
    $this->loadApiKeys();
}
    public function deleteKey(ApiKey $apiKey)
    {
        $apiKey->delete();
        session()->flash('success', 'API Key berhasil dihapus.');
    }

    public function showLogs($keyId)
{
    $this->selectedApiKey = ApiKey::with(['logs' => function($query) {
        $query->latest()->take(50); 
    }])->find($keyId);
    
    $this->showLogsModal = true;
}

    public function closeLogsModal()
    {
        $this->showLogsModal = false;
        $this->selectedApiKey = null;
    }

    public function resetModal()
    {
        $this->reset([
            'showCreateModal', 
            'newKeyName', 
            'newKeyDescription',
            'rateLimit', 
            'expiresAt', 
            'allowedIps', 
            'webhookUrl'
        ]);
        $this->resetPermissions();
        $this->resetErrorBag();
    }

    public function exportLogs()
    {
        return response()->streamDownload(function () {
            $logs = ApiLog::with('apiKey')
                ->when($this->dateFrom, function($query) {
                    $query->whereDate('requested_at', '>=', $this->dateFrom);
                })
                ->when($this->dateTo, function($query) {
                    $query->whereDate('requested_at', '<=', $this->dateTo);
                })
                ->get();

            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Timestamp', 'API Key', 'Endpoint', 'Method', 'Response Code', 'Response Time', 'IP Address']);
            
            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->requested_at,
                    $log->apiKey->name,
                    $log->endpoint,
                    $log->method,
                    $log->response_code,
                    $log->response_time,
                    $log->ip_address
                ]);
            }
            fclose($handle);
        }, "api-logs-" . now()->format('Y-m-d') . ".csv");
    }
}