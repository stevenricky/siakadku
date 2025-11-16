<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Prestasi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app-new')]
class PrestasiSiswa extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;
    public $jenisFilter = '';
    public $tingkatFilter = '';

    // Form properties
    public $showForm = false;
    public $formType = 'create';
    public $prestasiId = null;
    
    public $siswa_id;
    public $jenis_prestasi = 'akademik';
    public $tingkat = 'sekolah';
    public $nama_prestasi;
    public $penyelenggara;
    public $tanggal_prestasi;
    public $peringkat;
    public $deskripsi;
    public $sertifikat;
    public $foto;
    public $status = true;

    public $siswaList;
    public $jenisList = ['akademik', 'non-akademik', 'olahraga', 'seni', 'lainnya'];
    public $tingkatList = ['sekolah', 'kecamatan', 'kabupaten', 'provinsi', 'nasional', 'internasional'];
    public $peringkatList = ['Juara 1', 'Juara 2', 'Juara 3', 'Harapan 1', 'Harapan 2', 'Harapan 3', 'Peserta', 'Finalis', 'Lainnya'];

    protected $queryString = ['search', 'perPage', 'jenisFilter', 'tingkatFilter'];

    protected function rules()
    {
        return [
            'siswa_id' => 'required|exists:siswas,id',
            'jenis_prestasi' => 'required|in:akademik,non-akademik,olahraga,seni,lainnya',
            'tingkat' => 'required|in:sekolah,kecamatan,kabupaten,provinsi,nasional,internasional',
            'nama_prestasi' => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'tanggal_prestasi' => 'required|date',
            'peringkat' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'foto' => 'nullable|image|max:2048',
            'status' => 'boolean',
        ];
    }

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
        $this->tanggal_prestasi = now()->format('Y-m-d');
        $this->loadSiswaList();
    }

    public function loadSiswaList()
    {
        $this->siswaList = Siswa::with(['kelas'])
            ->where('status', 'aktif')
            ->orderBy('nama')
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
        $prestasi = Prestasi::findOrFail($id);
        
        $this->prestasiId = $prestasi->id;
        $this->siswa_id = $prestasi->siswa_id;
        $this->jenis_prestasi = $prestasi->jenis_prestasi;
        $this->tingkat = $prestasi->tingkat;
        $this->nama_prestasi = $prestasi->nama_prestasi;
        $this->penyelenggara = $prestasi->penyelenggara;
        $this->tanggal_prestasi = $prestasi->tanggal_prestasi->format('Y-m-d');
        $this->peringkat = $prestasi->peringkat;
        $this->deskripsi = $prestasi->deskripsi;
        $this->status = $prestasi->status;
        
        $this->formType = 'edit';
        $this->showForm = true;
    }

    public function resetForm()
    {
        $this->reset([
            'prestasiId', 'siswa_id', 'jenis_prestasi', 'tingkat', 'nama_prestasi',
            'penyelenggara', 'tanggal_prestasi', 'peringkat', 'deskripsi', 
            'sertifikat', 'foto', 'status'
        ]);
        $this->resetErrorBag();
        $this->tanggal_prestasi = now()->format('Y-m-d');
        $this->jenis_prestasi = 'akademik';
        $this->tingkat = 'sekolah';
        $this->status = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function savePrestasi()
    {
        $validatedData = $this->validate($this->rules());

        try {
            // Handle file uploads
            if ($this->sertifikat) {
                $validatedData['sertifikat'] = $this->sertifikat->store('prestasi/sertifikat', 'public');
            }

            if ($this->foto) {
                $validatedData['foto'] = $this->foto->store('prestasi/foto', 'public');
            }

            if ($this->formType === 'create') {
                Prestasi::create($validatedData);
                session()->flash('success', 'Data prestasi berhasil ditambahkan.');
            } else {
                $prestasi = Prestasi::find($this->prestasiId);
                
                // Delete old files if new files uploaded
                if ($this->sertifikat && $prestasi->sertifikat) {
                    Storage::disk('public')->delete($prestasi->sertifikat);
                }
                if ($this->foto && $prestasi->foto) {
                    Storage::disk('public')->delete($prestasi->foto);
                }

                $prestasi->update($validatedData);
                session()->flash('success', 'Data prestasi berhasil diperbarui.');
            }

            $this->closeForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deletePrestasi($id)
    {
        try {
            $prestasi = Prestasi::findOrFail($id);
            
            // Delete files
            if ($prestasi->sertifikat) {
                Storage::disk('public')->delete($prestasi->sertifikat);
            }
            if ($prestasi->foto) {
                Storage::disk('public')->delete($prestasi->foto);
            }
            
            $prestasi->delete();
            session()->flash('success', 'Data prestasi berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $prestasi = Prestasi::findOrFail($id);
            $prestasi->update(['status' => !$prestasi->status]);
            session()->flash('success', 'Status prestasi berhasil diperbarui.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Prestasi::with(['siswa.kelas'])
            ->when($this->search, function ($query) {
                $query->where('nama_prestasi', 'like', '%' . $this->search . '%')
                      ->orWhere('penyelenggara', 'like', '%' . $this->search . '%')
                      ->orWhereHas('siswa', function ($q) {
                          $q->where('nama', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->jenisFilter, function ($query) {
                $query->where('jenis_prestasi', $this->jenisFilter);
            })
            ->when($this->tingkatFilter, function ($query) {
                $query->where('tingkat', $this->tingkatFilter);
            })
            ->orderBy('tanggal_prestasi', 'desc')
            ->orderBy('created_at', 'desc');

        $prestasi = $query->paginate($this->perPage);

        // Hitung statistik
        $totalPrestasi = Prestasi::count();
        $nasionalCount = Prestasi::where('tingkat', 'nasional')->count();
        $provinsiCount = Prestasi::where('tingkat', 'provinsi')->count();
        $kabupatenCount = Prestasi::where('tingkat', 'kabupaten')->count();

        return view('livewire.admin.prestasi-siswa', [
            'prestasi' => $prestasi,
            'totalPrestasi' => $totalPrestasi,
            'nasionalCount' => $nasionalCount,
            'provinsiCount' => $provinsiCount,
            'kabupatenCount' => $kabupatenCount,
        ]);
    }
}