<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Setting;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class MaintenanceSetting extends Component
{
    public $maintenanceMode = false;
    public $maintenanceMessage = '';
    public $maintenanceTitle = '';
    public $maintenanceStart = '';
    public $maintenanceEnd = '';
    
    public function mount()
    {
        $this->loadSettings();
    }
    
    public function loadSettings()
    {
        $this->maintenanceMode = Setting::where('key', 'maintenance_mode')->value('value') == '1';
        $this->maintenanceMessage = Setting::where('key', 'maintenance_message')->value('value') ?? 'Sistem sedang dalam pemeliharaan. Silakan coba lagi nanti.';
        $this->maintenanceTitle = Setting::where('key', 'maintenance_title')->value('value') ?? 'Maintenance Mode';
        $this->maintenanceStart = Setting::where('key', 'maintenance_start')->value('value') ?? now()->format('Y-m-d\TH:i');
        $this->maintenanceEnd = Setting::where('key', 'maintenance_end')->value('value') ?? now()->addHours(2)->format('Y-m-d\TH:i');
        
        // Set kode akses tetap ke 261102
        Setting::updateOrCreate(
            ['key' => 'maintenance_access_code'],
            ['value' => '261102']
        );
    }
    
    public function toggleMaintenance()
    {
        $this->maintenanceMode = !$this->maintenanceMode;
        
        Setting::updateOrCreate(
            ['key' => 'maintenance_mode'],
            ['value' => $this->maintenanceMode ? '1' : '0']
        );

        // Jika mengaktifkan maintenance, set waktu mulai
        if ($this->maintenanceMode) {
            Setting::updateOrCreate(
                ['key' => 'maintenance_start'],
                ['value' => now()->toDateTimeString()]
            );
        }
        
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Maintenance mode ' . ($this->maintenanceMode ? 'diaktifkan' : 'dinonaktifkan')
        ]);
    }
    
    public function updateMessage()
    {
        Setting::updateOrCreate(
            ['key' => 'maintenance_message'],
            ['value' => $this->maintenanceMessage]
        );
        
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Pesan maintenance berhasil diperbarui'
        ]);
    }

    public function updateSchedule()
    {
        $this->validate([
            'maintenanceTitle' => 'required|string|max:255',
            'maintenanceStart' => 'required|date',
            'maintenanceEnd' => 'required|date|after:maintenanceStart',
        ]);

        Setting::updateOrCreate(
            ['key' => 'maintenance_title'],
            ['value' => $this->maintenanceTitle]
        );

        Setting::updateOrCreate(
            ['key' => 'maintenance_start'],
            ['value' => $this->maintenanceStart]
        );

        Setting::updateOrCreate(
            ['key' => 'maintenance_end'],
            ['value' => $this->maintenanceEnd]
        );
        
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Jadwal maintenance berhasil diperbarui'
        ]);
    }

    public function getEstimatedDuration()
    {
        if (!$this->maintenanceStart || !$this->maintenanceEnd) {
            return '2 jam';
        }

        $start = \Carbon\Carbon::parse($this->maintenanceStart);
        $end = \Carbon\Carbon::parse($this->maintenanceEnd);
        
        $duration = $start->diff($end);
        
        if ($duration->d > 0) {
            return $duration->d . ' hari ' . $duration->h . ' jam';
        } elseif ($duration->h > 0) {
            return $duration->h . ' jam ' . $duration->i . ' menit';
        } else {
            return $duration->i . ' menit';
        }
    }

    public function getTimeRemaining()
    {
        if (!$this->maintenanceEnd) {
            return 'Tidak tersedia';
        }

        $end = \Carbon\Carbon::parse($this->maintenanceEnd);
        $now = now();
        
        if ($now->gt($end)) {
            return 'Maintenance selesai';
        }

        $remaining = $now->diff($end);
        
        if ($remaining->d > 0) {
            return $remaining->d . ' hari ' . $remaining->h . ' jam';
        } elseif ($remaining->h > 0) {
            return $remaining->h . ' jam ' . $remaining->i . ' menit';
        } else {
            return $remaining->i . ' menit';
        }
    }
    
    public function render()
    {
        return view('livewire.admin.maintenance-setting');
    }
}