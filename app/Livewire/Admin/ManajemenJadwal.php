<?php

namespace App\Livewire\Admin;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Guru;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Validation\ValidationException;

#[Layout('layouts.app-new')]
class ManajemenJadwal extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $kelasFilter;
    public $hariFilter;

    public $kelasList;
    public $mapelList;
    public $guruList;
    public $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    // Form properties
    public $showForm = false;
    public $formType = 'create';
    public $jadwalId = null;
    
    public $kelas_id;
    public $mapel_id;
    public $guru_id;
    public $hari;
    public $jam_mulai;
    public $jam_selesai;
    public $ruangan;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'kelasFilter' => ['except' => ''],
        'hariFilter' => ['except' => '']
    ];

    protected $rules = [
        'kelas_id' => 'required|exists:kelas,id',
        'mapel_id' => 'required|exists:mapels,id',
        'guru_id' => 'required|exists:gurus,id',
        'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
        'jam_mulai' => 'required|date_format:H:i',
        'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        'ruangan' => 'required|string|max:50',
    ];

    protected $messages = [
        'kelas_id.required' => 'Kelas harus dipilih.',
        'mapel_id.required' => 'Mata pelajaran harus dipilih.',
        'guru_id.required' => 'Guru pengajar harus dipilih.',
        'hari.required' => 'Hari harus dipilih.',
        'jam_mulai.required' => 'Jam mulai harus diisi.',
        'jam_selesai.required' => 'Jam selesai harus diisi.',
        'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
        'ruangan.required' => 'Ruangan harus diisi.',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->kelasList = Kelas::all();
        $this->mapelList = Mapel::all();
        $this->guruList = Guru::where('status', 'aktif')->get();
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedKelasFilter()
    {
        $this->resetPage();
    }

    public function updatedHariFilter()
    {
        $this->resetPage();
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->formType = 'create';
        $this->showForm = true;
    }

    public function openEditForm($id)
    {
        try {
            $jadwal = Jadwal::with(['kelas', 'mapel', 'guru'])->findOrFail($id);
            
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
        } catch (\Exception $e) {
            $this->dispatch('show-error', message: 'Jadwal tidak ditemukan.');
        }
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'jadwalId', 'kelas_id', 'mapel_id', 'guru_id', 'hari', 
            'jam_mulai', 'jam_selesai', 'ruangan'
        ]);
        $this->resetErrorBag();
    }

    public function saveJadwal()
    {
        $this->validate();

        try {
            // Validasi konflik jadwal
            $conflict = Jadwal::where('hari', $this->hari)
                ->where('kelas_id', $this->kelas_id)
                ->where(function($query) {
                    $query->whereBetween('jam_mulai', [$this->jam_mulai, $this->jam_selesai])
                          ->orWhereBetween('jam_selesai', [$this->jam_mulai, $this->jam_selesai])
                          ->orWhere(function($q) {
                              $q->where('jam_mulai', '<=', $this->jam_mulai)
                                ->where('jam_selesai', '>=', $this->jam_selesai);
                          });
                })
                ->when($this->formType === 'edit', function($query) {
                    $query->where('id', '!=', $this->jadwalId);
                })
                ->exists();

            if ($conflict) {
                throw ValidationException::withMessages([
                    'jam_mulai' => 'Konflik jadwal! Sudah ada jadwal lain di waktu yang sama untuk kelas ini.'
                ]);
            }

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
                $message = 'Jadwal berhasil ditambahkan.';
            } else {
                $jadwal = Jadwal::findOrFail($this->jadwalId);
                $jadwal->update($data);
                $message = 'Jadwal berhasil diperbarui.';
            }

            $this->closeForm();
            $this->dispatch('show-success', message: $message);
            
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('show-error', message: 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteJadwal($id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);
            $jadwal->delete();
            
            $this->dispatch('show-success', message: 'Jadwal berhasil dihapus.');
        } catch (\Exception $e) {
            $this->dispatch('show-error', message: 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Jadwal::with(['kelas', 'mapel', 'guru'])
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('ruangan', 'like', '%' . $this->search . '%')
                      ->orWhere('hari', 'like', '%' . $this->search . '%')
                      ->orWhereHas('mapel', function ($q) {
                          $q->where('nama_mapel', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('guru', function ($q) {
                          $q->where('nama_lengkap', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('kelas', function ($q) {
                          $q->where('nama_kelas', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->kelasFilter, function ($query) {
                $query->where('kelas_id', $this->kelasFilter);
            })
            ->when($this->hariFilter, function ($query) {
                $query->where('hari', $this->hariFilter);
            })
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jam_mulai')
            ->orderBy('kelas_id');

        $jadwals = $query->paginate($this->perPage);

        return view('livewire.admin.manajemen-jadwal', [
            'jadwals' => $jadwals,
        ]);
    }
}