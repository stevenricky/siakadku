<?php

namespace App\Livewire\Admin;

use App\Models\Pesan;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class ManajemenPesan extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $showKirimPesan = false;
    
    // Properties untuk form kirim pesan
    public $tipePenerima = 'guru';
    public $penerima_id;
    public $subjek;
    public $pesan;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleKirimPesan()
    {
        $this->showKirimPesan = !$this->showKirimPesan;
        $this->reset(['tipePenerima', 'penerima_id', 'subjek', 'pesan']);
    }

    public function kirimPesan()
    {
        $this->validate([
            'subjek' => 'required|string|max:255',
            'pesan' => 'required|string',
            'tipePenerima' => 'required|in:guru,siswa,semua,individu',
        ]);

        if ($this->tipePenerima === 'individu') {
            $this->validate([
                'penerima_id' => 'required|exists:users,id',
            ]);
        }

        try {
            $pengirimId = auth()->id();
            $penerimaIds = [];

            switch ($this->tipePenerima) {
                case 'semua':
                    $penerimaIds = User::where('id', '!=', $pengirimId)
                        ->whereIn('role', ['guru', 'siswa'])
                        ->pluck('id')
                        ->toArray();
                    break;
                case 'guru':
                    $penerimaIds = User::where('role', 'guru')->pluck('id')->toArray();
                    break;
                case 'siswa':
                    $penerimaIds = User::where('role', 'siswa')->pluck('id')->toArray();
                    break;
                case 'individu':
                    $penerimaIds = [$this->penerima_id];
                    break;
            }

            // Kirim pesan ke semua penerima
            foreach ($penerimaIds as $penerimaId) {
                Pesan::create([
                    'pengirim_id' => $pengirimId,
                    'penerima_id' => $penerimaId,
                    'subjek' => $this->subjek,
                    'pesan' => $this->pesan,
                    'dibaca' => false,
                ]);
            }

            session()->flash('message', 'Pesan berhasil dikirim ke ' . count($penerimaIds) . ' penerima.');
            $this->toggleKirimPesan();

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengirim pesan: ' . $e->getMessage());
        }
    }

    public function deletePesan($id)
    {
        try {
            $pesan = Pesan::findOrFail($id);
            $pesan->delete();
            
            session()->flash('message', 'Pesan berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus pesan: ' . $e->getMessage());
        }
    }

    public function markAsRead($id)
    {
        try {
            $pesan = Pesan::findOrFail($id);
            $pesan->update([
                'dibaca' => true,
                'dibaca_pada' => now()
            ]);
            
            session()->flash('message', 'Pesan ditandai sebagai sudah dibaca.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui status pesan: ' . $e->getMessage());
        }
    }

    public function markAsUnread($id)
    {
        try {
            $pesan = Pesan::findOrFail($id);
            $pesan->update([
                'dibaca' => false,
                'dibaca_pada' => null
            ]);
            
            session()->flash('message', 'Pesan ditandai sebagai belum dibaca.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui status pesan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Pesan::with(['pengirim', 'penerima'])
            ->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('pesan', 'like', '%' . $this->search . '%')
                  ->orWhere('subjek', 'like', '%' . $this->search . '%')
                  ->orWhereHas('pengirim', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('penerima', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $pesanList = $query->paginate($this->perPage);
        $users = User::where('id', '!=', auth()->id())->get();
        $gurus = User::where('role', 'guru')->get();
        $siswas = User::where('role', 'siswa')->get();

        return view('livewire.admin.manajemen-pesan', [
            'pesanList' => $pesanList,
            'users' => $users,
            'gurus' => $gurus,
            'siswas' => $siswas,
        ]);
    }
}