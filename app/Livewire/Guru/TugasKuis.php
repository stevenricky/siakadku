<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Mapel;
use App\Models\Tugas;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class TugasKuis extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;
    public $mapelId;
    public $judul;
    public $deskripsi;
    public $deadline;
    public $tipe = 'tugas';
    public $instruksi;
    public $file;
    public $max_score = 100;
    public $is_published = true;
    
    public $showModal = false;
    public $showViewModal = false;
    public $editMode = false;
    public $tugasId;
    public $selectedTugas;

    protected $queryString = ['search', 'perPage'];

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
        $guruId = auth()->user()->guru->id;

        $tugasList = Tugas::with(['mapel', 'guru'])
            ->where('guru_id', $guruId)
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('judul', 'like', '%'.$this->search.'%')
                      ->orWhereHas('mapel', function($q2) {
                          $q2->where('nama_mapel', 'like', '%'.$this->search.'%');
                      });
                });
            })
            ->latest()
            ->paginate($this->perPage);

        $mapels = Mapel::whereHas('guru', function($query) use ($guruId) {
            $query->where('guru_id', $guruId);
        })->get();

        return view('livewire.guru.tugas-kuis', [
            'tugasList' => $tugasList,
            'mapels' => $mapels
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $tugas = Tugas::where('id', $id)
                      ->where('guru_id', auth()->user()->guru->id)
                      ->firstOrFail();

        $this->tugasId = $id;
        $this->mapelId = $tugas->mapel_id;
        $this->judul = $tugas->judul;
        $this->deskripsi = $tugas->deskripsi;
        $this->deadline = $tugas->deadline->format('Y-m-d\TH:i');
        $this->tipe = $tugas->tipe;
        $this->instruksi = $tugas->instruksi;
        $this->max_score = $tugas->max_score;
        $this->is_published = $tugas->is_published;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function view($id)
    {
        $this->selectedTugas = Tugas::with(['mapel', 'guru'])
                                   ->where('id', $id)
                                   ->where('guru_id', auth()->user()->guru->id)
                                   ->firstOrFail();
        $this->showViewModal = true;
    }

    public function save()
    {
        $this->validate([
            'mapelId' => 'required|exists:mapels,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'deadline' => 'required|date',
            'tipe' => 'required|in:tugas,kuis,ulangan',
            'instruksi' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
            'max_score' => 'required|integer|min:1|max:1000',
        ]);

        $guruId = auth()->user()->guru->id;

        $data = [
            'mapel_id' => $this->mapelId,
            'guru_id' => $guruId,
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'deadline' => $this->deadline,
            'tipe' => $this->tipe,
            'instruksi' => $this->instruksi,
            'max_score' => $this->max_score,
            'is_published' => $this->is_published,
        ];

        // Handle file upload
        if ($this->file) {
            // Delete old file if exists
            if ($this->editMode && $tugas = Tugas::find($this->tugasId)) {
                if ($tugas->file) {
                    Storage::delete('public/tugas/' . $tugas->file);
                }
            }
            
            $fileName = time() . '_' . $this->file->getClientOriginalName();
            $this->file->storeAs('public/tugas', $fileName);
            $data['file'] = $fileName;
        }

        try {
            if ($this->editMode) {
                $tugas = Tugas::where('id', $this->tugasId)
                              ->where('guru_id', $guruId)
                              ->firstOrFail();
                $tugas->update($data);
                session()->flash('success', 'Tugas berhasil diupdate.');
            } else {
                Tugas::create($data);
                session()->flash('success', 'Tugas berhasil ditambahkan.');
            }

            $this->resetForm();
            $this->showModal = false;
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $tugas = Tugas::where('id', $id)
                          ->where('guru_id', auth()->user()->guru->id)
                          ->firstOrFail();
            
            // Delete file if exists
            if ($tugas->file) {
                Storage::delete('public/tugas/' . $tugas->file);
            }
            
            $tugas->delete();
            session()->flash('success', 'Tugas berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function togglePublish($id)
    {
        try {
            $tugas = Tugas::where('id', $id)
                          ->where('guru_id', auth()->user()->guru->id)
                          ->firstOrFail();
            
            $tugas->update(['is_published' => !$tugas->is_published]);
            
            $status = $tugas->is_published ? 'dipublikasikan' : 'disembunyikan';
            session()->flash('success', "Tugas berhasil {$status}.");
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function download($id)
    {
        try {
            $tugas = Tugas::where('id', $id)
                          ->where('guru_id', auth()->user()->guru->id)
                          ->firstOrFail();

            if ($tugas->file) {
                return Storage::download('public/tugas/' . $tugas->file, $tugas->judul . '.' . pathinfo($tugas->file, PATHINFO_EXTENSION));
            }

            session()->flash('error', 'File tidak ditemukan.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->reset([
            'tugasId',
            'mapelId',
            'judul',
            'deskripsi',
            'deadline',
            'tipe',
            'instruksi',
            'file',
            'max_score',
            'is_published',
            'editMode'
        ]);
    }
}