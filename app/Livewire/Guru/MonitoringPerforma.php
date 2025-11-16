<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Mapel;
use App\Models\TahunAjaran;

#[Layout('layouts.app-new')]
class MonitoringPerforma extends Component
{
    public $kelasId;
    public $mapelId;
    public $semester;
    public $tahunAjaranId;
    public $search = '';

    public function mount()
    {
        // Set tahun ajaran aktif sebagai default
        $tahunAjaranAktif = TahunAjaran::where('status', 'aktif')->first();
        if ($tahunAjaranAktif) {
            $this->tahunAjaranId = $tahunAjaranAktif->id;
        }
    }

    public function render()
    {
        $kelasList = Kelas::whereHas('siswa.nilai', function($query) {
            $query->where('guru_id', auth()->user()->guru->id);
        })->get();

        $mapelList = Mapel::whereHas('nilai', function($query) {
            $query->where('guru_id', auth()->user()->guru->id);
        })->get();

        $tahunAjaranList = TahunAjaran::all();

        $performaData = collect();
        if ($this->kelasId) {
            $performaData = Nilai::with(['siswa.kelas', 'mapel', 'tahun_ajaran'])
                ->where('guru_id', auth()->user()->guru->id)
                ->when($this->kelasId, function($query) {
                    $query->whereHas('siswa', function($q) {
                        $q->where('kelas_id', $this->kelasId);
                    });
                })
                ->when($this->mapelId, function($query) {
                    $query->where('mapel_id', $this->mapelId);
                })
                ->when($this->semester, function($query) {
                    $query->where('semester', $this->semester);
                })
                ->when($this->tahunAjaranId, function($query) {
                    $query->where('tahun_ajaran_id', $this->tahunAjaranId);
                })
                ->when($this->search, function($query) {
                    $query->whereHas('siswa', function($q) {
                        $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                          ->orWhere('nis', 'like', '%' . $this->search . '%');
                    });
                })
                ->get()
                ->groupBy('siswa_id')
                ->map(function($nilaiSiswa) {
                    $siswa = $nilaiSiswa->first()->siswa;
                    $rataRata = $nilaiSiswa->avg('nilai_akhir');
                    $totalNilai = $nilaiSiswa->sum('nilai_akhir');
                    
                    // Hitung jumlah nilai lulus dan tidak lulus
                    $lulus = $nilaiSiswa->filter(function($nilai) {
                        return $nilai->status === 'Lulus';
                    })->count();
                    
                    $tidakLulus = $nilaiSiswa->count() - $lulus;

                    // Tentukan kategori performa
                    if ($rataRata >= 90) {
                        $kategori = 'Sangat Baik';
                        $warna = 'green';
                    } elseif ($rataRata >= 80) {
                        $kategori = 'Baik';
                        $warna = 'blue';
                    } elseif ($rataRata >= 70) {
                        $kategori = 'Cukup';
                        $warna = 'yellow';
                    } else {
                        $kategori = 'Perlu Perhatian';
                        $warna = 'red';
                    }

                    return [
                        'siswa' => $siswa,
                        'rata_rata' => round($rataRata, 2),
                        'total_nilai' => $totalNilai,
                        'jumlah_mapel' => $nilaiSiswa->count(),
                        'nilai_tertinggi' => $nilaiSiswa->max('nilai_akhir'),
                        'nilai_terendah' => $nilaiSiswa->min('nilai_akhir'),
                        'lulus' => $lulus,
                        'tidak_lulus' => $tidakLulus,
                        'kategori' => $kategori,
                        'warna' => $warna,
                        'detail_nilai' => $nilaiSiswa->map(function($nilai) {
                            return [
                                'mapel' => $nilai->mapel->nama_mapel ?? '-',
                                'nilai_akhir' => $nilai->nilai_akhir,
                                'predikat' => $nilai->predikat,
                                'status' => $nilai->status,
                                'keterangan' => $nilai->deskripsi ?? '-'
                            ];
                        })
                    ];
                })
                ->sortByDesc('rata_rata')
                ->values();
        }

        // Hitung statistik
        $statistik = [
            'total_siswa' => $performaData->count(),
            'rata_rata_kelas' => $performaData->avg('rata_rata') ? round($performaData->avg('rata_rata'), 2) : 0,
            'tertinggi' => $performaData->max('rata_rata') ? round($performaData->max('rata_rata'), 2) : 0,
            'terendah' => $performaData->min('rata_rata') ? round($performaData->min('rata_rata'), 2) : 0,
            'total_lulus' => $performaData->sum('lulus'),
            'total_tidak_lulus' => $performaData->sum('tidak_lulus'),
        ];

        return view('livewire.guru.monitoring-performa', [
            'kelasList' => $kelasList,
            'mapelList' => $mapelList,
            'tahunAjaranList' => $tahunAjaranList,
            'performaData' => $performaData,
            'statistik' => $statistik
        ]);
    }

    public function resetFilters()
    {
        $this->reset(['kelasId', 'mapelId', 'semester', 'search']);
        // Reset tahun ajaran ke aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'aktif')->first();
        if ($tahunAjaranAktif) {
            $this->tahunAjaranId = $tahunAjaranAktif->id;
        }
    }
}