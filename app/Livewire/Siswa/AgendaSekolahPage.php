<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\AgendaSekolah;

#[Layout('layouts.app-new')]
class AgendaSekolahPage extends Component
{
    public $search = '';
    public $filterJenis = '';
    public $filterStatus = '';

    public function render()
    {
        $agenda = AgendaSekolah::query()
            ->when($this->search, function ($query) {
                $query->where('judul_agenda', 'like', '%' . $this->search . '%')
                      ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterJenis, function ($query) {
                $query->where('jenis_agenda', $this->filterJenis);
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->orderBy('tanggal_mulai', 'asc')
            ->orderBy('waktu_mulai', 'asc')
            ->get();

        return view('livewire.siswa.agenda-sekolah', [
            'agenda' => $agenda
        ]);
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filterJenis = '';
        $this->filterStatus = '';
    }
}