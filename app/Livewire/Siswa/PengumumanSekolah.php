<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pengumuman;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class PengumumanSekolah extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $filterKategori = 'semua'; // semua, penting, biasa
    public $selectedPengumuman = null;
    public $showDetailModal = false;

    public function render()
{
    $siswa = auth()->user()->siswa;
    
    $pengumumanList = Pengumuman::with(['user', 'kelas'])
        ->where(function($query) use ($siswa) {
            // PENGUMUMAN UNTUK SEMUA ORANG
            $query->where('target', 'semua')
                  // PENGUMUMAN KHUSUS UNTUK SISWA
                  ->orWhere('target', 'siswa')
                  // PENGUMUMAN KELAS: hanya untuk kelas siswa ini
                  ->orWhere(function($q) use ($siswa) {
                      $q->where('target', 'kelas')
                        ->where('kelas_id', $siswa->kelas_id);
                  });
        })
        ->when($this->search, function($query) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%'.$this->search.'%')
                  ->orWhere('isi', 'like', '%'.$this->search.'%')
                  ->orWhereHas('user', function($userQuery) {
                      $userQuery->where('name', 'like', '%'.$this->search.'%');
                  })
                  ->orWhereHas('kelas', function($kelasQuery) {
                      $kelasQuery->where('nama_kelas', 'like', '%'.$this->search.'%');
                  });
            });
        })
        ->when($this->filterKategori !== 'semua', function($query) {
            if ($this->filterKategori === 'penting') {
                $query->where('is_urgent', true);
            } elseif ($this->filterKategori === 'biasa') {
                $query->where('is_urgent', false);
            }
        })
        ->where(function($query) {
            // Hanya pengumuman yang masih berlaku
            $query->whereNull('tanggal_berlaku')
                  ->orWhere('tanggal_berlaku', '>=', now());
        })
        ->latest()
        ->paginate($this->perPage);

    return view('livewire.siswa.pengumuman-sekolah', [
        'pengumumanList' => $pengumumanList
    ]);
}



    public function lihatDetail($pengumumanId)
    {
        $this->selectedPengumuman = Pengumuman::with(['user', 'kelas'])->findOrFail($pengumumanId);
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedPengumuman = null;
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterKategori = 'semua';
        $this->perPage = 10;
        $this->resetPage();
    }
}