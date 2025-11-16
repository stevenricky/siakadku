<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Konseling;
use App\Models\LayananBk;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app-new')]
class RiwayatKonseling extends Component
{
    use WithPagination;

    public $search = '';
    public $filterLayanan = '';
    public $filterBulan = '';
    public $filterTahun = '';
    public $perPage = 10;
    public $showDetailModal = false;
    public $selectedKonseling = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterLayanan' => ['except' => ''],
        'filterBulan' => ['except' => ''],
        'filterTahun' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterLayanan()
    {
        $this->resetPage();
    }

    public function updatingFilterBulan()
    {
        $this->resetPage();
    }

    public function updatingFilterTahun()
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

    public function showDetail($konselingId)
    {
        $this->selectedKonseling = Konseling::with(['siswa', 'guruBk', 'layananBk'])
            ->where('id', $konselingId)
            ->where('siswa_id', $this->getSiswaId())
            ->where('status', 'selesai') // Hanya yang selesai
            ->first();

        if ($this->selectedKonseling) {
            $this->showDetailModal = true;
        }
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedKonseling = null;
    }

    public function getBulanOptions()
    {
        $siswaId = $this->getSiswaId();
        
        return Konseling::where('siswa_id', $siswaId)
            ->where('status', 'selesai')
            ->selectRaw('YEAR(tanggal_konseling) as tahun, MONTH(tanggal_konseling) as bulan')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get()
            ->mapWithKeys(function ($item) {
                $bulan = \Carbon\Carbon::createFromDate($item->tahun, $item->bulan, 1);
                $key = $item->tahun . '-' . str_pad($item->bulan, 2, '0', STR_PAD_LEFT);
                return [$key => $bulan->translatedFormat('F Y')];
            });
    }

    public function getTahunOptions()
    {
        $siswaId = $this->getSiswaId();
        
        return Konseling::where('siswa_id', $siswaId)
            ->where('status', 'selesai')
            ->selectRaw('YEAR(tanggal_konseling) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun', 'tahun')
            ->toArray();
    }

    public function render()
    {
        $siswaId = $this->getSiswaId();
        
        // HANYA ambil konseling yang SELESAI
        $query = Konseling::with(['guruBk', 'layananBk'])
            ->where('siswa_id', $siswaId)
            ->where('status', 'selesai'); // Fokus pada yang selesai

        // Filter pencarian - fokus pada hasil dan tindakan
        if ($this->search) {
            $query->where(function($q) {
                $q->where('permasalahan', 'like', '%'.$this->search.'%')
                  ->orWhere('tindakan', 'like', '%'.$this->search.'%')
                  ->orWhere('hasil', 'like', '%'.$this->search.'%')
                  ->orWhereHas('guruBk', function($q2) {
                      $q2->where('nama_lengkap', 'like', '%'.$this->search.'%');
                  })
                  ->orWhereHas('layananBk', function($q2) {
                      $q2->where('nama_layanan', 'like', '%'.$this->search.'%');
                  });
            });
        }

        // Filter layanan
        if ($this->filterLayanan) {
            $query->where('layanan_bk_id', $this->filterLayanan);
        }

        // Filter bulan
        if ($this->filterBulan) {
            list($tahun, $bulan) = explode('-', $this->filterBulan);
            $query->whereYear('tanggal_konseling', $tahun)
                  ->whereMonth('tanggal_konseling', $bulan);
        }

        // Filter tahun
        if ($this->filterTahun) {
            $query->whereYear('tanggal_konseling', $this->filterTahun);
        }

        // Order by tanggal terbaru
        $query->orderBy('tanggal_konseling', 'desc')
              ->orderBy('waktu_mulai', 'desc');

        $konseling = $query->paginate($this->perPage);

        // Data untuk filter
        $layananOptions = LayananBk::where('status', true)
            ->orderBy('nama_layanan')
            ->get()
            ->mapWithKeys(function ($layanan) {
                return [$layanan->id => $layanan->nama_layanan];
            });

        $bulanOptions = $this->getBulanOptions();
        $tahunOptions = $this->getTahunOptions();

        // Stats untuk dashboard - khusus yang selesai
        $totalSelesai = Konseling::where('siswa_id', $siswaId)
            ->where('status', 'selesai')
            ->count();
            
        $rataRataDurasi = Konseling::where('siswa_id', $siswaId)
            ->where('status', 'selesai')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, waktu_mulai, waktu_selesai)) as avg_duration')
            ->first()->avg_duration ?? 0;

        $konselingBulanIni = Konseling::where('siswa_id', $siswaId)
            ->where('status', 'selesai')
            ->whereMonth('tanggal_konseling', now()->month)
            ->whereYear('tanggal_konseling', now()->year)
            ->count();

        // PERBAIKAN: Spesifik tabel untuk kolom status
        $layananTerbanyak = Konseling::where('siswa_id', $siswaId)
            ->where('konseling.status', 'selesai') // Spesifik tabel konseling
            ->join('layanan_bk', 'konseling.layanan_bk_id', '=', 'layanan_bk.id')
            ->selectRaw('layanan_bk.nama_layanan, COUNT(*) as total')
            ->groupBy('layanan_bk.id', 'layanan_bk.nama_layanan')
            ->orderBy('total', 'desc')
            ->first();

        return view('livewire.siswa.riwayat-konseling', [
            'konseling' => $konseling,
            'layananOptions' => $layananOptions,
            'bulanOptions' => $bulanOptions,
            'tahunOptions' => $tahunOptions,
            'totalSelesai' => $totalSelesai,
            'rataRataDurasi' => round($rataRataDurasi),
            'konselingBulanIni' => $konselingBulanIni,
            'layananTerbanyak' => $layananTerbanyak,
        ]);
    }
}