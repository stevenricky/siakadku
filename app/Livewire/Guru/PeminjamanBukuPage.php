<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PeminjamanBuku;
use App\Models\Siswa;
use App\Models\Buku;
use App\Models\Kelas;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class PeminjamanBukuPage extends Component
{
    use WithPagination;

    // Properties untuk filter
    public $search = '';
    public $statusFilter = '';
    public $tanggalFilter = '';
    public $perPage = 10;

    // Properties untuk modal tambah
    public $showTambahModal = false;
    public $siswaId = '';
    public $bukuId = '';
    public $tanggalPinjam = '';
    public $tanggalKembali = '';
    public $keterangan = '';

    // Properties untuk modal detail
    public $showDetailModal = false;
    public $selectedPeminjaman = null;

    // Properties untuk modal pengembalian
    public $showKembalikanModal = false;
    public $tanggalDikembalikan = '';
    public $kondisiBuku = 'baik';
    public $denda = 0;
    public $keteranganPengembalian = '';

    // Data lists
    public $siswaList = [];
    public $bukuList = [];

    protected $queryString = ['search', 'statusFilter', 'tanggalFilter', 'perPage'];

    public function mount()
    {
        $this->tanggalPinjam = now()->format('Y-m-d');
        $this->tanggalKembali = now()->addDays(7)->format('Y-m-d');
        $this->tanggalDikembalikan = now()->format('Y-m-d');
        
        // Load data siswa dan buku
        $this->loadSiswaList();
        $this->loadBukuList();
    }

    public function loadSiswaList()
    {
        $guru = auth()->user()->guru;
        
        if ($guru->kelasWali) {
            $this->siswaList = Siswa::where('kelas_id', $guru->kelasWali->id)
                ->where('status', 'aktif')
                ->with('kelas')
                ->get();
        } else {
            $this->siswaList = Siswa::where('status', 'aktif')
                ->with('kelas')
                ->get();
        }
    }

    public function loadBukuList()
    {
        // ✅ PERBAIKAN: Gunakan scope tersedia dan kategoriAktif
        $this->bukuList = Buku::tersedia()
            ->kategoriAktif()
            ->with('kategori')
            ->get();
    }

    public function render()
    {
        $query = PeminjamanBuku::with(['siswa.kelas', 'buku.kategori', 'petugas'])
            ->when($this->search, function($q) {
                $q->where(function($query) {
                    $query->where('kode_peminjaman', 'like', '%' . $this->search . '%')
                          ->orWhereHas('siswa', function($q) {
                              $q->where('nama_lengkap', 'like', '%' . $this->search . '%');
                          })
                          ->orWhereHas('buku', function($q) {
                              $q->where('judul', 'like', '%' . $this->search . '%');
                          });
                });
            })
            ->when($this->statusFilter, function($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->tanggalFilter, function($q) {
                $q->whereDate('tanggal_pinjam', $this->tanggalFilter);
            });

        // Jika guru adalah wali kelas, filter berdasarkan kelasnya
        $guru = auth()->user()->guru;
        if ($guru->kelasWali) {
            $query->whereHas('siswa', function($q) use ($guru) {
                $q->where('kelas_id', $guru->kelasWali->id);
            });
        }

        $peminjaman = $query->latest()->paginate($this->perPage);

        // Hitung statistik
        $totalDipinjam = PeminjamanBuku::whereIn('status', ['dipinjam', 'terlambat'])
            ->when($guru->kelasWali, function($q) use ($guru) {
                $q->whereHas('siswa', function($q) use ($guru) {
                    $q->where('kelas_id', $guru->kelasWali->id);
                });
            })
            ->count();

        $totalTerlambat = PeminjamanBuku::where('status', 'terlambat')
            ->when($guru->kelasWali, function($q) use ($guru) {
                $q->whereHas('siswa', function($q) use ($guru) {
                    $q->where('kelas_id', $guru->kelasWali->id);
                });
            })
            ->count();

        $totalDikembalikan = PeminjamanBuku::where('status', 'dikembalikan')
            ->when($guru->kelasWali, function($q) use ($guru) {
                $q->whereHas('siswa', function($q) use ($guru) {
                    $q->where('kelas_id', $guru->kelasWali->id);
                });
            })
            ->count();

        return view('livewire.guru.peminjaman-buku', [
            'peminjaman' => $peminjaman,
            'totalDipinjam' => $totalDipinjam,
            'totalTerlambat' => $totalTerlambat,
            'totalDikembalikan' => $totalDikembalikan,
        ]);
    }

    public function openTambahModal()
    {
        $this->reset(['siswaId', 'bukuId', 'keterangan']);
        $this->tanggalPinjam = now()->format('Y-m-d');
        $this->tanggalKembali = now()->addDays(7)->format('Y-m-d');
        $this->showTambahModal = true;
        
        // Reload daftar buku untuk memastikan stok terbaru
        $this->loadBukuList();
    }

    public function closeTambahModal()
    {
        $this->showTambahModal = false;
        $this->resetValidation();
    }

    public function simpanPeminjaman()
    {
        $this->validate([
            'siswaId' => 'required|exists:siswas,id',
            'bukuId' => 'required|exists:buku,id',
            'tanggalPinjam' => 'required|date',
            'tanggalKembali' => 'required|date|after:tanggalPinjam',
        ]);

        try {
            // Cek stok buku menggunakan accessor stok_tersedia
            $buku = Buku::find($this->bukuId);
            if (!$buku->can_borrow) {
                session()->flash('error', 'Buku tidak tersedia untuk dipinjam. Stok habis atau status tidak tersedia.');
                return;
            }

            // Generate kode peminjaman
            $kodePeminjaman = 'PJM-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4));

            // Simpan peminjaman
            $peminjaman = PeminjamanBuku::create([
                'kode_peminjaman' => $kodePeminjaman,
                'siswa_id' => $this->siswaId,
                'buku_id' => $this->bukuId,
                'tanggal_pinjam' => $this->tanggalPinjam,
                'tanggal_kembali' => $this->tanggalKembali,
                'status' => 'dipinjam',
                'keterangan' => $this->keterangan,
                'petugas_id' => auth()->id(),
            ]);

            // ✅ PERBAIKAN: Gunakan method pinjam() dari model Buku
            $buku->pinjam();

            session()->flash('success', 'Peminjaman buku berhasil disimpan. Kode: ' . $kodePeminjaman);
            $this->closeTambahModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showDetail($id)
    {
        $this->selectedPeminjaman = PeminjamanBuku::with(['siswa.kelas', 'buku.kategori', 'petugas'])
            ->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedPeminjaman = null;
    }

    public function openKembalikanModal($id)
    {
        $this->selectedPeminjaman = PeminjamanBuku::with(['siswa', 'buku'])
            ->findOrFail($id);
        
        $this->tanggalDikembalikan = now()->format('Y-m-d');
        $this->kondisiBuku = 'baik';
        $this->denda = 0;
        $this->keteranganPengembalian = '';

        // Hitung denda jika terlambat
        if ($this->selectedPeminjaman->is_terlambat) {
            $hariTerlambat = Carbon::parse($this->selectedPeminjaman->tanggal_kembali)
                ->diffInDays(Carbon::today(), false);
            
            if ($hariTerlambat > 0) {
                // Denda Rp 1.000 per hari
                $this->denda = $hariTerlambat * 1000;
            }
        }

        $this->showKembalikanModal = true;
    }

    public function closeKembalikanModal()
    {
        $this->showKembalikanModal = false;
        $this->selectedPeminjaman = null;
        $this->reset(['tanggalDikembalikan', 'kondisiBuku', 'denda', 'keteranganPengembalian']);
        $this->resetValidation();
    }

    public function kembalikanBuku()
    {
        $this->validate([
            'tanggalDikembalikan' => 'required|date',
            'kondisiBuku' => 'required|in:baik,rusak,hilang',
        ]);

        try {
            $peminjaman = $this->selectedPeminjaman;
            $buku = $peminjaman->buku;

            // Update status berdasarkan kondisi
            $status = $this->kondisiBuku === 'hilang' ? 'hilang' : 'dikembalikan';

            $updateData = [
                'tanggal_dikembalikan' => $this->tanggalDikembalikan,
                'status' => $status,
                'denda' => $this->denda,
                'keterangan' => $this->keteranganPengembalian ?: 'Buku dikembalikan dalam kondisi ' . $this->kondisiBuku,
            ];

            $peminjaman->update($updateData);

            // ✅ PERBAIKAN: Gunakan method kembalikan() dari model Buku jika tidak hilang
            if ($this->kondisiBuku !== 'hilang') {
                $buku->kembalikan();
            } else {
                // Jika hilang, kurangi stok total
                $buku->decrement('stok');
                $buku->update(['status' => 'hilang']);
            }

            // Jika kondisi rusak, update status buku
            if ($this->kondisiBuku === 'rusak') {
                $buku->update(['status' => 'rusak']);
            }

            session()->flash('success', 'Pengembalian buku berhasil diproses. ' . 
                ($this->denda > 0 ? 'Denda: Rp ' . number_format($this->denda, 0, ',', '.') : ''));
            $this->closeKembalikanModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'tanggalFilter']);
        $this->resetPage();
    }

    public function updatedBukuId($value)
    {
        if ($value) {
            $buku = Buku::find($value);
            if (!$buku || !$buku->can_borrow) {
                session()->flash('error', 'Buku tidak tersedia untuk dipinjam. Stok habis atau status tidak tersedia.');
                $this->bukuId = '';
            }
        }
    }

    public function updated($property)
    {
        // Reset halaman ketika filter berubah
        if (in_array($property, ['search', 'statusFilter', 'tanggalFilter', 'perPage'])) {
            $this->resetPage();
        }
    }
}