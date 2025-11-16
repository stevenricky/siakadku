<?php

namespace App\Livewire\Admin;

use App\Models\Semester;
use App\Models\TahunAjaran;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')] // TAMBAHKAN INI
class SemesterComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $semesterId;
    public $nama_semester;
    public $tahun_ajaran_id;
    public $tanggal_mulai;
    public $tanggal_selesai;
    public $is_aktif = false;
    public $showModal = false;
    public $modalTitle = 'Tambah Semester';

    protected $rules = [
        'nama_semester' => 'required|in:Ganjil,Genap',
        'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        'is_aktif' => 'boolean'
    ];

    public function mount()
    {
        // Optional: inisialisasi tambahan jika diperlukan
        $this->perPage = session()->get('perPage', 10);
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function render()
    {
        $semesters = Semester::with('tahunAjaran')
            ->when($this->search, function($query) {
                $query->where('nama_semester', 'like', '%'.$this->search.'%')
                      ->orWhereHas('tahunAjaran', function($q) {
                          $q->where('tahun_ajaran', 'like', '%'.$this->search.'%');
                      });
            })
            ->latest()
            ->paginate($this->perPage);

        $tahunAjarans = TahunAjaran::all();

        return view('livewire.admin.semester', [
            'semesters' => $semesters,
            'tahunAjarans' => $tahunAjarans
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->modalTitle = 'Tambah Semester';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $semester = Semester::findOrFail($id);
        $this->semesterId = $id;
        $this->nama_semester = $semester->nama_semester;
        $this->tahun_ajaran_id = $semester->tahun_ajaran_id;
        $this->tanggal_mulai = $semester->tanggal_mulai->format('Y-m-d');
        $this->tanggal_selesai = $semester->tanggal_selesai->format('Y-m-d');
        $this->is_aktif = $semester->is_aktif;
        $this->modalTitle = 'Edit Semester';
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Jika status aktif true, nonaktifkan yang lain
        if ($this->is_aktif) {
            Semester::where('is_aktif', true)->update(['is_aktif' => false]);
        }

        $data = [
            'nama_semester' => $this->nama_semester,
            'tahun_ajaran_id' => $this->tahun_ajaran_id,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'is_aktif' => $this->is_aktif
        ];

        if ($this->semesterId) {
            $semester = Semester::findOrFail($this->semesterId);
            $semester->update($data);
            session()->flash('success', 'Semester berhasil diupdate.');
        } else {
            Semester::create($data);
            session()->flash('success', 'Semester berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function delete($id)
    {
        $semester = Semester::findOrFail($id);
        
        // Tambahkan pengecekan jika semester masih digunakan
        // Contoh: if ($semester->jadwalPelajarans()->exists()) { ... }
        
        $semester->delete();
        session()->flash('success', 'Semester berhasil dihapus.');
    }

    private function resetForm()
    {
        $this->reset([
            'semesterId',
            'nama_semester',
            'tahun_ajaran_id',
            'tanggal_mulai',
            'tanggal_selesai',
            'is_aktif'
        ]);
        $this->resetErrorBag(); // TAMBAHKAN INI
    }
}