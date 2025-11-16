<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Kelas;
use App\Models\Absensi;

#[Layout('layouts.app-new')]
class MonitoringKehadiran extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $kelasId;
    public $tanggal;
    public $tahunAjaranId;

    public function mount()
    {
        $this->tanggal = now()->format('Y-m-d');
    }

    public function render()
    {
        $kelasList = Kelas::whereHas('jadwal', function($query) {
            $query->where('guru_id', auth()->user()->guru->id);
        })->get();

        $absensiList = Absensi::with(['siswa', 'jadwal.kelas', 'jadwal.mapel'])
            ->whereHas('jadwal', function($query) {
                $query->where('guru_id', auth()->user()->guru->id);
            })
            ->when($this->kelasId, function($query) {
                $query->whereHas('siswa', function($q) {
                    $q->where('kelas_id', $this->kelasId);
                });
            })
            ->when($this->tanggal, function($query) {
                $query->whereDate('tanggal', $this->tanggal);
            })
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->whereHas('siswa', function($q2) {
                        $q2->where('nama_lengkap', 'like', '%'.$this->search.'%')
                           ->orWhere('nis', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('jadwal.kelas', function($q3) {
                        $q3->where('nama_kelas', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('jadwal.mapel', function($q4) {
                        $q4->where('nama_mapel', 'like', '%'.$this->search.'%');
                    });
                });
            })
            ->latest()
            ->paginate($this->perPage);

        // Statistik untuk hari ini
        $statistikHariIni = [
            'total' => Absensi::whereHas('jadwal', function($query) {
                $query->where('guru_id', auth()->user()->guru->id);
            })->whereDate('tanggal', $this->tanggal)->count(),
            'hadir' => Absensi::whereHas('jadwal', function($query) {
                $query->where('guru_id', auth()->user()->guru->id);
            })->whereDate('tanggal', $this->tanggal)->where('status', 'hadir')->count(),
            'izin' => Absensi::whereHas('jadwal', function($query) {
                $query->where('guru_id', auth()->user()->guru->id);
            })->whereDate('tanggal', $this->tanggal)->where('status', 'izin')->count(),
            'sakit' => Absensi::whereHas('jadwal', function($query) {
                $query->where('guru_id', auth()->user()->guru->id);
            })->whereDate('tanggal', $this->tanggal)->where('status', 'sakit')->count(),
            'alpha' => Absensi::whereHas('jadwal', function($query) {
                $query->where('guru_id', auth()->user()->guru->id);
            })->whereDate('tanggal', $this->tanggal)->where('status', 'alpha')->count(),
        ];

        // Statistik keseluruhan
        $statistikKeseluruhan = [
            'hadir' => Absensi::whereHas('jadwal', function($query) {
                $query->where('guru_id', auth()->user()->guru->id);
            })->where('status', 'hadir')->count(),
            'izin' => Absensi::whereHas('jadwal', function($query) {
                $query->where('guru_id', auth()->user()->guru->id);
            })->where('status', 'izin')->count(),
            'sakit' => Absensi::whereHas('jadwal', function($query) {
                $query->where('guru_id', auth()->user()->guru->id);
            })->where('status', 'sakit')->count(),
            'alpha' => Absensi::whereHas('jadwal', function($query) {
                $query->where('guru_id', auth()->user()->guru->id);
            })->where('status', 'alpha')->count(),
        ];

        return view('livewire.guru.monitoring-kehadiran', [
            'absensiList' => $absensiList,
            'kelasList' => $kelasList,
            'statistikHariIni' => $statistikHariIni,
            'statistikKeseluruhan' => $statistikKeseluruhan
        ]);
    }

    public function updateStatus($absensiId, $status)
    {
        $absensi = Absensi::findOrFail($absensiId);
        $absensi->update(['status' => $status]);
        
        session()->flash('success', 'Status kehadiran berhasil diupdate.');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'kelasId']);
        $this->tanggal = now()->format('Y-m-d');
    }
}