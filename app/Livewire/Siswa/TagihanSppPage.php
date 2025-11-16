<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\TagihanSpp;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app-new')]
class TagihanSppPage extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = 'semua';
    public $filterBulan = '';
    public $filterTahun = '';

    // Statistics properties
    public $totalTagihan = 0;
    public $totalLunas = 0;
    public $totalBelumBayar = 0;
    public $totalTertunggak = 0;
    public $totalNominalLunas = 0;
    public $totalNominalBelumBayar = 0;
    public $totalNominalTertunggak = 0;

    public function mount()
    {
        $this->filterTahun = now()->year;
        $this->calculateStatistics();
    }

    public function updated()
    {
        $this->calculateStatistics();
    }

    private function calculateStatistics()
    {
        $siswa = Auth::user()->siswa;
        
        if (!$siswa) {
            return;
        }

        $query = TagihanSpp::where('siswa_id', $siswa->id);

        // Apply filters for statistics
        if ($this->filterBulan) {
            $query->where('bulan', $this->filterBulan);
        }
        if ($this->filterTahun) {
            $query->where('tahun', $this->filterTahun);
        }

        $tagihan = $query->get();

        $this->totalTagihan = $tagihan->count();
        $this->totalLunas = $tagihan->where('status', 'lunas')->count();
        $this->totalBelumBayar = $tagihan->where('status', 'belum_bayar')->count();
        $this->totalTertunggak = $tagihan->where('status', 'tertunggak')->count();
        
        $this->totalNominalLunas = $tagihan->where('status', 'lunas')->sum('jumlah_tagihan') ?? 0;
        $this->totalNominalBelumBayar = $tagihan->where('status', 'belum_bayar')->sum('jumlah_tagihan') ?? 0;
        $this->totalNominalTertunggak = $tagihan->where('status', 'tertunggak')->sum('jumlah_tagihan') ?? 0;
    }

    public function render()
    {
        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            return view('livewire.siswa.tagihan-spp', [
                'tagihanList' => collect(),
                'totalTagihan' => 0,
                'totalLunas' => 0,
                'totalBelumBayar' => 0,
                'totalTertunggak' => 0,
                'totalNominalLunas' => 0,
                'totalNominalBelumBayar' => 0,
                'totalNominalTertunggak' => 0,
            ]);
        }

        $tagihanQuery = TagihanSpp::with(['biayaSpp'])
            ->where('siswa_id', $siswa->id)
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('bulan', 'like', '%'.$this->search.'%')
                      ->orWhere('keterangan', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->filterStatus !== 'semua', function($query) {
                $query->where('status', $this->filterStatus);
            })
            ->when($this->filterBulan, function($query) {
                $query->where('bulan', $this->filterBulan);
            })
            ->when($this->filterTahun, function($query) {
                $query->where('tahun', $this->filterTahun);
            })
            ->orderBy('tahun', 'desc')
            ->orderByRaw("FIELD(bulan, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
            ->orderBy('created_at', 'desc');

        $tagihanList = $tagihanQuery->paginate(10);

        return view('livewire.siswa.tagihan-spp', [
            'tagihanList' => $tagihanList,
            'totalTagihan' => $this->totalTagihan,
            'totalLunas' => $this->totalLunas,
            'totalBelumBayar' => $this->totalBelumBayar,
            'totalTertunggak' => $this->totalTertunggak,
            'totalNominalLunas' => $this->totalNominalLunas,
            'totalNominalBelumBayar' => $this->totalNominalBelumBayar,
            'totalNominalTertunggak' => $this->totalNominalTertunggak,
        ]);
    }

    public function bayarTagihan($tagihanId)
    {
        $tagihan = TagihanSpp::find($tagihanId);
        
        if (!$tagihan) {
            session()->flash('error', 'Tagihan tidak ditemukan.');
            return;
        }

        if ($tagihan->status === 'lunas') {
            session()->flash('info', 'Tagihan sudah lunas.');
            return;
        }

        // Simulasi pembayaran
        $tagihan->update([
            'status' => 'lunas',
            'tanggal_bayar' => now(),
            'metode_pembayaran' => 'transfer',
            'keterangan' => 'Pembayaran via sistem'
        ]);

        // Recalculate statistics
        $this->calculateStatistics();

        session()->flash('success', 'Pembayaran berhasil dilakukan. Menunggu verifikasi admin.');
    }

    public function lihatDetail($tagihanId)
    {
        $tagihan = TagihanSpp::with(['biayaSpp', 'verifier'])->find($tagihanId);
        
        if ($tagihan) {
            $this->dispatch('show-tagihan-detail', tagihan: [
                'id' => $tagihan->id,
                'bulan' => $tagihan->bulan,
                'tahun' => $tagihan->tahun,
                'jumlah_tagihan' => $tagihan->jumlah_tagihan,
                'jumlah_tagihan_formatted' => $tagihan->jumlah_tagihan_formatted,
                'denda' => $tagihan->denda,
                'denda_formatted' => $tagihan->denda_formatted,
                'total_pembayaran' => $tagihan->total_pembayaran,
                'total_pembayaran_formatted' => $tagihan->total_pembayaran_formatted,
                'status' => $tagihan->status,
                'status_lengkap' => $tagihan->status_lengkap,
                'tanggal_jatuh_tempo' => $tagihan->tanggal_jatuh_tempo,
                'keterangan' => $tagihan->keterangan,
                'metode_pembayaran' => $tagihan->metode_pembayaran,
            ]);
        } else {
            session()->flash('error', 'Tagihan tidak ditemukan.');
        }
    }
}