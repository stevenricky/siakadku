<?php

namespace App\Livewire\Guru;

use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class InputNilai extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $kelasFilter;
    public $mapelFilter;
    public $semesterFilter = 'ganjil';

    public $kelasList;
    public $mapelList;
    public $tahunAjaranAktif;

    // Form properties
    public $showForm = false;
    public $siswaId;
    public $nilai_uh1;
    public $nilai_uh2;
    public $nilai_uts;
    public $nilai_uas;

    protected $queryString = ['search', 'perPage', 'kelasFilter', 'mapelFilter', 'semesterFilter'];

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
        
        // PERBAIKAN: Tambahkan null checking untuk guru
        $guru = auth()->user()->guru;
        if (!$guru) {
            $this->kelasList = collect();
            $this->mapelList = collect();
            $this->tahunAjaranAktif = null;
            return;
        }

        $guruId = $guru->id;
        
        $this->kelasList = Kelas::whereHas('jadwal', function ($query) use ($guruId) {
            $query->where('guru_id', $guruId);
        })->get() ?? collect();

        $this->mapelList = Mapel::whereHas('jadwal', function ($query) use ($guruId) {
            $query->where('guru_id', $guruId);
        })->get() ?? collect();

        $this->tahunAjaranAktif = TahunAjaran::where('status', 'aktif')->first();
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function openInputForm($siswaId)
    {
        $this->siswaId = $siswaId;
        
        // PERBAIKAN: Tambahkan null checking untuk tahunAjaranAktif
        if (!$this->tahunAjaranAktif) {
            session()->flash('error', 'Tahun ajaran aktif tidak ditemukan.');
            return;
        }

        // Load existing nilai if any
        $nilai = Nilai::where('siswa_id', $siswaId)
            ->where('mapel_id', $this->mapelFilter)
            ->where('tahun_ajaran_id', $this->tahunAjaranAktif->id)
            ->where('semester', $this->semesterFilter)
            ->first();

        if ($nilai) {
            $this->nilai_uh1 = $nilai->nilai_uh1;
            $this->nilai_uh2 = $nilai->nilai_uh2;
            $this->nilai_uts = $nilai->nilai_uts;
            $this->nilai_uas = $nilai->nilai_uas;
        } else {
            $this->reset(['nilai_uh1', 'nilai_uh2', 'nilai_uts', 'nilai_uas']);
        }

        $this->showForm = true;
    }

    public function saveNilai()
    {
        $this->validate([
            'nilai_uh1' => 'required|numeric|min:0|max:100',
            'nilai_uh2' => 'required|numeric|min:0|max:100',
            'nilai_uts' => 'required|numeric|min:0|max:100',
            'nilai_uas' => 'required|numeric|min:0|max:100',
        ]);

        try {
            // PERBAIKAN: Tambahkan null checking untuk guru dan tahunAjaranAktif
            $guru = auth()->user()->guru;
            if (!$guru || !$this->tahunAjaranAktif) {
                session()->flash('error', 'Data guru atau tahun ajaran tidak valid.');
                return;
            }

            $nilaiAkhir = ($this->nilai_uh1 * 0.2) + ($this->nilai_uh2 * 0.2) + 
                         ($this->nilai_uts * 0.3) + ($this->nilai_uas * 0.3);

            $predikat = $this->getPredikat($nilaiAkhir);

            Nilai::updateOrCreate(
                [
                    'siswa_id' => $this->siswaId,
                    'mapel_id' => $this->mapelFilter,
                    'tahun_ajaran_id' => $this->tahunAjaranAktif->id,
                    'semester' => $this->semesterFilter,
                    'guru_id' => $guru->id,
                ],
                [
                    'nilai_uh1' => $this->nilai_uh1,
                    'nilai_uh2' => $this->nilai_uh2,
                    'nilai_uts' => $this->nilai_uts,
                    'nilai_uas' => $this->nilai_uas,
                    'nilai_akhir' => $nilaiAkhir,
                    'predikat' => $predikat,
                    'deskripsi' => $this->getDeskripsi($predikat),
                ]
            );

            session()->flash('success', 'Nilai berhasil disimpan.');
            $this->showForm = false;
            $this->reset(['nilai_uh1', 'nilai_uh2', 'nilai_uts', 'nilai_uas']);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function getPredikat($nilai)
    {
        if ($nilai >= 90) return 'A';
        if ($nilai >= 80) return 'B';
        if ($nilai >= 70) return 'C';
        return 'D';
    }

    private function getDeskripsi($predikat)
    {
        $deskripsi = [
            'A' => 'Sangat baik dalam memahami materi',
            'B' => 'Baik dalam memahami materi',
            'C' => 'Cukup dalam memahami materi',
            'D' => 'Perlu peningkatan dalam memahami materi',
        ];

        return $deskripsi[$predikat] ?? 'Belum ada deskripsi';
    }

    public function render()
    {
        $siswas = collect();

        if ($this->kelasFilter && $this->mapelFilter && $this->tahunAjaranAktif) {
            $siswas = Siswa::with(['nilai' => function ($query) {
                    $query->where('mapel_id', $this->mapelFilter)
                          ->where('tahun_ajaran_id', $this->tahunAjaranAktif->id)
                          ->where('semester', $this->semesterFilter);
                }])
                ->where('kelas_id', $this->kelasFilter)
                ->where('status', 'aktif')
                ->when($this->search, function ($query) {
                    $query->where('nama_lengkap', 'like', '%' . $this->search . '%')
                          ->orWhere('nis', 'like', '%' . $this->search . '%');
                })
                ->orderBy('nama_lengkap')
                ->paginate($this->perPage);
        }

        // HAPUS layout() dari sini
        return view('livewire.guru.input-nilai', [
            'siswas' => $siswas,
        ]);
    }
}