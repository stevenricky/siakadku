<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\PeminjamanBuku;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app-new')]
class RiwayatPeminjaman extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = 'semua';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => 'semua'],
        'perPage' => ['except' => 10]
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = PeminjamanBuku::with(['buku', 'buku.kategori'])
            ->where('siswa_id', Auth::id())
            ->latest();

        // Filter pencarian
        if ($this->search) {
            $query->where(function($q) {
                $q->where('kode_peminjaman', 'like', '%'.$this->search.'%')
                  ->orWhereHas('buku', function($q) {
                      $q->where('judul', 'like', '%'.$this->search.'%')
                        ->orWhere('penulis', 'like', '%'.$this->search.'%');
                  });
            });
        }

        // Filter status
        if ($this->filterStatus !== 'semua') {
            $query->where('status', $this->filterStatus);
        }

        $peminjaman = $query->paginate($this->perPage);

        return view('livewire.siswa.riwayat-peminjaman', [
            'peminjaman' => $peminjaman,
            'totalPeminjaman' => PeminjamanBuku::where('siswa_id', Auth::id())->count(),
            'peminjamanAktif' => PeminjamanBuku::where('siswa_id', Auth::id())->aktif()->count(),
            'peminjamanSelesai' => PeminjamanBuku::where('siswa_id', Auth::id())->selesai()->count(),
        ]);
    }

    public function batalkanPeminjaman($id)
    {
        $peminjaman = PeminjamanBuku::where('siswa_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'dipinjam')
            ->first();

        if ($peminjaman) {
            try {
                // Kembalikan stok buku
                $peminjaman->buku->kembalikan();
                
                // Hapus peminjaman
                $peminjaman->delete();
                
                session()->flash('success', 'Peminjaman berhasil dibatalkan!');
            } catch (\Exception $e) {
                session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        } else {
            session()->flash('error', 'Peminjaman tidak ditemukan atau tidak dapat dibatalkan!');
        }
    }
}