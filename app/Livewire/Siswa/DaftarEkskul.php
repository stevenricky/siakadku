<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Ekstrakurikuler;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app-new')]
class DaftarEkskul extends Component
{
    use WithPagination;

    public $search = '';
    public $filterHari = '';
    public $perPage = 9;
    public $showDetailModal = false;
    public $selectedEkskul = null;
    public $showDaftarModal = false;
    public $ekskulToRegister = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterHari' => ['except' => ''],
        'perPage' => ['except' => 9]
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterHari()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function showDetail($ekskulId)
    {
        $this->selectedEkskul = Ekstrakurikuler::with(['guruPembina'])->find($ekskulId);
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedEkskul = null;
    }

    public function showDaftarForm($ekskulId)
    {
        $this->ekskulToRegister = Ekstrakurikuler::find($ekskulId);
        
        if (!$this->ekskulToRegister) {
            session()->flash('error', 'Ekstrakurikuler tidak ditemukan!');
            return;
        }
        
        // Cek apakah siswa sudah terdaftar
        if ($this->ekskulToRegister->isTerdaftar(Auth::id())) {
            session()->flash('error', 'Anda sudah terdaftar di ekstrakurikuler ini!');
            return;
        }
        
        // Cek kuota
        if (!$this->ekskulToRegister->bisa_daftar) {
            session()->flash('error', 'Kuota ekstrakurikuler ini sudah penuh!');
            return;
        }
        
        $this->showDaftarModal = true;
    }

    public function closeDaftarForm()
    {
        $this->showDaftarModal = false;
        $this->ekskulToRegister = null;
    }

    public function daftarEkskul()
    {
        try {
            $siswa = Siswa::where('user_id', Auth::id())->first();
            
            if (!$siswa) {
                session()->flash('error', 'Data siswa tidak ditemukan!');
                return;
            }

            if (!$this->ekskulToRegister) {
                session()->flash('error', 'Data ekstrakurikuler tidak valid!');
                return;
            }

            // Attach siswa ke ekstrakurikuler
            $this->ekskulToRegister->siswa()->attach($siswa->id, [
                'status' => 'pending',
                'tanggal_daftar' => now()
            ]);

            // Update jumlah peserta
            $this->ekskulToRegister->increment('jumlah_peserta');

            session()->flash('success', 'Pendaftaran berhasil! Menunggu persetujuan pembina.');
            $this->closeDaftarForm();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function batalkanPendaftaran($ekskulId)
    {
        try {
            $siswa = Siswa::where('user_id', Auth::id())->first();
            $ekskul = Ekstrakurikuler::find($ekskulId);
            
            if ($ekskul && $siswa) {
                // Detach siswa dari ekstrakurikuler
                $ekskul->siswa()->detach($siswa->id);
                
                // Update jumlah peserta
                if ($ekskul->jumlah_peserta > 0) {
                    $ekskul->decrement('jumlah_peserta');
                }
                
                session()->flash('success', 'Pendaftaran berhasil dibatalkan!');
            }
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Ekstrakurikuler::with(['guruPembina'])
            ->aktif();

        // Filter pencarian
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_ekstra', 'like', '%'.$this->search.'%')
                  ->orWhere('pembina', 'like', '%'.$this->search.'%')
                  ->orWhere('tempat', 'like', '%'.$this->search.'%');
            });
        }

        // Filter hari
        if ($this->filterHari) {
            $query->where('hari', $this->filterHari);
        }

        $ekskul = $query->paginate($this->perPage);
        
        // Ambil data siswa untuk cek pendaftaran
        $siswa = Siswa::where('user_id', Auth::id())->first();
        $ekskulTerdaftar = [];
        
        if ($siswa) {
            $ekskulTerdaftar = $siswa->ekstrakurikuler()->pluck('ekstrakurikulers.id')->toArray();
        }

        $hariOptions = [
            'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];

        return view('livewire.siswa.daftar-ekskul', [
            'ekskul' => $ekskul,
            'hariOptions' => $hariOptions,
            'ekskulTerdaftar' => $ekskulTerdaftar,
            'siswa' => $siswa
        ]);
    }
}