<?php

namespace App\Livewire\Admin;

use App\Models\KegiatanEkskul;
use App\Models\Ekstrakurikuler;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\TemporaryUploadedFile; // Tambahkan import ini

#[Layout('layouts.app-new')]
class KegiatanEkskulPage extends Component
{
    use WithPagination, WithFileUploads;

    // Properties
    public $search = '';
    public $ekskulFilter = '';
    public $statusFilter = '';
    public $perPage = 10;
    public $showForm = false;
    public $formType = 'create';

    // Form properties
    public $kegiatanId;
    public $ekstrakurikuler_id;
    public $nama_kegiatan;
    public $deskripsi;
    public $tanggal_kegiatan;
    public $waktu_mulai;
    public $waktu_selesai;
    public $tempat;
    public $pembina;
    public $hasil_kegiatan;
    public $dokumentasi;
    public $status = 'terlaksana';
    public $existing_dokumentasi;

    // Data lists
    public $ekskulList;
    public $statusList = ['terlaksana', 'dibatalkan', 'ditunda'];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->ekskulList = Ekstrakurikuler::all();
    }

    protected function rules()
    {
        return [
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikulers,id',
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_kegiatan' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'tempat' => 'required|string|max:255',
            'pembina' => 'required|string|max:255',
            'hasil_kegiatan' => 'nullable|string',
            'status' => 'required|in:' . implode(',', $this->statusList),
            'dokumentasi' => 'nullable|image|max:2048',
        ];
    }

    public function render()
    {
        $query = KegiatanEkskul::with(['ekstrakurikuler'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_kegiatan', 'like', '%' . $this->search . '%')
                      ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                      ->orWhere('tempat', 'like', '%' . $this->search . '%')
                      ->orWhere('pembina', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->ekskulFilter, fn($query) => $query->where('ekstrakurikuler_id', $this->ekskulFilter))
            ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
            ->orderBy('tanggal_kegiatan', 'desc')
            ->orderBy('waktu_mulai');

        $kegiatans = $query->paginate($this->perPage);

        return view('livewire.admin.kegiatan-ekskul', compact('kegiatans'));
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->formType = 'create';
        $this->showForm = true;
    }

    public function openEditForm($id)
    {
        $kegiatan = KegiatanEkskul::findOrFail($id);

        $this->kegiatanId = $kegiatan->id;
        $this->ekstrakurikuler_id = $kegiatan->ekstrakurikuler_id;
        $this->nama_kegiatan = $kegiatan->nama_kegiatan;
        $this->deskripsi = $kegiatan->deskripsi;
        $this->tanggal_kegiatan = $kegiatan->tanggal_kegiatan;
        $this->waktu_mulai = substr($kegiatan->waktu_mulai, 0, 5);
        $this->waktu_selesai = substr($kegiatan->waktu_selesai, 0, 5);
        $this->tempat = $kegiatan->tempat;
        $this->pembina = $kegiatan->pembina;
        $this->hasil_kegiatan = $kegiatan->hasil_kegiatan;
        $this->existing_dokumentasi = $kegiatan->dokumentasi;
        $this->dokumentasi = null;
        $this->status = $kegiatan->status;

        $this->formType = 'edit';
        $this->showForm = true;
    }

    public function saveKegiatan()
    {
        $this->validate();

        $data = [
            'ekstrakurikuler_id' => $this->ekstrakurikuler_id,
            'nama_kegiatan' => $this->nama_kegiatan,
            'deskripsi' => $this->deskripsi,
            'tanggal_kegiatan' => $this->tanggal_kegiatan,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_selesai' => $this->waktu_selesai,
            'tempat' => $this->tempat,
            'pembina' => $this->pembina,
            'hasil_kegiatan' => $this->hasil_kegiatan,
            'status' => $this->status,
        ];

        // Handle file upload dengan cara yang lebih sederhana
        if ($this->dokumentasi) {
            // Hapus gambar lama jika ada
            if ($this->existing_dokumentasi && Storage::disk('public')->exists($this->existing_dokumentasi)) {
                Storage::disk('public')->delete($this->existing_dokumentasi);
            }
            
            // Simpan gambar baru
            $data['dokumentasi'] = $this->dokumentasi->store('kegiatan-ekskul', 'public');
        } else {
            // Gunakan gambar lama jika tidak ada gambar baru diupload
            $data['dokumentasi'] = $this->existing_dokumentasi;
        }

        if ($this->formType === 'create') {
            KegiatanEkskul::create($data);
            $message = 'Kegiatan berhasil ditambahkan!';
        } else {
            KegiatanEkskul::findOrFail($this->kegiatanId)->update($data);
            $message = 'Kegiatan berhasil diperbarui!';
        }

        $this->closeForm();
        session()->flash('success', $message);
    }

    public function deleteKegiatan($id)
    {
        try {
            $kegiatan = KegiatanEkskul::findOrFail($id);
            
            // Hapus gambar jika ada
            if ($kegiatan->dokumentasi && Storage::disk('public')->exists($kegiatan->dokumentasi)) {
                Storage::disk('public')->delete($kegiatan->dokumentasi);
            }
            
            $kegiatan->delete();

            session()->flash('success', 'Kegiatan berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus kegiatan: ' . $e->getMessage());
        }
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'kegiatanId',
            'ekstrakurikuler_id',
            'nama_kegiatan',
            'deskripsi',
            'tanggal_kegiatan',
            'waktu_mulai',
            'waktu_selesai',
            'tempat',
            'pembina',
            'hasil_kegiatan',
            'dokumentasi',
            'existing_dokumentasi',
            'status',
        ]);
        $this->resetErrorBag();
    }
}