<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\ArtikelKarir;
use App\Models\LowonganKerja;
use App\Models\Beasiswa;

#[Layout('layouts.app-new')]
class InformasiKarir extends Component
{
    use WithPagination;

    public $search = '';
    public $filterKategori = '';
    public $perPage = 9;
    public $showDetailModal = false;
    public $selectedArtikel = null;
    public $activeTab = 'artikel';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterKategori' => ['except' => ''],
        'perPage' => ['except' => 9],
        'activeTab' => ['except' => 'artikel']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterKategori()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function showDetail($artikelId)
    {
        $this->selectedArtikel = ArtikelKarir::find($artikelId);
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedArtikel = null;
    }

    public function render()
    {
        // Data untuk semua tab
        $artikel = [];
        $lowongan = [];
        $beasiswa = [];

        if ($this->activeTab === 'artikel') {
            $artikelQuery = ArtikelKarir::where('status', 'publik');

            if ($this->search) {
                $artikelQuery->where(function($q) {
                    $q->where('judul', 'like', '%'.$this->search.'%')
                      ->orWhere('konten', 'like', '%'.$this->search.'%')
                      ->orWhere('kategori', 'like', '%'.$this->search.'%');
                });
            }

            if ($this->filterKategori) {
                $artikelQuery->where('kategori', $this->filterKategori);
            }

            $artikel = $artikelQuery->orderBy('created_at', 'desc')
                                   ->paginate($this->perPage);
        }
        elseif ($this->activeTab === 'lowongan') {
            $lowongan = LowonganKerja::aktif()
                ->when($this->search, function($q) {
                    $q->where(function($query) {
                        $query->where('nama_perusahaan', 'like', '%'.$this->search.'%')
                              ->orWhere('posisi', 'like', '%'.$this->search.'%')
                              ->orWhere('lokasi', 'like', '%'.$this->search.'%');
                    });
                })
                ->orderBy('tanggal_tutup', 'asc')
                ->paginate($this->perPage);
        }
        elseif ($this->activeTab === 'beasiswa') {
            $beasiswa = Beasiswa::aktif()
                ->when($this->search, function($q) {
                    $q->where(function($query) {
                        $query->where('nama_beasiswa', 'like', '%'.$this->search.'%')
                              ->orWhere('penyelenggara', 'like', '%'.$this->search.'%');
                    });
                })
                ->orderBy('tanggal_tutup', 'asc')
                ->paginate($this->perPage);
        }

        // Data untuk filter
        $kategoriOptions = ArtikelKarir::select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori')
            ->mapWithKeys(function ($kategori) {
                return [$kategori => $kategori];
            });

        // Stats untuk dashboard
        $totalArtikel = ArtikelKarir::where('status', 'publik')->count();
        $totalLowonganAktif = LowonganKerja::aktif()->count();
        $totalBeasiswaAktif = Beasiswa::aktif()->count();

        return view('livewire.siswa.informasi-karir', [
            'artikel' => $artikel,
            'lowongan' => $lowongan,
            'beasiswa' => $beasiswa,
            'kategoriOptions' => $kategoriOptions,
            'totalArtikel' => $totalArtikel,
            'totalLowonganAktif' => $totalLowonganAktif,
            'totalBeasiswaAktif' => $totalBeasiswaAktif,
        ]);
    }
}