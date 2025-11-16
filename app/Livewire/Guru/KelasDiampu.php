<?php

namespace App\Livewire\Guru;

use App\Models\Jadwal;
use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class KelasDiampu extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $queryString = ['search', 'perPage'];

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function render()
    {
        // PERBAIKAN: Tambahkan null checking untuk guru
        $guru = auth()->user()->guru;
        if (!$guru) {
            $kelas = collect()->paginate($this->perPage);
            return view('livewire.guru.kelas-diampu', [
                'kelas' => $kelas,
            ]);
        }

        $guruId = $guru->id;

        $query = Kelas::whereHas('jadwal', function ($query) use ($guruId) {
                $query->where('guru_id', $guruId);
            })
            ->when($this->search, function ($query) {
                $query->where('nama_kelas', 'like', '%' . $this->search . '%');
            })
            ->withCount(['siswa' => function ($query) {
                $query->where('status', 'aktif');
            }])
            ->orderBy('tingkat')
            ->orderBy('nama_kelas');

        $kelas = $query->paginate($this->perPage);

        // HAPUS layout() dari sini
        return view('livewire.guru.kelas-diampu', [
            'kelas' => $kelas,
        ]);
    }
}