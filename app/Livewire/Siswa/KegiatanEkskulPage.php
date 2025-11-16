<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\KegiatanEkskul;
use App\Models\Ekstrakurikuler;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app-new')]
class KegiatanEkskulPage extends Component
{
    use WithPagination;

    public $search = '';
    public $filterEkskul = '';
    public $filterStatus = '';
    public $filterTanggal = '';
    public $perPage = 9;
    public $showDetailModal = false;
    public $selectedKegiatan = null;
    public $viewMode = 'grid';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterEkskul' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterTanggal' => ['except' => ''],
        'perPage' => ['except' => 9],
        'viewMode' => ['except' => 'grid']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterEkskul()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterTanggal()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function showDetail($kegiatanId)
    {
        $this->selectedKegiatan = KegiatanEkskul::with(['ekstrakurikuler'])->find($kegiatanId);
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedKegiatan = null;
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function render()
    {
        // Ambil ekstrakurikuler yang diikuti siswa
        $siswa = Siswa::where('user_id', Auth::id())->first();
        $ekskulTerdaftar = $siswa ? $siswa->ekstrakurikuler()->pluck('ekstrakurikulers.id')->toArray() : [];

        $query = KegiatanEkskul::with(['ekstrakurikuler'])
            ->whereIn('ekstrakurikuler_id', $ekskulTerdaftar);

        // Filter pencarian
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_kegiatan', 'like', '%'.$this->search.'%')
                  ->orWhere('deskripsi', 'like', '%'.$this->search.'%')
                  ->orWhere('tempat', 'like', '%'.$this->search.'%')
                  ->orWhereHas('ekstrakurikuler', function($q2) {
                      $q2->where('nama_ekstra', 'like', '%'.$this->search.'%')
                         ->orWhere('pembina', 'like', '%'.$this->search.'%');
                  });
            });
        }

        // Filter ekstrakurikuler
        if ($this->filterEkskul) {
            $query->where('ekstrakurikuler_id', $this->filterEkskul);
        }

        // Filter status
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        // Filter tanggal
        if ($this->filterTanggal) {
            if ($this->filterTanggal === 'hari_ini') {
                $query->whereDate('tanggal_kegiatan', today());
            } elseif ($this->filterTanggal === 'minggu_ini') {
                $query->whereBetween('tanggal_kegiatan', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($this->filterTanggal === 'bulan_ini') {
                $query->whereMonth('tanggal_kegiatan', now()->month)
                      ->whereYear('tanggal_kegiatan', now()->year);
            } elseif ($this->filterTanggal === 'akan_datang') {
                $query->where('tanggal_kegiatan', '>=', today())
                      ->where('status', '!=', 'dibatalkan');
            } elseif ($this->filterTanggal === 'selesai') {
                $query->where('status', 'terlaksana');
            }
        }

        // Default order by tanggal terbaru
        $query->orderBy('tanggal_kegiatan', 'desc')
              ->orderBy('waktu_mulai', 'desc');

        $kegiatan = $query->paginate($this->perPage);

        // Data untuk filter
        $ekskulOptions = Ekstrakurikuler::whereIn('id', $ekskulTerdaftar)
            ->get()
            ->mapWithKeys(function ($ekskul) {
                return [$ekskul->id => $ekskul->nama_ekstra];
            });

        $statusOptions = [
            'terlaksana' => 'Terlaksana',
            'dibatalkan' => 'Dibatalkan',
            'ditunda' => 'Ditunda'
        ];

        $tanggalOptions = [
            '' => 'Semua Tanggal',
            'hari_ini' => 'Hari Ini',
            'minggu_ini' => 'Minggu Ini',
            'bulan_ini' => 'Bulan Ini',
            'akan_datang' => 'Akan Datang',
            'selesai' => 'Selesai'
        ];

        // Stats untuk dashboard
        $totalKegiatan = KegiatanEkskul::whereIn('ekstrakurikuler_id', $ekskulTerdaftar)->count();
        $kegiatanHariIni = KegiatanEkskul::whereIn('ekstrakurikuler_id', $ekskulTerdaftar)
            ->whereDate('tanggal_kegiatan', today())
            ->count();
        $kegiatanAkanDatang = KegiatanEkskul::whereIn('ekstrakurikuler_id', $ekskulTerdaftar)
            ->where('tanggal_kegiatan', '>=', today())
            ->where('status', '!=', 'dibatalkan')
            ->count();

        return view('livewire.siswa.kegiatan-ekskul', [
            'kegiatan' => $kegiatan,
            'ekskulOptions' => $ekskulOptions,
            'statusOptions' => $statusOptions,
            'tanggalOptions' => $tanggalOptions,
            'totalKegiatan' => $totalKegiatan,
            'kegiatanHariIni' => $kegiatanHariIni,
            'kegiatanAkanDatang' => $kegiatanAkanDatang,
            'ekskulTerdaftar' => $ekskulTerdaftar
        ]);
    }
}