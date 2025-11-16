<?php

namespace App\Livewire\Admin;

use App\Models\KategoriBiaya as ModelsKategoriBiaya;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app-new')]
class KategoriBiaya extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $jenisFilter = '';
    public $periodeFilter = '';
    public $statusFilter = '';

    // Form properties
    public $showForm = false;
    public $formType = 'create';
    public $kategoriId = null;

    public $nama_kategori;
    public $deskripsi;
    public $jenis = 'spp';
    public $jumlah_biaya;
    public $periode = 'bulanan';
    public $status = true;

    public $jenisList = [
        'spp' => 'SPP',
        'dana_siswa' => 'Dana Siswa', 
        'lainnya' => 'Lainnya'
    ];

    public $periodeList = [
        'bulanan' => 'Bulanan',
        'semester' => 'Semester',
        'tahunan' => 'Tahunan'
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'jenisFilter' => ['except' => ''],
        'periodeFilter' => ['except' => ''],
        'statusFilter' => ['except' => '']
    ];

    protected $rules = [
        'nama_kategori' => 'required|string|max:255|unique:kategori_biaya,nama_kategori',
        'deskripsi' => 'nullable|string|max:500',
        'jenis' => 'required|in:spp,dana_siswa,lainnya',
        'jumlah_biaya' => 'required|numeric|min:0',
        'periode' => 'required|in:bulanan,semester,tahunan',
        'status' => 'required|boolean',
    ];

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
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

    public function updatedJenisFilter()
    {
        $this->resetPage();
    }

    public function updatedPeriodeFilter()
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
        $kategori = ModelsKategoriBiaya::findOrFail($id);
        
        $this->kategoriId = $kategori->id;
        $this->nama_kategori = $kategori->nama_kategori;
        $this->deskripsi = $kategori->deskripsi;
        $this->jenis = $kategori->jenis;
        $this->jumlah_biaya = $kategori->jumlah_biaya;
        $this->periode = $kategori->periode;
        $this->status = (bool) $kategori->status;
        
        $this->formType = 'edit';
        $this->showForm = true;
    }

    public function resetForm()
    {
        $this->reset([
            'kategoriId', 'nama_kategori', 'deskripsi', 'jenis', 
            'jumlah_biaya', 'periode', 'status'
        ]);
        $this->resetErrorBag();
        $this->jenis = 'spp';
        $this->periode = 'bulanan';
        $this->status = true;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'jenisFilter', 'periodeFilter', 'statusFilter']);
        $this->resetPage();
    }

    public function saveKategori()
    {
        if ($this->formType === 'edit') {
            $this->rules['nama_kategori'] = 'required|string|max:255|unique:kategori_biaya,nama_kategori,' . $this->kategoriId;
        }

        $validatedData = $this->validate();

        try {
            DB::transaction(function () use ($validatedData) {
                if ($this->formType === 'create') {
                    ModelsKategoriBiaya::create($validatedData);
                    session()->flash('success', 'Data kategori biaya berhasil ditambahkan.');
                } else {
                    $kategori = ModelsKategoriBiaya::findOrFail($this->kategoriId);
                    $kategori->update($validatedData);
                    session()->flash('success', 'Data kategori biaya berhasil diperbarui.');
                }
            });

            $this->closeForm();
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteKategori($id)
    {
        try {
            $kategori = ModelsKategoriBiaya::findOrFail($id);
            
            // Cek apakah kategori digunakan di biaya_spp
            if ($kategori->biayaSpp()->exists()) {
                session()->flash('error', 'Tidak dapat menghapus kategori yang masih digunakan dalam data biaya SPP.');
                return;
            }

            $kategori->delete();
            session()->flash('success', 'Data kategori biaya berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $kategori = ModelsKategoriBiaya::findOrFail($id);
            $kategori->update([
                'status' => !$kategori->status
            ]);
            
            $status = $kategori->status ? 'diaktifkan' : 'dinonaktifkan';
            session()->flash('success', "Kategori biaya berhasil {$status}.");
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Computed properties untuk statistik
    public function getTotalKategoriProperty()
    {
        return ModelsKategoriBiaya::count();
    }

    public function getTotalAktifProperty()
    {
        return ModelsKategoriBiaya::where('status', 1)->count();
    }

    public function getTotalNonAktifProperty()
    {
        return ModelsKategoriBiaya::where('status', 0)->count();
    }

    public function getTotalSppProperty()
    {
        return ModelsKategoriBiaya::where('jenis', 'spp')->count();
    }

    public function render()
    {
        $query = ModelsKategoriBiaya::query()
            ->when($this->search, function ($query) {
                $query->where('nama_kategori', 'like', '%' . $this->search . '%')
                      ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            })
            ->when($this->jenisFilter, function ($query) {
                $query->where('jenis', $this->jenisFilter);
            })
            ->when($this->periodeFilter, function ($query) {
                $query->where('periode', $this->periodeFilter);
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('jenis')
            ->orderBy('nama_kategori');

        $kategoriList = $query->paginate($this->perPage);

        return view('livewire.admin.kategori-biaya', [
            'kategoriList' => $kategoriList,
            'totalKategori' => $this->totalKategori,
            'totalAktif' => $this->totalAktif,
            'totalNonAktif' => $this->totalNonAktif,
            'totalSpp' => $this->totalSpp,
        ]);
    }
}