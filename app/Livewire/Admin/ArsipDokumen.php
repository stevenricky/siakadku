<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Arsip;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app-new')]
class ArsipDokumen extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;
    public $arsipId;
    public $nama_dokumen;
    public $kategori_arsip;
    public $deskripsi;
    public $nomor_dokumen;
    public $tanggal_dokumen;
    public $file_dokumen;
    public $akses;
    public $tahun_arsip;
    public $lokasi_fisik;
    public $keterangan;
    public $showModal = false;
    public $modalTitle = 'Unggah Dokumen';
    public $showDetailModal = false;
    public $selectedArsip = null;

    // Options
    public $kategoriOptions = [
        'akademik' => 'Akademik',
        'kesiswaan' => 'Kesiswaan',
        'administrasi' => 'Administrasi',
        'keuangan' => 'Keuangan',
        'laporan' => 'Laporan',
        'sarana_prasarana' => 'Sarana & Prasarana',
        'lainnya' => 'Lainnya'
    ];

    public $aksesOptions = [
        'publik' => 'Publik',
        'terbatas' => 'Terbatas',
        'rahasia' => 'Rahasia'
    ];

    public $tahunOptions = [];

    protected function rules()
    {
        $rules = [
            'nama_dokumen' => 'required|string|max:200',
            'kategori_arsip' => 'required|string',
            'deskripsi' => 'nullable|string',
            'nomor_dokumen' => 'nullable|string|max:100',
            'tanggal_dokumen' => 'required|date',
            'akses' => 'required|in:publik,terbatas,rahasia',
            'tahun_arsip' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'lokasi_fisik' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string'
        ];

        // File hanya required saat create, optional saat edit
        if (!$this->arsipId) {
            $rules['file_dokumen'] = 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240';
        } else {
            $rules['file_dokumen'] = 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240';
        }

        return $rules;
    }

    public function mount()
    {
        $this->showModal = false;
        $this->showDetailModal = false;
        $this->tanggal_dokumen = now()->format('Y-m-d');
        $this->tahun_arsip = now()->year;
        $this->akses = 'publik';

        // Generate tahun options (10 tahun terakhir dan 2 tahun ke depan)
        $currentYear = now()->year;
        for ($year = $currentYear + 2; $year >= $currentYear - 8; $year--) {
            $this->tahunOptions[$year] = $year;
        }
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function render()
    {
        $arsip = Arsip::with('createdBy')
            ->when($this->search, function ($query) {
                $query->where('nama_dokumen', 'like', '%' . $this->search . '%')
                      ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                      ->orWhere('nomor_dokumen', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        // Hitung statistik
        $totalDokumen = Arsip::count();
        $totalKategori = Arsip::distinct('kategori_arsip')->count('kategori_arsip');
        $dokumenPublik = Arsip::where('akses', 'publik')->count();
        $dokumenTerbatas = Arsip::where('akses', 'terbatas')->count();

        // Hitung per kategori
        $kategoriCounts = Arsip::selectRaw('kategori_arsip, COUNT(*) as count')
            ->groupBy('kategori_arsip')
            ->get()
            ->pluck('count', 'kategori_arsip');

        return view('livewire.admin.arsip-dokumen', [
            'arsip' => $arsip,
            'totalDokumen' => $totalDokumen,
            'totalKategori' => $totalKategori,
            'dokumenPublik' => $dokumenPublik,
            'dokumenTerbatas' => $dokumenTerbatas,
            'kategoriCounts' => $kategoriCounts
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->modalTitle = 'Unggah Dokumen';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $arsip = Arsip::findOrFail($id);
        $this->arsipId = $id;
        $this->nama_dokumen = $arsip->nama_dokumen;
        $this->kategori_arsip = $arsip->kategori_arsip;
        $this->deskripsi = $arsip->deskripsi;
        $this->nomor_dokumen = $arsip->nomor_dokumen;
        $this->tanggal_dokumen = $arsip->tanggal_dokumen->format('Y-m-d');
        $this->akses = $arsip->akses;
        $this->tahun_arsip = $arsip->tahun_arsip;
        $this->lokasi_fisik = $arsip->lokasi_fisik;
        $this->keterangan = $arsip->keterangan;
        $this->modalTitle = 'Edit Dokumen';
        $this->showModal = true;
    }

    public function showDetail($id)
    {
        $this->selectedArsip = Arsip::with('createdBy')->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function save()
    {
        $this->validate($this->rules());

        $data = [
            'nama_dokumen' => $this->nama_dokumen,
            'kategori_arsip' => $this->kategori_arsip,
            'deskripsi' => $this->deskripsi,
            'nomor_dokumen' => $this->nomor_dokumen,
            'tanggal_dokumen' => $this->tanggal_dokumen,
            'akses' => $this->akses,
            'tahun_arsip' => $this->tahun_arsip,
            'lokasi_fisik' => $this->lokasi_fisik,
            'keterangan' => $this->keterangan,
            'created_by' => auth()->id()
        ];

        // Handle file upload
        if ($this->file_dokumen) {
            // Hapus file lama jika ada (saat edit)
            if ($this->arsipId) {
                $arsip = Arsip::findOrFail($this->arsipId);
                if ($arsip->file_dokumen && Storage::disk('public')->exists($arsip->file_dokumen)) {
                    Storage::disk('public')->delete($arsip->file_dokumen);
                }
            }
            
            $data['file_dokumen'] = $this->file_dokumen->store('arsip', 'public');
        }

        if ($this->arsipId) {
            $arsip = Arsip::findOrFail($this->arsipId);
            $arsip->update($data);
            session()->flash('success', 'Dokumen berhasil diupdate.');
        } else {
            Arsip::create($data);
            session()->flash('success', 'Dokumen berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function delete($id)
    {
        $arsip = Arsip::findOrFail($id);
        
        // Delete file from storage jika ada
        if ($arsip->file_dokumen && Storage::disk('public')->exists($arsip->file_dokumen)) {
            Storage::disk('public')->delete($arsip->file_dokumen);
        }
        
        $arsip->delete();
        
        session()->flash('success', 'Dokumen berhasil dihapus.');
    }

    public function download($id)
    {
        $arsip = Arsip::findOrFail($id);
        
        if (!$arsip->file_exists) {
            session()->flash('error', 'File tidak ditemukan.');
            return;
        }

        return response()->download(
            storage_path('app/public/' . $arsip->file_dokumen),
            $arsip->nama_dokumen . '.' . $arsip->file_extension
        );
    }

    private function resetForm()
    {
        $this->reset([
            'arsipId',
            'nama_dokumen',
            'kategori_arsip',
            'deskripsi',
            'nomor_dokumen',
            'tanggal_dokumen',
            'file_dokumen',
            'akses',
            'tahun_arsip',
            'lokasi_fisik',
            'keterangan'
        ]);
        $this->resetErrorBag();
        $this->tanggal_dokumen = now()->format('Y-m-d');
        $this->tahun_arsip = now()->year;
        $this->akses = 'publik';
    }

    // Helper methods untuk view
    public function getFileIcon($filename)
    {
        if (!$filename) return 'file';
        
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        $icons = [
            'pdf' => 'file-pdf',
            'doc' => 'file-word',
            'docx' => 'file-word',
            'xls' => 'file-excel',
            'xlsx' => 'file-excel',
            'ppt' => 'file-powerpoint',
            'pptx' => 'file-powerpoint',
            'jpg' => 'file-image',
            'jpeg' => 'file-image',
            'png' => 'file-image',
        ];

        return $icons[$extension] ?? 'file';
    }

    public function getFileColor($filename)
    {
        if (!$filename) return 'gray';
        
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        $colors = [
            'pdf' => 'red',
            'doc' => 'blue',
            'docx' => 'blue',
            'xls' => 'green',
            'xlsx' => 'green',
            'ppt' => 'orange',
            'pptx' => 'orange',
            'jpg' => 'purple',
            'jpeg' => 'purple',
            'png' => 'purple',
        ];

        return $colors[$extension] ?? 'gray';
    }
}