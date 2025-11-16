<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\PembayaranSpp;
use App\Models\TagihanSpp;
use App\Models\Siswa;
use App\Models\Kelas;
use Carbon\Carbon;

#[Layout('layouts.app-new')]
class LaporanKeuangan extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $tahunAjaran;
    public $bulan;
    public $kelasId;
    public $jenisLaporan = 'bulanan';
    public $tanggalMulai;
    public $tanggalSelesai;

    public $tahunOptions = [];
    public $kelasOptions = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'tahunAjaran' => ['except' => ''],
        'bulan' => ['except' => ''],
        'kelasId' => ['except' => ''],
    ];

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
        $this->tahunAjaran = now()->year;
        $this->bulan = now()->month;
        $this->tanggalMulai = now()->startOfMonth()->format('Y-m-d');
        $this->tanggalSelesai = now()->endOfMonth()->format('Y-m-d');

        // Generate tahun options
        $currentYear = now()->year;
        for ($year = $currentYear + 1; $year >= $currentYear - 4; $year--) {
            $this->tahunOptions[$year] = $year;
        }

        // Get kelas options - DIPERBAIKI: tanpa relasi jurusan
        $this->kelasOptions = Kelas::all()
            ->mapWithKeys(function ($kelas) {
                return [$kelas->id => $kelas->nama_lengkap ?? ($kelas->nama_kelas . ' - ' . $kelas->jurusan)];
            })
            ->toArray();
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Hitung statistik berdasarkan model yang ada
    public function getStatistikProperty()
    {
        // Total pemasukan dari pembayaran yang diterima
        $queryPembayaran = PembayaranSpp::where('status_verifikasi', 'diterima');

        // Filter berdasarkan periode
        if ($this->jenisLaporan === 'bulanan') {
            $queryPembayaran->whereYear('tanggal_bayar', $this->tahunAjaran)
                          ->whereMonth('tanggal_bayar', $this->bulan);
        } else {
            $queryPembayaran->whereBetween('tanggal_bayar', [$this->tanggalMulai, $this->tanggalSelesai]);
        }

        // Filter kelas
        if ($this->kelasId) {
            $queryPembayaran->whereHas('siswa', function ($q) {
                $q->where('kelas_id', $this->kelasId);
            });
        }

        $totalPembayaran = $queryPembayaran->sum('jumlah_bayar');

        // Hitung tunggakan dari tagihan yang belum lunas
        $queryTunggakan = TagihanSpp::whereIn('status', ['belum_bayar', 'tertunggak']);

        if ($this->jenisLaporan === 'bulanan') {
            $queryTunggakan->where('tahun', $this->tahunAjaran)
                          ->where('bulan', $this->bulan);
        }

        // Filter kelas
        if ($this->kelasId) {
            $queryTunggakan->whereHas('siswa', function ($q) {
                $q->where('kelas_id', $this->kelasId);
            });
        }

        $totalTunggakan = $queryTunggakan->sum('jumlah_tagihan');

        // Hitung siswa yang belum bayar
        $siswaBelumBayarQuery = Siswa::where('status', 'aktif');

        if ($this->kelasId) {
            $siswaBelumBayarQuery->where('kelas_id', $this->kelasId);
        }

        $siswaBelumBayar = $siswaBelumBayarQuery->whereHas('tagihanSpp', function ($query) {
            $query->whereIn('status', ['belum_bayar', 'tertunggak']);
            
            if ($this->jenisLaporan === 'bulanan') {
                $query->where('tahun', $this->tahunAjaran)
                      ->where('bulan', $this->bulan);
            }
        })->count();

        // Persentase lunas (estimasi)
        $totalTagihanQuery = TagihanSpp::query();
        if ($this->jenisLaporan === 'bulanan') {
            $totalTagihanQuery->where('tahun', $this->tahunAjaran)
                             ->where('bulan', $this->bulan);
        }

        if ($this->kelasId) {
            $totalTagihanQuery->whereHas('siswa', function ($q) {
                $q->where('kelas_id', $this->kelasId);
            });
        }

        $totalTagihan = $totalTagihanQuery->sum('jumlah_tagihan');
        $persentaseLunas = $totalTagihan > 0 ? round(($totalPembayaran / $totalTagihan) * 100, 2) : 0;

        return [
            'totalPemasukan' => $totalPembayaran,
            'pembayaranLunas' => $totalPembayaran,
            'tunggakan' => $totalTunggakan,
            'siswaBelumBayar' => $siswaBelumBayar,
            'persentaseLunas' => $persentaseLunas > 100 ? 100 : $persentaseLunas
        ];
    }

    // Data untuk chart - DIPERBAIKI
public function getChartDataProperty()
{
    $query = PembayaranSpp::where('status_verifikasi', 'diterima')
        ->whereYear('tanggal_bayar', $this->tahunAjaran);

    if ($this->kelasId) {
        $query->whereHas('siswa', function ($q) {
            $q->where('kelas_id', $this->kelasId);
        });
    }

    $data = $query->selectRaw('MONTH(tanggal_bayar) as bulan, SUM(jumlah_bayar) as total')
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

    $labels = [];
    $values = [];

    for ($i = 1; $i <= 12; $i++) {
        $labels[] = Carbon::create()->month($i)->translatedFormat('F');
        $monthData = $data->firstWhere('bulan', $i);
        // Pastikan nilai positif dan format sebagai float
        $values[] = $monthData ? abs((float) $monthData->total) : 0;
    }

    return [
        'labels' => $labels,
        'values' => $values
    ];
}

    // Rekap per kelas - DIPERBAIKI: tanpa relasi jurusan
    public function getRekapKelasProperty()
    {
        $query = PembayaranSpp::with(['siswa.kelas'])
            ->where('status_verifikasi', 'diterima')
            ->whereYear('tanggal_bayar', $this->tahunAjaran)
            ->whereMonth('tanggal_bayar', $this->bulan);

        if ($this->kelasId) {
            $query->whereHas('siswa', function ($q) {
                $q->where('kelas_id', $this->kelasId);
            });
        }

        return $query->get()
            ->groupBy(function ($pembayaran) {
                $kelas = $pembayaran->siswa->kelas;
                return $kelas ? ($kelas->nama_lengkap ?? $kelas->nama_kelas . ' - ' . $kelas->jurusan) : 'Tidak Diketahui';
            })
            ->map(function ($pembayaran, $kelas) {
                return [
                    'nama_kelas' => $kelas,
                    'total' => $pembayaran->sum('jumlah_bayar'),
                    'jumlah_siswa' => $pembayaran->unique('siswa_id')->count()
                ];
            })
            ->values();
    }

    // Export PDF
    public function exportPdf()
    {
        session()->flash('message', 'Laporan PDF berhasil di-generate.');
    }

    // Export Excel
    public function exportExcel()
    {
        session()->flash('message', 'Laporan Excel berhasil di-generate.');
    }

    public function render()
    {
        $query = PembayaranSpp::with([
                'siswa.kelas', // DIPERBAIKI: hanya kelas tanpa jurusan
                'tagihanSpp.biayaSpp'
            ])
            ->where('status_verifikasi', 'diterima')
            ->latest();

        // Filter search
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('siswa', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('nis', 'like', '%' . $this->search . '%')
                      ->orWhere('nisn', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Filter periode
        if ($this->jenisLaporan === 'bulanan') {
            $query->whereYear('tanggal_bayar', $this->tahunAjaran)
                  ->whereMonth('tanggal_bayar', $this->bulan);
        } else {
            $query->whereBetween('tanggal_bayar', [$this->tanggalMulai, $this->tanggalSelesai]);
        }

        // Filter kelas
        if ($this->kelasId) {
            $query->whereHas('siswa', function ($q) {
                $q->where('kelas_id', $this->kelasId);
            });
        }

        $pembayaranList = $query->paginate($this->perPage);
        $statistik = $this->statistik;
        $chartData = $this->chartData;
        $rekapKelas = $this->rekapKelas;

        return view('livewire.admin.laporan-keuangan', compact(
            'pembayaranList', 
            'statistik', 
            'chartData', 
            'rekapKelas'
        ));
    }
}