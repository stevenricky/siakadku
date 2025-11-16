<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\PeminjamanBuku;
use App\Models\KategoriBuku;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app-new')]
class LaporanPeminjaman extends Component
{
    use WithPagination;

    public $periode = 'bulan_ini';
    public $bulanTahun;
    public $kategoriFilter = '';
    public $tanggalAwal;
    public $tanggalAkhir;

    // Stats properties
    public $totalPeminjaman = 0;
    public $rataRataPerHari = 0;
    public $peminjamanTertinggi = 0;
    public $distribusiPeminjam = [];
    public $bukuTerpopuler = [];
    public $detailPeminjaman = [];
    public $statusDistribution = [];

    public function mount()
    {
        $this->bulanTahun = now()->format('Y-m');
        $this->updateTanggalBerdasarkanPeriode();
        $this->generateLaporan();
    }

    public function updatedPeriode()
    {
        $this->updateTanggalBerdasarkanPeriode();
        $this->generateLaporan();
    }

    public function updatedBulanTahun()
    {
        if ($this->periode === 'custom') {
            $this->updateTanggalBerdasarkanPeriode();
            $this->generateLaporan();
        }
    }

    public function updatedKategoriFilter()
    {
        $this->resetPage();
        $this->generateLaporan();
    }

    private function updateTanggalBerdasarkanPeriode()
    {
        switch ($this->periode) {
            case 'bulan_ini':
                $this->tanggalAwal = now()->startOfMonth()->format('Y-m-d');
                $this->tanggalAkhir = now()->endOfMonth()->format('Y-m-d');
                break;
            case 'semester_ini':
                $this->tanggalAwal = now()->month >= 7 ? now()->setMonth(7)->startOfMonth()->format('Y-m-d') : now()->setMonth(1)->startOfMonth()->format('Y-m-d');
                $this->tanggalAkhir = now()->month >= 7 ? now()->setMonth(12)->endOfMonth()->format('Y-m-d') : now()->setMonth(6)->endOfMonth()->format('Y-m-d');
                break;
            case 'tahun_ini':
                $this->tanggalAwal = now()->startOfYear()->format('Y-m-d');
                $this->tanggalAkhir = now()->endOfYear()->format('Y-m-d');
                break;
            case 'custom':
                if ($this->bulanTahun) {
                    $this->tanggalAwal = Carbon::parse($this->bulanTahun . '-01')->format('Y-m-d');
                    $this->tanggalAkhir = Carbon::parse($this->bulanTahun . '-01')->endOfMonth()->format('Y-m-d');
                }
                break;
        }
    }

    public function generateLaporan()
    {
        $this->hitungStatistik();
        $this->hitungDistribusi();
        $this->hitungBukuTerpopuler();
        $this->getDetailPeminjaman();
        $this->hitungStatusDistribution();
    }

    private function hitungStatistik()
    {
        $query = PeminjamanBuku::with(['siswa.kelas', 'buku.kategori'])
            ->whereBetween('tanggal_pinjam', [$this->tanggalAwal, $this->tanggalAkhir]);

        if ($this->kategoriFilter) {
            $query->whereHas('buku', function($q) {
                $q->where('kategori_id', $this->kategoriFilter);
            });
        }

        $peminjaman = $query->get();

        // Total Peminjaman
        $this->totalPeminjaman = $peminjaman->count();

        // Rata-rata per Hari
        $hari = Carbon::parse($this->tanggalAwal)->diffInDays(Carbon::parse($this->tanggalAkhir)) + 1;
        $this->rataRataPerHari = $hari > 0 ? round($this->totalPeminjaman / $hari, 1) : 0;

        // Peminjaman Tertinggi per Hari
        $peminjamanPerHari = $peminjaman->groupBy(function($item) {
            return Carbon::parse($item->tanggal_pinjam)->format('Y-m-d');
        })->map->count();

        $this->peminjamanTertinggi = $peminjamanPerHari->max() ?? 0;
    }

    private function hitungDistribusi()
    {
        $query = PeminjamanBuku::with('siswa')
            ->whereBetween('tanggal_pinjam', [$this->tanggalAwal, $this->tanggalAkhir]);

        if ($this->kategoriFilter) {
            $query->whereHas('buku', function($q) {
                $q->where('kategori_id', $this->kategoriFilter);
            });
        }

        $peminjaman = $query->get();

        $total = $peminjaman->count();
        
        if ($total > 0) {
            $this->distribusiPeminjam = [
                'siswa' => round(($peminjaman->count() / $total) * 100, 1),
                'guru' => 0,
                'staf' => 0
            ];
        } else {
            $this->distribusiPeminjam = ['siswa' => 0, 'guru' => 0, 'staf' => 0];
        }
    }

    private function hitungBukuTerpopuler()
    {
        $this->bukuTerpopuler = PeminjamanBuku::select('buku_id', DB::raw('COUNT(*) as total_peminjaman'))
            ->with('buku.kategori')
            ->whereBetween('tanggal_pinjam', [$this->tanggalAwal, $this->tanggalAkhir])
            ->when($this->kategoriFilter, function($query) {
                $query->whereHas('buku', function($q) {
                    $q->where('kategori_id', $this->kategoriFilter);
                });
            })
            ->groupBy('buku_id')
            ->orderBy('total_peminjaman', 'desc')
            ->limit(5)
            ->get();
    }

    private function getDetailPeminjaman()
    {
        $this->detailPeminjaman = PeminjamanBuku::with(['siswa.kelas', 'buku'])
            ->whereBetween('tanggal_pinjam', [$this->tanggalAwal, $this->tanggalAkhir])
            ->when($this->kategoriFilter, function($query) {
                $query->whereHas('buku', function($q) {
                    $q->where('kategori_id', $this->kategoriFilter);
                });
            })
            ->orderBy('tanggal_pinjam', 'desc')
            ->limit(50)
            ->get();
    }

    private function hitungStatusDistribution()
    {
        $statusCounts = PeminjamanBuku::whereBetween('tanggal_pinjam', [$this->tanggalAwal, $this->tanggalAkhir])
            ->when($this->kategoriFilter, function($query) {
                $query->whereHas('buku', function($q) {
                    $q->where('kategori_id', $this->kategoriFilter);
                });
            })
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();

        $total = $statusCounts->sum('total');
        
        $this->statusDistribution = $statusCounts->mapWithKeys(function($item) use ($total) {
            $percentage = $total > 0 ? round(($item->total / $total) * 100, 1) : 0;
            return [$item->status => [
                'count' => $item->total,
                'percentage' => $percentage
            ]];
        })->toArray();
    }

    public function exportPDF()
    {
        session()->flash('info', 'Fitur export PDF akan segera tersedia.');
    }

    public function exportCSV()
    {
        $peminjaman = PeminjamanBuku::with(['siswa.kelas', 'buku'])
            ->whereBetween('tanggal_pinjam', [$this->tanggalAwal, $this->tanggalAkhir])
            ->when($this->kategoriFilter, function($query) {
                $query->whereHas('buku', function($q) {
                    $q->where('kategori_id', $this->kategoriFilter);
                });
            })
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        $fileName = 'laporan-peminjaman-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($peminjaman) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'Kode Peminjaman', 'Tanggal Pinjam', 'Tanggal Kembali', 
                'Nama Peminjam', 'Kelas', 'Judul Buku', 'Penulis',
                'Status', 'Denda', 'Durasi (hari)'
            ]);

            // Data CSV
            foreach ($peminjaman as $item) {
                fputcsv($file, [
                    $item->kode_peminjaman,
                    $item->tanggal_pinjam->format('d/m/Y'),
                    $item->tanggal_kembali ? $item->tanggal_kembali->format('d/m/Y') : '-',
                    $item->siswa->nama,
                    $item->siswa->kelas->nama_kelas ?? '-',
                    $item->buku->judul,
                    $item->buku->penulis,
                    $item->status_text,
                    $item->denda,
                    $item->durasi
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        $kategoriList = KategoriBuku::aktif()->get();

        return view('livewire.admin.laporan-peminjaman', [
            'kategoriList' => $kategoriList,
        ]);
    }
}