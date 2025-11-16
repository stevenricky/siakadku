<?php

namespace App\Livewire\Admin;

use App\Models\CatatanBk;
use App\Models\Siswa;
use App\Models\Guru;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class CatatanBkPage extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $catatanId;
    public $siswa_id;
    public $guru_id;
    // Hapus layanan_bk_id
    public $tanggal_catatan;
    public $jenis_catatan;
    public $deskripsi;
    public $tingkat_keparahan = 'ringan';
    public $tindak_lanjut;
    public $status_selesai = false;
    public $showModal = false;
    public $modalTitle = 'Tambah Catatan BK';
    public $showDetailModal = false;
    public $selectedCatatan = null;

    // Options
    public $jenisCatatanOptions = [
        'kasus' => 'Kasus',
        'perkembangan' => 'Perkembangan', 
        'khusus' => 'Khusus'
    ];

    public $tingkatKeparahanOptions = [
        'ringan' => 'Ringan',
        'sedang' => 'Sedang',
        'berat' => 'Berat'
    ];

    protected $rules = [
        'siswa_id' => 'required|exists:siswas,id',
        'guru_id' => 'required|exists:gurus,id',
        // Hapus validasi untuk layanan_bk_id
        'tanggal_catatan' => 'required|date',
        'jenis_catatan' => 'required|in:kasus,perkembangan,khusus',
        'deskripsi' => 'required|string',
        'tingkat_keparahan' => 'required|in:ringan,sedang,berat',
        'tindak_lanjut' => 'nullable|string',
        'status_selesai' => 'boolean'
    ];

    public function mount()
    {
        $this->showModal = false;
        $this->showDetailModal = false;
        $this->tanggal_catatan = now()->format('Y-m-d');
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function render()
    {
        $catatanBk = CatatanBk::with(['siswa', 'guruBk']) // Hapus layananBk dari with
            ->when($this->search, function ($query) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                })
                ->orWhere('jenis_catatan', 'like', '%' . $this->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            })
            ->latest('tanggal_catatan')
            ->paginate($this->perPage);

        // Hitung statistik
        $kasusRingan = CatatanBk::where('tingkat_keparahan', 'ringan')->count();
        $kasusSedang = CatatanBk::where('tingkat_keparahan', 'sedang')->count();
        $kasusBerat = CatatanBk::where('tingkat_keparahan', 'berat')->count();

        $siswa = Siswa::where('status', 'aktif')->get();
        $guruBk = Guru::all();
        // Hapus $layananBk karena tidak digunakan

        return view('livewire.admin.catatan-bk', [
            'catatanBk' => $catatanBk,
            'kasusRingan' => $kasusRingan,
            'kasusSedang' => $kasusSedang,
            'kasusBerat' => $kasusBerat,
            'siswa' => $siswa,
            'guruBk' => $guruBk,
            // Hapus layananBk dari return
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->modalTitle = 'Tambah Catatan BK';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $catatan = CatatanBk::findOrFail($id);
        $this->catatanId = $id;
        $this->siswa_id = $catatan->siswa_id;
        $this->guru_id = $catatan->guru_id;
        // Hapus layanan_bk_id
        $this->tanggal_catatan = $catatan->tanggal_catatan->format('Y-m-d');
        $this->jenis_catatan = $catatan->jenis_catatan;
        $this->deskripsi = $catatan->deskripsi;
        $this->tingkat_keparahan = $catatan->tingkat_keparahan;
        $this->tindak_lanjut = $catatan->tindak_lanjut;
        $this->status_selesai = $catatan->status_selesai;
        $this->modalTitle = 'Edit Catatan BK';
        $this->showModal = true;
    }

    public function showDetail($id)
    {
        $this->selectedCatatan = CatatanBk::with(['siswa', 'guruBk'])->findOrFail($id); // Hapus layananBk
        $this->showDetailModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'siswa_id' => $this->siswa_id,
            'guru_id' => $this->guru_id,
            // Hapus layanan_bk_id
            'tanggal_catatan' => $this->tanggal_catatan,
            'jenis_catatan' => $this->jenis_catatan,
            'deskripsi' => $this->deskripsi,
            'tingkat_keparahan' => $this->tingkat_keparahan,
            'tindak_lanjut' => $this->tindak_lanjut,
            'status_selesai' => $this->status_selesai
        ];

        if ($this->catatanId) {
            $catatan = CatatanBk::findOrFail($this->catatanId);
            $catatan->update($data);
            session()->flash('success', 'Catatan BK berhasil diupdate.');
        } else {
            CatatanBk::create($data);
            session()->flash('success', 'Catatan BK berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function updateStatus($id)
    {
        $catatan = CatatanBk::findOrFail($id);
        $catatan->update(['status_selesai' => !$catatan->status_selesai]);
        
        session()->flash('success', 'Status catatan berhasil diupdate.');
    }

    public function delete($id)
    {
        $catatan = CatatanBk::findOrFail($id);
        $catatan->delete();
        
        session()->flash('success', 'Catatan BK berhasil dihapus.');
    }

    private function resetForm()
    {
        $this->reset([
            'catatanId',
            'siswa_id',
            'guru_id',
            // Hapus layanan_bk_id
            'tanggal_catatan',
            'jenis_catatan',
            'deskripsi',
            'tingkat_keparahan',
            'tindak_lanjut',
            'status_selesai'
        ]);
        $this->resetErrorBag();
        $this->tanggal_catatan = now()->format('Y-m-d');
        $this->tingkat_keparahan = 'ringan';
    }
}