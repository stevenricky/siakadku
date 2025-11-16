<?php
// app/Livewire/Admin/PengaturanUmum.php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class PengaturanUmum extends Component
{
    use WithFileUploads;

    public $settings = [];
    public $logoFile;
    public $existingLogo;

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $defaultSettings = [
            'nama_sekolah' => 'SMA Negeri 1 Example',
            'alamat_sekolah' => 'Jl. Contoh No. 123',
            'telepon_sekolah' => '(021) 1234567',
            'email_sekolah' => 'info@sman1example.sch.id',
            'website_sekolah' => 'https://sman1example.sch.id',
            'tahun_ajaran_aktif' => date('Y') . '/' . (date('Y') + 1),
            'kepala_sekolah' => 'Dr. John Doe, M.Pd',
            'nip_kepala_sekolah' => '196512341987011001',
            'zona_waktu' => 'Asia/Jakarta',
            'format_tanggal' => 'd/m/Y',
            'logo_sekolah' => null,
        ];

        foreach ($defaultSettings as $key => $defaultValue) {
            $this->settings[$key] = Setting::getValue($key, $defaultValue);
        }

        $this->existingLogo = $this->settings['logo_sekolah'];
    }

    public function saveSettings()
    {
        $this->validate([
            'settings.nama_sekolah' => 'required|string|max:255',
            'settings.alamat_sekolah' => 'nullable|string',
            'settings.telepon_sekolah' => 'nullable|string|max:20',
            'settings.email_sekolah' => 'nullable|email',
            'settings.website_sekolah' => 'nullable|url',
            'settings.tahun_ajaran_aktif' => 'nullable|string',
            'settings.kepala_sekolah' => 'nullable|string',
            'settings.nip_kepala_sekolah' => 'nullable|string',
            'settings.zona_waktu' => 'nullable|string',
            'settings.format_tanggal' => 'nullable|string',
            'logoFile' => 'nullable|max:2048|mimes:jpg,jpeg,png,svg',
        ]);

        try {
            // Handle logo upload
            if ($this->logoFile) {
                // Delete old logo if exists
                if ($this->existingLogo && Storage::disk('public')->exists($this->existingLogo)) {
                    Storage::disk('public')->delete($this->existingLogo);
                }
                
                // Save new logo
                $logoPath = $this->logoFile->storePublicly('settings/logo', 'public');
                $this->settings['logo_sekolah'] = $logoPath;
                $this->existingLogo = $logoPath;
            }

            // Save all settings
            foreach ($this->settings as $key => $value) {
                Setting::setValue($key, $value);
            }

            // Clear temporary file
            $this->logoFile = null;

            // Clear settings cache
            Setting::clearCache();

            session()->flash('success', 'Pengaturan berhasil disimpan!');
            
            // Dispatch event for real-time updates
            $this->dispatch('settingsUpdated');

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan pengaturan: ' . $e->getMessage());
        }
    }

    public function resetToDefault()
    {
        $defaults = [
            'nama_sekolah' => 'SMA Negeri 1 Example',
            'alamat_sekolah' => '',
            'telepon_sekolah' => '',
            'email_sekolah' => '',
            'website_sekolah' => '',
            'tahun_ajaran_aktif' => date('Y') . '/' . (date('Y') + 1),
            'kepala_sekolah' => '',
            'nip_kepala_sekolah' => '',
            'zona_waktu' => 'Asia/Jakarta',
            'format_tanggal' => 'd/m/Y',
            'logo_sekolah' => null,
        ];

        foreach ($defaults as $key => $value) {
            Setting::setValue($key, $value);
            $this->settings[$key] = $value;
        }

        // Delete logo
        if ($this->existingLogo && Storage::disk('public')->exists($this->existingLogo)) {
            Storage::disk('public')->delete($this->existingLogo);
        }
        $this->existingLogo = null;

        // Clear settings cache
        Setting::clearCache();

        session()->flash('success', 'Pengaturan berhasil direset ke default!');
        $this->dispatch('settingsUpdated');
    }

    public function render()
    {
        return view('livewire.admin.pengaturan-umum');
    }
}