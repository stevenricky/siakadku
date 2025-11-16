<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Pemeliharaan;
use App\Models\BarangInventaris;
use App\Models\User;
use Carbon\Carbon;

#[Layout('layouts.app-new')]
class PemeliharaanBarang extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $statusFilter = '';
    public $monthFilter = '';

    // Form properties
    public $showForm = false;
    public $formType = 'create';
    public $pemeliharaanId = null;
    
    public $barang_id;
    public $tanggal_pemeliharaan;
    public $jenis_pemeliharaan = 'perbaikan';
    public $deskripsi_kerusakan;
    public $tindakan;
    public $biaya = 0;
    public $teknisi;
    public $status = 'dilaporkan';
    public $catatan;
    public $pelapor_id;

    public $barangList;
    public $jenisList = ['perbaikan', 'pemeliharaan_rutin', 'kalibrasi', 'upgrade', 'lainnya'];
    public $statusList = ['dilaporkan', 'diproses', 'selesai', 'batal'];
    public $teknisiList = ['Teknisi A', 'Teknisi B', 'Teknisi C', 'Vendor Eksternal'];

    protected $queryString = ['search', 'perPage', 'statusFilter', 'monthFilter'];

    protected function rules()
    {
        return [
            'barang_id' => 'required|exists:barang_inventaris,id',
            'tanggal_pemeliharaan' => 'required|date',
            'jenis_pemeliharaan' => 'required|in:perbaikan,pemeliharaan_rutin,kalibrasi,upgrade,lainnya',
            'deskripsi_kerusakan' => 'nullable|string',
            'tindakan' => 'required|string',
            'biaya' => 'required|numeric|min:0',
            'teknisi' => 'nullable|string|max:100',
            'status' => 'required|in:dilaporkan,diproses,selesai,batal',
            'catatan' => 'nullable|string',
        ];
    }

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
        $this->tanggal_pemeliharaan = now()->format('Y-m-d');
        $this->pelapor_id = auth()->id();
        $this->loadBarangList();
    }

    public function loadBarangList()
    {
        $this->barangList = BarangInventaris::with(['kategori', 'ruangan'])
            ->orderBy('nama_barang')
            ->get();
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->formType = 'create';
        $this->showForm = true;
    }

    public function openEditForm($id)
    {
        $pemeliharaan = Pemeliharaan::findOrFail($id);
        
        $this->pemeliharaanId = $pemeliharaan->id;
        $this->barang_id = $pemeliharaan->barang_id;
        $this->tanggal_pemeliharaan = $pemeliharaan->tanggal_pemeliharaan->format('Y-m-d');
        $this->jenis_pemeliharaan = $pemeliharaan->jenis_pemeliharaan;
        $this->deskripsi_kerusakan = $pemeliharaan->deskripsi_kerusakan;
        $this->tindakan = $pemeliharaan->tindakan;
        $this->biaya = $pemeliharaan->biaya;
        $this->teknisi = $pemeliharaan->teknisi;
        $this->status = $pemeliharaan->status;
        $this->catatan = $pemeliharaan->catatan;
        
        $this->formType = 'edit';
        $this->showForm = true;
    }

    public function resetForm()
    {
        $this->reset([
            'pemeliharaanId', 'barang_id', 'tanggal_pemeliharaan', 'jenis_pemeliharaan',
            'deskripsi_kerusakan', 'tindakan', 'biaya', 'teknisi', 'status', 'catatan'
        ]);
        $this->resetErrorBag();
        $this->tanggal_pemeliharaan = now()->format('Y-m-d');
        $this->jenis_pemeliharaan = 'perbaikan';
        $this->status = 'dilaporkan';
        $this->biaya = 0;
    }

    public function savePemeliharaan()
    {
        $validatedData = $this->validate($this->rules());

        // Tambah pelapor_id
        $validatedData['pelapor_id'] = auth()->id();

        try {
            if ($this->formType === 'create') {
                Pemeliharaan::create($validatedData);
                session()->flash('success', 'Laporan pemeliharaan berhasil ditambahkan.');
            } else {
                $pemeliharaan = Pemeliharaan::find($this->pemeliharaanId);
                $pemeliharaan->update($validatedData);
                session()->flash('success', 'Data pemeliharaan berhasil diperbarui.');
            }

            $this->showForm = false;
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            $pemeliharaan = Pemeliharaan::findOrFail($id);
            $pemeliharaan->update(['status' => $status]);
            session()->flash('success', 'Status pemeliharaan berhasil diperbarui.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deletePemeliharaan($id)
    {
        try {
            $pemeliharaan = Pemeliharaan::findOrFail($id);
            $pemeliharaan->delete();
            session()->flash('success', 'Data pemeliharaan berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Pemeliharaan::with(['barang.kategori', 'barang.ruangan', 'pelapor'])
            ->when($this->search, function ($query) {
                $query->whereHas('barang', function ($q) {
                    $q->where('nama_barang', 'like', '%' . $this->search . '%')
                      ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
                })->orWhere('jenis_pemeliharaan', 'like', '%' . $this->search . '%')
                  ->orWhere('teknisi', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->monthFilter, function ($query) {
                $query->whereYear('tanggal_pemeliharaan', substr($this->monthFilter, 0, 4))
                      ->whereMonth('tanggal_pemeliharaan', substr($this->monthFilter, 5, 2));
            })
            ->orderBy('tanggal_pemeliharaan', 'desc')
            ->orderBy('created_at', 'desc');

        $pemeliharaan = $query->paginate($this->perPage);

        // Hitung statistik
        $dilaporkanCount = Pemeliharaan::where('status', 'dilaporkan')->count();
        $diprosesCount = Pemeliharaan::where('status', 'diproses')->count();
        $selesaiCount = Pemeliharaan::where('status', 'selesai')->count();
        $batalCount = Pemeliharaan::where('status', 'batal')->count();

        return view('livewire.admin.pemeliharaan-barang', [
            'pemeliharaan' => $pemeliharaan,
            'dilaporkanCount' => $dilaporkanCount,
            'diprosesCount' => $diprosesCount,
            'selesaiCount' => $selesaiCount,
            'batalCount' => $batalCount,
        ]);
    }
}