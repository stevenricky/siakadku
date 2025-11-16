<?php

namespace App\Livewire\Siswa;

use App\Models\Nilai;
use App\Models\TahunAjaran;
use App\Models\Siswa;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('layouts.app-new')]
class CetakRapor extends Component
{
    public $semesterFilter = 'ganjil';
    public $tahunAjaranFilter;

    public $tahunAjaranList;

    public function mount()
    {
        $this->tahunAjaranList = TahunAjaran::all();
        
        // Set default to active tahun ajaran
        $activeTahunAjaran = TahunAjaran::where('status', 'aktif')->first();
        if ($activeTahunAjaran) {
            $this->tahunAjaranFilter = $activeTahunAjaran->id;
        }
    }

    public function render()
    {
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            return view('livewire.siswa.cetak-rapor', [
                'nilai' => collect(),
                'statistics' => [
                    'average' => 0,
                    'totalMapel' => 0,
                    'lulus' => 0,
                ],
                'tahunAjaranList' => $this->tahunAjaranList,
            ]);
        }

        // AMBIL NILAI SISWA UNTUK SEMESTER & TAHUN AJARAN YANG DIPILIH
        $nilai = Nilai::with(['mapel', 'guru'])
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $this->tahunAjaranFilter)
            ->where('semester', $this->semesterFilter)
            ->orderBy('mapel_id')
            ->get();

        // Calculate statistics
        $statistics = [
            'average' => $nilai->avg('nilai_akhir') ?? 0,
            'totalMapel' => $nilai->count(),
            'lulus' => $nilai->filter(function ($item) {
                return $item->nilai_akhir >= ($item->mapel->kkm ?? 75);
            })->count(),
        ];

        return view('livewire.siswa.cetak-rapor', [
            'nilai' => $nilai,
            'statistics' => $statistics,
            'tahunAjaranList' => $this->tahunAjaranList,
            'siswa' => $siswa,
        ]);
    }

    public function cetakRapor()
    {
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            session()->flash('error', 'Data siswa tidak ditemukan.');
            return;
        }

        $nilai = Nilai::with(['mapel', 'guru'])
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $this->tahunAjaranFilter)
            ->where('semester', $this->semesterFilter)
            ->orderBy('mapel_id')
            ->get();
        
        if ($nilai->isEmpty()) {
            session()->flash('error', 'Tidak ada data nilai untuk semester ini.');
            return;
        }

        $tahunAjaran = TahunAjaran::find($this->tahunAjaranFilter);
        
        // Bersihkan nama file
        $namaSiswa = preg_replace('/[\/\\\\]/', '-', $siswa->nama_lengkap);
        $tahunAjaranClean = $tahunAjaran ? preg_replace('/[\/\\\\:\s]/', '-', $tahunAjaran->tahun_ajaran) : '';
        
        $data = [
            'nilai' => $nilai,
            'siswa' => $siswa,
            'tahunAjaran' => $tahunAjaran,
            'semester' => $this->semesterFilter,
            'statistics' => [
                'average' => $nilai->avg('nilai_akhir') ?? 0,
                'totalMapel' => $nilai->count(),
                'lulus' => $nilai->filter(function ($item) {
                    return $item->nilai_akhir >= ($item->mapel->kkm ?? 75);
                })->count(),
            ]
        ];

        $pdf = Pdf::loadView('exports.rapor', $data);
        
        $filename = "rapor-{$namaSiswa}-{$this->semesterFilter}-{$tahunAjaranClean}.pdf";
        
        return response()->streamDownload(
            function () use ($pdf) {
                echo $pdf->output();
            },
            $filename
        );
    }

    public function downloadRapor()
    {
        return $this->cetakRapor();
    }
}