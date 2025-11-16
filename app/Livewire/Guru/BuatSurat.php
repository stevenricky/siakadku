<?php

namespace App\Livewire\Guru;

use App\Models\Surat;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app-new')]
class BuatSurat extends Component
{
    use WithPagination, WithFileUploads;

    // Properties untuk form
    public $jenis_surat = '';
    public $nomor_surat = '';
    public $perihal = '';
    public $isi_singkat = '';
    public $pengirim = '';
    public $penerima = '';
    public $tanggal_surat = '';
    public $tanggal_terima = '';
    public $file_surat;
    public $disposisi_ke = '';
    public $catatan_disposisi = '';
    public $status = 'baru';

    // Properties untuk list & filter
    public $search = '';
    public $jenisFilter = '';
    public $statusFilter = '';
    public $perPage = 10;

    // Properties untuk edit
    public $editingSurat = null;
    public $existing_file = '';

    // Properties untuk detail
    public $viewingSurat = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'jenisFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    protected $rules = [
        'jenis_surat' => 'required|in:masuk,keluar',
        'nomor_surat' => 'required|string|max:100|unique:surat,nomor_surat',
        'perihal' => 'required|string|max:255',
        'isi_singkat' => 'required|string',
        'pengirim' => 'required|string|max:255',
        'penerima' => 'nullable|string|max:255',
        'tanggal_surat' => 'required|date',
        'tanggal_terima' => 'nullable|date|after_or_equal:tanggal_surat',
        'file_surat' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        'disposisi_ke' => 'nullable|string',
        'catatan_disposisi' => 'nullable|string',
        'status' => 'required|in:baru,diproses,selesai,arsip'
    ];

    public function mount()
    {
        $this->tanggal_surat = now()->format('Y-m-d');
        $this->pengirim = auth()->user()->name;
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'jenisFilter', 'statusFilter'])) {
            $this->resetPage();
        } else {
            $this->validateOnly($propertyName);
        }
    }

    // Simpan surat baru
    public function simpanSurat()
    {
        $this->validate();

        try {
            $filePath = null;
            if ($this->file_surat) {
                $filePath = $this->file_surat->store('surat', 'public');
            }

            Surat::create([
                'jenis_surat' => $this->jenis_surat,
                'nomor_surat' => $this->nomor_surat,
                'perihal' => $this->perihal,
                'isi_singkat' => $this->isi_singkat,
                'pengirim' => $this->pengirim,
                'penerima' => $this->penerima,
                'tanggal_surat' => $this->tanggal_surat,
                'tanggal_terima' => $this->tanggal_terima,
                'file_surat' => $filePath,
                'disposisi_ke' => $this->disposisi_ke,
                'catatan_disposisi' => $this->catatan_disposisi,
                'status' => $this->status,
                'created_by' => auth()->id(),
            ]);

            $this->resetForm();
            session()->flash('success', 'Surat berhasil dibuat!');

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat surat: ' . $e->getMessage());
        }
    }

    // Edit surat
    public function editSurat($id)
    {
        $this->editingSurat = Surat::where('created_by', auth()->id())->findOrFail($id);
        
        $this->jenis_surat = $this->editingSurat->jenis_surat;
        $this->nomor_surat = $this->editingSurat->nomor_surat;
        $this->perihal = $this->editingSurat->perihal;
        $this->isi_singkat = $this->editingSurat->isi_singkat;
        $this->pengirim = $this->editingSurat->pengirim;
        $this->penerima = $this->editingSurat->penerima;
        $this->tanggal_surat = $this->editingSurat->tanggal_surat->format('Y-m-d');
        $this->tanggal_terima = $this->editingSurat->tanggal_terima?->format('Y-m-d');
        $this->existing_file = $this->editingSurat->file_surat;
        $this->disposisi_ke = $this->editingSurat->disposisi_ke;
        $this->catatan_disposisi = $this->editingSurat->catatan_disposisi;
        $this->status = $this->editingSurat->status;

        // Update rule untuk unique nomor_surat
        $this->rules['nomor_surat'] .= $this->editingSurat->id;
    }

    // Update surat
    public function updateSurat()
    {
        $this->validate();

        try {
            $filePath = $this->existing_file;
            
            if ($this->file_surat) {
                if ($this->existing_file && Storage::disk('public')->exists($this->existing_file)) {
                    Storage::disk('public')->delete($this->existing_file);
                }
                $filePath = $this->file_surat->store('surat', 'public');
            }

            $this->editingSurat->update([
                'jenis_surat' => $this->jenis_surat,
                'nomor_surat' => $this->nomor_surat,
                'perihal' => $this->perihal,
                'isi_singkat' => $this->isi_singkat,
                'pengirim' => $this->pengirim,
                'penerima' => $this->penerima,
                'tanggal_surat' => $this->tanggal_surat,
                'tanggal_terima' => $this->tanggal_terima,
                'file_surat' => $filePath,
                'disposisi_ke' => $this->disposisi_ke,
                'catatan_disposisi' => $this->catatan_disposisi,
                'status' => $this->status,
            ]);

            $this->cancelEdit();
            session()->flash('success', 'Surat berhasil diperbarui!');

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui surat: ' . $e->getMessage());
        }
    }

    // View detail surat
    public function viewSurat($id)
    {
        $this->viewingSurat = Surat::with('createdBy')
            ->where('created_by', auth()->id())
            ->findOrFail($id);
    }

    // Hapus surat
    public function deleteSurat($id)
    {
        $surat = Surat::where('created_by', auth()->id())->findOrFail($id);
        
        if ($surat->file_surat && Storage::disk('public')->exists($surat->file_surat)) {
            Storage::disk('public')->delete($surat->file_surat);
        }
        
        $surat->delete();
        session()->flash('success', 'Surat berhasil dihapus!');
    }

    // Reset form
    public function resetForm()
    {
        $this->reset([
            'jenis_surat', 'nomor_surat', 'perihal', 'isi_singkat', 
            'pengirim', 'penerima', 'tanggal_surat', 'tanggal_terima',
            'file_surat', 'disposisi_ke', 'catatan_disposisi', 'status'
        ]);
        $this->tanggal_surat = now()->format('Y-m-d');
        $this->pengirim = auth()->user()->name;
        $this->resetValidation();
    }

    // Cancel edit
    public function cancelEdit()
    {
        $this->editingSurat = null;
        $this->resetForm();
        $this->rules['nomor_surat'] = 'required|string|max:100|unique:surat,nomor_surat';
    }

    // Close view
    public function closeView()
    {
        $this->viewingSurat = null;
    }

    public function render()
    {
        $query = Surat::with('createdBy')
            ->where('created_by', auth()->id())
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nomor_surat', 'like', '%' . $this->search . '%')
                      ->orWhere('perihal', 'like', '%' . $this->search . '%')
                      ->orWhere('pengirim', 'like', '%' . $this->search . '%')
                      ->orWhere('penerima', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->jenisFilter, function ($query) {
                $query->where('jenis_surat', $this->jenisFilter);
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc');

        $surat = $query->paginate($this->perPage);

        $stats = [
            'total' => Surat::where('created_by', auth()->id())->count(),
            'masuk' => Surat::where('created_by', auth()->id())->masuk()->count(),
            'keluar' => Surat::where('created_by', auth()->id())->keluar()->count(),
            'baru' => Surat::where('created_by', auth()->id())->status('baru')->count(),
        ];

        return view('livewire.guru.buat-surat', compact('surat', 'stats'));
    }
}