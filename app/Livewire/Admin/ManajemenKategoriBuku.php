<?php

namespace App\Livewire\Admin;

use App\Models\KategoriBuku;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('layouts.app-new')]
class ManajemenKategoriBuku extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    
    // Form properties
    public $showForm = false;
    public $formType = 'create';
    public $kategoriId = null;
    
    public $nama_kategori;
    public $kode_kategori;
    public $deskripsi;
    public $status = true;

    protected $queryString = ['search', 'perPage'];

    protected $rules = [
        'nama_kategori' => 'required|min:3|max:255',
        'kode_kategori' => 'required|min:2|max:10|unique:kategori_buku,kode_kategori',
        'deskripsi' => 'nullable|max:500',
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->formType = 'create';
        $this->showForm = true;
    }

    public function openEditForm($id)
    {
        $kategori = KategoriBuku::findOrFail($id);
        
        $this->kategoriId = $kategori->id;
        $this->nama_kategori = $kategori->nama_kategori;
        $this->kode_kategori = $kategori->kode_kategori;
        $this->deskripsi = $kategori->deskripsi;
        $this->status = $kategori->status;
        
        $this->formType = 'edit';
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'kategoriId', 'nama_kategori', 'kode_kategori', 'deskripsi', 'status'
        ]);
        $this->resetErrorBag();
    }

    public function saveKategori()
    {
        if ($this->formType === 'edit') {
            $this->rules['kode_kategori'] = 'required|min:2|max:10|unique:kategori_buku,kode_kategori,' . $this->kategoriId;
        }

        $validatedData = $this->validate();

        try {
            $validatedData['kode_kategori'] = Str::upper($validatedData['kode_kategori']);

            if ($this->formType === 'create') {
                KategoriBuku::create($validatedData);
                session()->flash('success', 'Kategori buku berhasil ditambahkan.');
            } else {
                $kategori = KategoriBuku::find($this->kategoriId);
                $kategori->update($validatedData);
                session()->flash('success', 'Kategori buku berhasil diperbarui.');
            }

            $this->closeForm();
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $kategori = KategoriBuku::findOrFail($id);
            $kategori->update([
                'status' => !$kategori->status
            ]);

            session()->flash('success', 'Status kategori berhasil diubah.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteKategori($id)
    {
        try {
            $kategori = KategoriBuku::findOrFail($id);
            
            // Cek apakah kategori memiliki buku
            if ($kategori->buku()->count() > 0) {
                session()->flash('error', 'Tidak dapat menghapus kategori karena masih memiliki buku terkait.');
                return;
            }

            $kategori->delete();
            session()->flash('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = KategoriBuku::withCount(['buku as buku_count'])
            ->when($this->search, function ($query) {
                $query->where('nama_kategori', 'like', '%' . $this->search . '%')
                      ->orWhere('kode_kategori', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc');

        $kategori = $query->paginate($this->perPage);

        // Stats untuk cards
        $totalKategori = KategoriBuku::count();
        $totalAktif = KategoriBuku::where('status', true)->count();
        $totalNonaktif = KategoriBuku::where('status', false)->count();

        return view('livewire.admin.manajemen-kategori-buku', [
            'kategori' => $kategori,
            'totalKategori' => $totalKategori,
            'totalAktif' => $totalAktif,
            'totalNonaktif' => $totalNonaktif,
        ]);
    }
}