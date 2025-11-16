<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Prestasi;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app-new')]
class PrestasiSaya extends Component
{
    use WithPagination;

    public $search = '';
    public $filterTahun = '';
    public $filterJenis = '';
    public $filterTingkat = '';
    public $perPage = 9;
    public $showDetailModal = false;
    public $selectedPrestasi = null;
    public $viewMode = 'grid';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterTahun' => ['except' => ''],
        'filterJenis' => ['except' => ''],
        'filterTingkat' => ['except' => ''],
        'perPage' => ['except' => 9],
        'viewMode' => ['except' => 'grid']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterTahun()
    {
        $this->resetPage();
    }

    public function updatingFilterJenis()
    {
        $this->resetPage();
    }

    public function updatingFilterTingkat()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function getSiswaId()
    {
        return Auth::user()->siswa->id ?? null;
    }

    public function showDetail($prestasiId)
    {
        $this->selectedPrestasi = Prestasi::with(['siswa'])->find($prestasiId);
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedPrestasi = null;
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function downloadSertifikat($prestasiId)
    {
        $prestasi = Prestasi::where('id', $prestasiId)
            ->where('siswa_id', $this->getSiswaId())
            ->first();

        if ($prestasi && $prestasi->sertifikat) {
            return response()->download(storage_path('app/' . $prestasi->sertifikat));
        }

        session()->flash('error', 'Sertifikat tidak ditemukan.');
    }

    public function render()
    {
        $siswaId = $this->getSiswaId();
        
        $query = Prestasi::with(['siswa'])
            ->where('siswa_id', $siswaId)
            ->where('status', true);

        // Filter pencarian
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_prestasi', 'like', '%'.$this->search.'%')
                  ->orWhere('penyelenggara', 'like', '%'.$this->search.'%')
                  ->orWhere('deskripsi', 'like', '%'.$this->search.'%');
            });
        }

        // Filter tahun
        if ($this->filterTahun) {
            $query->whereYear('tanggal_prestasi', $this->filterTahun);
        }

        // Filter jenis
        if ($this->filterJenis) {
            $query->where('jenis_prestasi', $this->filterJenis);
        }

        // Filter tingkat
        if ($this->filterTingkat) {
            $query->where('tingkat', $this->filterTingkat);
        }

        // Order by tanggal terbaru
        $query->orderBy('tanggal_prestasi', 'desc');

        $prestasi = $query->paginate($this->perPage);

        // Data untuk filter
        $tahunOptions = Prestasi::where('siswa_id', $siswaId)
            ->where('status', true)
            ->selectRaw('YEAR(tanggal_prestasi) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->mapWithKeys(function ($tahun) {
                return [$tahun => $tahun];
            });

        $jenisOptions = Prestasi::where('siswa_id', $siswaId)
            ->where('status', true)
            ->select('jenis_prestasi')
            ->distinct()
            ->orderBy('jenis_prestasi')
            ->pluck('jenis_prestasi')
            ->mapWithKeys(function ($jenis) {
                return [$jenis => $jenis];
            });

        $tingkatOptions = Prestasi::where('siswa_id', $siswaId)
            ->where('status', true)
            ->select('tingkat')
            ->distinct()
            ->orderBy('tingkat')
            ->pluck('tingkat')
            ->mapWithKeys(function ($tingkat) {
                return [$tingkat => $tingkat];
            });

        // Stats untuk dashboard
        $totalPrestasi = Prestasi::where('siswa_id', $siswaId)->where('status', true)->count();
        $prestasiAkademik = Prestasi::where('siswa_id', $siswaId)->where('jenis_prestasi', 'Akademik')->where('status', true)->count();
        $prestasiNonAkademik = Prestasi::where('siswa_id', $siswaId)->where('jenis_prestasi', 'Non Akademik')->where('status', true)->count();
        $prestasiTahunIni = Prestasi::where('siswa_id', $siswaId)
            ->whereYear('tanggal_prestasi', date('Y'))
            ->where('status', true)
            ->count();

        return view('livewire.siswa.prestasi-saya', [
            'prestasi' => $prestasi,
            'tahunOptions' => $tahunOptions,
            'jenisOptions' => $jenisOptions,
            'tingkatOptions' => $tingkatOptions,
            'totalPrestasi' => $totalPrestasi,
            'prestasiAkademik' => $prestasiAkademik,
            'prestasiNonAkademik' => $prestasiNonAkademik,
            'prestasiTahunIni' => $prestasiTahunIni,
        ]);
    }
}