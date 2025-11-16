<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\Pesan;

#[Layout('layouts.app-new')]
class PesanChat extends Component
{
    public $pesanTeks = '';
    public $subjek = '';
    public $penerimaId;
    public $pesanTerpilih = null;
    public $showModal = false;
    public $search = '';

    public function render()
    {
        $siswaList = User::where('role', 'siswa')
            ->whereHas('siswa.kelas.jadwal', function($query) {
                $query->where('guru_id', auth()->user()->guru->id);
            })
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('siswa', function($q2) {
                          $q2->where('nama_lengkap', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('siswa.kelas', function($q3) {
                          $q3->where('nama_kelas', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->with('siswa.kelas')
            ->get();

        // Hitung statistik manual
        $totalPesanDikirim = Pesan::where('pengirim_id', auth()->id())->count();
        $totalPesanDiterima = Pesan::where('penerima_id', auth()->id())->count();
        $totalPesan = $totalPesanDikirim + $totalPesanDiterima;
        $pesanBelumDibaca = Pesan::where('penerima_id', auth()->id())->where('dibaca', false)->count();

        $pesanList = [];
        if ($this->penerimaId) {
            $pesanList = Pesan::where(function($query) {
                $query->where('pengirim_id', auth()->id())
                      ->where('penerima_id', $this->penerimaId);
            })->orWhere(function($query) {
                $query->where('pengirim_id', $this->penerimaId)
                      ->where('penerima_id', auth()->id());
            })
            ->with('pengirim', 'penerima')
            ->latest()
            ->take(50)
            ->get()
            ->reverse();
        }

        return view('livewire.guru.pesan-chat', [
            'siswaList' => $siswaList,
            'pesanList' => $pesanList,
            'totalPesan' => $totalPesan,
            'totalSiswa' => $siswaList->count(),
            'pesanBelumDibaca' => $pesanBelumDibaca
        ]);
    }

    public function pilihPenerima($userId)
    {
        $this->penerimaId = $userId;
        $this->pesanTerpilih = null;
        $this->reset(['subjek', 'pesanTeks']);
    }

    public function kirimPesan()
    {
        $this->validate([
            'subjek' => 'required|string|max:255',
            'pesanTeks' => 'required|string|max:1000',
            'penerimaId' => 'required|exists:users,id',
        ]);

        Pesan::create([
            'pengirim_id' => auth()->id(),
            'penerima_id' => $this->penerimaId,
            'subjek' => $this->subjek,
            'pesan' => $this->pesanTeks,
            'dibaca' => false,
        ]);

        $this->reset(['subjek', 'pesanTeks', 'showModal']);
        session()->flash('success', 'Pesan berhasil dikirim.');
    }

    public function bukaModalPesan()
    {
        if (!$this->penerimaId) {
            session()->flash('error', 'Pilih siswa terlebih dahulu.');
            return;
        }
        $this->showModal = true;
    }

    public function pilihPesan($pesanId)
    {
        $this->pesanTerpilih = Pesan::with(['pengirim', 'penerima'])->find($pesanId);
        
        // Tandai sebagai dibaca jika penerima adalah user saat ini
        if ($this->pesanTerpilih->penerima_id == auth()->id() && !$this->pesanTerpilih->dibaca) {
            $this->pesanTerpilih->update([
                'dibaca' => true,
                'dibaca_pada' => now()
            ]);
        }
    }

    public function hapusPesan($pesanId)
    {
        $pesan = Pesan::where('id', $pesanId)
                     ->where('pengirim_id', auth()->id())
                     ->first();

        if ($pesan) {
            $pesan->delete();
            session()->flash('success', 'Pesan berhasil dihapus.');
        }
    }

    public function hitungPesanBelumDibaca($userId)
    {
        return Pesan::where('pengirim_id', $userId)
                   ->where('penerima_id', auth()->id())
                   ->where('dibaca', false)
                   ->count();
    }

    public function updatedSearch()
    {
        // Reset penerima yang dipilih saat mencari
        $this->penerimaId = null;
        $this->pesanTerpilih = null;
    }
}