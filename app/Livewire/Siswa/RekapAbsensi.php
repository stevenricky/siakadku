<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use App\Models\Absensi;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class RekapAbsensi extends Component
{
    public $bulan;
    public $tahun;
    public $semesterId;

    public function mount()
    {
        $this->bulan = now()->month;
        $this->tahun = now()->year;
    }

    public function render()
    {
        $siswa = auth()->user()->siswa;
        
        $absensiList = Absensi::with(['jadwal.mapel'])
            ->where('siswa_id', $siswa->id)
            ->when($this->bulan, function($query) {
                $query->whereMonth('tanggal', $this->bulan);
            })
            ->when($this->tahun, function($query) {
                $query->whereYear('tanggal', $this->tahun);
            })
            // HAPUS filter semester_id karena kolom tidak ada
            ->orderBy('tanggal', 'desc')
            ->get();

        // Hitung rekap per mapel
        $rekapPerMapel = $absensiList->groupBy('jadwal.mapel.nama_mapel')
            ->map(function($absensiMapel, $mapel) {
                return [
                    'mapel' => $mapel,
                    'hadir' => $absensiMapel->where('status', 'hadir')->count(),
                    'izin' => $absensiMapel->where('status', 'izin')->count(),
                    'sakit' => $absensiMapel->where('status', 'sakit')->count(),
                    'alpha' => $absensiMapel->where('status', 'alpha')->count(),
                    'total' => $absensiMapel->count(),
                    'persentase_hadir' => $absensiMapel->count() > 0 ? 
                        round(($absensiMapel->where('status', 'hadir')->count() / $absensiMapel->count()) * 100, 1) : 0,
                ];
            })->values();

        // Statistik keseluruhan
        $statistik = [
            'hadir' => $absensiList->where('status', 'hadir')->count(),
            'izin' => $absensiList->where('status', 'izin')->count(),
            'sakit' => $absensiList->where('status', 'sakit')->count(),
            'alpha' => $absensiList->where('status', 'alpha')->count(),
            'total' => $absensiList->count(),
            'persentase_kehadiran' => $absensiList->count() > 0 ? 
                round(($absensiList->where('status', 'hadir')->count() / $absensiList->count()) * 100, 1) : 0,
        ];

        return view('livewire.siswa.rekap-absensi', [
            'absensiList' => $absensiList,
            'rekapPerMapel' => $rekapPerMapel,
            'statistik' => $statistik,
            'siswa' => $siswa
        ]);
    }

    public function exportExcel()
    {
        session()->flash('success', 'Fitur export Excel dalam pengembangan.');
    }

    public function exportPDF()
    {
        session()->flash('success', 'Fitur export PDF dalam pengembangan.');
    }
}