<?php

namespace App\Livewire\Siswa;

use App\Models\Absensi as AbsensiModel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class Absensi extends Component
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
        $siswa = auth()->user()->siswa;

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

        // Statistics
        $statistics = AbsensiModel::getStatistikKehadiran(
            $siswa->id, 
            $this->bulanFilter, 
            $this->tahunFilter
        );

        return view('livewire.siswa.data-absensi', [
            'absensi' => $absensi,
            'statistics' => $statistics,
        ])->layout('layouts.siswa', [
            'title' => 'Data Absensi',
            'subtitle' => 'Riwayat kehadiran Anda'
        ]);
    }
}