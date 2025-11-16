<?php

namespace App\Livewire\Admin;

use App\Models\Ruangan as RuanganModel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class Ruangan extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $ruanganId;
    public $kode_ruangan;
    public $nama_ruangan;
    public $gedung;
    public $kapasitas = 30;
    public $fasilitas = '';
    public $status = true;
    public $showModal = false;
    public $modalTitle = 'Tambah Ruangan';

    protected $rules = [
        'kode_ruangan' => 'required|string|max:20|unique:ruangans,kode_ruangan',
        'nama_ruangan' => 'required|string|max:100',
        'gedung' => 'required|string|max:50',
        'kapasitas' => 'required|integer|min:1|max:100',
        'fasilitas' => 'nullable|string',
        'status' => 'boolean'
    ];

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
        $ruangans = RuanganModel::when($this->search, function ($query) {
            $query->where('kode_ruangan', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_ruangan', 'like', '%' . $this->search . '%')
                  ->orWhere('gedung', 'like', '%' . $this->search . '%');
        })
        ->latest()
        ->paginate($this->perPage);

        return view('livewire.admin.ruangan', [
            'ruangans' => $ruangans
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->modalTitle = 'Tambah Ruangan';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $ruangan = RuanganModel::findOrFail($id);
        $this->ruanganId = $id;
        $this->kode_ruangan = $ruangan->kode_ruangan;
        $this->nama_ruangan = $ruangan->nama_ruangan;
        $this->gedung = $ruangan->gedung;
        $this->kapasitas = $ruangan->kapasitas;
        $this->fasilitas = $ruangan->fasilitas;
        $this->status = $ruangan->status;
        $this->modalTitle = 'Edit Ruangan';
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->ruanganId) {
            $this->rules['kode_ruangan'] = 'required|string|max:20|unique:ruangans,kode_ruangan,' . $this->ruanganId;
        }

        $this->validate();

        $data = [
            'kode_ruangan' => $this->kode_ruangan,
            'nama_ruangan' => $this->nama_ruangan,
            'gedung' => $this->gedung,
            'kapasitas' => $this->kapasitas,
            'fasilitas' => $this->fasilitas,
            'status' => $this->status
        ];

        if ($this->ruanganId) {
            $ruangan = RuanganModel::findOrFail($this->ruanganId);
            $ruangan->update($data);
            session()->flash('success', 'Ruangan berhasil diupdate.');
        } else {
            RuanganModel::create($data);
            session()->flash('success', 'Ruangan berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function delete($id)
    {
        $ruangan = RuanganModel::findOrFail($id);
        
        // Cek apakah ruangan masih digunakan di jadwal
        if ($ruangan->jadwal()->exists()) {
            session()->flash('error', 'Ruangan tidak dapat dihapus karena masih digunakan dalam jadwal.');
            return;
        }

        $ruangan->delete();
        session()->flash('success', 'Ruangan berhasil dihapus.');
    }

    private function resetForm()
    {
        $this->reset([
            'ruanganId',
            'kode_ruangan',
            'nama_ruangan',
            'gedung',
            'kapasitas',
            'fasilitas',
            'status'
        ]);
        $this->resetErrorBag();
    }
}