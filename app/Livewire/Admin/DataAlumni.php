<?php

namespace App\Livewire\Admin;

use App\Models\Alumni;
use App\Models\Siswa;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class DataAlumni extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $alumniId;
    public $siswa_id;
    public $tahun_lulus;
    public $no_ijazah;
    public $status_setelah_lulus = 'kuliah';
    public $instansi;
    public $jurusan_kuliah;
    public $jabatan_pekerjaan;
    public $alamat_instansi;
    public $no_telepon;
    public $email;
    public $prestasi_setelah_lulus;
    public $status_aktif = true;
    public $showModal = false;
    public $modalTitle = 'Tambah Alumni';
    public $showDetailModal = false;
    public $selectedAlumni = null;

    // Status options
    public $statusOptions = [
        'kuliah' => 'Kuliah',
        'kerja' => 'Bekerja',
        'wirausaha' => 'Wirausaha',
        'lainnya' => 'Lainnya'
    ];

    // Pindahkan rules ke method atau gunakan array biasa
    protected function rules()
    {
        return [
            'siswa_id' => 'required|exists:siswas,id',
            'tahun_lulus' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'no_ijazah' => 'nullable|string|max:100',
            'status_setelah_lulus' => 'required|in:kuliah,kerja,wirausaha,lainnya',
            'instansi' => 'nullable|string|max:200',
            'jurusan_kuliah' => 'nullable|string|max:100',
            'jabatan_pekerjaan' => 'nullable|string|max:100',
            'alamat_instansi' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'prestasi_setelah_lulus' => 'nullable|string',
            'status_aktif' => 'boolean'
        ];
    }

    public function mount()
    {
        $this->showModal = false;
        $this->showDetailModal = false;
        $this->tahun_lulus = date('Y');
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function render()
    {
        $alumni = Alumni::with(['siswa'])
            ->when($this->search, function ($query) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                })
                ->orWhere('no_ijazah', 'like', '%' . $this->search . '%')
                ->orWhere('instansi', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest('tahun_lulus')
            ->paginate($this->perPage);

        // Hitung statistik
        $totalAlumni = Alumni::count();
        $kuliah = Alumni::where('status_setelah_lulus', 'kuliah')->count();
        $bekerja = Alumni::where('status_setelah_lulus', 'kerja')->count();
        $wirausaha = Alumni::where('status_setelah_lulus', 'wirausaha')->count();

        $siswa = Siswa::where('status', 'aktif')->get();

        return view('livewire.admin.data-alumni', [
            'alumni' => $alumni,
            'totalAlumni' => $totalAlumni,
            'kuliah' => $kuliah,
            'bekerja' => $bekerja,
            'wirausaha' => $wirausaha,
            'siswa' => $siswa
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->modalTitle = 'Tambah Alumni';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $alumni = Alumni::findOrFail($id);
        $this->alumniId = $id;
        $this->siswa_id = $alumni->siswa_id;
        $this->tahun_lulus = $alumni->tahun_lulus;
        $this->no_ijazah = $alumni->no_ijazah;
        $this->status_setelah_lulus = $alumni->status_setelah_lulus;
        $this->instansi = $alumni->instansi;
        $this->jurusan_kuliah = $alumni->jurusan_kuliah;
        $this->jabatan_pekerjaan = $alumni->jabatan_pekerjaan;
        $this->alamat_instansi = $alumni->alamat_instansi;
        $this->no_telepon = $alumni->no_telepon;
        $this->email = $alumni->email;
        $this->prestasi_setelah_lulus = $alumni->prestasi_setelah_lulus;
        $this->status_aktif = $alumni->status_aktif;
        $this->modalTitle = 'Edit Data Alumni';
        $this->showModal = true;
    }

    public function showDetail($id)
    {
        $this->selectedAlumni = Alumni::with(['siswa'])->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function save()
    {
        $this->validate($this->rules());

        $data = [
            'siswa_id' => $this->siswa_id,
            'tahun_lulus' => $this->tahun_lulus,
            'no_ijazah' => $this->no_ijazah,
            'status_setelah_lulus' => $this->status_setelah_lulus,
            'instansi' => $this->instansi,
            'jurusan_kuliah' => $this->jurusan_kuliah,
            'jabatan_pekerjaan' => $this->jabatan_pekerjaan,
            'alamat_instansi' => $this->alamat_instansi,
            'no_telepon' => $this->no_telepon,
            'email' => $this->email,
            'prestasi_setelah_lulus' => $this->prestasi_setelah_lulus,
            'status_aktif' => $this->status_aktif
        ];

        if ($this->alumniId) {
            $alumni = Alumni::findOrFail($this->alumniId);
            $alumni->update($data);
            session()->flash('success', 'Data alumni berhasil diupdate.');
        } else {
            Alumni::create($data);
            session()->flash('success', 'Data alumni berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function delete($id)
    {
        $alumni = Alumni::findOrFail($id);
        $alumni->delete();
        
        session()->flash('success', 'Data alumni berhasil dihapus.');
    }

    public function updateStatus($id)
    {
        $alumni = Alumni::findOrFail($id);
        $alumni->update(['status_aktif' => !$alumni->status_aktif]);
        
        session()->flash('success', 'Status alumni berhasil diupdate.');
    }

    private function resetForm()
    {
        $this->reset([
            'alumniId',
            'siswa_id',
            'tahun_lulus',
            'no_ijazah',
            'status_setelah_lulus',
            'instansi',
            'jurusan_kuliah',
            'jabatan_pekerjaan',
            'alamat_instansi',
            'no_telepon',
            'email',
            'prestasi_setelah_lulus',
            'status_aktif'
        ]);
        $this->resetErrorBag();
        $this->tahun_lulus = date('Y');
        $this->status_setelah_lulus = 'kuliah';
    }
}