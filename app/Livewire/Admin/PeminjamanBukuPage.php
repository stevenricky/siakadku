<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\PeminjamanBuku;
use App\Models\Siswa;
use App\Models\Buku;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app-new')]
class PeminjamanBukuPage extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $tanggalFilter = '';
    public $perPage = 10;

    // Modal properties
    public $showTambahModal = false;
    public $showDetailModal = false;
    public $showKembalikanModal = false;

    // Form properties
    public $peminjamanId;
    public $siswaId;
    public $bukuId;
    public $tanggalPinjam;
    public $tanggalKembali;
    public $tanggalDikembalikan;
    public $keterangan = '';

    // Data selected
    public $selectedPeminjaman = null;
    public $denda = 0;
    public $kondisiBuku = 'baik';

    public $siswaList;
    public $bukuList;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'tanggalFilter' => ['except' => '']
    ];

    public function mount()
    {
        $this->perPage = session()->get('perPage_peminjaman', 10);
        $this->tanggalPinjam = now()->format('Y-m-d');
        $this->tanggalKembali = now()->addDays(7)->format('Y-m-d');
        $this->tanggalDikembalikan = now()->format('Y-m-d');
        $this->loadData();
        
        // Update status terlambat otomatis
        PeminjamanBuku::updateStatusTerlambat();
    }

    private function loadData()
    {
        $this->siswaList = Siswa::with('kelas')
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $this->bukuList = Buku::where('status', 'tersedia')
            ->whereRaw('stok > dipinjam')
            ->orderBy('judul')
            ->get();
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage_peminjaman', $value);
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTanggalFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $peminjaman = PeminjamanBuku::with(['siswa.kelas', 'buku', 'petugas'])
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('kode_peminjaman', 'like', '%'.$this->search.'%')
                      ->orWhereHas('siswa', function($q) {
                          $q->where('nama', 'like', '%'.$this->search.'%');
                      })
                      ->orWhereHas('buku', function($q) {
                          $q->where('judul', 'like', '%'.$this->search.'%');
                      });
                });
            })
            ->when($this->statusFilter, function($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->tanggalFilter, function($query) {
                $query->whereDate('tanggal_pinjam', $this->tanggalFilter);
            })
            ->latest()
            ->paginate($this->perPage);

        // Hitung statistik real-time
        $stats = PeminjamanBuku::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = "dipinjam" THEN 1 ELSE 0 END) as dipinjam,
            SUM(CASE WHEN status = "terlambat" THEN 1 ELSE 0 END) as terlambat,
            SUM(CASE WHEN status = "dikembalikan" THEN 1 ELSE 0 END) as dikembalikan,
            SUM(CASE WHEN status = "hilang" THEN 1 ELSE 0 END) as hilang
        ')->first();

        return view('livewire.admin.peminjaman-buku', [
            'peminjaman' => $peminjaman,
            'totalDipinjam' => $stats->dipinjam ?? 0,
            'totalTerlambat' => $stats->terlambat ?? 0,
            'totalDikembalikan' => $stats->dikembalikan ?? 0,
            'totalHilang' => $stats->hilang ?? 0,
        ]);
    }

    // Modal Methods
    public function openTambahModal()
    {
        $this->loadData();
        $this->showTambahModal = true;
        $this->resetForm();
    }

    public function closeTambahModal()
    {
        $this->showTambahModal = false;
        $this->resetForm();
    }

    public function showDetail($peminjamanId)
    {
        $this->selectedPeminjaman = PeminjamanBuku::with(['siswa.kelas', 'buku.kategori', 'petugas'])->find($peminjamanId);
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedPeminjaman = null;
    }

    public function openKembalikanModal($peminjamanId)
    {
        $peminjaman = PeminjamanBuku::with(['buku'])->find($peminjamanId);
        
        if ($peminjaman) {
            $this->peminjamanId = $peminjaman->id;
            $this->selectedPeminjaman = $peminjaman;
            
            // Hitung denda jika terlambat
            $this->denda = $this->hitungDenda($peminjaman);
            
            $this->showKembalikanModal = true;
        }
    }

    public function closeKembalikanModal()
    {
        $this->showKembalikanModal = false;
        $this->reset(['peminjamanId', 'denda', 'kondisiBuku', 'keterangan']);
        $this->selectedPeminjaman = null;
    }

    private function resetForm()
    {
        $this->reset(['siswaId', 'bukuId', 'keterangan']);
        $this->tanggalPinjam = now()->format('Y-m-d');
        $this->tanggalKembali = now()->addDays(7)->format('Y-m-d');
    }

    // CRUD Methods
    public function simpanPeminjaman()
    {
        $this->validate([
            'siswaId' => 'required|exists:siswas,id',
            'bukuId' => 'required|exists:buku,id',
            'tanggalPinjam' => 'required|date',
            'tanggalKembali' => 'required|date|after:tanggalPinjam',
        ]);

        // Cek stok buku
        $buku = Buku::find($this->bukuId);
        if (!$buku || $buku->stok_tersedia <= 0) {
            session()->flash('error', 'Buku tidak tersedia untuk dipinjam. Stok tersedia: ' . ($buku->stok_tersedia ?? 0));
            return;
        }

        // Generate kode peminjaman
        $countToday = PeminjamanBuku::whereDate('created_at', today())->count();
        $kodePeminjaman = 'PJN-' . date('Ymd') . '-' . str_pad($countToday + 1, 3, '0', STR_PAD_LEFT);

        try {
            DB::transaction(function () use ($kodePeminjaman, $buku) {
                // Buat peminjaman
                $peminjaman = PeminjamanBuku::create([
                    'kode_peminjaman' => $kodePeminjaman,
                    'siswa_id' => $this->siswaId,
                    'buku_id' => $this->bukuId,
                    'tanggal_pinjam' => $this->tanggalPinjam,
                    'tanggal_kembali' => $this->tanggalKembali,
                    'keterangan' => $this->keterangan,
                    'petugas_id' => auth()->id(),
                    'status' => 'dipinjam',
                ]);

                // Update stok buku
                $buku->increment('dipinjam');
                
                // Update status buku jika stok habis
                if ($buku->stok_tersedia <= 0) {
                    $buku->update(['status' => 'dipinjam']);
                }
            });

            $this->closeTambahModal();
            session()->flash('success', 'Peminjaman berhasil dibuat dengan kode: ' . $kodePeminjaman);
            
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

        $peminjaman = PeminjamanBuku::with(['buku'])->find($this->peminjamanId);
        
        if ($peminjaman) {
            try {
                DB::transaction(function () use ($peminjaman) {
                    // Hitung denda berdasarkan tanggal dikembalikan
                    $tanggalKembali = Carbon::parse($peminjaman->tanggal_kembali);
                    $tanggalDikembalikan = Carbon::parse($this->tanggalDikembalikan);
                    
                    $denda = 0;
                    if ($tanggalDikembalikan->gt($tanggalKembali)) {
                        $hariTerlambat = $tanggalDikembalikan->diffInDays($tanggalKembali);
                        $denda = $hariTerlambat * 2000; // Rp 2.000 per hari
                    }

                    // Update status peminjaman
                    $status = $this->kondisiBuku === 'hilang' ? 'hilang' : 'dikembalikan';
                    $peminjaman->update([
                        'tanggal_dikembalikan' => $this->tanggalDikembalikan,
                        'status' => $status,
                        'denda' => $denda,
                        'keterangan' => $this->keterangan ?: 'Buku dikembalikan dengan kondisi ' . $this->kondisiBuku,
                    ]);

                    // Update stok buku
                    if ($this->kondisiBuku === 'hilang') {
                        // Jika buku hilang, kurangi stok permanen
                        $peminjaman->buku->decrement('stok');
                        $peminjaman->buku->decrement('dipinjam');
                        $peminjaman->buku->update(['status' => 'hilang']);
                    } else {
                        // Jika dikembalikan, kurangi jumlah dipinjam
                        $peminjaman->buku->decrement('dipinjam');
                        
                        // Update status buku
                        if ($this->kondisiBuku === 'rusak') {
                            $peminjaman->buku->update(['status' => 'rusak']);
                        } elseif ($peminjaman->buku->stok_tersedia > 0) {
                            $peminjaman->buku->update(['status' => 'tersedia']);
                        }
                    }
                });

                $this->closeKembalikanModal();
                session()->flash('success', 'Buku berhasil dikembalikan. ' . 
                    ($this->denda > 0 ? 'Denda: Rp ' . number_format($this->denda, 0, ',', '.') : ''));
                
            } catch (\Exception $e) {
                session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }
    }

    // Helper Methods
    private function hitungDenda($peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam' && $peminjaman->status !== 'terlambat') {
            return 0;
        }

        $tanggalKembali = Carbon::parse($peminjaman->tanggal_kembali);
        $today = Carbon::today();

        if ($today->gt($tanggalKembali)) {
            $hariTerlambat = $today->diffInDays($tanggalKembali);
            return $hariTerlambat * 2000; // Denda Rp 2.000 per hari
        }

        return 0;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'tanggalFilter']);
        $this->resetPage();
    }

    // Quick Actions
    public function refreshData()
    {
        PeminjamanBuku::updateStatusTerlambat();
        session()->flash('info', 'Data berhasil diperbarui.');
    }

    // Listeners
    protected $listeners = ['refreshComponent' => '$refresh'];
}