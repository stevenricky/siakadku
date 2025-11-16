<?php

namespace App\Livewire\Siswa;

use App\Models\Jadwal;
use Livewire\Component;
use Livewire\Attributes\Layout;


#[Layout('layouts.app-new')]
class JadwalPelajaran extends Component

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
        // PERBAIKAN: Tambahkan null checking untuk siswa
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            return view('livewire.siswa.jadwal-pelajaran', [
                'jadwal' => collect(),
                'jadwalMingguan' => collect(),
            ]);
        }

        $jadwal = Jadwal::with(['mapel', 'guru'])
            ->where('kelas_id', $siswa->kelas_id)
            ->when($this->hariFilter, function ($query) {
                $query->where('hari', $this->hariFilter);
            })
            ->orderBy('jam_mulai')
            ->get();

        // Group by hari for weekly view
        $jadwalMingguan = Jadwal::with(['mapel', 'guru'])
            ->where('kelas_id', $siswa->kelas_id)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        // HAPUS layout() dari sini
        return view('livewire.siswa.jadwal-pelajaran', [
            'jadwal' => $jadwal,
            'jadwalMingguan' => $jadwalMingguan,
        ]);
    }
}