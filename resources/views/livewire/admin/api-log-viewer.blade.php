<?php
// app/Livewire/Admin/ApiLogViewer.php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ApiLog;

class ApiLogViewer extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $methodFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $apiKeyFilter = '';

    public function render()
    {
        $logs = ApiLog::with('apiKey')
            ->when($this->search, function($query) {
                $query->where('endpoint', 'like', '%' . $this->search . '%')
                      ->orWhere('ip_address', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function($query) {
                if ($this->statusFilter === 'success') {
                    $query->where('response_code', '<', 400);
                } else {
                    $query->where('response_code', '>=', 400);
                }
            })
            ->when($this->methodFilter, function($query) {
                $query->where('method', $this->methodFilter);
            })
            ->when($this->dateFrom, function($query) {
                $query->whereDate('requested_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function($query) {
                $query->whereDate('requested_at', '<=', $this->dateTo);
            })
            ->when($this->apiKeyFilter, function($query) {
                $query->whereHas('apiKey', function($q) {
                    $q->where('name', 'like', '%' . $this->apiKeyFilter . '%');
                });
            })
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => ApiLog::count(),
            'today' => ApiLog::whereDate('requested_at', today())->count(),
            'success' => ApiLog::where('response_code', '<', 400)->count(),
            'failed' => ApiLog::where('response_code', '>=', 400)->count(),
        ];

        return view('livewire.admin.api-log-viewer', [
            'logs' => $logs,
            'stats' => $stats
        ]);
    }

    public function clearFilters()
    {
        $this->reset(['search', 'statusFilter', 'methodFilter', 'dateFrom', 'dateTo', 'apiKeyFilter']);
    }
}