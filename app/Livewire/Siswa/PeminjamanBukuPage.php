<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use App\Models\Buku;
use App\Models\PeminjamanBuku;
use App\Models\Siswa;
use Illuminate\Support\Str;

#[Layout('layouts.app-new')]
class PeminjamanBukuPage extends Component
{
    public $bukuId;
    public $buku;
    
    #[Validate('required')]
    public $tanggal_pinjam;
    
    #[Validate('required')]
    public $tanggal_kembali;
    
    public $keterangan = '';

    public function mount($buku_id = null)
    {
        if ($buku_id) {
            $this->bukuId = $buku_id;
            $this->buku = Buku::with('kategori')->find($buku_id);
            
            if (!$this->buku) {
                session()->flash('error', 'Buku tidak ditemukan!');
                return redirect()->route('siswa.katalog-buku.index');
            }
            
            // Set tanggal default
            $this->tanggal_pinjam = now()->format('Y-m-d');
            $this->tanggal_kembali = now()->addDays(7)->format('Y-m-d');
        } else {
            // Jika tidak ada buku_id, redirect ke katalog
            return redirect()->route('siswa.katalog-buku.index');
        }
    }

    public function render()
    {
        return view('livewire.siswa.peminjaman-buku-page', [
            'buku' => $this->buku
        ]);
    }

    public function pinjamBuku()
{
    $this->validate();
    
    // Cek apakah buku masih tersedia
    if (!$this->buku->can_borrow) {
        session()->flash('error', 'Maaf, buku ini sudah tidak tersedia untuk dipinjam!');
        return redirect()->route('siswa.katalog-buku.index');
    }
    
    // Cek apakah siswa sudah meminjam buku yang sama
    $peminjamanAktif = PeminjamanBuku::where('siswa_id', auth()->id())
        ->where('buku_id', $this->bukuId)
        ->whereIn('status', ['dipinjam', 'terlambat'])
        ->exists();
        
    if ($peminjamanAktif) {
        session()->flash('error', 'Anda sudah meminjam buku ini dan belum mengembalikannya!');
        return;
    }

    try {
        // Generate kode peminjaman
        $kodePeminjaman = 'PJM-' . Str::upper(Str::random(8));
        
        // Buat peminjaman
        $peminjaman = PeminjamanBuku::create([
            'kode_peminjaman' => $kodePeminjaman,
            'siswa_id' => auth()->id(),
            'buku_id' => $this->bukuId,
            'tanggal_pinjam' => $this->tanggal_pinjam,
            'tanggal_kembali' => $this->tanggal_kembali,
            'status' => 'dipinjam',
            'denda' => 0,
            'keterangan' => $this->keterangan,
            'petugas_id' => auth()->id() // Untuk sementara, siswa sebagai petugas
        ]);
        
        // Update stok buku
        $this->buku->pinjam();
        
        session()->flash('success', 'Buku berhasil dipinjam! Kode peminjaman: ' . $kodePeminjaman);
        
        // REDIRECT KE HALAMAN RIWAYAT PEMINJAMAN
        return redirect()->route('siswa.riwayat-peminjaman.index');
        
    } catch (\Exception $e) {
        session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    public function batal()
    {
        return redirect()->route('siswa.katalog-buku.index');
    }
}