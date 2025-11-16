<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\PembayaranSpp;
use App\Models\TagihanSpp;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app-new')]
class VerifikasiPembayaran extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;
    public $statusFilter = '';
    public $showDetailModal = false;
    public $selectedPembayaran = null;
    public $catatanVerifikasi = '';

    // Properties untuk upload bukti
    public $showUploadModal = false;
    public $tagihanIdForUpload;
    public $buktiUpload;
    public $jumlahBayar;
    public $tanggalBayar;
    public $metodeBayar = 'transfer';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'statusFilter' => ['except' => '']
    ];

    public function mount()
    {
        $this->perPage = session()->get('verifikasiPerPage', 10);
        $this->tanggalBayar = now()->format('Y-m-d');
    }

    public function updatedPerPage($value)
    {
        session()->put('verifikasiPerPage', $value);
        $this->resetPage();
    }

    public function openDetail($pembayaranId)
    {
        $this->selectedPembayaran = PembayaranSpp::with([
            'tagihanSpp.siswa.kelas', 
            'tagihanSpp.biayaSpp',
            'verifikator'
        ])->findOrFail($pembayaranId);
        
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedPembayaran = null;
        $this->catatanVerifikasi = '';
    }

    public function openUploadModal($tagihanId)
    {
        $this->tagihanIdForUpload = $tagihanId;
        $tagihan = TagihanSpp::with(['biayaSpp'])->findOrFail($tagihanId);
        $this->jumlahBayar = $tagihan->total_pembayaran;
        $this->showUploadModal = true;
    }

    public function closeUploadModal()
    {
        $this->showUploadModal = false;
        $this->reset(['tagihanIdForUpload', 'buktiUpload', 'jumlahBayar', 'metodeBayar']);
        $this->tanggalBayar = now()->format('Y-m-d');
    }

    public function uploadBukti()
    {
        $this->validate([
            'buktiUpload' => 'required|image|max:2048', // Max 2MB
            'jumlahBayar' => 'required|numeric|min:0',
            'tanggalBayar' => 'required|date',
            'metodeBayar' => 'required|in:tunai,transfer,qris'
        ]);

        try {
            $tagihan = TagihanSpp::findOrFail($this->tagihanIdForUpload);
            
            // Simpan file bukti
            $filename = 'bukti_' . time() . '_' . $tagihan->siswa_id . '.' . $this->buktiUpload->getClientOriginalExtension();
            $path = $this->buktiUpload->storeAs('bukti-upload', $filename, 'public');

            // Buat record pembayaran
            $pembayaran = PembayaranSpp::create([
                'tagihan_spp_id' => $tagihan->id,
                'siswa_id' => $tagihan->siswa_id,
                'jumlah_bayar' => $this->jumlahBayar,
                'tanggal_bayar' => $this->tanggalBayar,
                'metode_bayar' => $this->metodeBayar,
                'bukti_upload' => $filename,
                'tanggal_upload' => now(),
                'status_verifikasi' => PembayaranSpp::STATUS_PENDING,
                'catatan' => 'Bukti pembayaran diupload oleh admin'
            ]);

            session()->flash('success', 'Bukti pembayaran berhasil diupload dan menunggu verifikasi.');
            $this->closeUploadModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function verifikasiPembayaran($status)
    {
        if (!$this->selectedPembayaran) return;

        try {
            $userId = auth()->id();

            if ($status === 'diterima') {
                $this->selectedPembayaran->verify($userId, $this->catatanVerifikasi);
                
                // Update tagihan sebagai lunas
                $this->selectedPembayaran->tagihanSpp->update([
                    'status' => 'lunas',
                    'tanggal_bayar' => now(),
                    'metode_pembayaran' => $this->selectedPembayaran->metode_bayar
                ]);

                session()->flash('success', 'Pembayaran berhasil diverifikasi dan tagihan telah dilunasi.');
            } else {
                $this->selectedPembayaran->reject($userId, $this->catatanVerifikasi);
                session()->flash('success', 'Pembayaran berhasil ditolak.');
            }

            $this->closeDetail();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function hapusBukti($pembayaranId)
    {
        try {
            $pembayaran = PembayaranSpp::findOrFail($pembayaranId);
            
            // Hapus file bukti
            if ($pembayaran->bukti_upload) {
                Storage::disk('public')->delete('bukti-upload/' . $pembayaran->bukti_upload);
            }
            
            $pembayaran->delete();
            
            session()->flash('success', 'Bukti pembayaran berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = PembayaranSpp::with([
                'tagihanSpp.siswa.kelas', 
                'tagihanSpp.biayaSpp',
                'verifikator'
            ])
            ->when($this->search, function ($query) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('nis', 'like', '%' . $this->search . '%');
                })
                ->orWhere('kode_referensi', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status_verifikasi', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc');

        $pembayaranList = $query->paginate($this->perPage);

        // Statistik
        $totalMenunggu = PembayaranSpp::pending()->count();
        $totalDiterima = PembayaranSpp::diterima()->count();
        $totalDitolak = PembayaranSpp::ditolak()->count();
        $totalPerluVerifikasi = PembayaranSpp::perluVerifikasi()->count();

        return view('livewire.admin.verifikasi-pembayaran', [
            'pembayaranList' => $pembayaranList,
            'totalMenunggu' => $totalMenunggu,
            'totalDiterima' => $totalDiterima,
            'totalDitolak' => $totalDitolak,
            'totalPerluVerifikasi' => $totalPerluVerifikasi,
        ]);
    }
}