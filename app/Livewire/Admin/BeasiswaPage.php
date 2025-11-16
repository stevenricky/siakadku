<?php

namespace App\Livewire\Admin;

use App\Models\Beasiswa;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

#[Layout('layouts.app-new')]
class BeasiswaPage extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;
    public $beasiswaId;
    public $nama_beasiswa;
    public $penyelenggara;
    public $deskripsi;
    public $persyaratan;
    public $nilai_beasiswa;
    public $tanggal_buka;
    public $tanggal_tutup;
    public $kontak;
    public $website;
    public $dokumen;
    public $status = 'buka';
    public $showModal = false;
    public $modalTitle = 'Tambah Beasiswa';
    public $showDetailModal = false;
    public $selectedBeasiswa = null;

    // Status options
    public $statusOptions = [
        'buka' => 'Buka',
        'tutup' => 'Tutup'
    ];

    protected function rules()
    {
        return [
            'nama_beasiswa' => 'required|string|max:200',
            'penyelenggara' => 'required|string|max:200',
            'deskripsi' => 'required|string',
            'persyaratan' => 'required|string',
            'nilai_beasiswa' => 'nullable|numeric|min:0',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after:tanggal_buka',
            'kontak' => 'required|string|max:100',
            'website' => 'nullable|url|max:200',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'status' => 'required|in:buka,tutup'
        ];
    }

    public function mount()
    {
        $this->showModal = false;
        $this->showDetailModal = false;
        $this->tanggal_buka = now()->format('Y-m-d');
        $this->tanggal_tutup = now()->addMonth()->format('Y-m-d');
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function render()
    {
        $beasiswa = Beasiswa::when($this->search, function ($query) {
                $query->where('nama_beasiswa', 'like', '%' . $this->search . '%')
                      ->orWhere('penyelenggara', 'like', '%' . $this->search . '%')
                      ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        // Hitung statistik
        $beasiswaAktif = Beasiswa::where('status', 'buka')->count();
        $totalBeasiswa = Beasiswa::count();
        $beasiswaTutup = Beasiswa::where('status', 'tutup')->count();

        return view('livewire.admin.beasiswa', [
            'beasiswa' => $beasiswa,
            'beasiswaAktif' => $beasiswaAktif,
            'totalBeasiswa' => $totalBeasiswa,
            'beasiswaTutup' => $beasiswaTutup
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->modalTitle = 'Tambah Beasiswa';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $beasiswa = Beasiswa::findOrFail($id);
        $this->beasiswaId = $id;
        $this->nama_beasiswa = $beasiswa->nama_beasiswa;
        $this->penyelenggara = $beasiswa->penyelenggara;
        $this->deskripsi = $beasiswa->deskripsi;
        $this->persyaratan = $beasiswa->persyaratan;
        $this->nilai_beasiswa = $beasiswa->nilai_beasiswa;
        $this->tanggal_buka = $beasiswa->tanggal_buka->format('Y-m-d');
        $this->tanggal_tutup = $beasiswa->tanggal_tutup->format('Y-m-d');
        $this->kontak = $beasiswa->kontak;
        $this->website = $beasiswa->website;
        $this->status = $beasiswa->status;
        $this->modalTitle = 'Edit Beasiswa';
        $this->showModal = true;
    }

    public function showDetail($id)
    {
        $this->selectedBeasiswa = Beasiswa::findOrFail($id);
        $this->showDetailModal = true;
    }

    public function save()
    {
        $this->validate($this->rules());

        $data = [
            'nama_beasiswa' => $this->nama_beasiswa,
            'penyelenggara' => $this->penyelenggara,
            'deskripsi' => $this->deskripsi,
            'persyaratan' => $this->persyaratan,
            'nilai_beasiswa' => $this->nilai_beasiswa,
            'tanggal_buka' => $this->tanggal_buka,
            'tanggal_tutup' => $this->tanggal_tutup,
            'kontak' => $this->kontak,
            'website' => $this->website,
            'status' => $this->status
        ];

        // Handle file upload
        if ($this->dokumen) {
            $data['dokumen'] = $this->dokumen->store('beasiswa', 'public');
        }

        if ($this->beasiswaId) {
            $beasiswa = Beasiswa::findOrFail($this->beasiswaId);
            $beasiswa->update($data);
            session()->flash('success', 'Beasiswa berhasil diupdate.');
        } else {
            Beasiswa::create($data);
            session()->flash('success', 'Beasiswa berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function updateStatus($id, $status)
    {
        $beasiswa = Beasiswa::findOrFail($id);
        $beasiswa->update(['status' => $status]);
        
        session()->flash('success', 'Status beasiswa berhasil diupdate.');
    }

    public function delete($id)
    {
        $beasiswa = Beasiswa::findOrFail($id);
        $beasiswa->delete();
        
        session()->flash('success', 'Beasiswa berhasil dihapus.');
    }

    private function resetForm()
    {
        $this->reset([
            'beasiswaId',
            'nama_beasiswa',
            'penyelenggara',
            'deskripsi',
            'persyaratan',
            'nilai_beasiswa',
            'tanggal_buka',
            'tanggal_tutup',
            'kontak',
            'website',
            'dokumen',
            'status'
        ]);
        $this->resetErrorBag();
        $this->tanggal_buka = now()->format('Y-m-d');
        $this->tanggal_tutup = now()->addMonth()->format('Y-m-d');
        $this->status = 'buka';
    }

    // Method untuk cek status beasiswa berdasarkan tanggal
    public function checkBeasiswaStatus()
    {
        $today = now()->format('Y-m-d');
        Beasiswa::where('tanggal_tutup', '<', $today)
                ->where('status', 'buka')
                ->update(['status' => 'tutup']);
    }
}