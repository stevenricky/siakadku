<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ujian;
use Livewire\Attributes\Layout; 

#[Layout(name: 'layouts.app-new')]


class UjianOnline extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $filterStatus = 'semua';

    public function render()
    {
        $siswa = auth()->user()->siswa;
        
        $ujianList = Ujian::with(['mapel', 'guru'])
            ->whereHas('mapel.jadwal', function($query) use ($siswa) {
                $query->where('kelas_id', $siswa->kelas_id);
            })
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('judul', 'like', '%'.$this->search.'%')
                      ->orWhereHas('mapel', function($subQuery) {
                          $subQuery->where('nama_mapel', 'like', '%'.$this->search.'%');
                      });
                });
            })
            ->when($this->filterStatus !== 'semua', function($query) {
                $now = now();
                if ($this->filterStatus === 'akan_datang') {
                    $query->where('waktu_mulai', '>', $now);
                } elseif ($this->filterStatus === 'berlangsung') {
                    $query->where('waktu_mulai', '<=', $now)
                          ->where('waktu_selesai', '>=', $now);
                } elseif ($this->filterStatus === 'selesai') {
                    $query->where('waktu_selesai', '<', $now);
                }
            })
            ->where('is_active', true)
            ->orderBy('waktu_mulai')
            ->paginate($this->perPage);

        return view('livewire.siswa.ujian-online', [
            'ujianList' => $ujianList
        ]);
    }

    public function mulaiUjian($ujianId)
    {
        $ujian = Ujian::findOrFail($ujianId);
        
        // Cek apakah ujian sudah dimulai
        if (now() < $ujian->waktu_mulai) {
            session()->flash('error', 'Ujian belum dimulai.');
            return;
        }

        if (now() > $ujian->waktu_selesai) {
            session()->flash('error', 'Ujian sudah berakhir.');
            return;
        }

        // Redirect ke halaman ujian
        session()->flash('success', 'Memulai ujian...');
        // return redirect()->route('siswa.ujian.kerjakan', $ujianId);
    }

    public function lihatHasil($ujianId)
    {
        session()->flash('info', 'Fitur lihat hasil dalam pengembangan.');
    }
}