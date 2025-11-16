<?php

namespace App\Livewire\Guru;

use App\Models\Jadwal;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class JadwalMengajar extends Component
{

    public $hariFilter;

    public $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    public function mount()
    {
        $this->hariFilter = now()->translatedFormat('l');
        $hariMapping = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        $this->hariFilter = $hariMapping[$this->hariFilter] ?? 'Senin';
    }

    public function render()
    {
        // PERBAIKAN: Tambahkan null checking untuk guru
        $guru = auth()->user()->guru;
        if (!$guru) {
            return view('livewire.guru.jadwal-mengajar', [
                'jadwal' => collect(),
                'jadwalMingguan' => collect(),
            ]);
        }

        $guruId = $guru->id;

        $jadwal = Jadwal::with(['kelas', 'mapel'])
            ->where('guru_id', $guruId)
            ->when($this->hariFilter, function ($query) {
                $query->where('hari', $this->hariFilter);
            })
            ->orderBy('jam_mulai')
            ->get();

        // Group by hari for weekly view
        $jadwalMingguan = Jadwal::with(['kelas', 'mapel'])
            ->where('guru_id', $guruId)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        // HAPUS layout() dari sini
        return view('livewire.guru.jadwal-mengajar', [
            'jadwal' => $jadwal,
            'jadwalMingguan' => $jadwalMingguan,
        ]);
    }
}