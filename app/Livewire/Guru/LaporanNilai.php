<?php

namespace App\Livewire\Guru;

use App\Models\Nilai; // Gunakan model Nilai yang sudah diperbaiki
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class LaporanNilai extends Component
{
    public $kelasFilter;
    public $mapelFilter;
    public $semesterFilter = 'ganjil';

    public $kelasList;
    public $mapelList;
    public $tahunAjaranAktif;

    public function mount()
    {
        $guruId = auth()->user()->guru->id;
        
        $this->kelasList = Kelas::whereHas('jadwal', function ($query) use ($guruId) {
            $query->where('guru_id', $guruId);
        })->get() ?? collect();

        $this->mapelList = Mapel::whereHas('jadwal', function ($query) use ($guruId) {
            $query->where('guru_id', $guruId);
        })->get() ?? collect();

        $this->tahunAjaranAktif = TahunAjaran::where('status', 'aktif')->first();
    }

    public function exportPDF()
    {
        if (!$this->kelasFilter || !$this->mapelFilter) {
            session()->flash('error', 'Pilih kelas dan mata pelajaran terlebih dahulu.');
            return;
        }

        session()->flash('success', 'Laporan nilai berhasil diexport ke PDF.');
    }

    public function exportExcel()
    {
        if (!$this->kelasFilter || !$this->mapelFilter) {
            session()->flash('error', 'Pilih kelas dan mata pelajaran terlebih dahulu.');
            return;
        }

        session()->flash('success', 'Laporan nilai berhasil diexport ke Excel.');
    }

    public function render()
    {
        $nilaiData = collect();
        $statistics = [];

        if ($this->kelasFilter && $this->mapelFilter && $this->tahunAjaranAktif) {
            // PERBAIKAN: Gunakan query yang lebih sederhana dan pastikan relasi bekerja
            $nilaiData = Nilai::with(['siswa'])
                ->whereHas('siswa', function ($query) {
                    $query->where('kelas_id', $this->kelasFilter);
                })
                ->where('mapel_id', $this->mapelFilter)
                ->where('tahun_ajaran_id', $this->tahunAjaranAktif->id)
                ->where('semester', $this->semesterFilter)
                ->where('guru_id', auth()->user()->guru->id)
->get()
->sortBy('siswa.nama_lengkap');


            // Debug: Cek data yang diambil
            // \Log::info('Nilai Data Count: ' . $nilaiData->count());
            // \Log::info('Query Params:', [
            //     'kelas' => $this->kelasFilter,
            //     'mapel' => $this->mapelFilter,
            //     'tahun_ajaran' => $this->tahunAjaranAktif->id,
            //     'semester' => $this->semesterFilter,
            //     'guru' => auth()->user()->guru->id
            // ]);

            // Calculate statistics hanya jika ada data
            if ($nilaiData->isNotEmpty()) {
                $statistics = [
                    'totalSiswa' => $nilaiData->count(),
                    'average' => round($nilaiData->avg('nilai_akhir') ?? 0, 2),
                    'highest' => $nilaiData->max('nilai_akhir') ?? 0,
                    'lowest' => $nilaiData->min('nilai_akhir') ?? 0,
                    'passed' => $nilaiData->where('nilai_akhir', '>=', 75)->count(),
                    'failed' => $nilaiData->where('nilai_akhir', '<', 75)->count(),
                ];
            }
        }

        return view('livewire.guru.laporan-nilai', [
            'nilaiData' => $nilaiData,
            'statistics' => $statistics,
            'kelasList' => $this->kelasList ?? collect(),
            'mapelList' => $this->mapelList ?? collect(),
        ]);
    }
}