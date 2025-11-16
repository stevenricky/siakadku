<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PembayaranSpp;
use App\Models\TagihanSpp;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Exports\PembayaranExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class KonfirmasiPembayaran extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $statusFilter = '';
    public $kelasFilter = '';
    public $tanggalFilter = '';
    
    public $showModal = false;
    public $showDetailModal = false;
    public $selectedPembayaran;
    
    public $catatanVerifikasi;
    public $statusVerifikasi = 'diterima';

    public $kelasList;

    protected $queryString = ['search', 'perPage', 'statusFilter', 'kelasFilter', 'tanggalFilter'];

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
        $this->kelasList = Kelas::all();

        // Authorization check
        if (!auth()->user()->can('viewAny', PembayaranSpp::class)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function render()
    {
        $guru = auth()->user()->guru;
        $kelasWali = $guru->kelasWali;

        $pembayaranQuery = PembayaranSpp::with(['siswa.kelas', 'tagihanSpp', 'verifikator'])
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
            ->when($this->statusFilter, function($query) {
                $query->where('status_verifikasi', $this->statusFilter);
            })
            ->when($this->tanggalFilter, function($query) {
                $query->whereDate('tanggal_bayar', $this->tanggalFilter);
            })
            ->when($this->search, function($query) {
                $query->whereHas('siswa', function($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                      ->orWhere('nis', 'like', '%' . $this->search . '%');
                });
            });

        $pembayaranList = $pembayaranQuery->latest()->paginate($this->perPage);

        // Hitung statistik
        $totalPembayaran = $pembayaranQuery->count();
        $pendingCount = $pembayaranQuery->clone()->where('status_verifikasi', 'pending')->count();
        $diterimaCount = $pembayaranQuery->clone()->where('status_verifikasi', 'diterima')->count();
        $ditolakCount = $pembayaranQuery->clone()->where('status_verifikasi', 'ditolak')->count();

        return view('livewire.guru.konfirmasi-pembayaran', [
            'pembayaranList' => $pembayaranList,
            'totalPembayaran' => $totalPembayaran,
            'pendingCount' => $pendingCount,
            'diterimaCount' => $diterimaCount,
            'ditolakCount' => $ditolakCount,
            'guru' => $guru,
            'kelasWali' => $kelasWali
        ]);
    }

    public function viewDetail($id)
    {
        $this->selectedPembayaran = PembayaranSpp::with([
            'siswa.kelas', 
            'tagihanSpp',
            'verifikator'
        ])->findOrFail($id);
        
        // Authorization check
        if (!auth()->user()->can('view', $this->selectedPembayaran)) {
            session()->flash('error', 'Anda tidak memiliki akses untuk melihat detail pembayaran ini.');
            return;
        }
        
        $this->showDetailModal = true;
    }

    public function verifikasiPembayaran($id)
    {
        $this->selectedPembayaran = PembayaranSpp::with(['siswa', 'tagihanSpp'])->findOrFail($id);
        
        // Authorization check
        if (!auth()->user()->can('verify', $this->selectedPembayaran)) {
            session()->flash('error', 'Anda tidak memiliki akses untuk memverifikasi pembayaran ini.');
            return;
        }
        
        $this->statusVerifikasi = 'diterima';
        $this->catatanVerifikasi = '';
        $this->showModal = true;
    }

    public function tolakPembayaran($id)
    {
        $this->selectedPembayaran = PembayaranSpp::with(['siswa', 'tagihanSpp'])->findOrFail($id);
        
        // Authorization check
        if (!auth()->user()->can('verify', $this->selectedPembayaran)) {
            session()->flash('error', 'Anda tidak memiliki akses untuk memverifikasi pembayaran ini.');
            return;
        }
        
        $this->statusVerifikasi = 'ditolak';
        $this->catatanVerifikasi = '';
        $this->showModal = true;
    }

    public function prosesVerifikasi()
    {
        $this->validate([
            'statusVerifikasi' => 'required|in:diterima,ditolak',
            'catatanVerifikasi' => 'nullable|string|max:500',
        ]);

        try {
            // Authorization check
            if (!auth()->user()->can('verify', $this->selectedPembayaran)) {
                session()->flash('error', 'Anda tidak memiliki akses untuk memverifikasi pembayaran ini.');
                return;
            }

            $pembayaran = $this->selectedPembayaran;
            
            // Cek apakah pembayaran masih pending
            if ($pembayaran->status_verifikasi !== PembayaranSpp::STATUS_PENDING) {
                session()->flash('error', 'Pembayaran ini sudah diverifikasi sebelumnya.');
                return;
            }

            if ($this->statusVerifikasi === 'diterima') {
                $pembayaran->verify(auth()->id(), $this->catatanVerifikasi);
                
                // Update status tagihan menjadi lunas
                $pembayaran->tagihanSpp->update([
                    'status' => 'lunas',
                    'tanggal_bayar' => $pembayaran->tanggal_bayar,
                ]);
                
                session()->flash('success', 'Pembayaran berhasil diterima.');
            } else {
                $pembayaran->reject(auth()->id(), $this->catatanVerifikasi);
                session()->flash('success', 'Pembayaran berhasil ditolak.');
            }

            $this->resetModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function downloadBukti($id)
    {
        try {
            $pembayaran = PembayaranSpp::findOrFail($id);

            // Authorization check
            if (!auth()->user()->can('downloadBukti', $pembayaran)) {
                session()->flash('error', 'Anda tidak memiliki akses untuk mendownload bukti ini.');
                return;
            }

            if (!$pembayaran->bukti_bayar) {
                session()->flash('error', 'Bukti bayar tidak tersedia.');
                return;
            }

            $filePath = storage_path('app/public/bukti-bayar/' . $pembayaran->bukti_bayar);
            
            if (!file_exists($filePath)) {
                session()->flash('error', 'File bukti bayar tidak ditemukan.');
                return;
            }

            return response()->download($filePath, 'bukti-bayar-' . $pembayaran->siswa->nis . '.pdf');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function exportLaporan()
    {
        try {
            // Authorization check
            if (!auth()->user()->can('exportLaporan', PembayaranSpp::class)) {
                session()->flash('error', 'Anda tidak memiliki akses untuk export laporan.');
                return;
            }

            $filename = 'laporan-pembayaran-spp-' . now()->format('Y-m-d') . '.xlsx';
            
            return Excel::download(
                new PembayaranExport($this->kelasFilter, $this->statusFilter, $this->tanggalFilter), 
                $filename
            );
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengexport laporan: ' . $e->getMessage());
        }
    }

    private function resetModal()
    {
        $this->reset([
            'showModal',
            'showDetailModal',
            'selectedPembayaran',
            'catatanVerifikasi',
            'statusVerifikasi',
        ]);
    }

    public function resetFilters()
    {
        $this->reset(['search', 'kelasFilter', 'statusFilter', 'tanggalFilter']);
    }
}