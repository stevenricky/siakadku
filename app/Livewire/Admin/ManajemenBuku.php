<?php

namespace App\Livewire\Admin;

use App\Models\Buku;
use App\Models\KategoriBuku;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('layouts.app-new')]
class ManajemenBuku extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $kategoriFilter = '';
    public $statusFilter = '';
    public $perPage = 10;

    // Properties untuk modal
    public $showTambahModal = false;
    public $showEditModal = false;
    public $showDetailModal = false;

    // Form properties
    public $bukuId = null;
    public $isbn = '';
    public $judul = '';
    public $penulis = '';
    public $penerbit = '';
    public $tahunTerbit = '';
    public $kategoriId = '';
    public $stok = 1;
    public $rakBuku = '';
    public $deskripsi = '';
    public $cover = null;
    public $status = 'tersedia';

    // Data selected
    public $selectedBuku = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'kategoriFilter' => ['except' => ''],
        'statusFilter' => ['except' => '']
    ];

    public function mount()
    {
        $this->perPage = session()->get('perPage_buku', 10);
        $this->tahunTerbit = date('Y');
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage_buku', $value);
        $this->resetPage();
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

   public function render()
{
    $buku = Buku::with('kategori')
        ->when($this->search, function($query) {
            $query->where('judul', 'like', '%'.$this->search.'%')
                  ->orWhere('penulis', 'like', '%'.$this->search.'%')
                  ->orWhere('penerbit', 'like', '%'.$this->search.'%')
                  ->orWhere('isbn', 'like', '%'.$this->search.'%');
        })
        ->when($this->kategoriFilter, function($query) {
            $query->where('kategori_id', $this->kategoriFilter);
        })
        ->when($this->statusFilter, function($query) {
            $query->where('status', $this->statusFilter);
        })
        ->latest()
        ->paginate($this->perPage);

    $kategoriList = KategoriBuku::where('status', true)->get();

    // Hitung statistik langsung
    $totalBuku = Buku::count();
    $tersedia = Buku::where('status', 'tersedia')->count();
    $dipinjam = Buku::where('status', 'dipinjam')->count();
    $rusakHilang = Buku::whereIn('status', ['rusak', 'hilang'])->count();

    return view('livewire.admin.manajemen-buku', [
        'buku' => $buku,
        'kategoriList' => $kategoriList,
        'totalBuku' => $totalBuku,
        'tersedia' => $tersedia,
        'dipinjam' => $dipinjam,
        'rusakHilang' => $rusakHilang,
    ]);
}
    // Modal Methods
    public function openTambahModal()
    {
        $this->showTambahModal = true;
        $this->resetForm();
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
            $this->tahunTerbit = $buku->tahun_terbit;
            $this->kategoriId = $buku->kategori_id;
            $this->stok = $buku->stok;
            $this->rakBuku = $buku->rak_buku;
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

    public function closeModal()
    {
        $this->showTambahModal = false;
        $this->showEditModal = false;
        $this->showDetailModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'bukuId', 'isbn', 'judul', 'penulis', 'penerbit', 'tahunTerbit',
            'kategoriId', 'stok', 'rakBuku', 'deskripsi', 'cover', 'status'
        ]);
        $this->tahunTerbit = date('Y');
        $this->stok = 1;
        $this->status = 'tersedia';
    }

    // CRUD Methods
    public function simpanBuku()
    {
        $this->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahunTerbit' => 'required|integer|min:1900|max:'.(date('Y')+1),
            'kategoriId' => 'required|exists:kategori_buku,id',
            'stok' => 'required|integer|min:1',
            'rakBuku' => 'required|string|max:100',
            'cover' => 'nullable|image|max:2048',
            'status' => 'required|in:tersedia,dipinjam,rusak,hilang'
        ]);

        $data = [
            'isbn' => $this->isbn,
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'penerbit' => $this->penerbit,
            'tahun_terbit' => $this->tahunTerbit,
            'kategori_id' => $this->kategoriId,
            'stok' => $this->stok,
            'rak_buku' => $this->rakBuku,
            'deskripsi' => $this->deskripsi,
            'status' => $this->status,
        ];

        // Upload cover jika ada
        if ($this->cover) {
            $filename = $this->cover->store('buku-covers', 'public');
            $data['cover'] = $filename;
        }

        Buku::create($data);

        $this->closeTambahModal();
        $this->dispatch('refresh-component');
        session()->flash('success', 'Buku berhasil ditambahkan.');
    }

    public function updateBuku()
    {
        $this->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahunTerbit' => 'required|integer|min:1900|max:'.(date('Y')+1),
            'kategoriId' => 'required|exists:kategori_buku,id',
            'stok' => 'required|integer|min:0',
            'rakBuku' => 'required|string|max:100',
            'cover' => 'nullable|image|max:2048',
            'status' => 'required|in:tersedia,dipinjam,rusak,hilang'
        ]);

        $buku = Buku::find($this->bukuId);
        
        if ($buku) {
            $data = [
                'isbn' => $this->isbn,
                'judul' => $this->judul,
                'penulis' => $this->penulis,
                'penerbit' => $this->penerbit,
                'tahun_terbit' => $this->tahunTerbit,
                'kategori_id' => $this->kategoriId,
                'stok' => $this->stok,
                'rak_buku' => $this->rakBuku,
                'deskripsi' => $this->deskripsi,
                'status' => $this->status,
            ];

            // Upload cover baru jika ada
            if ($this->cover) {
                // Hapus cover lama jika ada
                if ($buku->cover) {
                    Storage::disk('public')->delete($buku->cover);
                }
                $filename = $this->cover->store('buku-covers', 'public');
                $data['cover'] = $filename;
            }

            $buku->update($data);

            $this->closeEditModal();
            $this->dispatch('refresh-component');
            session()->flash('success', 'Buku berhasil diperbarui.');
        }
    }

    public function hapusBuku($bukuId)
    {
        $buku = Buku::find($bukuId);
        
        if ($buku) {
            // Hapus cover jika ada
            if ($buku->cover) {
                Storage::disk('public')->delete($buku->cover);
            }
            
            $buku->delete();

            $this->dispatch('refresh-component');
            session()->flash('success', 'Buku berhasil dihapus.');
        }
    }

    // Export & Print Methods
    public function exportCSV()
    {
        $buku = Buku::with('kategori')
            ->when($this->kategoriFilter, function($query) {
                $query->where('kategori_id', $this->kategoriFilter);
            })
            ->when($this->statusFilter, function($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->get();

        $fileName = 'data-buku-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($buku) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            
            fputcsv($file, [
                'ISBN', 'Judul', 'Penulis', 'Penerbit', 'Tahun Terbit', 
                'Kategori', 'Stok', 'Dipinjam', 'Stok Tersedia', 'Rak', 'Status'
            ]);

            foreach ($buku as $item) {
                fputcsv($file, [
                    $item->isbn ?? '-',
                    $item->judul,
                    $item->penulis,
                    $item->penerbit,
                    $item->tahun_terbit,
                    $item->kategori->nama_kategori ?? '-',
                    $item->stok,
                    $item->dipinjam,
                    $item->stok_tersedia,
                    $item->rak_buku,
                    $item->status_text
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function printPDF()
    {
        $buku = Buku::with('kategori')
            ->when($this->kategoriFilter, function($query) {
                $query->where('kategori_id', $this->kategoriFilter);
            })
            ->when($this->statusFilter, function($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->get();

        $data = [
            'buku' => $buku,
            'tanggalCetak' => now()->translatedFormat('d F Y'),
            'totalBuku' => $buku->count(),
            'totalStok' => $buku->sum('stok'),
            'filterKategori' => $this->kategoriFilter ? 
                (KategoriBuku::find($this->kategoriFilter)->nama_kategori ?? 'Semua Kategori') : 'Semua Kategori',
            'filterStatus' => $this->statusFilter ?: 'Semua Status'
        ];

        $pdf = PDF::loadView('exports.buku-pdf', $data)
                  ->setPaper('a4', 'landscape')
                  ->setOptions([
                      'dpi' => 150, 
                      'defaultFont' => 'sans-serif',
                      'isHtml5ParserEnabled' => true,
                      'isRemoteEnabled' => true
                  ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'laporan-data-buku-' . date('Y-m-d') . '.pdf');
    }

    // Helper Methods
    public function resetFilters()
    {
        $this->reset(['search', 'kategoriFilter', 'statusFilter']);
        $this->resetPage();
    }

    // Computed Properties - PERBAIKAN: Sesuaikan dengan nama di view
    public function getTotalBukuProperty()
    {
        return Buku::count();
    }

    public function getTersediaProperty()
    {
        return Buku::where('status', 'tersedia')->count();
    }

    public function getDipinjamProperty()
    {
        return Buku::where('status', 'dipinjam')->count();
    }

    public function getRusakHilangProperty()
    {
        return Buku::whereIn('status', ['rusak', 'hilang'])->count();
    }

    // Listeners
    protected $listeners = ['refreshComponent' => '$refresh'];
}