<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Konseling;
use App\Models\Siswa;
use App\Models\LayananBk;
use App\Models\Kelas;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class KonselingSiswa extends Component
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
    public $layananBkId = '';
    public $tanggalKonseling = '';
    public $waktuMulai = '';
    public $waktuSelesai = '';
    public $tempat = '';
    public $permasalahan = '';
    public $catatan = '';

    // Properties untuk modal detail
    public $showDetailModal = false;
    public $selectedKonseling = null;

    // Properties untuk modal edit
    public $showEditModal = false;
    public $tindakan = '';
    public $hasil = '';

    // Data lists
    public $siswaList = [];
    public $layananBkList = [];

    protected $queryString = ['search', 'statusFilter', 'tanggalFilter', 'perPage'];

    public function mount()
    {
        $this->tanggalKonseling = now()->format('Y-m-d');
        $this->waktuMulai = '08:00';
        $this->waktuSelesai = '09:00';
        $this->tempat = 'Ruang BK';
        
        // Load data siswa dan layanan BK
        $this->loadSiswaList();
        $this->loadLayananBkList();
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

    public function loadLayananBkList()
    {
        $this->layananBkList = LayananBk::where('status', true)->get();
    }

    public function render()
    {
        $guru = auth()->user()->guru;
        
        $query = Konseling::with(['siswa.kelas', 'guruBk', 'layananBk'])
            ->when($this->search, function($q) {
                $q->where(function($query) {
                    $query->whereHas('siswa', function($q) {
                        $q->where('nama_lengkap', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('tempat', 'like', '%' . $this->search . '%')
                    ->orWhere('permasalahan', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->tanggalFilter, function($q) {
                $q->whereDate('tanggal_konseling', $this->tanggalFilter);
            });

        // Jika guru adalah wali kelas, filter berdasarkan siswa di kelasnya
        if ($guru->kelasWali) {
            $query->whereHas('siswa', function($q) use ($guru) {
                $q->where('kelas_id', $guru->kelasWali->id);
            });
        }

        $konseling = $query->latest()->paginate($this->perPage);

        // Hitung statistik
        $statsQuery = Konseling::query();
        
        if ($guru->kelasWali) {
            $statsQuery->whereHas('siswa', function($q) use ($guru) {
                $q->where('kelas_id', $guru->kelasWali->id);
            });
        }

        $totalTerjadwal = (clone $statsQuery)->where('status', 'terjadwal')->count();
        $totalSelesai = (clone $statsQuery)->where('status', 'selesai')->count();
        $totalDibatalkan = (clone $statsQuery)->where('status', 'dibatalkan')->count();

        return view('livewire.guru.konseling-siswa', [
            'konseling' => $konseling,
            'totalTerjadwal' => $totalTerjadwal,
            'totalSelesai' => $totalSelesai,
            'totalDibatalkan' => $totalDibatalkan,
            'guru' => $guru,
        ]);
    }

    public function openTambahModal()
    {
        $this->reset(['siswaId', 'layananBkId', 'permasalahan', 'catatan']);
        $this->tanggalKonseling = now()->format('Y-m-d');
        $this->waktuMulai = '08:00';
        $this->waktuSelesai = '09:00';
        $this->tempat = 'Ruang BK';
        $this->showTambahModal = true;
    }

    public function closeTambahModal()
    {
        $this->showTambahModal = false;
        $this->resetValidation();
    }

    public function simpanKonseling()
    {
        $this->validate([
            'siswaId' => 'required|exists:siswas,id',
            'layananBkId' => 'required|exists:layanan_bk,id',
            'tanggalKonseling' => 'required|date',
            'waktuMulai' => 'required',
            'waktuSelesai' => 'required',
            'tempat' => 'required|string|max:100',
            'permasalahan' => 'required|string|min:10',
        ]);

        try {
            $guru = auth()->user()->guru;

            Konseling::create([
                'siswa_id' => $this->siswaId,
                'guru_id' => $guru->id,
                'layanan_bk_id' => $this->layananBkId,
                'tanggal_konseling' => $this->tanggalKonseling,
                'waktu_mulai' => $this->waktuMulai,
                'waktu_selesai' => $this->waktuSelesai,
                'tempat' => $this->tempat,
                'permasalahan' => $this->permasalahan,
                'status' => 'terjadwal',
                'catatan' => $this->catatan,
            ]);

            session()->flash('success', 'Jadwal konseling berhasil dibuat.');
            $this->closeTambahModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showDetail($id)
    {
        $this->selectedKonseling = Konseling::with(['siswa.kelas', 'guruBk', 'layananBk'])
            ->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedKonseling = null;
    }

    public function openEditModal($id)
    {
        $this->selectedKonseling = Konseling::with(['siswa', 'layananBk'])
            ->findOrFail($id);
        
        $this->tindakan = $this->selectedKonseling->tindakan ?? '';
        $this->hasil = $this->selectedKonseling->hasil ?? '';
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->selectedKonseling = null;
        $this->reset(['tindakan', 'hasil']);
        $this->resetValidation();
    }

    public function updateKonseling()
    {
        $this->validate([
            'tindakan' => 'required|string|min:10',
            'hasil' => 'required|string|min:10',
        ]);

        try {
            $this->selectedKonseling->update([
                'tindakan' => $this->tindakan,
                'hasil' => $this->hasil,
                'status' => 'selesai',
            ]);

            session()->flash('success', 'Hasil konseling berhasil disimpan.');
            $this->closeEditModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function batalkanKonseling($id)
    {
        try {
            $konseling = Konseling::findOrFail($id);
            $konseling->update([
                'status' => 'dibatalkan',
                'catatan' => 'Dibatalkan oleh guru pada ' . now()->format('d/m/Y H:i'),
            ]);

            session()->flash('success', 'Konseling berhasil dibatalkan.');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'tanggalFilter']);
        $this->resetPage();
    }

    public function updated($property)
    {
        // Reset halaman ketika filter berubah
        if (in_array($property, ['search', 'statusFilter', 'tanggalFilter', 'perPage'])) {
            $this->resetPage();
        }
    }
}