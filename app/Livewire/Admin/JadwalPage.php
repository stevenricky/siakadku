<?php

namespace App\Livewire\Admin;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Guru;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class JadwalPage extends Component
{
    use WithPagination;

    // Properties
    public $search = '';
    public $kelasFilter = '';
    public $hariFilter = '';
    public $perPage = 10;
    public $showForm = false;
    public $formType = 'create';
    
    // Form properties
    public $jadwalId;
    public $kelas_id;
    public $mapel_id;
    public $guru_id;
    public $hari;
    public $jam_mulai;
    public $jam_selesai;
    public $ruangan;

    // Data lists
    public $kelasList;
    public $mapelList;
    public $guruList;
    public $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->kelasList = Kelas::all();
        $this->mapelList = Mapel::all();
        $this->guruList = Guru::all();
    }

    public function render()
    {
        $query = Jadwal::with(['kelas', 'mapel', 'guru'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('kelas', function ($q) {
                        $q->where('nama_kelas', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('mapel', function ($q) {
                        $q->where('nama_mapel', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('guru', function ($q) {
                        $q->where('nama_lengkap', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('ruangan', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->kelasFilter, function ($query) {
                $query->where('kelas_id', $this->kelasFilter);
            })
            ->when($this->hariFilter, function ($query) {
                $query->where('hari', $this->hariFilter);
            })
            ->orderBy('hari')
            ->orderBy('jam_mulai');

        $jadwals = $query->paginate($this->perPage);

        return view('livewire.admin.jadwal-page', compact('jadwals'));
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->formType = 'create';
        $this->showForm = true;
    }

    public function openEditForm($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        
        $this->jadwalId = $jadwal->id;
        $this->kelas_id = $jadwal->kelas_id;
        $this->mapel_id = $jadwal->mapel_id;
        $this->guru_id = $jadwal->guru_id;
        $this->hari = $jadwal->hari;
        $this->jam_mulai = $jadwal->jam_mulai;
        $this->jam_selesai = $jadwal->jam_selesai;
        $this->ruangan = $jadwal->ruangan;
        
        $this->formType = 'edit';
        $this->showForm = true;
    }

    public function saveJadwal()
    {
        $this->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapels,id',
            'guru_id' => 'required|exists:gurus,id',
            'hari' => 'required|in:' . implode(',', $this->hariList),
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruangan' => 'required|string|max:50',
        ]);

        $data = [
            'kelas_id' => $this->kelas_id,
            'mapel_id' => $this->mapel_id,
            'guru_id' => $this->guru_id,
            'hari' => $this->hari,
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'ruangan' => $this->ruangan,
        ];

        if ($this->formType === 'create') {
            Jadwal::create($data);
            $message = 'Jadwal berhasil ditambahkan!';
        } else {
            Jadwal::findOrFail($this->jadwalId)->update($data);
            $message = 'Jadwal berhasil diupdate!';
        }

        $this->closeForm();
        $this->dispatch('show-success', message: $message);
    }

    public function deleteJadwal($id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);
            $jadwal->delete();
            
            $this->dispatch('show-success', message: 'Jadwal berhasil dihapus!');
        } catch (\Exception $e) {
            $this->dispatch('show-error', message: 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'jadwalId',
            'kelas_id',
            'mapel_id',
            'guru_id',
            'hari',
            'jam_mulai',
            'jam_selesai',
            'ruangan'
        ]);
        $this->resetErrorBag();
    }
}