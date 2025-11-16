<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Beasiswa;

#[Layout('layouts.app-new')]
class BeasiswaPage extends Component
{
    use WithPagination;

    public $search = '';
    public $filterPenyelenggara = '';
    public $filterStatus = '';
    public $perPage = 9;
    public $showDetailModal = false;
    public $selectedBeasiswa = null;
    public $viewMode = 'grid';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterPenyelenggara' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'perPage' => ['except' => 9],
        'viewMode' => ['except' => 'grid']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterPenyelenggara()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function showDetail($beasiswaId)
    {
        $this->selectedBeasiswa = Beasiswa::find($beasiswaId);
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedBeasiswa = null;
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function downloadDokumen($beasiswaId)
    {
        $beasiswa = Beasiswa::find($beasiswaId);
        
        if ($beasiswa && $beasiswa->dokumen) {
            return response()->download(storage_path('app/' . $beasiswa->dokumen));
        }

        session()->flash('error', 'Dokumen tidak ditemukan.');
    }

    public function render()
    {
        $query = Beasiswa::query();

        // Filter pencarian
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_beasiswa', 'like', '%'.$this->search.'%')
                  ->orWhere('penyelenggara', 'like', '%'.$this->search.'%')
                  ->orWhere('deskripsi', 'like', '%'.$this->search.'%');
            });
        }

        // Filter penyelenggara
        if ($this->filterPenyelenggara) {
            $query->where('penyelenggara', 'like', '%'.$this->filterPenyelenggara.'%');
        }

        // Filter status
        if ($this->filterStatus) {
            if ($this->filterStatus === 'aktif') {
                $query->aktif();
            } elseif ($this->filterStatus === 'tutup') {
                $query->where('status', 'tutup');
            }
        } else {
            // Default: tampilkan yang aktif
            $query->aktif();
        }

        // Order by tanggal tutup terdekat
        $query->orderBy('tanggal_tutup', 'asc')
              ->orderBy('created_at', 'desc');

        $beasiswa = $query->paginate($this->perPage);

        // Data untuk filter
        $penyelenggaraOptions = Beasiswa::select('penyelenggara')
            ->distinct()
            ->orderBy('penyelenggara')
            ->pluck('penyelenggara')
            ->mapWithKeys(function ($penyelenggara) {
                return [$penyelenggara => $penyelenggara];
            });

        $statusOptions = [
            '' => 'Semua Status',
            'aktif' => 'Sedang Buka',
            'tutup' => 'Sudah Tutup'
        ];

        // Stats untuk dashboard
        $totalBeasiswa = Beasiswa::count();
        $beasiswaAktif = Beasiswa::aktif()->count();
        $beasiswaTutup = Beasiswa::where('status', 'tutup')->count();
        $beasiswaBulanIni = Beasiswa::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('livewire.siswa.beasiswa', [
            'beasiswa' => $beasiswa,
            'penyelenggaraOptions' => $penyelenggaraOptions,
            'statusOptions' => $statusOptions,
            'totalBeasiswa' => $totalBeasiswa,
            'beasiswaAktif' => $beasiswaAktif,
            'beasiswaTutup' => $beasiswaTutup,
            'beasiswaBulanIni' => $beasiswaBulanIni,
        ]);
    }
}