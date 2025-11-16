<?php

namespace App\Livewire\Admin;

use App\Models\LayananBk as LayananBkModel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class LayananBk extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $layananId;
    public $nama_layanan;
    public $deskripsi;
    public $jenis_layanan;
    public $sasaran;
    public $status = true;
    public $showModal = false;
    public $modalTitle = 'Tambah Layanan BK';

    // Opsi untuk dropdown
    public $jenisLayananOptions = [
        'bimbingan pribadi' => 'Bimbingan Pribadi',
        'bimbingan sosial' => 'Bimbingan Sosial', 
        'bimbingan belajar' => 'Bimbingan Belajar',
        'bimbingan karir' => 'Bimbingan Karir'
    ];

    public $sasaranOptions = [
        'individu' => 'Individu',
        'kelompok' => 'Kelompok',
        'kelas' => 'Kelas'
    ];

    protected $rules = [
        'nama_layanan' => 'required|string|max:100',
        'deskripsi' => 'required|string',
        'jenis_layanan' => 'required|in:bimbingan pribadi,bimbingan sosial,bimbingan belajar,bimbingan karir',
        'sasaran' => 'required|in:individu,kelompok,kelas',
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
        $layananBk = LayananBkModel::when($this->search, function ($query) {
            $query->where('nama_layanan', 'like', '%' . $this->search . '%')
                  ->orWhere('jenis_layanan', 'like', '%' . $this->search . '%')
                  ->orWhere('sasaran', 'like', '%' . $this->search . '%');
        })
        ->latest()
        ->paginate($this->perPage);

        // Hitung statistik
        $totalLayanan = LayananBkModel::count();
        $layananIndividu = LayananBkModel::where('sasaran', 'individu')->count();
        $layananKelompok = LayananBkModel::where('sasaran', 'kelompok')->count();
        $layananKelas = LayananBkModel::where('sasaran', 'kelas')->count();

        return view('livewire.admin.layanan-bk', [
            'layananBk' => $layananBk,
            'totalLayanan' => $totalLayanan,
            'layananIndividu' => $layananIndividu,
            'layananKelompok' => $layananKelompok,
            'layananKelas' => $layananKelas
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->modalTitle = 'Tambah Layanan BK';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $layanan = LayananBkModel::findOrFail($id);
        $this->layananId = $id;
        $this->nama_layanan = $layanan->nama_layanan;
        $this->deskripsi = $layanan->deskripsi;
        $this->jenis_layanan = $layanan->jenis_layanan;
        $this->sasaran = $layanan->sasaran;
        $this->status = $layanan->status;
        $this->modalTitle = 'Edit Layanan BK';
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'nama_layanan' => $this->nama_layanan,
            'deskripsi' => $this->deskripsi,
            'jenis_layanan' => $this->jenis_layanan,
            'sasaran' => $this->sasaran,
            'status' => $this->status
        ];

        if ($this->layananId) {
            $layanan = LayananBkModel::findOrFail($this->layananId);
            $layanan->update($data);
            session()->flash('success', 'Layanan BK berhasil diupdate.');
        } else {
            LayananBkModel::create($data);
            session()->flash('success', 'Layanan BK berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function delete($id)
    {
        $layanan = LayananBkModel::findOrFail($id);
        $layanan->delete();
        session()->flash('success', 'Layanan BK berhasil dihapus.');
    }

    private function resetForm()
    {
        $this->reset([
            'layananId',
            'nama_layanan',
            'deskripsi',
            'jenis_layanan',
            'sasaran',
            'status'
        ]);
        $this->resetErrorBag();
    }
}