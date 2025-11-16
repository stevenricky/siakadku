<?php

namespace App\Livewire\Admin;

use App\Models\PembayaranSpp;
use App\Models\Siswa;
use App\Models\TagihanSpp;
use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('layouts.app-new')]
class PembayaranSppManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $bulanFilter = '';
    public $siswaFilter = '';
    public $kelasFilter = '';
    public $perPage = 10;

    // Properties untuk modal
    public $showDetailModal = false;
    public $showVerifikasiModal = false;
    public $showTambahModal = false;
    public $showEditModal = false;

    // Data selected
    public $selectedPembayaran = null;
    public $verifikasiAction = '';
    public $catatanVerifikasi = '';

    // Form properties
    public $pembayaranId = null;
    public $siswaId = '';
    public $tagihanSppId = '';
    public $jumlahBayar = '';
    public $tanggalBayar = '';
    public $metodeBayar = 'tunai';
    public $statusVerifikasi = 'pending';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'bulanFilter' => ['except' => ''],
        'siswaFilter' => ['except' => ''],
        'kelasFilter' => ['except' => '']
    ];

    public function mount()
    {
        $this->perPage = session()->get('perPage_pembayaran', 10);
        $this->tanggalBayar = now()->format('Y-m-d');
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage_pembayaran', $value);
        $this->resetPage();
    }

    public function render()
    {
        $pembayaran = PembayaranSpp::with(['siswa.kelas', 'verifikator', 'tagihanSpp'])
            ->when($this->search, function($query) {
                $query->whereHas('siswa', function($q) {
                    $q->where('nama', 'like', '%'.$this->search.'%')
                      ->orWhere('nis', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->statusFilter, function($query) {
                $query->where('status_verifikasi', $this->statusFilter);
            })
            ->when($this->bulanFilter, function($query) {
                $query->whereMonth('tanggal_bayar', date('m', strtotime($this->bulanFilter)))
                      ->whereYear('tanggal_bayar', date('Y', strtotime($this->bulanFilter)));
            })
            ->when($this->siswaFilter, function($query) {
                $query->where('siswa_id', $this->siswaFilter);
            })
            ->when($this->kelasFilter, function($query) {
                $query->whereHas('siswa', function($q) {
                    $q->where('kelas_id', $this->kelasFilter);
                });
            })
            ->latest()
            ->paginate($this->perPage);

        $siswaList = Siswa::aktif()->with('kelas')->get();
        $kelasList = Kelas::all();

        return view('livewire.admin.pembayaran-spp-management', compact('pembayaran', 'siswaList', 'kelasList'));
    }

    // Modal Methods
    public function showDetail($pembayaranId)
    {
        $this->selectedPembayaran = PembayaranSpp::with([
            'siswa.kelas', 
            'verifikator', 
            'tagihanSpp'
        ])->find($pembayaranId);
        
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedPembayaran = null;
    }

    public function openVerifikasi($pembayaranId, $action)
    {
        $this->selectedPembayaran = PembayaranSpp::with(['siswa'])->find($pembayaranId);
        
        if (!$this->selectedPembayaran) {
            session()->flash('error', 'Pembayaran tidak ditemukan.');
            return;
        }
        
        $this->verifikasiAction = $action;
        $this->showVerifikasiModal = true;
        $this->catatanVerifikasi = ''; // Reset catatan
        
        \Log::info('Open Verifikasi:', [
            'pembayaran_id' => $pembayaranId,
            'action' => $action,
            'siswa' => $this->selectedPembayaran->siswa->nama
        ]);
    }

    public function closeVerifikasi()
    {
        $this->showVerifikasiModal = false;
        $this->selectedPembayaran = null;
        $this->verifikasiAction = '';
        $this->catatanVerifikasi = '';
    }

    public function openTambahModal()
    {
        $this->showTambahModal = true;
        $this->resetForm();
    }

    public function closeTambahModal()
    {
        $this->showTambahModal = false;
        $this->resetForm();
    }

    public function openEditModal($pembayaranId)
    {
        $pembayaran = PembayaranSpp::find($pembayaranId);
        
        if ($pembayaran) {
            $this->pembayaranId = $pembayaran->id;
            $this->siswaId = $pembayaran->siswa_id;
            $this->tagihanSppId = $pembayaran->tagihan_spp_id;
            $this->jumlahBayar = $pembayaran->jumlah_bayar;
            $this->tanggalBayar = $pembayaran->tanggal_bayar->format('Y-m-d');
            $this->metodeBayar = $pembayaran->metode_bayar;
            $this->statusVerifikasi = $pembayaran->status_verifikasi;
            $this->showEditModal = true;
        }
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function closeModal()
    {
        $this->showTambahModal = false;
        $this->showEditModal = false;
        $this->showVerifikasiModal = false;
        $this->showDetailModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'pembayaranId', 'siswaId', 'tagihanSppId', 'jumlahBayar', 
            'tanggalBayar', 'metodeBayar', 'statusVerifikasi',
            'verifikasiAction', 'catatanVerifikasi'
        ]);
        $this->tanggalBayar = now()->format('Y-m-d');
    }

    // CRUD Methods
    public function simpanPembayaran()
    {
        $this->validate([
            'siswaId' => 'required|exists:siswas,id',
            'tagihanSppId' => 'required|exists:tagihan_spp,id',
            'jumlahBayar' => 'required|numeric|min:1000',
            'tanggalBayar' => 'required|date',
            'metodeBayar' => 'required|in:tunai,transfer,qris'
        ]);

        $tagihan = TagihanSpp::find($this->tagihanSppId);

        PembayaranSpp::create([
            'tagihan_spp_id' => $this->tagihanSppId,
            'siswa_id' => $this->siswaId,
            'jumlah_bayar' => $this->jumlahBayar,
            'tanggal_bayar' => $this->tanggalBayar,
            'metode_bayar' => $this->metodeBayar,
            'status_verifikasi' => 'diterima',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        $tagihan->update(['status' => 'lunas']);

        $this->closeTambahModal();
        $this->dispatch('refresh-component');
        session()->flash('success', 'Pembayaran manual berhasil ditambahkan.');
    }

    public function updatePembayaran()
    {
        $this->validate([
            'siswaId' => 'required|exists:siswas,id',
            'tagihanSppId' => 'required|exists:tagihan_spp,id',
            'jumlahBayar' => 'required|numeric|min:1000',
            'tanggalBayar' => 'required|date',
            'metodeBayar' => 'required|in:tunai,transfer,qris',
            'statusVerifikasi' => 'required|in:pending,diterima,ditolak'
        ]);

        $pembayaran = PembayaranSpp::find($this->pembayaranId);
        
        if ($pembayaran) {
            $pembayaran->update([
                'siswa_id' => $this->siswaId,
                'tagihan_spp_id' => $this->tagihanSppId,
                'jumlah_bayar' => $this->jumlahBayar,
                'tanggal_bayar' => $this->tanggalBayar,
                'metode_bayar' => $this->metodeBayar,
                'status_verifikasi' => $this->statusVerifikasi,
            ]);

            if ($this->statusVerifikasi === 'diterima') {
                $pembayaran->tagihanSpp->update(['status' => 'lunas']);
            } elseif ($this->statusVerifikasi === 'pending') {
                $pembayaran->tagihanSpp->update(['status' => 'belum_bayar']);
            }

            $this->closeEditModal();
            $this->dispatch('refresh-component');
            session()->flash('success', 'Pembayaran berhasil diperbarui.');
        }
    }

    public function hapusPembayaran($pembayaranId)
    {
        $pembayaran = PembayaranSpp::find($pembayaranId);
        
        if ($pembayaran) {
            $pembayaran->tagihanSpp->update(['status' => 'belum_bayar']);
            $pembayaran->delete();

            $this->dispatch('refresh-component');
            session()->flash('success', 'Pembayaran berhasil dihapus.');
        }
    }

    public function prosesVerifikasi()
    {
        $this->validate([
            'catatanVerifikasi' => $this->verifikasiAction === 'ditolak' ? 'required|min:5' : 'nullable'
        ]);

        if ($this->selectedPembayaran) {
            \Log::info('Proses Verifikasi:', [
                'pembayaran_id' => $this->selectedPembayaran->id,
                'action' => $this->verifikasiAction,
                'user_id' => auth()->id()
            ]);

            try {
                $this->selectedPembayaran->update([
                    'status_verifikasi' => $this->verifikasiAction,
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                    'catatan' => $this->catatanVerifikasi
                ]);

                if ($this->verifikasiAction === 'diterima') {
                    $this->selectedPembayaran->tagihanSpp->update(['status' => 'lunas']);
                }

                $this->closeVerifikasi();
                $this->dispatch('refresh-component');
                session()->flash('success', 'Pembayaran berhasil ' . ($this->verifikasiAction === 'diterima' ? 'diverifikasi' : 'ditolak'));
                
            } catch (\Exception $e) {
                \Log::error('Error verifikasi:', ['error' => $e->getMessage()]);
                session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }
    }

    // Method baru untuk membuka verifikasi dari modal detail
    public function openVerifikasiFromDetail($pembayaranId, $action)
    {
        $this->closeDetail(); // Tutup modal detail dulu
        $this->openVerifikasi($pembayaranId, $action); // Buka modal verifikasi
    }

    // Method baru untuk debug storage
    public function debugStorage()
    {
        $pembayaran = PembayaranSpp::whereNotNull('bukti_upload')->first();
        
        if ($pembayaran) {
            $path = 'public/bukti-upload/' . $pembayaran->bukti_upload;
            $exists = Storage::exists($path);
            
            \Log::info('Storage Debug:', [
                'file' => $pembayaran->bukti_upload,
                'path' => $path,
                'exists' => $exists,
                'files_in_directory' => Storage::files('public/bukti-upload')
            ]);
            
            session()->flash('info', 'File exists: ' . ($exists ? 'YES' : 'NO') . ' - Check laravel.log for details');
        } else {
            session()->flash('error', 'No pembayaran with bukti_upload found');
        }
    }

    // Export & Print Methods
    public function exportCSV()
    {
        $pembayaran = PembayaranSpp::with(['siswa', 'tagihanSpp', 'verifikator'])
            ->when($this->statusFilter, function($query) {
                $query->where('status_verifikasi', $this->statusFilter);
            })
            ->when($this->bulanFilter, function($query) {
                $query->whereMonth('tanggal_bayar', date('m', strtotime($this->bulanFilter)))
                      ->whereYear('tanggal_bayar', date('Y', strtotime($this->bulanFilter)));
            })
            ->when($this->siswaFilter, function($query) {
                $query->where('siswa_id', $this->siswaFilter);
            })
            ->latest()
            ->get();

        $fileName = 'pembayaran-spp-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($pembayaran) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            
            fputcsv($file, [
                'Tanggal Bayar', 'NIS', 'Nama Siswa', 'Kelas', 'Bulan Tagihan', 
                'Jumlah Bayar', 'Metode Bayar', 'Status', 'Verifikator', 'Tanggal Verifikasi'
            ]);

            foreach ($pembayaran as $item) {
                fputcsv($file, [
                    $item->tanggal_bayar->format('d/m/Y'),
                    $item->siswa->nis,
                    $item->siswa->nama,
                    $item->siswa->kelas->nama_kelas ?? '-',
                    $item->tagihanSpp->bulan . ' ' . $item->tagihanSpp->tahun,
                    number_format($item->jumlah_bayar, 0, ',', '.'),
                    ucfirst($item->metode_bayar),
                    $item->status_text,
                    $item->verifikator ? $item->verifikator->name : '-',
                    $item->verified_at ? $item->verified_at->format('d/m/Y H:i') : '-'
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function printPDF()
    {
        $pembayaran = PembayaranSpp::with(['siswa.kelas', 'tagihanSpp', 'verifikator'])
            ->when($this->statusFilter, function($query) {
                $query->where('status_verifikasi', $this->statusFilter);
            })
            ->when($this->bulanFilter, function($query) {
                $query->whereMonth('tanggal_bayar', date('m', strtotime($this->bulanFilter)))
                      ->whereYear('tanggal_bayar', date('Y', strtotime($this->bulanFilter)));
            })
            ->when($this->siswaFilter, function($query) {
                $query->where('siswa_id', $this->siswaFilter);
            })
            ->latest()
            ->get();

        $data = [
            'pembayaran' => $pembayaran,
            'tanggalCetak' => now()->translatedFormat('d F Y'),
            'totalPembayaran' => $pembayaran->sum('jumlah_bayar'),
            'filterStatus' => $this->statusFilter ?: 'Semua Status',
            'filterBulan' => $this->bulanFilter ? date('F Y', strtotime($this->bulanFilter)) : 'Semua Bulan'
        ];

        $pdf = PDF::loadView('exports.pembayaran-pdf', $data)
                  ->setPaper('a4', 'landscape')
                  ->setOptions([
                      'dpi' => 150, 
                      'defaultFont' => 'sans-serif',
                      'isHtml5ParserEnabled' => true,
                      'isRemoteEnabled' => true
                  ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'laporan-pembayaran-spp-' . date('Y-m-d') . '.pdf');
    }

    // Method downloadBukti yang diperbaiki
    public function downloadBukti($pembayaranId)
    {
        $pembayaran = PembayaranSpp::find($pembayaranId);
        
        if (!$pembayaran) {
            session()->flash('error', 'Pembayaran tidak ditemukan.');
            return;
        }

        \Log::info('Download Bukti Debug:', [
            'pembayaran_id' => $pembayaran->id,
            'bukti_upload' => $pembayaran->bukti_upload,
            'bukti_bayar' => $pembayaran->bukti_bayar
        ]);

        // Cek bukti_upload (file dari siswa) - Priority 1
        if ($pembayaran->bukti_upload) {
            // Coba beberapa kemungkinan path
            $possiblePaths = [
                'public/bukti-upload/' . $pembayaran->bukti_upload,
                'bukti-upload/' . $pembayaran->bukti_upload,
                'app/public/bukti-upload/' . $pembayaran->bukti_upload
            ];
            
            foreach ($possiblePaths as $path) {
                if (Storage::exists($path)) {
                    \Log::info('File ditemukan di path:', ['path' => $path]);
                    return Storage::download($path);
                }
            }
            
            // Jika tidak ditemukan di storage, coba direct path
            $directPath = storage_path('app/public/bukti-upload/' . $pembayaran->bukti_upload);
            if (file_exists($directPath)) {
                \Log::info('File ditemukan di direct path:', ['path' => $directPath]);
                return response()->download($directPath, 'bukti-pembayaran-' . $pembayaran->id . '.jpg');
            }
            
            \Log::error('File bukti_upload tidak ditemukan di semua path');
            session()->flash('error', 'File bukti upload tidak ditemukan di storage.');
            return;
        }
        
        // Fallback ke bukti_bayar - Priority 2
        if ($pembayaran->bukti_bayar) {
            if (Storage::exists($pembayaran->bukti_bayar)) {
                return Storage::download($pembayaran->bukti_bayar);
            } else {
                session()->flash('error', 'File bukti bayar tidak ditemukan di storage.');
                return;
            }
        }

        session()->flash('error', 'Tidak ada bukti bayar yang tersedia.');
    }

    public function getTagihanListProperty()
    {
        if (!$this->siswaId) {
            return collect();
        }

        return TagihanSpp::where('siswa_id', $this->siswaId)
            ->where('status', 'belum_bayar')
            ->get();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'bulanFilter', 'siswaFilter', 'kelasFilter']);
        $this->resetPage();
    }

    // Computed Properties
    public function getTotalPembayaranProperty()
    {
        return PembayaranSpp::count();
    }

    public function getTotalPendingProperty()
    {
        return PembayaranSpp::pending()->count();
    }

    public function getTotalDiterimaProperty()
    {
        return PembayaranSpp::diterima()->count();
    }

    public function getTotalDitolakProperty()
    {
        return PembayaranSpp::ditolak()->count();
    }

    public function getTotalBulanIniProperty()
    {
        return PembayaranSpp::diterima()
            ->whereMonth('tanggal_bayar', now()->month)
            ->whereYear('tanggal_bayar', now()->year)
            ->sum('jumlah_bayar');
    }

    // Listeners
    protected $listeners = ['refreshComponent' => '$refresh'];
}