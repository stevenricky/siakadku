<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Mapel;
use App\Models\Rpp;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app-new')]
class RppModul extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $mapelId;
    public $judul;
    public $kompetensiDasar;
    public $tujuanPembelajaran;
    public $materi;
    public $metode;
    public $media;
    public $langkahKegiatan;
    public $penilaian;
    public $showModal = false;
    public $modalTitle = 'Tambah RPP';
    public $rppId;

    protected $queryString = ['search', 'perPage'];

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function render()
    {
        // Dapatkan ID guru dari user yang login
        $guruId = Auth::user()->guru->id;
        
        // Query RPP dengan eager loading
        $rppList = Rpp::with(['mapel'])
            ->where('guru_id', $guruId)
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('judul', 'like', '%'.$this->search.'%')
                      ->orWhereHas('mapel', function($q2) {
                          $q2->where('nama_mapel', 'like', '%'.$this->search.'%');
                      });
                });
            })
            ->latest()
            ->paginate($this->perPage);

        // Dapatkan mapel yang diampu oleh guru ini
        $mapels = Mapel::whereHas('guru', function($query) use ($guruId) {
            $query->where('guru_id', $guruId);
        })->get();

        return view('livewire.guru.rpp-modul', [
            'rppList' => $rppList,
            'mapels' => $mapels
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->modalTitle = 'Tambah RPP';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $guruId = Auth::user()->guru->id;
        
        $rpp = Rpp::with('mapel')
                  ->where('id', $id)
                  ->where('guru_id', $guruId)
                  ->firstOrFail();

        $this->rppId = $id;
        $this->mapelId = $rpp->mapel_id;
        $this->judul = $rpp->judul;
        $this->kompetensiDasar = $rpp->kompetensi_dasar;
        $this->tujuanPembelajaran = $rpp->tujuan_pembelajaran;
        $this->materi = $rpp->materi;
        $this->metode = $rpp->metode;
        $this->media = $rpp->media;
        $this->langkahKegiatan = $rpp->langkah_kegiatan;
        $this->penilaian = $rpp->penilaian;
        $this->modalTitle = 'Edit RPP';
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'mapelId' => 'required|exists:mapels,id',
            'judul' => 'required|string|max:255',
            'kompetensiDasar' => 'required|string',
            'tujuanPembelajaran' => 'required|string',
            'materi' => 'required|string',
        ]);

        $guruId = Auth::user()->guru->id;

        // Validasi tambahan: pastikan guru mengampu mapel yang dipilih
        $mengampuMapel = Auth::user()->guru->mengampuMapel($this->mapelId);
        if (!$mengampuMapel) {
            session()->flash('error', 'Anda tidak mengampu mata pelajaran ini.');
            return;
        }

        $data = [
            'mapel_id' => $this->mapelId,
            'guru_id' => $guruId,
            'judul' => $this->judul,
            'kompetensi_dasar' => $this->kompetensiDasar,
            'tujuan_pembelajaran' => $this->tujuanPembelajaran,
            'materi' => $this->materi,
            'metode' => $this->metode,
            'media' => $this->media,
            'langkah_kegiatan' => $this->langkahKegiatan,
            'penilaian' => $this->penilaian,
        ];

        try {
            if (isset($this->rppId)) {
                $rpp = Rpp::where('id', $this->rppId)
                          ->where('guru_id', $guruId)
                          ->firstOrFail();
                $rpp->update($data);
                session()->flash('success', 'RPP berhasil diupdate.');
            } else {
                Rpp::create($data);
                session()->flash('success', 'RPP berhasil dibuat.');
            }

            $this->resetForm();
            $this->showModal = false;
            $this->dispatch('rpp-saved'); // Untuk realtime update jika diperlukan
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $guruId = Auth::user()->guru->id;
            $rpp = Rpp::where('id', $id)
                      ->where('guru_id', $guruId)
                      ->firstOrFail();
            $rpp->delete();
            session()->flash('success', 'RPP berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->reset([
            'rppId',
            'mapelId',
            'judul',
            'kompetensiDasar',
            'tujuanPembelajaran',
            'materi',
            'metode',
            'media',
            'langkahKegiatan',
            'penilaian'
        ]);
    }

    // Untuk integrasi realtime
    protected $listeners = ['refreshRpp' => '$refresh'];

    public function refreshData()
    {
        $this->dispatch('$refresh');
    }
}