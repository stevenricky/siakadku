<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\PeminjamanBuku;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app-new')]
class InventarisPerpustakaan extends Component
{
    use WithPagination;

    public $search = '';
    public $kategoriFilter = '';
    public $statusFilter = '';
    public $perPage = 10;

    // Modal properties
    public $showTambahModal = false;
    public $showEditModal = false;
    public $showDetailModal = false;

    // Form properties
    public $bukuId;
    public $isbn;
    public $judul;
    public $penulis;
    public $penerbit;
    public $tahun_terbit;
    public $kategori_id;
    public $stok;
    public $rak_buku;
    public $deskripsi;
    public $status = 'tersedia';

    // Data selected
    public $selectedBuku = null;

    // Stats properties
    public $totalBuku;
    public $totalKategori;
    public $peminjamAktif;
    public $peminjamanBulanIni;
    public $bukuPopuler;
    public $statistikKategori;

    public function mount()
    {
        $this->hitungStatistik();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingKategoriFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedPerPage($value)
    {
        $this->resetPage();
    }

    private function hitungStatistik()
    {
        // Total buku
        $this->totalBuku = Buku::count();

        // Total kategori aktif
        $this->totalKategori = KategoriBuku::aktif()->count();

        // Peminjam aktif
        $this->peminjamAktif = PeminjamanBuku::whereIn('status', ['dipinjam', 'terlambat'])->count();

        // Peminjaman bulan ini
        $this->peminjamanBulanIni = PeminjamanBuku::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Buku populer (buku dengan peminjaman terbanyak)
        $this->bukuPopuler = PeminjamanBuku::select('buku_id', DB::raw('COUNT(*) as total_peminjaman'))
            ->with(['buku' => function($query) {
                $query->with('kategori');
            }])
            ->groupBy('buku_id')
            ->orderBy('total_peminjaman', 'desc')
            ->limit(5)
            ->get();

        // Statistik kategori
        $this->statistikKategori = KategoriBuku::aktif()
            ->withCount('buku')
            ->orderBy('buku_count', 'desc')
            ->get();
    }

    // Modal Methods
    public function openTambahModal()
    {
        $this->resetForm();
        $this->showTambahModal = true;
    }

    public function closeTambahModal()
    {
        $this->showTambahModal = false;
        $this->resetForm();
    }

    public function openEditModal($bukuId)
    {
        $buku = Buku::find($bukuId);
        if ($buku) {
            $this->bukuId = $buku->id;
            $this->isbn = $buku->isbn;
            $this->judul = $buku->judul;
            $this->penulis = $buku->penulis;
            $this->penerbit = $buku->penerbit;
            $this->tahun_terbit = $buku->tahun_terbit;
            $this->kategori_id = $buku->kategori_id;
            $this->stok = $buku->stok;
            $this->rak_buku = $buku->rak_buku;
            $this->deskripsi = $buku->deskripsi;
            $this->status = $buku->status;
            $this->showEditModal = true;
        }
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
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

    private function resetForm()
    {
        $this->reset([
            'bukuId', 'isbn', 'judul', 'penulis', 'penerbit', 
            'tahun_terbit', 'kategori_id', 'stok', 'rak_buku', 
            'deskripsi', 'status'
        ]);
        $this->status = 'tersedia';
    }

    // CRUD Methods
    public function simpanBuku()
    {
        $this->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'kategori_id' => 'required|exists:kategori_buku,id',
            'stok' => 'required|integer|min:0',
            'rak_buku' => 'required|string|max:255',
        ]);

        try {
            Buku::create([
                'isbn' => $this->isbn,
                'judul' => $this->judul,
                'penulis' => $this->penulis,
                'penerbit' => $this->penerbit,
                'tahun_terbit' => $this->tahun_terbit,
                'kategori_id' => $this->kategori_id,
                'stok' => $this->stok,
                'rak_buku' => $this->rak_buku,
                'deskripsi' => $this->deskripsi,
                'status' => $this->status,
            ]);

            $this->closeTambahModal();
            $this->hitungStatistik(); // Refresh stats
            session()->flash('success', 'Buku berhasil ditambahkan.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateBuku()
    {
        $this->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'kategori_id' => 'required|exists:kategori_buku,id',
            'stok' => 'required|integer|min:0',
            'rak_buku' => 'required|string|max:255',
        ]);

        try {
            $buku = Buku::find($this->bukuId);
            if ($buku) {
                $buku->update([
                    'isbn' => $this->isbn,
                    'judul' => $this->judul,
                    'penulis' => $this->penulis,
                    'penerbit' => $this->penerbit,
                    'tahun_terbit' => $this->tahun_terbit,
                    'kategori_id' => $this->kategori_id,
                    'stok' => $this->stok,
                    'rak_buku' => $this->rak_buku,
                    'deskripsi' => $this->deskripsi,
                    'status' => $this->status,
                ]);

                $this->closeEditModal();
                $this->hitungStatistik(); // Refresh stats
                session()->flash('success', 'Buku berhasil diperbarui.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function hapusBuku($bukuId)
    {
        try {
            $buku = Buku::find($bukuId);
            if ($buku) {
                // Cek apakah buku sedang dipinjam
                if ($buku->peminjaman()->whereIn('status', ['dipinjam', 'terlambat'])->exists()) {
                    session()->flash('error', 'Tidak dapat menghapus buku yang sedang dipinjam.');
                    return;
                }

                $buku->delete();
                $this->hitungStatistik(); // Refresh stats
                session()->flash('success', 'Buku berhasil dihapus.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Quick Actions
    public function refreshData()
    {
        $this->hitungStatistik();
        session()->flash('info', 'Data berhasil diperbarui.');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'kategoriFilter', 'statusFilter']);
        $this->resetPage();
    }

    public function render()
{
    $buku = Buku::with('kategori')
        // HAPUS BARIS INI: ->kategoriAktif()
        ->when($this->search, function($query) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%'.$this->search.'%')
                  ->orWhere('penulis', 'like', '%'.$this->search.'%')
                  ->orWhere('penerbit', 'like', '%'.$this->search.'%')
                  ->orWhere('isbn', 'like', '%'.$this->search.'%');
            });
        })
        ->when($this->kategoriFilter, function($query) {
            $query->where('kategori_id', $this->kategoriFilter);
        })
        ->when($this->statusFilter, function($query) {
            $query->where('status', $this->statusFilter);
        })
        ->latest()
        ->paginate($this->perPage);

        $kategoriList = KategoriBuku::aktif()->get();

        return view('livewire.admin.inventaris-perpustakaan', [
            'buku' => $buku,
            'kategoriList' => $kategoriList,
        ]);
    }
}