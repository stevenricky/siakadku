<?php

namespace App\Livewire\Admin;

use App\Models\Spp;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app-new')]
class ManajemenSpp extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $tahunAjaranFilter = '';
    public $statusFilter = '';
    
    // Form properties
    public $showForm = false;
    public $formType = 'create';
    public $sppId = null;
    
    public $siswa_id;
    public $tahun_ajaran_id;
    public $nominal;
    public $bulan;
    public $tahun;
    public $tanggal_bayar;
    public $status = 'belum_bayar';
    public $keterangan;

    public $bulanList = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    public $statusList = [
        'belum_bayar' => 'Belum Bayar',
        'lunas' => 'Lunas',
        'tertunggak' => 'Tertunggak'
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'tahunAjaranFilter' => ['except' => ''],
        'statusFilter' => ['except' => '']
    ];

    protected $rules = [
        'siswa_id' => 'required|exists:siswas,id',
        'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        'nominal' => 'required|numeric|min:0',
        'bulan' => 'required|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember',
        'tahun' => 'required|numeric|min:2020|max:2030',
        'tanggal_bayar' => 'nullable|date',
        'status' => 'required|in:belum_bayar,lunas,tertunggak',
        'keterangan' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
        $this->tahun = date('Y');
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedTahunAjaranFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->formType = 'create';
        $this->showForm = true;
    }

    public function openEditForm($id)
    {
        $spp = Spp::with(['siswa', 'tahunAjaran'])->findOrFail($id);
        
        $this->sppId = $spp->id;
        $this->siswa_id = $spp->siswa_id;
        $this->tahun_ajaran_id = $spp->tahun_ajaran_id;
        $this->nominal = $spp->nominal;
        $this->bulan = $spp->bulan;
        $this->tahun = $spp->tahun;
        $this->tanggal_bayar = $spp->tanggal_bayar?->format('Y-m-d');
        $this->status = $spp->status;
        $this->keterangan = $spp->keterangan;
        
        $this->formType = 'edit';
        $this->showForm = true;
    }

    public function resetForm()
    {
        $this->reset([
            'sppId', 'siswa_id', 'tahun_ajaran_id', 'nominal', 'bulan', 
            'tahun', 'tanggal_bayar', 'status', 'keterangan'
        ]);
        $this->resetErrorBag();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'tahunAjaranFilter', 'statusFilter']);
        $this->resetPage();
    }

    public function saveSpp()
    {
        if ($this->formType === 'edit') {
            $this->rules['siswa_id'] = 'required|exists:siswas,id';
        }

        // Auto set tanggal_bayar jika status lunas
        if ($this->status === 'lunas' && empty($this->tanggal_bayar)) {
            $this->tanggal_bayar = now()->format('Y-m-d');
        }

        // Auto clear tanggal_bayar jika status bukan lunas
        if ($this->status !== 'lunas') {
            $this->tanggal_bayar = null;
        }

        $validatedData = $this->validate();

        try {
            DB::transaction(function () use ($validatedData) {
                if ($this->formType === 'create') {
                    Spp::create($validatedData);
                    session()->flash('success', 'Data SPP berhasil ditambahkan.');
                } else {
                    $spp = Spp::findOrFail($this->sppId);
                    $spp->update($validatedData);
                    session()->flash('success', 'Data SPP berhasil diperbarui.');
                }
            });

            $this->closeForm();
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteSpp($id)
    {
        try {
            $spp = Spp::findOrFail($id);
            $spp->delete();
            
            session()->flash('success', 'Data SPP berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function markAsPaid($id)
    {
        try {
            $spp = Spp::findOrFail($id);
            $spp->update([
                'status' => 'lunas',
                'tanggal_bayar' => now()
            ]);
            
            session()->flash('success', 'SPP berhasil ditandai sebagai lunas.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function generateSpp()
    {
        $this->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'nominal' => 'required|numeric|min:0',
            'tahun' => 'required|numeric|min:2020|max:2030'
        ]);

        try {
            DB::transaction(function () {
                $siswas = Siswa::where('status', 'aktif')->get();
                $existingSpp = Spp::where('tahun_ajaran_id', $this->tahun_ajaran_id)
                    ->where('tahun', $this->tahun)
                    ->pluck('bulan')
                    ->toArray();

                $bulanToGenerate = array_diff($this->bulanList, $existingSpp);

                foreach ($siswas as $siswa) {
                    foreach ($bulanToGenerate as $bulan) {
                        Spp::create([
                            'siswa_id' => $siswa->id,
                            'tahun_ajaran_id' => $this->tahun_ajaran_id,
                            'nominal' => $this->nominal,
                            'bulan' => $bulan,
                            'tahun' => $this->tahun,
                            'status' => 'belum_bayar',
                            'keterangan' => 'Generated automatically'
                        ]);
                    }
                }
            });

            session()->flash('success', 'SPP berhasil digenerate untuk semua siswa aktif.');
            $this->reset(['tahun_ajaran_id', 'nominal']);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getTotalNominalProperty()
    {
        return Spp::where('status', 'lunas')->sum('nominal');
    }

    public function getTotalTunggakanProperty()
    {
        return Spp::whereIn('status', ['belum_bayar', 'tertunggak'])->sum('nominal');
    }

    public function render()
{
    $query = Spp::with(['siswa', 'tahunAjaran'])
        ->when($this->search, function ($query) {
            $query->whereHas('siswa', function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('nis', 'like', '%' . $this->search . '%')
                  ->orWhere('nisn', 'like', '%' . $this->search . '%');
            });
        })
        ->when($this->tahunAjaranFilter, function ($query) {
            $query->where('tahun_ajaran_id', $this->tahunAjaranFilter);
        })
        ->when($this->statusFilter, function ($query) {
            $query->where('status', $this->statusFilter);
        })
        ->orderBy('tahun', 'desc')
        ->orderByRaw("FIELD(bulan, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
        ->orderBy('siswa_id');

    $sppList = $query->paginate($this->perPage);

    return view('livewire.admin.manajemen-spp', [
        'sppList' => $sppList,
        'siswas' => Siswa::where('status', 'aktif')->orderBy('nama')->get(),
        // PERBAIKAN: Gunakan tahun_awal atau tahun_akhir untuk sorting
        'tahunAjarans' => TahunAjaran::orderBy('tahun_awal', 'desc')->get(),
        'totalNominal' => $this->totalNominal,
        'totalTunggakan' => $this->totalTunggakan,
    ]);
}
}