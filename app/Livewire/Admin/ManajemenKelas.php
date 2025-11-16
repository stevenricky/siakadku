<?php

namespace App\Livewire\Admin;

use App\Models\Kelas;
use App\Models\Guru;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class ManajemenKelas extends Component
{

    use WithPagination;

    public $search = '';
    public $perPage = 10;
    
    // Form properties
    public $showForm = false;
    public $formType = 'create';
    public $kelasId = null;
    
    public $nama_kelas;
    public $tingkat;
    public $jurusan;
    public $wali_kelas_id;
    public $kapasitas = 36;

    public $guruList;
    public $tingkatList = ['10', '11', '12'];
    public $jurusanList = ['IPA', 'IPS'];

    protected $queryString = ['search', 'perPage'];

    protected $rules = [
        'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas',
        'tingkat' => 'required|in:10,11,12',
        'jurusan' => 'required|in:IPA,IPS',
        'wali_kelas_id' => 'nullable|exists:gurus,id',
        'kapasitas' => 'required|integer|min:1|max:50',
    ];

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
        $this->guruList = Guru::where('status', 'aktif')->get();
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->formType = 'create';
        $this->showForm = true;
    }

    public function openEditForm($id)
    {
        $kelas = Kelas::findOrFail($id);
        
        $this->kelasId = $kelas->id;
        $this->nama_kelas = $kelas->nama_kelas;
        $this->tingkat = $kelas->tingkat;
        $this->jurusan = $kelas->jurusan;
        $this->wali_kelas_id = $kelas->wali_kelas_id;
        $this->kapasitas = $kelas->kapasitas;
        
        $this->formType = 'edit';
        $this->showForm = true;
    }

    public function resetForm()
    {
        $this->reset([
            'kelasId', 'nama_kelas', 'tingkat', 'jurusan', 'wali_kelas_id', 'kapasitas'
        ]);
        $this->resetErrorBag();
    }

    public function saveKelas()
    {
        if ($this->formType === 'edit') {
            $this->rules['nama_kelas'] = 'required|string|max:255|unique:kelas,nama_kelas,' . $this->kelasId;
        }

        $validatedData = $this->validate();

        try {
            if ($this->formType === 'create') {
                Kelas::create($validatedData);
                session()->flash('success', 'Data kelas berhasil ditambahkan.');
            } else {
                $kelas = Kelas::find($this->kelasId);
                $kelas->update($validatedData);
                session()->flash('success', 'Data kelas berhasil diperbarui.');
            }

            $this->showForm = false;
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteKelas($id)
    {
        try {
            $kelas = Kelas::findOrFail($id);
            
            // Check if kelas has students
            if ($kelas->siswa()->count() > 0) {
                session()->flash('error', 'Tidak dapat menghapus kelas yang masih memiliki siswa.');
                return;
            }

            $kelas->delete();
            session()->flash('success', 'Data kelas berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Kelas::with(['waliKelas'])
            ->when($this->search, function ($query) {
                $query->where('nama_kelas', 'like', '%' . $this->search . '%')
                      ->orWhere('jurusan', 'like', '%' . $this->search . '%');
            })
            ->orderBy('tingkat')
            ->orderBy('nama_kelas');

        $kelas = $query->paginate($this->perPage);

        return view('livewire.admin.manajemen-kelas', [
            'kelas' => $kelas,
        ]);
    }
}