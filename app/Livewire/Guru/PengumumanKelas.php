<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Kelas;
use App\Models\Pengumuman;
use Carbon\Carbon;

#[Layout('layouts.app-new')]
class PengumumanKelas extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $kelasId;
    public $judul;
    public $isi;
    public $isUrgent = false;
    public $tanggalBerlaku;
    public $showModal = false;
    public $editId = null;

    public function mount()
    {
        $this->tanggalBerlaku = Carbon::now()->addWeek()->format('Y-m-d');
    }

    public function render()
    {
        // Ambil kelas yang diajar oleh guru ini
        $kelasDiajar = Kelas::whereHas('jadwal', function($query) {
            $query->where('guru_id', auth()->user()->guru->id);
        })->get();

        // Ambil pengumuman yang dibuat oleh guru ini untuk kelas yang diajar
        $pengumumanList = Pengumuman::with(['kelas', 'user'])
            ->where('user_id', auth()->id())
            ->where(function($query) use ($kelasDiajar) {
                $query->whereIn('kelas_id', $kelasDiajar->pluck('id'))
                      ->orWhere('target', 'semua');
            })
            ->when($this->search, function($query) {
                $query->where('judul', 'like', '%'.$this->search.'%')
                      ->orWhere('isi', 'like', '%'.$this->search.'%')
                      ->orWhereHas('kelas', function($q) {
                          $q->where('nama_kelas', 'like', '%'.$this->search.'%');
                      });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.guru.pengumuman-kelas', [
            'pengumumanList' => $pengumumanList,
            'kelasDiajar' => $kelasDiajar
        ]);
    }

    public function openModal()
    {
        $this->reset(['kelasId', 'judul', 'isi', 'isUrgent', 'tanggalBerlaku', 'editId']);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['kelasId', 'judul', 'isi', 'isUrgent', 'tanggalBerlaku', 'editId']);
    }

    public function edit($id)
    {
        $pengumuman = Pengumuman::where('id', $id)
                                ->where('user_id', auth()->id())
                                ->firstOrFail();

        $this->editId = $pengumuman->id;
        $this->kelasId = $pengumuman->kelas_id;
        $this->judul = $pengumuman->judul;
        $this->isi = $pengumuman->isi;
        $this->isUrgent = $pengumuman->is_urgent;
        $this->tanggalBerlaku = $pengumuman->tanggal_berlaku?->format('Y-m-d');
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'kelasId' => 'required|exists:kelas,id',
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tanggalBerlaku' => 'nullable|date|after_or_equal:today',
        ]);

        $data = [
            'kelas_id' => $this->kelasId,
            'user_id' => auth()->id(),
            'judul' => $this->judul,
            'isi' => $this->isi,
            'is_urgent' => $this->isUrgent,
            'target' => 'kelas',
            'tanggal_berlaku' => $this->tanggalBerlaku ? Carbon::parse($this->tanggalBerlaku) : null,
        ];

        if ($this->editId) {
            $pengumuman = Pengumuman::where('id', $this->editId)
                                    ->where('user_id', auth()->id())
                                    ->firstOrFail();
            $pengumuman->update($data);
            $message = 'Pengumuman berhasil diperbarui.';
        } else {
            Pengumuman::create($data);
            $message = 'Pengumuman berhasil ditambahkan.';
        }

        session()->flash('success', $message);
        $this->closeModal();
    }

    public function delete($id)
    {
        $pengumuman = Pengumuman::where('id', $id)
                                ->where('user_id', auth()->id())
                                ->firstOrFail();
        $pengumuman->delete();
        
        session()->flash('success', 'Pengumuman berhasil dihapus.');
    }

    public function toggleUrgent($id)
    {
        $pengumuman = Pengumuman::where('id', $id)
                                ->where('user_id', auth()->id())
                                ->firstOrFail();
        
        $pengumuman->update([
            'is_urgent' => !$pengumuman->is_urgent
        ]);

        session()->flash('success', 'Status urgensi berhasil diubah.');
    }
}