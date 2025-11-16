<?php

namespace App\Livewire\Admin;

use App\Models\TahunAjaran as TahunAjaranModel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class TahunAjaranPage extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $tahunAjaranId;
    public $tahun_awal;
    public $tahun_akhir;
    public $semester;
    public $tanggal_awal;
    public $tanggal_akhir;
    public $status = false;
    public $showModal = false;
    public $modalTitle = 'Tambah Tahun Ajaran';

    protected $rules = [
        'tahun_awal' => 'required|integer|min:2000|max:2100',
        'tahun_akhir' => 'required|integer|min:2000|max:2100|gt:tahun_awal',
        'semester' => 'required|in:Ganjil,Genap',
        'tanggal_awal' => 'required|date',
        'tanggal_akhir' => 'required|date|after:tanggal_awal',
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

    public function updatedTahunAwal($value)
    {
        if ($value && !$this->tahun_akhir) {
            $this->tahun_akhir = $value + 1;
        }
    }

    public function render()
    {
        $tahunAjarans = TahunAjaranModel::when($this->search, function ($query) {
            $query->where('tahun_awal', 'like', '%' . $this->search . '%')
                  ->orWhere('tahun_akhir', 'like', '%' . $this->search . '%')
                  ->orWhere('semester', 'like', '%' . $this->search . '%');
        })
        ->latest()
        ->paginate($this->perPage);

        return view('livewire.admin.tahun-ajaran-page', [
            'tahunAjarans' => $tahunAjarans
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->modalTitle = 'Tambah Tahun Ajaran';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $tahunAjaran = TahunAjaranModel::findOrFail($id);
        $this->tahunAjaranId = $id;
        $this->tahun_awal = $tahunAjaran->tahun_awal;
        $this->tahun_akhir = $tahunAjaran->tahun_akhir;
        $this->semester = $tahunAjaran->semester;
        $this->tanggal_awal = $tahunAjaran->tanggal_awal->format('Y-m-d');
        $this->tanggal_akhir = $tahunAjaran->tanggal_akhir->format('Y-m-d');
        $this->status = $tahunAjaran->status;
        $this->modalTitle = 'Edit Tahun Ajaran';
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Jika status aktif, nonaktifkan yang lain
        if ($this->status) {
            TahunAjaranModel::where('status', 'aktif')->update(['status' => 'tidak aktif']);
        }

        $data = [
            'tahun_awal' => $this->tahun_awal,
            'tahun_akhir' => $this->tahun_akhir,
            'semester' => $this->semester,
            'tanggal_awal' => $this->tanggal_awal,
            'tanggal_akhir' => $this->tanggal_akhir,
            'status' => $this->status ? 'aktif' : 'tidak aktif'
        ];

        if ($this->tahunAjaranId) {
            $tahunAjaran = TahunAjaranModel::findOrFail($this->tahunAjaranId);
            $tahunAjaran->update($data);
            session()->flash('success', 'Tahun ajaran berhasil diupdate.');
        } else {
            TahunAjaranModel::create($data);
            session()->flash('success', 'Tahun ajaran berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function delete($id)
    {
        $tahunAjaran = TahunAjaranModel::findOrFail($id);
        
        // Cek apakah tahun ajaran digunakan
        if ($tahunAjaran->siswa()->exists() || $tahunAjaran->nilai()->exists()) {
            session()->flash('error', 'Tahun ajaran tidak dapat dihapus karena masih digunakan.');
            return;
        }

        $tahunAjaran->delete();
        session()->flash('success', 'Tahun ajaran berhasil dihapus.');
    }

    private function resetForm()
    {
        $this->reset([
            'tahunAjaranId',
            'tahun_awal',
            'tahun_akhir',
            'semester',
            'tanggal_awal',
            'tanggal_akhir',
            'status'
        ]);
        $this->resetErrorBag();
    }
}