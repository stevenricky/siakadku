<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\PendaftaranEkskul;
use App\Models\Ekstrakurikuler;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;

#[Layout('layouts.app-new')]
class PendaftaranEkskulPage extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $ekskulFilter = '';
    public $statusFilter = '';

    // Form properties
    public $showForm = false;
    public $showTolakModal = false;
    public $formType = 'create';
    public $pendaftaranId = null;
    public $tolakId = null;
    public $tolakAlasan = '';
    
    public $siswa_id;
    public $ekstrakurikuler_id;
    public $tahun_ajaran;
    public $status_pendaftaran = 'pending';
    public $alasan_ditolak;

    public $siswaList;
    public $ekskulList;
    public $statusList = ['pending', 'diterima', 'ditolak'];

    protected $queryString = ['search', 'perPage', 'ekskulFilter', 'statusFilter'];

    protected function rules()
    {
        return [
            'siswa_id' => 'required|exists:siswas,id',
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikulers,id',
            'tahun_ajaran' => 'required|integer|min:2020|max:2030',
            'status_pendaftaran' => 'required|in:pending,diterima,ditolak',
            'alasan_ditolak' => 'nullable|string|required_if:status_pendaftaran,ditolak',
        ];
    }

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
        $this->tahun_ajaran = date('Y');
        $this->loadData();
    }

    public function loadData()
    {
        $this->siswaList = Siswa::with(['kelas'])
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $this->ekskulList = Ekstrakurikuler::where('status', 1)
            ->orderBy('nama_ekstra')
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
        $pendaftaran = PendaftaranEkskul::findOrFail($id);
        
        $this->pendaftaranId = $pendaftaran->id;
        $this->siswa_id = $pendaftaran->siswa_id;
        $this->ekstrakurikuler_id = $pendaftaran->ekstrakurikuler_id;
        $this->tahun_ajaran = $pendaftaran->tahun_ajaran;
        $this->status_pendaftaran = $pendaftaran->status_pendaftaran;
        $this->alasan_ditolak = $pendaftaran->alasan_ditolak;
        
        $this->formType = 'edit';
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'pendaftaranId', 'siswa_id', 'ekstrakurikuler_id', 
            'tahun_ajaran', 'status_pendaftaran', 'alasan_ditolak'
        ]);
        $this->resetErrorBag();
        $this->tahun_ajaran = date('Y');
        $this->status_pendaftaran = 'pending';
    }

    public function savePendaftaran()
    {
        $validatedData = $this->validate($this->rules());

        try {
            if ($this->formType === 'create') {
                $existing = PendaftaranEkskul::where('siswa_id', $validatedData['siswa_id'])
                    ->where('ekstrakurikuler_id', $validatedData['ekstrakurikuler_id'])
                    ->where('tahun_ajaran', $validatedData['tahun_ajaran'])
                    ->exists();

                if ($existing) {
                    $this->addError('form', 'Siswa sudah terdaftar di ekstrakurikuler ini untuk tahun ajaran ' . $validatedData['tahun_ajaran']);
                    return;
                }

                PendaftaranEkskul::create($validatedData);
                session()->flash('success', 'Pendaftaran ekstrakurikuler berhasil ditambahkan.');
            } else {
                $pendaftaran = PendaftaranEkskul::find($this->pendaftaranId);
                
                if (in_array($validatedData['status_pendaftaran'], ['diterima', 'ditolak']) && 
                    $pendaftaran->status_pendaftaran === 'pending') {
                    $validatedData['disetujui_oleh'] = auth()->id();
                    $validatedData['disetujui_pada'] = now();
                }

                $pendaftaran->update($validatedData);
                session()->flash('success', 'Data pendaftaran berhasil diperbarui.');
            }

            $this->closeForm();
            $this->dispatch('pg:eventRefresh-default');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateStatus($id, $status, $alasan = null)
    {
        try {
            $pendaftaran = PendaftaranEkskul::findOrFail($id);
            
            $updateData = ['status_pendaftaran' => $status];
            
            if ($status === 'ditolak') {
                $updateData['alasan_ditolak'] = $alasan;
            } else {
                $updateData['alasan_ditolak'] = null;
            }

            if (in_array($status, ['diterima', 'ditolak']) && $pendaftaran->status_pendaftaran === 'pending') {
                $updateData['disetujui_oleh'] = auth()->id();
                $updateData['disetujui_pada'] = now();
            }

            $pendaftaran->update($updateData);
            session()->flash('success', 'Status pendaftaran berhasil diperbarui.');
            $this->dispatch('pg:eventRefresh-default');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deletePendaftaran($id)
    {
        try {
            $pendaftaran = PendaftaranEkskul::findOrFail($id);
            $pendaftaran->delete();
            session()->flash('success', 'Data pendaftaran berhasil dihapus.');
            $this->dispatch('pg:eventRefresh-default');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function terimaPendaftaran($id)
    {
        $this->updateStatus($id, 'diterima');
    }

    public function openTolakModal($id)
    {
        $this->tolakId = $id;
        $this->tolakAlasan = '';
        $this->showTolakModal = true;
    }

    public function closeTolakModal()
    {
        $this->showTolakModal = false;
        $this->tolakId = null;
        $this->tolakAlasan = '';
    }

    public function confirmTolak()
    {
        if (empty($this->tolakAlasan)) {
            session()->flash('error', 'Alasan penolakan tidak boleh kosong.');
            return;
        }

        $this->updateStatus($this->tolakId, 'ditolak', $this->tolakAlasan);
        $this->closeTolakModal();
    }

    public function render()
    {
        $query = PendaftaranEkskul::with([
                'siswa.kelas', 
                'ekstrakurikuler', 
                'disetujuiOleh'
            ])
            ->when($this->search, function ($query) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('nis', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->ekskulFilter, function ($query) {
                $query->where('ekstrakurikuler_id', $this->ekskulFilter);
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status_pendaftaran', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc');

        $pendaftaran = $query->paginate($this->perPage);

        $pendingCount = PendaftaranEkskul::where('status_pendaftaran', 'pending')->count();
        $diterimaCount = PendaftaranEkskul::where('status_pendaftaran', 'diterima')->count();
        $ditolakCount = PendaftaranEkskul::where('status_pendaftaran', 'ditolak')->count();

        return view('livewire.admin.pendaftaran-ekskul', [
            'pendaftaran' => $pendaftaran,
            'pendingCount' => $pendingCount,
            'diterimaCount' => $diterimaCount,
            'ditolakCount' => $ditolakCount,
        ]);
    }
}