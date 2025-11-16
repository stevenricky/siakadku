<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Buku;
use App\Models\KategoriBuku;

#[Layout('layouts.app-new')]
class KatalogBuku extends Component
{
    use WithPagination;

    public $search = '';
    public $kategoriFilter = '';
    public $perPage = 12;
    public $showDetailModal = false;
    public $selectedBuku = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'kategoriFilter' => ['except' => ''],
        'perPage' => ['except' => 12]
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingKategoriFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function pinjamBuku($bukuId)
    {
        return redirect()->route('siswa.peminjaman-buku.create', ['buku_id' => $bukuId]);
    }

    public function showDetail($bukuId)
    {
        $this->selectedBuku = Buku::with('kategori')->find($bukuId);
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedBuku = null;
    }

    public function render()
    {
        $query = Buku::with('kategori')
            ->where('status', 'tersedia')
            ->whereRaw('stok > dipinjam');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%'.$this->search.'%')
                  ->orWhere('penulis', 'like', '%'.$this->search.'%')
                  ->orWhere('penerbit', 'like', '%'.$this->search.'%')
                  ->orWhere('isbn', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->kategoriFilter) {
            $query->where('kategori_id', $this->kategoriFilter);
        }

        $buku = $query->paginate($this->perPage);
        $kategori = KategoriBuku::all();

        return view('livewire.siswa.katalog-buku', [
            'buku' => $buku,
            'kategori' => $kategori
        ]);
    }
}