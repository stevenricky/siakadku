<?php

namespace App\Livewire\Admin;

use App\Models\Mapel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class ManajemenMapel extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $tingkatFilter = '';
    public $jurusanFilter = '';
    
    // Form properties
    public $showForm = false;
    public $formType = 'create';
    public $mapelId = null;
    
    public $kode_mapel;
    public $nama_mapel;
    public $tingkat;
    public $jurusan;
    public $kkm = 75;
    public $deskripsi;

    public $tingkatList = ['10', '11', '12'];
    public $jurusanList = [null, 'IPA', 'IPS'];

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'tingkatFilter' => ['except' => ''],
        'jurusanFilter' => ['except' => '']
    ];

    protected $rules = [
        'kode_mapel' => 'required|string|max:10|unique:mapels,kode_mapel',
        'nama_mapel' => 'required|string|max:255',
        'tingkat' => 'required|in:10,11,12',
        'jurusan' => 'nullable|in:IPA,IPS',
        'kkm' => 'required|integer|min:0|max:100',
        'deskripsi' => 'nullable|string',
    ];

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
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

    public function updatedTingkatFilter()
    {
        $this->resetPage();
    }

    public function updatedJurusanFilter()
    {
        $this->resetPage();
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->formType = 'create';
        $this->showForm = true;
    }

    public function openEditForm($id)
    {
        $mapel = Mapel::findOrFail($id);
        
        $this->mapelId = $mapel->id;
        $this->kode_mapel = $mapel->kode_mapel;
        $this->nama_mapel = $mapel->nama_mapel;
        $this->tingkat = $mapel->tingkat;
        $this->jurusan = $mapel->jurusan;
        $this->kkm = $mapel->kkm;
        $this->deskripsi = $mapel->deskripsi;
        
        $this->formType = 'edit';
        $this->showForm = true;
    }

    public function resetForm()
    {
        $this->reset([
            'mapelId', 'kode_mapel', 'nama_mapel', 'tingkat', 'jurusan', 'kkm', 'deskripsi'
        ]);
        $this->resetErrorBag();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'tingkatFilter', 'jurusanFilter']);
    }

    public function saveMapel()
    {
        if ($this->formType === 'edit') {
            $this->rules['kode_mapel'] = 'required|string|max:10|unique:mapels,kode_mapel,' . $this->mapelId;
        }

        $validatedData = $this->validate();

        try {
            if ($this->formType === 'create') {
                Mapel::create($validatedData);
                session()->flash('success', 'Data mata pelajaran berhasil ditambahkan.');
            } else {
                $mapel = Mapel::find($this->mapelId);
                $mapel->update($validatedData);
                session()->flash('success', 'Data mata pelajaran berhasil diperbarui.');
            }

            $this->closeForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteMapel($id)
    {
        try {
            $mapel = Mapel::findOrFail($id);
            
            // Check if mapel has related data
            if ($mapel->jadwal()->count() > 0 || $mapel->nilai()->count() > 0) {
                session()->flash('error', 'Tidak dapat menghapus mata pelajaran yang masih memiliki data terkait (jadwal atau nilai).');
                return;
            }

            $mapel->delete();
            session()->flash('success', 'Data mata pelajaran berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Mapel::when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('nama_mapel', 'like', '%' . $this->search . '%')
                      ->orWhere('kode_mapel', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->tingkatFilter, function ($query) {
                $query->where('tingkat', $this->tingkatFilter);
            })
            ->when($this->jurusanFilter, function ($query) {
                if ($this->jurusanFilter === 'Umum') {
                    $query->whereNull('jurusan');
                } else {
                    $query->where('jurusan', $this->jurusanFilter);
                }
            })
            ->orderBy('tingkat')
            ->orderBy('nama_mapel');

        $mapels = $query->paginate($this->perPage);

        return view('livewire.admin.manajemen-mapel', [
            'mapels' => $mapels,
        ]);
    }
}