<?php

namespace App\Livewire\Admin;

use App\Models\Ekstrakurikuler as EkstraModel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class Ekstrakurikuler extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $ekstraId;
    public $nama_ekstra;
    public $deskripsi;
    public $pembina;
    public $hari;
    public $jam_mulai;
    public $jam_selesai;
    public $tempat;
    public $status = true;
    public $showModal = false;
    public $modalTitle = 'Tambah Ekstrakurikuler';

    protected $rules = [
        'nama_ekstra' => 'required|string|max:100',
        'deskripsi' => 'nullable|string',
        'pembina' => 'required|string|max:100',
        'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
        'jam_mulai' => 'required|date_format:H:i',
        'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        'tempat' => 'required|string|max:100',
        'status' => 'boolean'
    ];


public function mount()
{
    $this->showModal = false;
}



    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function render()
    {
        $ekstrakurikulers = EkstraModel::when($this->search, function ($query) {
            $query->where('nama_ekstra', 'like', '%' . $this->search . '%')
                  ->orWhere('pembina', 'like', '%' . $this->search . '%')
                  ->orWhere('tempat', 'like', '%' . $this->search . '%');
        })
        ->latest()
        ->paginate($this->perPage);

        return view('livewire.admin.ekstrakurikuler', [
            'ekstrakurikulers' => $ekstrakurikulers
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->modalTitle = 'Tambah Ekstrakurikuler';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $ekstra = EkstraModel::findOrFail($id);
        $this->ekstraId = $id;
        $this->nama_ekstra = $ekstra->nama_ekstra;
        $this->deskripsi = $ekstra->deskripsi;
        $this->pembina = $ekstra->pembina;
        $this->hari = $ekstra->hari;
        $this->jam_mulai = $ekstra->jam_mulai->format('H:i');
        $this->jam_selesai = $ekstra->jam_selesai->format('H:i');
        $this->tempat = $ekstra->tempat;
        $this->status = $ekstra->status;
        $this->modalTitle = 'Edit Ekstrakurikuler';
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'nama_ekstra' => $this->nama_ekstra,
            'deskripsi' => $this->deskripsi,
            'pembina' => $this->pembina,
            'hari' => $this->hari,
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'tempat' => $this->tempat,
            'status' => $this->status
        ];

        if ($this->ekstraId) {
            $ekstra = EkstraModel::findOrFail($this->ekstraId);
            $ekstra->update($data);
            session()->flash('success', 'Ekstrakurikuler berhasil diupdate.');
        } else {
            EkstraModel::create($data);
            session()->flash('success', 'Ekstrakurikuler berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function delete($id)
    {
        $ekstra = EkstraModel::findOrFail($id);
        
        // Cek apakah ekstrakurikuler masih memiliki peserta
        if ($ekstra->siswa()->exists()) {
            session()->flash('error', 'Ekstrakurikuler tidak dapat dihapus karena masih memiliki peserta.');
            return;
        }

        $ekstra->delete();
        session()->flash('success', 'Ekstrakurikuler berhasil dihapus.');
    }

    private function resetForm()
    {
        $this->reset([
            'ekstraId',
            'nama_ekstra',
            'deskripsi',
            'pembina',
            'hari',
            'jam_mulai',
            'jam_selesai',
            'tempat',
            'status'
        ]);
        $this->resetErrorBag();
    }
}