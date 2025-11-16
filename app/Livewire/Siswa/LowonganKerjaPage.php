<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\LowonganKerja;

#[Layout('layouts.app-new')]
class LowonganKerjaPage extends Component
{
    use WithPagination;

    public $search = '';
    public $filterLokasi = '';
    public $perPage = 9;
    public $showDetailModal = false;
    public $selectedLowongan = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterLokasi' => ['except' => ''],
        'perPage' => ['except' => 9],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterLokasi()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function showDetail($lowonganId)
    {
        $this->selectedLowongan = LowonganKerja::find($lowonganId);
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedLowongan = null;
    }

    public function render()
    {
        $query = LowonganKerja::aktif(); // Hanya yang aktif

        // Filter pencarian
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_perusahaan', 'like', '%'.$this->search.'%')
                  ->orWhere('posisi', 'like', '%'.$this->search.'%')
                  ->orWhere('lokasi', 'like', '%'.$this->search.'%');
            });
        }

        // Filter lokasi
        if ($this->filterLokasi) {
            $query->where('lokasi', 'like', '%'.$this->filterLokasi.'%');
        }

        // Order by tanggal tutup terdekat
        $query->orderBy('tanggal_tutup', 'asc')
              ->orderBy('created_at', 'desc');

        $lowongan = $query->paginate($this->perPage);

        // Data untuk filter
        $lokasiOptions = LowonganKerja::aktif()
            ->select('lokasi')
            ->distinct()
            ->orderBy('lokasi')
            ->pluck('lokasi');

        // Stats sederhana
        $totalAktif = LowonganKerja::aktif()->count();

        return view('livewire.siswa.lowongan-kerja', [
            'lowongan' => $lowongan,
            'lokasiOptions' => $lokasiOptions,
            'totalAktif' => $totalAktif,
        ]);
    }
}