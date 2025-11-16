<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PeminjamanInventaris;
use App\Models\BarangInventaris;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\KategoriInventaris;
use App\Models\Ruangan;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class PeminjamanInventarisPage extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $statusFilter;
    public $tanggalFilter;

    public $siswaList;
    public $barangList;
    public $kategoriList;
    public $ruanganList;
    
    // Properties for modals
    public $showTambahModal = false;
    public $showDetailModal = false;
    public $showKembalikanModal = false;
    public $selectedPeminjaman = null;
    
    // Form properties
    public $peminjamId;
    public $barangId;
    public $tanggalPinjam;
    public $tanggalKembali;
    public $keterangan;
    public $tanggalDikembalikan;
    public $kondisiBuku;
    public $keteranganPengembalian;
    public $denda;

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
        
        $guru = auth()->user()->guru;
        $kelasWali = $guru->kelasWali;
        
        if ($kelasWali) {
            $this->siswaList = Siswa::where('kelas_id', $kelasWali->id)
                ->where('status', 'aktif')
                ->get();
        } else {
            $this->siswaList = Siswa::where('status', 'aktif')->get();
        }
        
        $this->barangList = BarangInventaris::where('kondisi', 'baik')
            ->where('jumlah', '>', 0)
            ->get();
        $this->kategoriList = KategoriInventaris::all();
        $this->ruanganList = Ruangan::all();
        
        $this->tanggalPinjam = now()->format('Y-m-d');
        $this->tanggalKembali = now()->addDays(7)->format('Y-m-d');
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function render()
    {
        $guru = auth()->user()->guru;
        $kelasWali = $guru->kelasWali;
        
        $peminjamanQuery = PeminjamanInventaris::with(['peminjam', 'barang'])
            ->when($kelasWali, function($query) use ($kelasWali) {
                // Filter berdasarkan kelas wali melalui relasi user-siswa
                $query->whereHas('peminjam', function($q) use ($kelasWali) {
                    $q->whereHas('siswa', function($sq) use ($kelasWali) {
                        $sq->where('kelas_id', $kelasWali->id);
                    });
                });
            })
            ->when($this->statusFilter, function($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->tanggalFilter, function($query) {
                $query->whereDate('tanggal_pinjam', $this->tanggalFilter);
            })
            ->when($this->search, function($query) {
                $query->whereHas('peminjam', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('siswa', function($sq) {
                          $sq->where('nama_lengkap', 'like', '%' . $this->search . '%');
                      });
                })
                ->orWhereHas('barang', function($q) {
                    $q->where('nama_barang', 'like', '%' . $this->search . '%');
                })
                ->orWhere('kode_peminjaman', 'like', '%' . $this->search . '%');
            });

        $peminjaman = $peminjamanQuery->latest()->paginate($this->perPage);
        
        // Calculate statistics
        $totalDipinjam = $peminjamanQuery->clone()->whereIn('status', ['dipinjam', 'terlambat'])->count();
        $totalTerlambat = $peminjamanQuery->clone()->where('status', 'terlambat')->count();
        $totalDikembalikan = $peminjamanQuery->clone()->where('status', 'dikembalikan')->count();

        return view('livewire.guru.peminjaman-inventaris', [
            'peminjaman' => $peminjaman,
            'totalDipinjam' => $totalDipinjam,
            'totalTerlambat' => $totalTerlambat,
            'totalDikembalikan' => $totalDikembalikan,
            'kelasWali' => $kelasWali
        ]);
    }

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

    public function showDetail($id)
    {
        $this->selectedPeminjaman = PeminjamanInventaris::with(['peminjam', 'barang'])->find($id);
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedPeminjaman = null;
    }

    public function openKembalikanModal($id)
    {
        $this->selectedPeminjaman = PeminjamanInventaris::with(['peminjam', 'barang'])->find($id);
        
        // Calculate denda if late
        if ($this->selectedPeminjaman->status === 'terlambat') {
            $tanggalKembali = \Carbon\Carbon::parse($this->selectedPeminjaman->tanggal_kembali);
            $hariTerlambat = now()->diffInDays($tanggalKembali, false);
            $this->denda = abs($hariTerlambat) * 1000; // Rp 1000 per hari
        } else {
            $this->denda = 0;
        }
        
        $this->tanggalDikembalikan = now()->format('Y-m-d');
        $this->showKembalikanModal = true;
    }

    public function closeKembalikanModal()
    {
        $this->showKembalikanModal = false;
        $this->selectedPeminjaman = null;
        $this->reset(['tanggalDikembalikan', 'kondisiBuku', 'keteranganPengembalian', 'denda']);
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'tanggalFilter']);
    }

    public function resetForm()
    {
        $this->reset(['peminjamId', 'barangId', 'tanggalPinjam', 'tanggalKembali', 'keterangan']);
        $this->tanggalPinjam = now()->format('Y-m-d');
        $this->tanggalKembali = now()->addDays(7)->format('Y-m-d');
    }

    public function simpanPeminjaman()
    {
        $this->validate([
            'peminjamId' => 'required|exists:users,id',
            'barangId' => 'required|exists:barang_inventaris,id',
            'tanggalPinjam' => 'required|date',
            'tanggalKembali' => 'required|date|after_or_equal:tanggalPinjam',
        ]);

        try {
            // Generate unique code
            $kodePeminjaman = 'PJ-' . date('Ymd') . '-' . rand(1000, 9999);
            
            // Create peminjaman
            $peminjaman = PeminjamanInventaris::create([
                'kode_peminjaman' => $kodePeminjaman,
                'peminjam_id' => $this->peminjamId,
                'barang_id' => $this->barangId,
                'tanggal_pinjam' => $this->tanggalPinjam,
                'tanggal_kembali' => $this->tanggalKembali,
                'keterangan' => $this->keterangan,
                'status' => 'dipinjam'
            ]);

            // Update barang stock
            $barang = BarangInventaris::find($this->barangId);
            $barang->jumlah = $barang->jumlah - 1;
            $barang->save();

            session()->flash('success', 'Peminjaman berhasil ditambahkan!');
            $this->closeTambahModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function kembalikanBuku()
    {
        $this->validate([
            'tanggalDikembalikan' => 'required|date',
            'kondisiBuku' => 'required|in:baik,rusak,hilang',
        ]);

        try {
            $peminjaman = $this->selectedPeminjaman;
            
            // Update peminjaman status
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_dikembalikan' => $this->tanggalDikembalikan,
                'kondisi_pengembalian' => $this->kondisiBuku,
                'keterangan_pengembalian' => $this->keteranganPengembalian,
                'denda' => $this->denda
            ]);

            // Update barang stock if not lost
            if ($this->kondisiBuku !== 'hilang') {
                $barang = BarangInventaris::find($peminjaman->barang_id);
                $barang->jumlah = $barang->jumlah + 1;
                $barang->save();
            }

            session()->flash('success', 'Barang berhasil dikembalikan!');
            $this->closeKembalikanModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}