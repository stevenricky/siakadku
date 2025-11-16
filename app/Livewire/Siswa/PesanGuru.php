<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use App\Models\User;
use App\Models\Pesan;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class PesanGuru extends Component
{
    public $pesanTeks = '';
    public $penerimaId;
    public $pesanTerpilih = null;
    public $subjek = '';

    public function render()
    {
        $guruList = User::where('role', 'guru')
            ->whereHas('guru.jadwal.kelas.siswa', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->with(['guru.mapel' => function($query) {
                $query->select('mapels.id', 'mapels.nama_mapel');
            }])
            ->select('id', 'name', 'email')
            ->get()
            ->map(function($user) {
                $mapel = $user->guru->mapel->first();
                $user->nama_mapel = $mapel ? $mapel->nama_mapel : 'Guru';
                return $user;
            });

        $pesanList = collect();
        $unreadCounts = [];

        if ($this->penerimaId) {
            $pesanList = Pesan::where(function($query) {
                $query->where('pengirim_id', auth()->id())
                      ->where('penerima_id', $this->penerimaId);
            })->orWhere(function($query) {
                $query->where('pengirim_id', $this->penerimaId)
                      ->where('penerima_id', auth()->id());
            })
            ->with(['pengirim:id,name', 'penerima:id,name'])
            ->orderBy('created_at', 'asc')
            ->get();

            Pesan::where('pengirim_id', $this->penerimaId)
                 ->where('penerima_id', auth()->id())
                 ->where('dibaca', false)
                 ->update([
                     'dibaca' => true,
                     'dibaca_pada' => now()
                 ]);
        }

        foreach ($guruList as $guru) {
            $unreadCounts[$guru->id] = Pesan::where('pengirim_id', $guru->id)
                ->where('penerima_id', auth()->id())
                ->where('dibaca', false)
                ->count();
        }

        return view('livewire.siswa.pesan-guru', [
            'guruList' => $guruList,
            'pesanList' => $pesanList,
            'unreadCounts' => $unreadCounts
        ]);
    }

    public function pilihPenerima($userId)
    {
        $this->penerimaId = $userId;
        $this->pesanTerpilih = null;
        $this->subjek = '';
        $this->pesanTeks = '';
    }

    public function kirimPesan()
    {

        if (empty($this->penerimaId)) {
            session()->flash('error', 'Silakan pilih guru terlebih dahulu.');
            return;
        }

        try {
            Pesan::create([
                'pengirim_id' => auth()->id(),
                'penerima_id' => $this->penerimaId,
                'subjek' => $this->subjek ?: 'Pesan dari ' . auth()->user()->name,
                'pesan' => $this->pesanTeks,
                'dibaca' => false,
            ]);

            // Reset hanya pesan teks, biar subjek tetap
            $this->pesanTeks = '';

            session()->flash('success', 'Pesan berhasil dikirim.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengirim pesan: ' . $e->getMessage());
        }
    }

    // Method untuk handle enter key
    public function updatedPesanTeks($value)
    {
        // Kosongkan flash messages ketika mengetik
        session()->forget(['success', 'error']);
    }

    public function pilihPesan($pesanId)
    {
        $this->pesanTerpilih = Pesan::with(['pengirim:id,name', 'penerima:id,name'])->find($pesanId);
    }

    public function hapusPesan($pesanId)
    {
        $pesan = Pesan::find($pesanId);
        
        if ($pesan && $pesan->pengirim_id === auth()->id()) {
            $pesan->delete();
            session()->flash('success', 'Pesan berhasil dihapus.');
            
            if ($this->pesanTerpilih && $this->pesanTerpilih->id === $pesanId) {
                $this->pesanTerpilih = null;
            }
        }
    }

    public function resetForm()
    {
        $this->pesanTeks = '';
        $this->subjek = '';
    }
}