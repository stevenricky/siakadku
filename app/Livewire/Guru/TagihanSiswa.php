<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TagihanSpp;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\BiayaSpp;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class TagihanSiswa extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $kelasFilter;
    public $bulanFilter;
    public $tahunFilter;
    public $statusFilter;

    public $kelasList;
    public $bulanList;
    public $tahunList;
    
    // Properties for modal
    public $showDetailModal = false;
    public $selectedTagihan = null;

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
        $this->kelasList = Kelas::all();
        $this->bulanList = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $this->tahunList = range(date('Y') - 1, date('Y') + 1);
        $this->bulanFilter = date('n'); // Bulan saat ini
        $this->tahunFilter = date('Y'); // Tahun saat ini
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function render()
    {
        $guru = auth()->user()->guru;
        
        // Jika guru adalah wali kelas, filter berdasarkan kelasnya
        $kelasWali = $guru->kelasWali;

        $tagihanQuery = TagihanSpp::with(['siswa.kelas', 'biayaSpp'])
            ->when($kelasWali, function($query) use ($kelasWali) {
                $query->whereHas('siswa', function($q) use ($kelasWali) {
                    $q->where('kelas_id', $kelasWali->id);
                });
            })
            ->when($this->kelasFilter, function($query) {
                $query->whereHas('siswa', function($q) {
                    $q->where('kelas_id', $this->kelasFilter);
                });
            })
            ->when($this->bulanFilter, function($query) {
                // Pastikan bulanFilter valid
                if ($this->bulanFilter >= 1 && $this->bulanFilter <= 12) {
                    $query->where('bulan', $this->bulanList[$this->bulanFilter - 1]);
                }
            })
            ->when($this->tahunFilter, function($query) {
                $query->where('tahun', $this->tahunFilter);
            })
            ->when($this->statusFilter, function($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->search, function($query) {
                $query->whereHas('siswa', function($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                      ->orWhere('nis', 'like', '%' . $this->search . '%');
                });
            });

        $tagihanList = $tagihanQuery->latest()->paginate($this->perPage);

        // Hitung statistik dengan query terpisah untuk menghindari masalah
        $totalTagihan = $tagihanQuery->count();
        $lunasCount = $tagihanQuery->clone()->where('status', 'lunas')->count();
        $belumBayarCount = $tagihanQuery->clone()->where('status', 'belum_bayar')->count();
        $tertunggakCount = $tagihanQuery->clone()->where('status', 'tertunggak')->count();

        return view('livewire.guru.tagihan-siswa', [
            'tagihanList' => $tagihanList,
            'totalTagihan' => $totalTagihan,
            'lunasCount' => $lunasCount,
            'belumBayarCount' => $belumBayarCount,
            'tertunggakCount' => $tertunggakCount,
            'guru' => $guru,
            'kelasWali' => $kelasWali
        ]);
    }

    public function exportLaporan()
    {
        // Logic untuk export laporan
        session()->flash('success', 'Laporan berhasil diexport.');
    }

    public function ingatkanSiswa($tagihanId)
    {
        $tagihan = TagihanSpp::findOrFail($tagihanId);
        
        // Logic untuk mengingatkan siswa (bisa melalui notifikasi, dll)
        session()->flash('success', 'Pengingat telah dikirim ke ' . ($tagihan->siswa->nama_lengkap ?? 'siswa'));
    }

    public function lihatDetail($tagihanId)
    {
        $this->selectedTagihan = TagihanSpp::with(['siswa', 'biayaSpp', 'pembayaran'])->findOrFail($tagihanId);
        $this->showDetailModal = true;
    }
    
    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedTagihan = null;
    }
}