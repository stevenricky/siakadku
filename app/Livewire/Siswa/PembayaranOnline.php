<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\TagihanSpp;
use Livewire\WithFileUploads;
use App\Models\PembayaranSpp;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app-new')]
class PembayaranOnline extends Component
{
    use WithPagination;
   use WithPagination, WithFileUploads;
    public $tagihanTerpilih = null;
    public $metodePembayaran = '';
    public $channelPembayaran = '';
    public $showFormPembayaran = false;
    public $showDetailPembayaran = false;
    public $pembayaranDetail = null;
    public $showUploadModal = false;
    public $buktiUpload;
    public $catatanUpload;

    // Channel pembayaran yang tersedia dengan logo
    public $channels = [
        'virtual_account' => [
            'name' => 'Transfer Bank',
            'icon' => 'fas fa-university',
            'color' => 'blue',
            'banks' => [
                'bca' => [
                    'name' => 'BCA', 
                    'fee' => 4000, 
                    'icon' => 'fab fa-bank',
                    'logo' => 'BCA.png', // Hanya nama file saja
                    'color' => 'blue'
                ],
                'bri' => [
                    'name' => 'BRI', 
                    'fee' => 4000, 
                    'icon' => 'fab fa-bank',
                    'logo' => 'BRI.png', // Hanya nama file saja
                    'color' => 'green'
                ],
                'bni' => [
                    'name' => 'BNI', 
                    'fee' => 4000, 
                    'icon' => 'fab fa-bank',
                    'logo' => 'BNI.png', // Hanya nama file saja
                    'color' => 'yellow'
                ],
                'mandiri' => [
                    'name' => 'Mandiri', 
                    'fee' => 4000, 
                    'icon' => 'fab fa-bank',
                    'logo' => 'MANDIRI.png', // Hanya nama file saja
                    'color' => 'red'
                ],
            ]
        ],
        'ewallet' => [
            'name' => 'E-Wallet',
            'icon' => 'fas fa-wallet',
            'color' => 'green',
            'wallets' => [
                'gopay' => [
                    'name' => 'GoPay', 
                    'fee' => 2000, 
                    'icon' => 'fas fa-mobile-alt',
                    'logo' => 'GOPAY.png', // Hanya nama file saja
                    'color' => 'blue'
                ],
                'ovo' => [
                    'name' => 'OVO', 
                    'fee' => 2000, 
                    'icon' => 'fas fa-mobile-alt',
                    'logo' => 'OVO.png', // Hanya nama file saja
                    'color' => 'purple'
                ],
                'dana' => [
                    'name' => 'DANA', 
                    'fee' => 2000, 
                    'icon' => 'fas fa-mobile-alt',
                    'logo' => 'DANA.png', // Hanya nama file saja
                    'color' => 'blue'
                ],
                'shopeepay' => [
                    'name' => 'ShopeePay', 
                    'fee' => 2000, 
                    'icon' => 'fas fa-shopping-bag',
                    'logo' => 'SHOPEEPAY.png', // Hanya nama file saja
                    'color' => 'orange'
                ],
            ]
        ],
        'qris' => [
            'name' => 'QRIS',
            'icon' => 'fas fa-qrcode',
            'color' => 'purple',
            'fee' => 1000,
            'logo' => 'QRIS.png' // Hanya nama file saja
        ]
    ];

    public function render()
    {
        $siswa = auth()->user()->siswa;

        // Ambil tagihan yang belum lunas dan bisa dibayar online
        $tagihanBelumLunas = TagihanSpp::with(['biayaSpp', 'pembayaranOnlinePending'])
            ->where('siswa_id', $siswa->id)
            ->whereIn('status', [TagihanSpp::STATUS_BELUM_BAYAR, TagihanSpp::STATUS_TERTUNGGAK])
            ->orderBy('tahun', 'desc')
            ->orderByRaw("FIELD(bulan, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
            ->get();

        // Ambil riwayat pembayaran online
        $riwayatPembayaran = PembayaranSpp::with(['tagihanSpp'])
            ->where('siswa_id', $siswa->id)
            ->whereIn('metode_bayar', ['virtual_account', 'ewallet', 'qris'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('livewire.siswa.pembayaran-online', [
            'tagihanBelumLunas' => $tagihanBelumLunas,
            'riwayatPembayaran' => $riwayatPembayaran,
        ]);
    }

    /**
     * Map metode pembayaran dari string ke konstanta model
     */
    private function mapMetodeBayar($metode): string
    {
        return match($metode) {
            'virtual_account' => PembayaranSpp::METHOD_VA,
            'ewallet' => PembayaranSpp::METHOD_EWALLET,
            'qris' => PembayaranSpp::METHOD_QRIS,
            default => PembayaranSpp::METHOD_TRANSFER
        };
    }

    /**
     * Map channel pembayaran dari string ke konstanta model
     */
    private function mapChannelBayar($channel): string
    {
        return match($channel) {
            'bca' => PembayaranSpp::CHANNEL_BCA,
            'bri' => PembayaranSpp::CHANNEL_BRI,
            'bni' => PembayaranSpp::CHANNEL_BNI,
            'mandiri' => PembayaranSpp::CHANNEL_MANDIRI,
            'gopay' => PembayaranSpp::CHANNEL_GOPAY,
            'ovo' => PembayaranSpp::CHANNEL_OVO,
            'dana' => PembayaranSpp::CHANNEL_DANA,
            'shopeepay' => PembayaranSpp::CHANNEL_SHOPEEPAY,
            'qris' => 'qris',
            default => 'other'
        };
    }

    public function pilihTagihan($tagihanId)
    {
        $this->tagihanTerpilih = TagihanSpp::with(['pembayaranOnlinePending'])->find($tagihanId);
        
        // Cek jika ada pembayaran pending
        if ($this->tagihanTerpilih && $this->tagihanTerpilih->has_pending_online_payment) {
            $pendingPayment = $this->tagihanTerpilih->latest_pending_payment;
            $this->lihatDetailPembayaran($pendingPayment->id);
            return;
        }

        $this->showFormPembayaran = true;
        $this->metodePembayaran = '';
        $this->channelPembayaran = '';
    }

    public function pilihMetode($metode)
    {
        $this->metodePembayaran = $metode;
        $this->channelPembayaran = '';
    }

    public function pilihChannel($channel)
    {
        $this->channelPembayaran = $channel;
    }

    public function prosesPembayaran()
    {
        $this->validate([
            'tagihanTerpilih' => 'required',
            'metodePembayaran' => 'required',
            'channelPembayaran' => 'required_if:metodePembayaran,virtual_account,ewallet',
        ]);

        try {
            // Hitung biaya admin
            $biayaAdmin = $this->hitungBiayaAdmin();

            // Map metode pembayaran ke konstanta model
            $metodeBayar = $this->mapMetodeBayar($this->metodePembayaran);
            $channelBayar = $this->mapChannelBayar($this->channelPembayaran);

            // Buat pembayaran online - TANGAL BAYAR DIISI DENGAN NOW()
            $pembayaran = PembayaranSpp::create([
                'tagihan_spp_id' => $this->tagihanTerpilih->id,
                'siswa_id' => $this->tagihanTerpilih->siswa_id,
                'jumlah_bayar' => $this->tagihanTerpilih->total_pembayaran,
                'biaya_admin' => $biayaAdmin,
                'tanggal_bayar' => now(), // ✅ WAJIB DIISI KARENA KOLOM NO NULL
                'metode_bayar' => $metodeBayar,
                'channel_pembayaran' => $channelBayar,
                'status_verifikasi' => PembayaranSpp::STATUS_PENDING,
                'status_pembayaran' => PembayaranSpp::PAYMENT_PENDING,
                'kode_referensi' => 'PAY-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6)),
                'waktu_kadaluarsa' => now()->addHours(24),
                'catatan' => 'Pembayaran online via ' . $this->getNamaChannel(),
            ]);

            // Reset form
            $this->reset(['tagihanTerpilih', 'metodePembayaran', 'channelPembayaran', 'showFormPembayaran']);

            // Tampilkan detail pembayaran yang baru dibuat
            $this->lihatDetailPembayaran($pembayaran->id);

            session()->flash('success', 
                "Pembayaran berhasil diproses!\n" .
                "Kode Referensi: {$pembayaran->kode_referensi}\n" .
                "Total: Rp " . number_format($pembayaran->total_bayar, 0, ',', '.') . "\n" .
                "Batas Waktu: " . $pembayaran->waktu_kadaluarsa->format('d M Y H:i')
            );

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function batalkanPembayaran()
    {
        $this->reset(['tagihanTerpilih', 'metodePembayaran', 'channelPembayaran', 'showFormPembayaran']);
    }

    public function lihatDetailPembayaran($pembayaranId)
    {
        $this->pembayaranDetail = PembayaranSpp::with(['tagihanSpp'])->find($pembayaranId);
        $this->showDetailPembayaran = true;
        $this->showFormPembayaran = false;
    }

    public function tutupDetailPembayaran()
    {
        $this->showDetailPembayaran = false;
        $this->pembayaranDetail = null;
    }

    public function simulasiPembayaranBerhasil($pembayaranId)
    {
        $pembayaran = PembayaranSpp::find($pembayaranId);
        
        if ($pembayaran && $pembayaran->is_pending) {
            // Update melalui model TagihanSpp
            $pembayaran->tagihanSpp->markOnlinePaymentAsPaid($pembayaranId);
            
            session()->flash('success', 'Pembayaran berhasil! Tagihan telah dilunasi.');
            $this->tutupDetailPembayaran();
        }
    }

    public function batalkanPembayaranOnline($pembayaranId)
    {
        $pembayaran = PembayaranSpp::find($pembayaranId);
        
        if ($pembayaran && $pembayaran->is_pending) {
            // Update melalui model TagihanSpp
            $pembayaran->tagihanSpp->markOnlinePaymentAsFailed($pembayaranId);
            
            session()->flash('info', 'Pembayaran berhasil dibatalkan.');
            $this->tutupDetailPembayaran();
        }
    }

    /**
     * Method untuk membuka modal upload bukti
     */
    public function openUploadModal($pembayaranId)
    {
        $this->pembayaranDetail = PembayaranSpp::with(['tagihanSpp'])->find($pembayaranId);
        
        if (!$this->pembayaranDetail) {
            session()->flash('error', 'Pembayaran tidak ditemukan.');
            return;
        }

        // Cek apakah pembayaran masih pending
        if (!$this->pembayaranDetail->is_pending) {
            session()->flash('error', 'Pembayaran sudah diverifikasi, tidak dapat upload bukti.');
            return;
        }

        $this->showUploadModal = true;
        $this->showDetailPembayaran = false;
        
        // Reset form upload
        $this->reset(['buktiUpload', 'catatanUpload']);
    }

    public function uploadBuktiPembayaran($pembayaranId)
    {
        $this->pembayaranDetail = PembayaranSpp::with(['tagihanSpp'])->find($pembayaranId);
        $this->showUploadModal = true;
        $this->showDetailPembayaran = false;
    }

    public function closeUploadModal()
    {
        $this->showUploadModal = false;
        $this->pembayaranDetail = null;
    }

    public function prosesUploadBukti()
    {
        $this->validate([
            'buktiUpload' => 'required|image|max:2048',
            'catatanUpload' => 'nullable|string|max:500'
        ]);

        try {
            $pembayaran = PembayaranSpp::where('id', $this->pembayaranDetail->id)
                ->where('siswa_id', auth()->user()->siswa->id)
                ->firstOrFail();

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

    /**
     * Method untuk download bukti pembayaran
     */
    public function downloadBukti($pembayaranId)
    {
        try {
            $pembayaran = PembayaranSpp::where('id', $pembayaranId)
                ->where('siswa_id', auth()->user()->siswa->id)
                ->firstOrFail();

            if (!$pembayaran->bukti_upload) {
                session()->flash('error', 'Bukti pembayaran tidak ditemukan.');
                return;
            }

            $filePath = storage_path('app/public/bukti-upload/' . $pembayaran->bukti_upload);
            
            if (!file_exists($filePath)) {
                session()->flash('error', 'File bukti tidak ditemukan.');
                return;
            }

            return response()->download($filePath, 'bukti-pembayaran-' . $pembayaran->kode_referensi . '.' . pathinfo($filePath, PATHINFO_EXTENSION));

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function hitungBiayaAdmin(): float
    {
        $biayaAdmin = 0;

        if ($this->metodePembayaran === 'virtual_account') {
            $biayaAdmin = $this->channels['virtual_account']['banks'][$this->channelPembayaran]['fee'] ?? 0;
        } elseif ($this->metodePembayaran === 'ewallet') {
            $biayaAdmin = $this->channels['ewallet']['wallets'][$this->channelPembayaran]['fee'] ?? 0;
        } elseif ($this->metodePembayaran === 'qris') {
            $biayaAdmin = $this->channels['qris']['fee'] ?? 0;
        }

        return $biayaAdmin;
    }

    private function getNamaChannel(): string
    {
        if ($this->metodePembayaran === 'virtual_account') {
            return $this->channels['virtual_account']['banks'][$this->channelPembayaran]['name'] ?? 'Bank';
        } elseif ($this->metodePembayaran === 'ewallet') {
            return $this->channels['ewallet']['wallets'][$this->channelPembayaran]['name'] ?? 'E-Wallet';
        } elseif ($this->metodePembayaran === 'qris') {
            return 'QRIS';
        }

        return 'Unknown';
    }

    // Method untuk mendapatkan URL logo
    public function getLogoUrl($logoName): string
    {
        // Simulasi URL logo - Anda bisa menyimpan logo di storage/public/images/payments/
        return asset("storage/payment/{$logoName}");
    }
}