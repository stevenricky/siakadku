<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\LayananBk;
use App\Models\Konseling;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app-new')]
class LayananBkPage extends Component
{
    use WithPagination;

    public $search = '';
    public $filterJenis = '';
    public $filterSasaran = '';
    public $perPage = 9;
    public $showDetailModal = false;
    public $selectedLayanan = null;
    public $viewMode = 'grid';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterJenis' => ['except' => ''],
        'filterSasaran' => ['except' => ''],
        'perPage' => ['except' => 9],
        'viewMode' => ['except' => 'grid']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterJenis()
    {
        $this->resetPage();
    }

    public function updatingFilterSasaran()
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

    public function showDetail($layananId)
    {
        $this->selectedLayanan = LayananBk::find($layananId);
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedLayanan = null;
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function buatJanjiKonseling($layananId)
    {
        // Redirect ke halaman buat janji konseling
        return redirect()->route('siswa.jadwal-konseling.index', ['layanan' => $layananId]);
    }

    public function render()
    {
        $query = LayananBk::where('status', true);

        // Filter pencarian
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_layanan', 'like', '%'.$this->search.'%')
                  ->orWhere('deskripsi', 'like', '%'.$this->search.'%')
                  ->orWhere('sasaran', 'like', '%'.$this->search.'%');
            });
        }

        // Filter jenis layanan
        if ($this->filterJenis) {
            $query->where('jenis_layanan', $this->filterJenis);
        }

        // Filter sasaran
        if ($this->filterSasaran) {
            $query->where('sasaran', $this->filterSasaran);
        }

        // Order by nama layanan
        $query->orderBy('nama_layanan');

        $layanan = $query->paginate($this->perPage);

        // Data untuk filter
        $jenisOptions = LayananBk::where('status', true)
            ->select('jenis_layanan')
            ->distinct()
            ->orderBy('jenis_layanan')
            ->pluck('jenis_layanan')
            ->mapWithKeys(function ($jenis) {
                return [$jenis => $jenis];
            });

        $sasaranOptions = LayananBk::where('status', true)
            ->select('sasaran')
            ->distinct()
            ->orderBy('sasaran')
            ->pluck('sasaran')
            ->mapWithKeys(function ($sasaran) {
                return [$sasaran => $sasaran];
            });

        // Stats untuk dashboard
        $totalLayanan = LayananBk::where('status', true)->count();
        $layananIndividu = LayananBk::where('status', true)->where('jenis_layanan', 'like', '%Individu%')->count();
        $layananKelompok = LayananBk::where('status', true)->where('jenis_layanan', 'like', '%Kelompok%')->count();

        // Riwayat konseling siswa
        $siswaId = $this->getSiswaId();
        $riwayatKonseling = $siswaId ? Konseling::where('siswa_id', $siswaId)->count() : 0;

        return view('livewire.siswa.layanan-bk', [
            'layanan' => $layanan,
            'jenisOptions' => $jenisOptions,
            'sasaranOptions' => $sasaranOptions,
            'totalLayanan' => $totalLayanan,
            'layananIndividu' => $layananIndividu,
            'layananKelompok' => $layananKelompok,
            'riwayatKonseling' => $riwayatKonseling,
        ]);
    }
}