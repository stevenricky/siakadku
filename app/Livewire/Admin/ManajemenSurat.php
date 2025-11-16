<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Surat;

#[Layout('layouts.app-new')]
class ManajemenSurat extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;
    public $suratId;
    public $nomor_surat;
    public $jenis_surat;
    public $perihal;
    public $isi_singkat;
    public $pengirim;
    public $penerima;
    public $tanggal_surat;
    public $tanggal_terima;
    public $file_surat;
    public $disposisi_ke;
    public $catatan_disposisi;
    public $status = 'baru';
    public $showModal = false;
    public $modalTitle = 'Tambah Surat';
    public $showDetailModal = false;
    public $selectedSurat = null;

    // Options
    public $jenisSuratOptions = [
        'masuk' => 'Surat Masuk',
        'keluar' => 'Surat Keluar'
    ];

    public $statusOptions = [
        'baru' => 'Baru',
        'diproses' => 'Diproses',
        'selesai' => 'Selesai',
        'arsip' => 'Arsip'
    ];

    public $disposisiOptions = [
        'kepala_sekolah' => 'Kepala Sekolah',
        'wakil_kepala_sekolah' => 'Wakil Kepala Sekolah',
        'guru' => 'Guru',
        'tata_usaha' => 'Tata Usaha',
        'lainnya' => 'Lainnya'
    ];

    protected function rules()
    {
        return [
            'nomor_surat' => 'required|string|max:100|unique:surat,nomor_surat,' . $this->suratId,
            'jenis_surat' => 'required|in:masuk,keluar',
            'perihal' => 'required|string|max:200',
            'isi_singkat' => 'required|string',
            'pengirim' => 'required|string|max:100',
            'penerima' => 'nullable|string|max:100',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'nullable|date',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'disposisi_ke' => 'nullable|string',
            'catatan_disposisi' => 'nullable|string',
            'status' => 'required|in:baru,diproses,selesai,arsip'
        ];
    }

    public function mount()
    {
        $this->showModal = false;
        $this->showDetailModal = false;
        $this->tanggal_surat = now()->format('Y-m-d');
        $this->tanggal_terima = now()->format('Y-m-d');
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function render()
    {
        $surat = Surat::with('createdBy')
            ->when($this->search, function ($query) {
                $query->where('nomor_surat', 'like', '%' . $this->search . '%')
                      ->orWhere('perihal', 'like', '%' . $this->search . '%')
                      ->orWhere('pengirim', 'like', '%' . $this->search . '%')
                      ->orWhere('penerima', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        // Hitung statistik
        $suratMasuk = Surat::where('jenis_surat', 'masuk')->count();
        $suratKeluar = Surat::where('jenis_surat', 'keluar')->count();
        $perluTindakan = Surat::whereIn('status', ['baru', 'diproses'])->count();
        $diarsipkan = Surat::where('status', 'arsip')->count();

        return view('livewire.admin.manajemen-surat', [
            'surat' => $surat,
            'suratMasuk' => $suratMasuk,
            'suratKeluar' => $suratKeluar,
            'perluTindakan' => $perluTindakan,
            'diarsipkan' => $diarsipkan
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->modalTitle = 'Tambah Surat';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $surat = Surat::findOrFail($id);
        $this->suratId = $id;
        $this->nomor_surat = $surat->nomor_surat;
        $this->jenis_surat = $surat->jenis_surat;
        $this->perihal = $surat->perihal;
        $this->isi_singkat = $surat->isi_singkat;
        $this->pengirim = $surat->pengirim;
        $this->penerima = $surat->penerima;
        $this->tanggal_surat = $surat->tanggal_surat->format('Y-m-d');
        $this->tanggal_terima = $surat->tanggal_terima ? $surat->tanggal_terima->format('Y-m-d') : null;
        $this->disposisi_ke = $surat->disposisi_ke;
        $this->catatan_disposisi = $surat->catatan_disposisi;
        $this->status = $surat->status;
        $this->modalTitle = 'Edit Surat';
        $this->showModal = true;
    }

    public function showDetail($id)
    {
        $this->selectedSurat = Surat::with('createdBy')->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function save()
    {
        $this->validate($this->rules());

        $data = [
            'nomor_surat' => $this->nomor_surat,
            'jenis_surat' => $this->jenis_surat,
            'perihal' => $this->perihal,
            'isi_singkat' => $this->isi_singkat,
            'pengirim' => $this->pengirim,
            'penerima' => $this->penerima,
            'tanggal_surat' => $this->tanggal_surat,
            'tanggal_terima' => $this->tanggal_terima,
            'disposisi_ke' => $this->disposisi_ke,
            'catatan_disposisi' => $this->catatan_disposisi,
            'status' => $this->status,
            'created_by' => auth()->id()
        ];

        // Handle file upload
        if ($this->file_surat) {
            $data['file_surat'] = $this->file_surat->store('surat', 'public');
        }

        if ($this->suratId) {
            $surat = Surat::findOrFail($this->suratId);
            $surat->update($data);
            session()->flash('success', 'Surat berhasil diupdate.');
        } else {
            Surat::create($data);
            session()->flash('success', 'Surat berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function updateStatus($id, $status)
    {
        $surat = Surat::findOrFail($id);
        $surat->update(['status' => $status]);
        
        session()->flash('success', 'Status surat berhasil diupdate.');
    }

    public function delete($id)
    {
        $surat = Surat::findOrFail($id);
        $surat->delete();
        
        session()->flash('success', 'Surat berhasil dihapus.');
    }

    public function generateNomorSurat()
    {
        if (!$this->nomor_surat && $this->jenis_surat) {
            $year = now()->format('Y');
            $prefix = $this->jenis_surat == 'masuk' ? 'SM' : 'SK';
            $lastNumber = Surat::whereYear('created_at', $year)
                ->where('jenis_surat', $this->jenis_surat)
                ->count();
            
            $this->nomor_surat = "{$prefix}/" . sprintf('%03d', $lastNumber + 1) . "/SMA/XII/{$year}";
        }
    }

    private function resetForm()
    {
        $this->reset([
            'suratId',
            'nomor_surat',
            'jenis_surat',
            'perihal',
            'isi_singkat',
            'pengirim',
            'penerima',
            'tanggal_surat',
            'tanggal_terima',
            'file_surat',
            'disposisi_ke',
            'catatan_disposisi',
            'status'
        ]);
        $this->resetErrorBag();
        $this->tanggal_surat = now()->format('Y-m-d');
        $this->tanggal_terima = now()->format('Y-m-d');
        $this->status = 'baru';
    }
}