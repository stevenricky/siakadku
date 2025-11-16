<?php

namespace App\Livewire\Siswa;

use App\Models\Absensi as AbsensiModel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class DataAbsensi extends Component

{
    use WithPagination;

    public $bulanFilter;
    public $tahunFilter;

    public function mount()
    {
        $this->bulanFilter = now()->month;
        $this->tahunFilter = now()->year;
    }

    public function render()
    {
        // PERBAIKAN: Tambahkan null checking untuk siswa
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            return view('livewire.siswa.data-absensi', [
                'absensi' => collect()->paginate(20),
                'statistics' => [
                    'hadir' => 0,
                    'sakit' => 0,
                    'izin' => 0,
                    'alpha' => 0,
                    'total' => 0
                ],
            ]);
        }

        $absensi = AbsensiModel::with(['jadwal.mapel'])
            ->where('siswa_id', $siswa->id)
            ->when($this->bulanFilter, function ($query) {
                $query->whereMonth('tanggal', $this->bulanFilter);
            })
            ->when($this->tahunFilter, function ($query) {
                $query->whereYear('tanggal', $this->tahunFilter);
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(20);

        // Statistics dengan fallback
        $statistics = [
            'hadir' => 0,
            'sakit' => 0,
            'izin' => 0,
            'alpha' => 0,
            'total' => 0
        ];

        if (method_exists(AbsensiModel::class, 'getStatistikKehadiran')) {
            $statistics = AbsensiModel::getStatistikKehadiran(
                $siswa->id, 
                $this->bulanFilter, 
                $this->tahunFilter
            ) ?? $statistics;
        }

        // HAPUS layout() dari sini
        return view('livewire.siswa.data-absensi', [
            'absensi' => $absensi,
            'statistics' => $statistics,
        ]);
    }
}