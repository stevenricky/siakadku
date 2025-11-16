<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AgendaSekolah;
use App\Models\Kelas;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class AgendaKelas extends Component
{
    use WithPagination;

    // Properties untuk filter
    public $search = '';
    public $jenisFilter = '';
    public $statusFilter = '';
    public $tanggalFilter = '';
    public $perPage = 10;

    // Properties untuk modal detail
    public $showDetailModal = false;
    public $selectedAgenda = null;

    // Data untuk kalender
    public $viewMode = 'list'; // list, calendar
    public $currentMonth;
    public $currentYear;

    protected $queryString = ['search', 'jenisFilter', 'statusFilter', 'tanggalFilter', 'perPage'];

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
    }

    public function render()
    {
        $guru = auth()->user()->guru;
        
        $query = AgendaSekolah::query()
            ->when($this->search, function($q) {
                $q->where(function($query) {
                    $query->where('judul_agenda', 'like', '%' . $this->search . '%')
                          ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                          ->orWhere('tempat', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->jenisFilter, function($q) {
                $q->where('jenis_agenda', $this->jenisFilter);
            })
            ->when($this->statusFilter, function($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->tanggalFilter, function($q) {
                $q->whereDate('tanggal_mulai', $this->tanggalFilter);
            });

        // Filter berdasarkan sasaran (kelas guru jika wali kelas)
        if ($guru->kelasWali) {
            $kelasGuru = $guru->kelasWali;
            $query->where(function($q) use ($kelasGuru) {
                $q->where('sasaran', 'seluruh_sekolah')
                  ->orWhere('sasaran', 'siswa')
                  ->orWhere('sasaran', 'kelas_' . strtolower(str_replace(' ', '_', $kelasGuru->nama_kelas)));
            });
        }

        $agenda = $query->latest()->paginate($this->perPage);

        // Hitung statistik
        $statsQuery = AgendaSekolah::query();
        
        if ($guru->kelasWali) {
            $kelasGuru = $guru->kelasWali;
            $statsQuery->where(function($q) use ($kelasGuru) {
                $q->where('sasaran', 'seluruh_sekolah')
                  ->orWhere('sasaran', 'siswa')
                  ->orWhere('sasaran', 'kelas_' . strtolower(str_replace(' ', '_', $kelasGuru->nama_kelas)));
            });
        }

        $totalMendatang = (clone $statsQuery)->where('status', 'terjadwal')->count();
        $totalBerlangsung = (clone $statsQuery)->where('status', 'berlangsung')->count();
        $totalSelesai = (clone $statsQuery)->where('status', 'selesai')->count();

        // Data untuk kalender
        $calendarEvents = [];
        if ($this->viewMode === 'calendar') {
            $calendarQuery = AgendaSekolah::query();
            
            if ($guru->kelasWali) {
                $kelasGuru = $guru->kelasWali;
                $calendarQuery->where(function($q) use ($kelasGuru) {
                    $q->where('sasaran', 'seluruh_sekolah')
                      ->orWhere('sasaran', 'siswa')
                      ->orWhere('sasaran', 'kelas_' . strtolower(str_replace(' ', '_', $kelasGuru->nama_kelas)));
                });
            }

            $calendarEvents = $calendarQuery->get()->map(function($agenda) {
                return [
                    'id' => $agenda->id,
                    'title' => $agenda->judul_agenda,
                    'start' => $agenda->tanggal_mulai->format('Y-m-d'),
                    'end' => $agenda->tanggal_selesai->format('Y-m-d'),
                    'color' => $this->getEventColor($agenda->jenis_agenda),
                    'extendedProps' => [
                        'tempat' => $agenda->tempat,
                        'status' => $agenda->status,
                    ]
                ];
            });
        }

        return view('livewire.guru.agenda-kelas', [
            'agenda' => $agenda,
            'totalMendatang' => $totalMendatang,
            'totalBerlangsung' => $totalBerlangsung,
            'totalSelesai' => $totalSelesai,
            'guru' => $guru,
            'calendarEvents' => $calendarEvents,
            'currentMonth' => $this->currentMonth,
            'currentYear' => $this->currentYear,
        ]);
    }

    public function showDetail($id)
    {
        $this->selectedAgenda = AgendaSekolah::findOrFail($id);
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedAgenda = null;
    }

    public function toggleViewMode()
    {
        $this->viewMode = $this->viewMode === 'list' ? 'calendar' : 'list';
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $date->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $date->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function goToToday()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'jenisFilter', 'statusFilter', 'tanggalFilter']);
        $this->resetPage();
    }

    private function getEventColor($jenisAgenda)
    {
        return match($jenisAgenda) {
            'akademik' => '#3b82f6', // blue
            'non_akademik' => '#10b981', // green
            'sosial' => '#f59e0b', // yellow
            'lainnya' => '#6b7280', // gray
            default => '#3b82f6'
        };
    }

    public function updated($property)
    {
        // Reset halaman ketika filter berubah
        if (in_array($property, ['search', 'jenisFilter', 'statusFilter', 'tanggalFilter', 'perPage'])) {
            $this->resetPage();
        }
    }
}