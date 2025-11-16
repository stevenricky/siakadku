<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Konseling;
use App\Models\LayananBk;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app-new')]
class JadwalKonseling extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterTanggal = '';
    public $filterLayanan = '';
    public $perPage = 10;
    public $showDetailModal = false;
    public $showCreateModal = false;
    public $selectedKonseling = null;

    // Form properties
    public $form = [
        'layanan_bk_id' => '',
        'guru_id' => '',
        'tanggal_konseling' => '',
        'waktu_mulai' => '',
        'waktu_selesai' => '',
        'tempat' => '',
        'permasalahan' => ''
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterTanggal' => ['except' => ''],
        'filterLayanan' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function mount()
    {
        // Set default values
        $this->form['tanggal_konseling'] = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterTanggal()
    {
        $this->resetPage();
    }

    public function updatingFilterLayanan()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function getSiswaId()
    {
        return Auth::user()->siswa->id ?? null;
    }

    public function showDetail($konselingId)
    {
        $this->selectedKonseling = Konseling::with(['siswa', 'guruBk', 'layananBk'])
            ->where('id', $konselingId)
            ->where('siswa_id', $this->getSiswaId())
            ->first();

        if ($this->selectedKonseling) {
            $this->showDetailModal = true;
        }
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedKonseling = null;
    }

    public function openCreateModal()
    {
        $this->showCreateModal = true;
        $this->resetForm();
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->form = [
            'layanan_bk_id' => '',
            'guru_id' => '',
            'tanggal_konseling' => now()->format('Y-m-d'),
            'waktu_mulai' => '',
            'waktu_selesai' => '',
            'tempat' => '',
            'permasalahan' => ''
        ];
    }

    public function buatJanji()
    {
        $this->validate([
            'form.layanan_bk_id' => 'required|exists:layanan_bk,id',
            'form.guru_id' => 'required|exists:gurus,id',
            'form.tanggal_konseling' => 'required|date|after_or_equal:today',
            'form.waktu_mulai' => 'required',
            'form.waktu_selesai' => 'required|after:form.waktu_mulai',
            'form.tempat' => 'required|string|max:255',
            'form.permasalahan' => 'required|string|min:10',
        ]);

        try {
            Konseling::create([
                'siswa_id' => $this->getSiswaId(),
                'guru_id' => $this->form['guru_id'],
                'layanan_bk_id' => $this->form['layanan_bk_id'],
                'tanggal_konseling' => $this->form['tanggal_konseling'],
                'waktu_mulai' => $this->form['waktu_mulai'],
                'waktu_selesai' => $this->form['waktu_selesai'],
                'tempat' => $this->form['tempat'],
                'permasalahan' => $this->form['permasalahan'],
                'status' => 'terjadwal',
            ]);

            session()->flash('success', 'Janji konseling berhasil dibuat!');
            $this->closeCreateModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat janji konseling: ' . $e->getMessage());
        }
    }

    public function batalkanKonseling($konselingId)
    {
        $konseling = Konseling::where('id', $konselingId)
            ->where('siswa_id', $this->getSiswaId())
            ->where('status', 'terjadwal')
            ->first();

        if ($konseling) {
            $konseling->update([
                'status' => 'dibatalkan',
                'catatan' => 'Dibatalkan oleh siswa'
            ]);

            session()->flash('success', 'Konseling berhasil dibatalkan!');
        } else {
            session()->flash('error', 'Tidak dapat membatalkan konseling ini.');
        }
    }

    public function render()
    {
        $siswaId = $this->getSiswaId();
        
        $query = Konseling::with(['guruBk', 'layananBk'])
            ->where('siswa_id', $siswaId);

        // Filter pencarian
        if ($this->search) {
            $query->where(function($q) {
                $q->where('permasalahan', 'like', '%'.$this->search.'%')
                  ->orWhere('tempat', 'like', '%'.$this->search.'%')
                  ->orWhereHas('guruBk', function($q2) {
                      $q2->where('nama_lengkap', 'like', '%'.$this->search.'%');
                  })
                  ->orWhereHas('layananBk', function($q2) {
                      $q2->where('nama_layanan', 'like', '%'.$this->search.'%');
                  });
            });
        }

        // Filter status
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        // Filter tanggal
        if ($this->filterTanggal) {
            if ($this->filterTanggal === 'hari_ini') {
                $query->whereDate('tanggal_konseling', today());
            } elseif ($this->filterTanggal === 'minggu_ini') {
                $query->whereBetween('tanggal_konseling', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($this->filterTanggal === 'bulan_ini') {
                $query->whereMonth('tanggal_konseling', now()->month)
                      ->whereYear('tanggal_konseling', now()->year);
            } elseif ($this->filterTanggal === 'akan_datang') {
                $query->where('tanggal_konseling', '>=', today())
                      ->where('status', 'terjadwal');
            } elseif ($this->filterTanggal === 'selesai') {
                $query->where('status', 'selesai');
            }
        }

        // Filter layanan
        if ($this->filterLayanan) {
            $query->where('layanan_bk_id', $this->filterLayanan);
        }

        // Order by tanggal terbaru
        $query->orderBy('tanggal_konseling', 'desc')
              ->orderBy('waktu_mulai', 'desc');

        $konseling = $query->paginate($this->perPage);

        // Data untuk filter dan form
        $layananOptions = LayananBk::where('status', true)
            ->orderBy('nama_layanan')
            ->get()
            ->mapWithKeys(function ($layanan) {
                return [$layanan->id => $layanan->nama_layanan];
            });

        // PERBAIKAN: Gunakan nama_lengkap bukan nama
        $guruOptions = Guru::where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get()
            ->mapWithKeys(function ($guru) {
                return [$guru->id => $guru->nama_lengkap];
            });

        $statusOptions = [
            'terjadwal' => 'Terjadwal',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan'
        ];

        $tanggalOptions = [
            '' => 'Semua Tanggal',
            'hari_ini' => 'Hari Ini',
            'minggu_ini' => 'Minggu Ini',
            'bulan_ini' => 'Bulan Ini',
            'akan_datang' => 'Akan Datang',
            'selesai' => 'Selesai'
        ];

        // Stats untuk dashboard
        $totalKonseling = Konseling::where('siswa_id', $siswaId)->count();
        $konselingHariIni = Konseling::where('siswa_id', $siswaId)
            ->whereDate('tanggal_konseling', today())
            ->count();
        $konselingAkanDatang = Konseling::where('siswa_id', $siswaId)
            ->where('tanggal_konseling', '>=', today())
            ->where('status', 'terjadwal')
            ->count();
        $konselingSelesai = Konseling::where('siswa_id', $siswaId)
            ->where('status', 'selesai')
            ->count();

        return view('livewire.siswa.jadwal-konseling', [
            'konseling' => $konseling,
            'layananOptions' => $layananOptions,
            'guruOptions' => $guruOptions,
            'statusOptions' => $statusOptions,
            'tanggalOptions' => $tanggalOptions,
            'totalKonseling' => $totalKonseling,
            'konselingHariIni' => $konselingHariIni,
            'konselingAkanDatang' => $konselingAkanDatang,
            'konselingSelesai' => $konselingSelesai,
        ]);
    }
}