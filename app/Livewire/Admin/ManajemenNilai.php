<?php

namespace App\Livewire\Admin;

use App\Models\Nilai;
use App\Models\TahunAjaran;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app-new')]
class ManajemenNilai extends Component
{
    use WithPagination;

    public $search = '';
    public $tahunAjaranFilter = '';
    public $semesterFilter = '';
    public $mapelFilter = '';
    public $kelasFilter = '';
    public $predikatFilter = '';
    public $statusFilter = '';
    public $rentangNilaiFilter = '';
    public $sortField = 'nilai_akhir';
    public $sortDirection = 'desc';

    public $showDetailModal = false;
    public $showFormModal = false;
    public $formType = 'create';
    public $selectedNilai = null;
    public $isExporting = false; // Tambahan untuk loading state

    // Form properties
    public $nilaiId;
    public $siswa_id;
    public $mapel_id;
    public $guru_id;
    public $tahun_ajaran_id;
    public $semester = 'Ganjil';
    public $nilai_uh1 = 0;
    public $nilai_uh2 = 0;
    public $nilai_uts = 0;
    public $nilai_uas = 0;
    public $nilai_akhir = 0;
    public $predikat = '';
    public $deskripsi = '';

    // Stats
    public $totalSiswa = 0;
    public $rataRataKelas = 0;
    public $nilaiTertinggi = 0;
    public $persentaseLulus = 0;

    // Real-time calculation
    public $isCalculating = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'tahunAjaranFilter' => ['except' => ''],
        'semesterFilter' => ['except' => ''],
        'mapelFilter' => ['except' => ''],
        'kelasFilter' => ['except' => ''],
        'predikatFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'rentangNilaiFilter' => ['except' => ''],
    ];

    protected $rules = [
        'siswa_id' => 'required|exists:siswas,id',
        'mapel_id' => 'required|exists:mapels,id',
        'guru_id' => 'required|exists:gurus,id',
        'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        'semester' => 'required|in:Ganjil,Genap',
        'nilai_uh1' => 'required|numeric|min:0|max:100',
        'nilai_uh2' => 'required|numeric|min:0|max:100',
        'nilai_uts' => 'required|numeric|min:0|max:100',
        'nilai_uas' => 'required|numeric|min:0|max:100',
        'deskripsi' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->tahun_ajaran_id = TahunAjaran::where('status', 'aktif')->first()?->id;
        $this->calculateStats();
    }

    // Real-time calculation when nilai components change
    public function updatedNilaiUh1() { $this->calculateNilaiAkhir(); }
    public function updatedNilaiUh2() { $this->calculateNilaiAkhir(); }
    public function updatedNilaiUts() { $this->calculateNilaiAkhir(); }
    public function updatedNilaiUas() { $this->calculateNilaiAkhir(); }

    public function calculateNilaiAkhir()
    {
        $this->isCalculating = true;
        
        $this->nilai_akhir = Nilai::hitungNilaiAkhir(
            $this->nilai_uh1,
            $this->nilai_uh2,
            $this->nilai_uts,
            $this->nilai_uas
        );

        // Auto calculate predikat
        $this->predikat = $this->calculatePredikat($this->nilai_akhir);
        
        $this->isCalculating = false;
    }

    private function calculatePredikat($nilai)
    {
        if ($nilai >= 90) return 'A';
        if ($nilai >= 80) return 'B';
        if ($nilai >= 70) return 'C';
        if ($nilai >= 60) return 'D';
        return 'E';
    }

    public function calculateStats()
    {
        // Total semua siswa aktif
        $this->totalSiswa = Siswa::where('status', 'aktif')->count();
        
        // Stats berdasarkan filter
        $query = Nilai::with(['siswa', 'mapel', 'guru', 'tahun_ajaran']);
        $filteredQuery = $this->applyFilters($query);
        
        $this->rataRataKelas = $filteredQuery->avg('nilai_akhir') ?? 0;
        $this->nilaiTertinggi = $filteredQuery->max('nilai_akhir') ?? 0;
        
        // Hitung persentase lulus
        $totalNilai = $filteredQuery->count();
        if ($totalNilai > 0) {
            $lulusCount = $filteredQuery->whereHas('mapel', function ($q) {
                $q->whereRaw('nilais.nilai_akhir >= mapels.kkm');
            })->count();
            $this->persentaseLulus = round(($lulusCount / $totalNilai) * 100, 1);
        } else {
            $this->persentaseLulus = 0;
        }
    }

    public function updated()
    {
        $this->resetPage();
        $this->calculateStats();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function resetFilters()
    {
        $this->reset([
            'search', 'tahunAjaranFilter', 'semesterFilter', 
            'mapelFilter', 'kelasFilter', 'predikatFilter', 
            'statusFilter', 'rentangNilaiFilter'
        ]);
        $this->calculateStats();
    }

    public function showDetail($nilaiId)
{
    $this->selectedNilai = Nilai::with([
        'siswa.kelas', 
        'mapel', 
        'guru', 
        'tahunAjaran' // GANTI: tahun_ajaran -> tahunAjaran
    ])->find($nilaiId);
    $this->showDetailModal = true;
}

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedNilai = null;
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->formType = 'create';
        $this->showFormModal = true;
    }

    public function openEditForm($nilaiId)
    {
        $nilai = Nilai::findOrFail($nilaiId);
        
        $this->nilaiId = $nilai->id;
        $this->siswa_id = $nilai->siswa_id;
        $this->mapel_id = $nilai->mapel_id;
        $this->guru_id = $nilai->guru_id;
        $this->tahun_ajaran_id = $nilai->tahun_ajaran_id;
        $this->semester = $nilai->semester;
        $this->nilai_uh1 = $nilai->nilai_uh1;
        $this->nilai_uh2 = $nilai->nilai_uh2;
        $this->nilai_uts = $nilai->nilai_uts;
        $this->nilai_uas = $nilai->nilai_uas;
        $this->nilai_akhir = $nilai->nilai_akhir;
        $this->predikat = $nilai->predikat;
        $this->deskripsi = $nilai->deskripsi;
        
        $this->formType = 'edit';
        $this->showFormModal = true;
    }

    public function closeForm()
    {
        $this->showFormModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'nilaiId', 'siswa_id', 'mapel_id', 'guru_id', 'tahun_ajaran_id',
            'semester', 'nilai_uh1', 'nilai_uh2', 'nilai_uts', 'nilai_uas',
            'nilai_akhir', 'predikat', 'deskripsi'
        ]);
        $this->resetErrorBag();
        $this->tahun_ajaran_id = TahunAjaran::where('status', 'aktif')->first()?->id;
    }

    public function saveNilai()
    {
        $this->validate();

        try {
            $data = [
                'siswa_id' => $this->siswa_id,
                'mapel_id' => $this->mapel_id,
                'guru_id' => $this->guru_id,
                'tahun_ajaran_id' => $this->tahun_ajaran_id,
                'semester' => $this->semester,
                'nilai_uh1' => $this->nilai_uh1,
                'nilai_uh2' => $this->nilai_uh2,
                'nilai_uts' => $this->nilai_uts,
                'nilai_uas' => $this->nilai_uas,
                'nilai_akhir' => $this->nilai_akhir,
                'predikat' => $this->predikat,
                'deskripsi' => $this->deskripsi,
            ];

            if ($this->formType === 'create') {
                Nilai::create($data);
                session()->flash('success', 'Nilai berhasil ditambahkan!');
            } else {
                $nilai = Nilai::findOrFail($this->nilaiId);
                $nilai->update($data);
                session()->flash('success', 'Nilai berhasil diperbarui!');
            }

            $this->closeForm();
            $this->calculateStats();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteNilai($nilaiId)
    {
        try {
            $nilai = Nilai::findOrFail($nilaiId);
            $nilai->delete();
            
            session()->flash('success', 'Nilai berhasil dihapus!');
            $this->calculateStats();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getPredikatColor($predikat)
    {
        $colors = [
            'A' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'B' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'C' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'D' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
            'E' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        ];

        return $colors[$predikat] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
    }

    public function getStatusColor($status)
    {
        return $status === 'Lulus' 
            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
    }

    public function exportCSV()
    {
        $this->isExporting = true;
        
        try {
            $query = $this->buildExportQuery();
            
            $filename = 'data-nilai-' . date('Y-m-d-H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            return new StreamedResponse(function () use ($query) {
                $handle = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fwrite($handle, "\xEF\xBB\xBF");
                
                // Header
                fputcsv($handle, [
                    'NIS',
                    'Nama Siswa',
                    'Kelas',
                    'Mata Pelajaran',
                    'Guru Pengajar',
                    'Tahun Ajaran',
                    'Semester',
                    'Nilai UH 1',
                    'Nilai UH 2',
                    'Nilai UTS',
                    'Nilai UAS',
                    'Nilai Akhir',
                    'Predikat',
                    'Status',
                    'KKM',
                    'Deskripsi',
                    'Tanggal Input'
                ]);

                // Data dengan chunk untuk hemat memory
                $query->chunk(1000, function ($nilais) use ($handle) {
                    foreach ($nilais as $nilai) {
                        $status = $nilai->nilai_akhir >= ($nilai->mapel->kkm ?? 75) ? 'Lulus' : 'Tidak Lulus';
                        
                        fputcsv($handle, [
                            $nilai->siswa->nis,
                            $nilai->siswa->nama_lengkap,
                            $nilai->siswa->kelas->nama_kelas ?? '-',
                            $nilai->mapel->nama_mapel,
                            $nilai->guru->nama_lengkap,
                            $nilai->tahun_ajaran->tahun_awal . '/' . $nilai->tahun_ajaran->tahun_akhir,
                            $nilai->semester,
                            number_format($nilai->nilai_uh1, 2),
                            number_format($nilai->nilai_uh2, 2),
                            number_format($nilai->nilai_uts, 2),
                            number_format($nilai->nilai_uas, 2),
                            number_format($nilai->nilai_akhir, 2),
                            $nilai->predikat,
                            $status,
                            $nilai->mapel->kkm ?? 75,
                            $nilai->deskripsi ?? '-',
                            $nilai->created_at->format('d/m/Y H:i')
                        ]);
                    }
                });

                fclose($handle);
            }, 200, $headers);

        } catch (\Exception $e) {
            $this->isExporting = false;
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan saat mengekspor data: ' . $e->getMessage()
            ]);
        } finally {
            // Reset loading state setelah download selesai
            $this->dispatch('export-finished');
        }
    }

    // Method untuk reset loading state dari JavaScript
    public function resetExporting()
    {
        $this->isExporting = false;
    }

    protected function buildExportQuery()
{
    $query = Nilai::with([
        'siswa:id,nama_lengkap,nis,kelas_id',
        'siswa.kelas:id,nama_kelas',
        'mapel:id,nama_mapel,kkm',
        'guru:id,nama_lengkap',
        'tahunAjaran:id,tahun_awal,tahun_akhir,semester' // GANTI: tahun_ajaran -> tahunAjaran
    ]);

    return $this->applyFilters($query)->orderBy('nilai_akhir', 'desc');
}

    protected function applyFilters($query)
    {
        return $query
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('siswa', function ($q) {
                        $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                          ->orWhere('nis', 'like', '%' . $this->search . '%');
                    })->orWhereHas('mapel', function ($q) {
                        $q->where('nama_mapel', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->tahunAjaranFilter, function ($query) {
                $query->where('tahun_ajaran_id', $this->tahunAjaranFilter);
            })
            ->when($this->semesterFilter, function ($query) {
                $query->where('semester', $this->semesterFilter);
            })
            ->when($this->mapelFilter, function ($query) {
                $query->where('mapel_id', $this->mapelFilter);
            })
            ->when($this->kelasFilter, function ($query) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('kelas_id', $this->kelasFilter);
                });
            })
            ->when($this->predikatFilter, function ($query) {
                $query->where('predikat', $this->predikatFilter);
            })
            ->when($this->statusFilter, function ($query) {
                if ($this->statusFilter === 'Lulus') {
                    $query->whereHas('mapel', function ($q) {
                        $q->whereRaw('nilais.nilai_akhir >= mapels.kkm');
                    });
                } elseif ($this->statusFilter === 'Tidak Lulus') {
                    $query->whereHas('mapel', function ($q) {
                        $q->whereRaw('nilais.nilai_akhir < mapels.kkm');
                    });
                }
            })
            ->when($this->rentangNilaiFilter, function ($query) {
                if ($this->rentangNilaiFilter === '90-100') {
                    $query->whereBetween('nilai_akhir', [90, 100]);
                } elseif ($this->rentangNilaiFilter === '80-89') {
                    $query->whereBetween('nilai_akhir', [80, 89.99]);
                } elseif ($this->rentangNilaiFilter === '70-79') {
                    $query->whereBetween('nilai_akhir', [70, 79.99]);
                } elseif ($this->rentangNilaiFilter === '0-69') {
                    $query->whereBetween('nilai_akhir', [0, 69.99]);
                }
            });
    }

    public function render()
{
    // Query utama dengan select specific columns dan eager loading optimized
    $query = Nilai::with([
        'siswa:id,nama_lengkap,nis,kelas_id',
        'siswa.kelas:id,nama_kelas',
        'mapel:id,nama_mapel,kkm',
        'guru:id,nama_lengkap',
        'tahunAjaran:id,tahun_awal,tahun_akhir,semester' // GANTI: tahun_ajaran -> tahunAjaran
    ]);

    $filteredQuery = $this->applyFilters($query);

    $nilais = $filteredQuery->orderBy($this->sortField, $this->sortDirection)
        ->paginate(10);

    // Data untuk dropdown
    $tahunAjaranList = TahunAjaran::all()->map(function ($tahun) {
        return [
            'id' => $tahun->id,
            'label' => $tahun->tahun_awal . '/' . $tahun->tahun_akhir . ' - ' . $tahun->semester
        ];
    });

    return view('livewire.admin.manajemen-nilai', [
        'nilais' => $nilais,
        'tahunAjaranList' => $tahunAjaranList,
        'mapelList' => Mapel::select('id', 'nama_mapel')->get(),
        'kelasList' => Kelas::select('id', 'nama_kelas')->get(),
        'siswaList' => Siswa::where('status', 'aktif')->select('id', 'nama_lengkap', 'nis')->get(),
        'guruList' => Guru::where('status', 'aktif')->select('id', 'nama_lengkap')->get(),
    ]);
}
}