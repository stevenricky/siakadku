<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Materi;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class MateriPembelajaran extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $filterMapel = '';

    public function render()
    {
        $siswa = auth()->user()->siswa;
        
        $materiList = Materi::with(['mapel', 'guru'])
            ->whereHas('mapel.jadwal', function($query) use ($siswa) {
                $query->where('kelas_id', $siswa->kelas_id);
            })
            ->when($this->search, function($query) {
                $query->where('judul', 'like', '%'.$this->search.'%')
                      ->orWhereHas('mapel', function($q) {
                          $q->where('nama_mapel', 'like', '%'.$this->search.'%');
                      });
            })
            ->when($this->filterMapel, function($query) {
                $query->where('mapel_id', $this->filterMapel);
            })
            ->latest()
            ->paginate($this->perPage);

        // Perbaiki query untuk mapels - gunakan relasi melalui jadwal
        $mapels = \App\Models\Mapel::whereHas('jadwal', function($query) use ($siswa) {
            $query->where('kelas_id', $siswa->kelas_id);
        })->get();

        return view('livewire.siswa.materi-pembelajaran', [
            'materiList' => $materiList,
            'mapels' => $mapels
        ]);
    }

    public function downloadMateri($materiId)
    {
        $materi = Materi::findOrFail($materiId);
        
        if ($materi->file) {
            return response()->download(
                storage_path('app/public/materi/' . $materi->file),
                $materi->judul . '.' . pathinfo($materi->file, PATHINFO_EXTENSION)
            );
        }
        
        session()->flash('error', 'File materi tidak ditemukan.');
    }

    public function bukaLink($materiId)
    {
        $materi = Materi::findOrFail($materiId);
        if ($materi->link) {
            return redirect()->away($materi->link);
        }
        session()->flash('error', 'Tidak ada link yang tersedia.');
    }

    public function previewMateri($materiId)
    {
        $materi = Materi::findOrFail($materiId);
        
        if ($materi->file && in_array($materi->file_type, ['pdf', 'image'])) {
            return redirect($materi->file_url);
        }
        
        session()->flash('error', 'Preview tidak tersedia untuk file ini.');
    }
}