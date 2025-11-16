<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\PembayaranSpp;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app-new')]
class RiwayatPembayaran extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $filterStatus = 'semua';
    public $filterBulan = '';
    public $filterTahun = '';

    // Upload bukti properties
    public $showUploadModal = false;
    public $pembayaranIdForUpload;
    public $buktiUpload;
    public $catatanUpload;

    public function mount()
    {
        $this->filterTahun = now()->year;
    }

    public function render()
    {
        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            return view('livewire.siswa.riwayat-pembayaran', [
                'pembayaranList' => collect(),
                'totalPembayaran' => 0,
                'totalDiterima' => 0,
                'totalPending' => 0,
                'totalDitolak' => 0,
                'totalNominalDiterima' => 0,
            ]);
        }

        $pembayaranQuery = PembayaranSpp::with(['tagihanSpp', 'verifikator'])
            ->where('siswa_id', $siswa->id)
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->whereHas('tagihanSpp', function($subQuery) {
                        $subQuery->where('bulan', 'like', '%'.$this->search.'%');
                    })
                    ->orWhere('catatan', 'like', '%'.$this->search.'%')
                    ->orWhere('kode_referensi', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->filterStatus !== 'semua', function($query) {
                $query->where('status_verifikasi', $this->filterStatus);
            })
            ->when($this->filterBulan, function($query) {
                $query->whereMonth('tanggal_bayar', $this->filterBulan);
            })
            ->when($this->filterTahun, function($query) {
                $query->whereYear('tanggal_bayar', $this->filterTahun);
            })
            ->orderBy('tanggal_bayar', 'desc')
            ->orderBy('created_at', 'desc');

        $pembayaranList = $pembayaranQuery->paginate(10);

        // Statistics
        $totalPembayaran = PembayaranSpp::where('siswa_id', $siswa->id)->count();
        $totalDiterima = PembayaranSpp::where('siswa_id', $siswa->id)->where('status_verifikasi', PembayaranSpp::STATUS_DITERIMA)->count();
        $totalPending = PembayaranSpp::where('siswa_id', $siswa->id)->where('status_verifikasi', PembayaranSpp::STATUS_PENDING)->count();
        $totalDitolak = PembayaranSpp::where('siswa_id', $siswa->id)->where('status_verifikasi', PembayaranSpp::STATUS_DITOLAK)->count();

        // Total nominal dengan handling null
        $totalNominalDiterima = PembayaranSpp::where('siswa_id', $siswa->id)
            ->where('status_verifikasi', PembayaranSpp::STATUS_DITERIMA)
            ->sum('jumlah_bayar') ?? 0;

        return view('livewire.siswa.riwayat-pembayaran', [
            'pembayaranList' => $pembayaranList,
            'totalPembayaran' => $totalPembayaran,
            'totalDiterima' => $totalDiterima,
            'totalPending' => $totalPending,
            'totalDitolak' => $totalDitolak,
            'totalNominalDiterima' => $totalNominalDiterima,
        ]);
    }

    public function openUploadModal($pembayaranId)
    {
        $this->pembayaranIdForUpload = $pembayaranId;
        $this->showUploadModal = true;
        $this->reset(['buktiUpload', 'catatanUpload']);
    }

    public function closeUploadModal()
    {
        $this->showUploadModal = false;
        $this->reset(['pembayaranIdForUpload', 'buktiUpload', 'catatanUpload']);
    }

    public function uploadBukti()
    {
        $this->validate([
            'buktiUpload' => 'required|image|max:2048', // Max 2MB
            'catatanUpload' => 'nullable|string|max:500'
        ]);

        try {
            $pembayaran = PembayaranSpp::where('id', $this->pembayaranIdForUpload)
                ->where('siswa_id', Auth::user()->siswa->id)
                ->firstOrFail();

            // Cek apakah pembayaran masih pending
            if ($pembayaran->status_verifikasi !== PembayaranSpp::STATUS_PENDING) {
                session()->flash('error', 'Pembayaran sudah diverifikasi, tidak dapat upload bukti.');
                return;
            }

            // Simpan file bukti
            $filename = 'bukti_' . time() . '_' . $pembayaran->id . '.' . $this->buktiUpload->getClientOriginalExtension();
            $path = $this->buktiUpload->storeAs('bukti-upload', $filename, 'public');

            // Update pembayaran dengan bukti upload
            $pembayaran->update([
                'bukti_upload' => $filename,
                'tanggal_upload' => now(),
                'catatan' => $this->catatanUpload ?: 'Bukti pembayaran diupload oleh siswa'
            ]);

            session()->flash('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
            $this->closeUploadModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function lihatDetail($pembayaranId)
    {
        $pembayaran = PembayaranSpp::with(['tagihanSpp', 'verifikator'])->find($pembayaranId);
        
        if ($pembayaran) {
            $this->dispatch('show-pembayaran-detail', pembayaran: [
                'id' => $pembayaran->id,
                'kode_referensi' => $pembayaran->kode_referensi,
                'jumlah_bayar' => $pembayaran->jumlah_bayar,
                'jumlah_bayar_formatted' => $pembayaran->jumlah_bayar_formatted,
                'biaya_admin' => $pembayaran->biaya_admin,
                'biaya_admin_formatted' => $pembayaran->biaya_admin_formatted,
                'total_bayar' => $pembayaran->total_bayar,
                'total_bayar_formatted' => $pembayaran->total_bayar_formatted,
                'tanggal_bayar' => $pembayaran->tanggal_bayar,
                'tanggal_bayar_formatted' => $pembayaran->tanggal_bayar_formatted,
                'metode_bayar' => $pembayaran->metode_bayar,
                'metode_bayar_text' => $pembayaran->metode_bayar_text,
                'status_verifikasi' => $pembayaran->status_verifikasi,
                'status_text' => $pembayaran->status_text,
                'catatan' => $pembayaran->catatan,
                'bukti_upload_url' => $pembayaran->bukti_upload_url,
                'bukti_bayar_url' => $pembayaran->bukti_bayar_url,
                'verified_at' => $pembayaran->verified_at,
                'nama_verifikator' => $pembayaran->nama_verifikator,
                'tagihan_spp' => $pembayaran->tagihanSpp ? [
                    'bulan' => $pembayaran->tagihanSpp->bulan,
                    'tahun' => $pembayaran->tagihanSpp->tahun,
                    'jumlah_tagihan_formatted' => $pembayaran->tagihanSpp->jumlah_tagihan_formatted,
                    'denda_formatted' => $pembayaran->tagihanSpp->denda_formatted,
                ] : null
            ]);
        } else {
            session()->flash('error', 'Pembayaran tidak ditemukan.');
        }
    }

    public function downloadBukti($pembayaranId)
    {
        $pembayaran = PembayaranSpp::where('id', $pembayaranId)
            ->where('siswa_id', Auth::user()->siswa->id)
            ->firstOrFail();

        if (!$pembayaran->bukti_upload) {
            session()->flash('error', 'Tidak ada bukti pembayaran.');
            return;
        }

        $path = storage_path('app/public/bukti-upload/' . $pembayaran->bukti_upload);
        
        if (!file_exists($path)) {
            session()->flash('error', 'File bukti tidak ditemukan.');
            return;
        }

        return response()->download($path, 'bukti-pembayaran-' . $pembayaran->kode_referensi . '.' . pathinfo($path, PATHINFO_EXTENSION));
    }
}