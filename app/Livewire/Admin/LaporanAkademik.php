<?php

namespace App\Livewire\Admin;

use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Mapel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app-new')]
class LaporanAkademik extends Component
{
    use WithPagination;

    public $tahunAjaranFilter;
    public $semesterFilter = 'ganjil';
    public $kelasFilter = '';

    public $tahunAjaranList;
    public $kelasList;

    public $statistics = [];

    public function mount()
    {
        $this->tahunAjaranList = TahunAjaran::orderBy('tahun_awal', 'desc')->get();
        $this->kelasList = Kelas::with('waliKelas')->get();
        
        // Set default tahun ajaran aktif
        $currentTahunAjaran = TahunAjaran::where('status', 'aktif')->first();
        $this->tahunAjaranFilter = $currentTahunAjaran?->id ?? ($this->tahunAjaranList->first()?->id ?? null);
        
        $this->updateStatistics();
    }

    // ⭐⭐ PERBAIKAN: Gunakan updated hook untuk realtime updates ⭐⭐
    public function updated($property, $value)
    {
        // Update statistics ketika filter berubah
        if (in_array($property, ['tahunAjaranFilter', 'semesterFilter', 'kelasFilter'])) {
            $this->updateStatistics();
        }
    }

    // ⭐⭐ ATAU: Buat method terpisah untuk setiap filter ⭐⭐
    public function updatedTahunAjaranFilter($value)
    {
        $this->updateStatistics();
    }

    public function updatedSemesterFilter($value)
    {
        $this->updateStatistics();
    }

    public function updatedKelasFilter($value)
    {
        $this->updateStatistics();
    }

    public function updateStatistics()
    {
        // Total Siswa
        $siswaQuery = Siswa::where('status', 'aktif');
        if ($this->kelasFilter) {
            $siswaQuery->where('kelas_id', $this->kelasFilter);
        }
        $totalSiswa = $siswaQuery->count();

        // Total Kelas
        $totalKelas = $this->kelasFilter ? 1 : Kelas::count();

        // Rata-rata Nilai
        $averageNilai = $this->getAverageNilai();

        // Distribusi Predikat
        $predikatStats = $this->getPredikatDistribution();

        // Top Performers
        $topPerformers = $this->getTopPerformers();

        // Mata Pelajaran Terbaik
        $bestSubjects = $this->getBestSubjects();

        $this->statistics = [
            'totalSiswa' => $totalSiswa,
            'totalKelas' => $totalKelas,
            'averageNilai' => $averageNilai,
            'predikatStats' => $predikatStats,
            'topPerformers' => $topPerformers,
            'bestSubjects' => $bestSubjects,
        ];
    }

    private function getAverageNilai()
    {
        return Nilai::when($this->tahunAjaranFilter, function ($query) {
                $query->where('tahun_ajaran_id', $this->tahunAjaranFilter);
            })
            ->when($this->semesterFilter, function ($query) {
                $query->where('semester', $this->semesterFilter);
            })
            ->when($this->kelasFilter, function ($query) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('kelas_id', $this->kelasFilter);
                });
            })
            ->avg('nilai_akhir') ?? 0;
    }

    private function getPredikatDistribution()
    {
        $stats = Nilai::when($this->tahunAjaranFilter, function ($query) {
                $query->where('tahun_ajaran_id', $this->tahunAjaranFilter);
            })
            ->when($this->semesterFilter, function ($query) {
                $query->where('semester', $this->semesterFilter);
            })
            ->when($this->kelasFilter, function ($query) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('kelas_id', $this->kelasFilter);
                });
            })
            ->selectRaw('predikat, COUNT(*) as count')
            ->groupBy('predikat')
            ->get();

        // Jika tidak ada data, return array default
        if ($stats->isEmpty()) {
            return collect([
                ['predikat' => 'A', 'count' => 0],
                ['predikat' => 'B', 'count' => 0],
                ['predikat' => 'C', 'count' => 0],
                ['predikat' => 'D', 'count' => 0],
            ]);
        }

        return $stats;
    }

    private function getTopPerformers()
    {
        return Nilai::when($this->tahunAjaranFilter, function ($query) {
                $query->where('tahun_ajaran_id', $this->tahunAjaranFilter);
            })
            ->when($this->semesterFilter, function ($query) {
                $query->where('semester', $this->semesterFilter);
            })
            ->when($this->kelasFilter, function ($query) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('kelas_id', $this->kelasFilter);
                });
            })
            ->select('siswa_id', DB::raw('AVG(nilai_akhir) as average'))
            ->with('siswa.kelas')
            ->groupBy('siswa_id')
            ->having('average', '>', 0) // Hanya yang punya nilai
            ->orderBy('average', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'nama' => $item->siswa->nama_lengkap ?? 'N/A',
                    'kelas' => $item->siswa->kelas->nama_kelas ?? 'N/A',
                    'average' => round($item->average, 2)
                ];
            });
    }

    private function getBestSubjects()
    {
        return Nilai::when($this->tahunAjaranFilter, function ($query) {
                $query->where('tahun_ajaran_id', $this->tahunAjaranFilter);
            })
            ->when($this->semesterFilter, function ($query) {
                $query->where('semester', $this->semesterFilter);
            })
            ->when($this->kelasFilter, function ($query) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('kelas_id', $this->kelasFilter);
                });
            })
            ->select('mapel_id', DB::raw('AVG(nilai_akhir) as average'))
            ->with('mapel')
            ->groupBy('mapel_id')
            ->having('average', '>', 0) // Hanya yang punya nilai
            ->orderBy('average', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'mapel' => $item->mapel->nama_mapel ?? 'N/A',
                    'average' => round($item->average, 2)
                ];
            });
    }

    public function exportPDF()
    {
        try {
            $data = [
                'statistics' => $this->statistics,
                'filters' => [
                    'tahun_ajaran' => $this->tahunAjaranList->firstWhere('id', $this->tahunAjaranFilter)?->label ?? 'Semua',
                    'semester' => ucfirst($this->semesterFilter),
                    'kelas' => $this->kelasFilter ? Kelas::find($this->kelasFilter)->nama_kelas : 'Semua Kelas',
                ],
                'export_date' => now()->format('d F Y H:i:s'),
            ];

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.laporan-akademik-pdf', $data);
            
            return response()->streamDownload(
                fn () => print($pdf->output()),
                "laporan-akademik-{$this->semesterFilter}-" . now()->format('Y-m-d') . ".pdf"
            );

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengexport PDF: ' . $e->getMessage());
        }
    }

    public function exportExcel()
    {
        try {
            session()->flash('success', 'Fitur export Excel akan segera tersedia.');
            
            // Untuk sementara, kita akan return success message saja
            // nanti bisa diimplementasi dengan Maatwebsite/Excel
            return;

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengexport Excel: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // ⭐⭐ PERBAIKAN: Pastikan statistics selalu ter-update ⭐⭐
        $this->updateStatistics();

        return view('livewire.admin.laporan-akademik', [
            'statistics' => $this->statistics,
        ]);
    }
}