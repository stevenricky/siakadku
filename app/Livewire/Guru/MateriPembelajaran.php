<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Mapel;
use App\Models\Materi;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Validator;

#[Layout('layouts.app-new')]
class MateriPembelajaran extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;
    public $mapelId = '';
    public $judul = '';
    public $deskripsi = '';
    public $file;
    public $link = '';
    public $showModal = false;
    public $showViewModal = false;
    public $editMode = false;
    public $materiId = null;
    public $selectedMateri = null;

    protected $queryString = ['search', 'perPage'];

    protected $rules = [
        'mapelId' => 'required|exists:mapels,id',
        'judul' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'file' => 'nullable|file|max:10240', // max 10MB
        'link' => 'nullable|url',
    ];

    public function mount()
    {
        $this->perPage = session()->get('materi_perPage', 10);
    }

    public function render()
    {
        $guruId = auth()->user()->guru->id;

        $materiList = Materi::with(['mapel', 'guru'])
            ->where('guru_id', $guruId)
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('judul', 'like', '%'.$this->search.'%')
                      ->orWhere('deskripsi', 'like', '%'.$this->search.'%')
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

        return view('livewire.guru.materi-pembelajaran', [
            'materiList' => $materiList,
            'mapels' => $mapels
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
        $this->dispatch('modal-opened'); // Untuk event listener jika diperlukan
    }

    public function edit($id)
    {
        try {
            $materi = Materi::where('id', $id)
                            ->where('guru_id', auth()->user()->guru->id)
                            ->firstOrFail();

            $this->materiId = $materi->id;
            $this->mapelId = $materi->mapel_id;
            $this->judul = $materi->judul;
            $this->deskripsi = $materi->deskripsi;
            $this->link = $materi->link;
            $this->editMode = true;
            $this->showModal = true;
            $this->dispatch('modal-opened');
            
        } catch (\Exception $e) {
            $this->dispatch('show-error', message: 'Materi tidak ditemukan');
        }
    }

    public function view($id)
    {
        try {
            $this->selectedMateri = Materi::with(['mapel', 'guru'])
                                        ->where('id', $id)
                                        ->where('guru_id', auth()->user()->guru->id)
                                        ->firstOrFail();
            $this->showViewModal = true;
            $this->dispatch('view-modal-opened');
        } catch (\Exception $e) {
            $this->dispatch('show-error', message: 'Materi tidak ditemukan');
        }
    }

    public function save()
    {
        $this->validate();

        try {
            $guruId = auth()->user()->guru->id;

            $data = [
                'mapel_id' => $this->mapelId,
                'guru_id' => $guruId,
                'judul' => $this->judul,
                'deskripsi' => $this->deskripsi,
                'link' => $this->link,
            ];

            // Handle file upload
            if ($this->file) {
                // Delete old file if exists in edit mode
                if ($this->editMode && $this->materiId) {
                    $existingMateri = Materi::find($this->materiId);
                    if ($existingMateri && $existingMateri->file) {
                        Storage::delete('public/materi/' . $existingMateri->file);
                    }
                }
                
                $fileName = time() . '_' . $this->file->getClientOriginalName();
                $this->file->storeAs('public/materi', $fileName);
                $data['file'] = $fileName;
            }

            if ($this->editMode) {
                $materi = Materi::where('id', $this->materiId)
                                ->where('guru_id', $guruId)
                                ->firstOrFail();
                $materi->update($data);
                $message = 'Materi berhasil diupdate.';
            } else {
                Materi::create($data);
                $message = 'Materi berhasil ditambahkan.';
            }

            $this->resetForm();
            $this->showModal = false;
            $this->dispatch('show-success', message: $message);

        } catch (\Exception $e) {
            $this->dispatch('show-error', message: 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $materi = Materi::where('id', $id)
                            ->where('guru_id', auth()->user()->guru->id)
                            ->firstOrFail();
            
            // Delete file if exists
            if ($materi->file) {
                Storage::delete('public/materi/' . $materi->file);
            }
            
            $materi->delete();
            $this->dispatch('show-success', message: 'Materi berhasil dihapus.');

        } catch (\Exception $e) {
            $this->dispatch('show-error', message: 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function download($id)
    {
        try {
            $materi = Materi::where('id', $id)
                            ->where('guru_id', auth()->user()->guru->id)
                            ->firstOrFail();

            if ($materi->file && Storage::exists('public/materi/' . $materi->file)) {
                return Storage::download('public/materi/' . $materi->file, 
                    $materi->judul . '.' . pathinfo($materi->file, PATHINFO_EXTENSION));
            }

            $this->dispatch('show-error', message: 'File tidak ditemukan.');

        } catch (\Exception $e) {
            $this->dispatch('show-error', message: 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->selectedMateri = null;
    }

    private function resetForm()
    {
        $this->reset([
            'materiId',
            'mapelId',
            'judul',
            'deskripsi',
            'file',
            'link',
            'editMode'
        ]);
        $this->resetErrorBag();
    }

    public function updatedPerPage($value)
    {
        session()->put('materi_perPage', $value);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
}