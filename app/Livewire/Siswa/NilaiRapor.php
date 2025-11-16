<?php

namespace App\Livewire\Siswa;

use App\Models\Nilai;
use App\Models\TahunAjaran;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout(name: 'layouts.app-new')]
class NilaiRapor extends Component
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
        // PERBAIKAN: Tambahkan null checking untuk siswa
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            return view('livewire.siswa.nilai-rapor', [
                'nilai' => collect(),
                'statistics' => [
                    'average' => 0,
                    'totalMapel' => 0,
                    'lulus' => 0,
                ],
            ]);
        }

        // PERBAIKAN: Gunakan join untuk order by mapel.nama_mapel
        $nilai = Nilai::with(['mapel', 'guru'])
            ->select('nilais.*')
            ->join('mapels', 'nilais.mapel_id', '=', 'mapels.id') // Join dengan tabel mapels
            ->where('nilais.siswa_id', $siswa->id)
            ->when($this->tahunAjaranFilter, function ($query) {
                $query->where('nilais.tahun_ajaran_id', $this->tahunAjaranFilter);
            })
            ->when($this->semesterFilter, function ($query) {
                $query->where('nilais.semester', $this->semesterFilter);
            })
            ->orderBy('mapels.nama_mapel') // Sekarang bisa order by karena sudah join
            ->get();

        // Calculate statistics
        $statistics = [
            'average' => $nilai->avg('nilai_akhir') ?? 0,
            'totalMapel' => $nilai->count(),
            'lulus' => $nilai->filter(function ($item) {
                // Gunakan accessor status atau hitung manual
                return $item->nilai_akhir >= ($item->mapel->kkm ?? 75);
            })->count(),
        ];

        // HAPUS layout() dari sini
        return view('livewire.siswa.nilai-rapor', [
            'nilai' => $nilai,
            'statistics' => $statistics,
        ]);
    }
}