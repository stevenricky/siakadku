<?php

namespace App\Livewire\Admin;

use App\Models\Konseling;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\LayananBk;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class JadwalKonseling extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $konselingId;
    public $siswa_id;
    public $guru_id;
    public $layanan_bk_id;
    public $tanggal_konseling;
    public $waktu_mulai;
    public $waktu_selesai;
    public $tempat;
    public $permasalahan;
    public $tindakan;
    public $hasil;
    public $status = 'terjadwal';
    public $catatan;
    public $showModal = false;
    public $modalTitle = 'Jadwalkan Konseling';

    // Status options
    public $statusOptions = [
        'terjadwal' => 'Terjadwal',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan'
    ];

    protected $rules = [
        'siswa_id' => 'required|exists:siswas,id',
        'guru_id' => 'required|exists:gurus,id',
        'layanan_bk_id' => 'required|exists:layanan_bk,id',
        'tanggal_konseling' => 'required|date',
        'waktu_mulai' => 'required|date_format:H:i',
        'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
        'tempat' => 'required|string|max:255',
        'permasalahan' => 'required|string',
        'tindakan' => 'nullable|string',
        'hasil' => 'nullable|string',
        'status' => 'required|in:terjadwal,selesai,dibatalkan',
        'catatan' => 'nullable|string'
    ];

    protected $messages = [
        'waktu_mulai.date_format' => 'Format waktu mulai harus HH:MM (contoh: 08:00)',
        'waktu_selesai.date_format' => 'Format waktu selesai harus HH:MM (contoh: 09:00)',
        'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai',
    ];

    public function mount()
    {
        $this->showModal = false;
        $this->tanggal_konseling = now()->format('Y-m-d');
        // Set default waktu
        $this->waktu_mulai = '08:00';
        $this->waktu_selesai = '09:00';
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function render()
    {
        $konseling = Konseling::with(['siswa', 'guruBk', 'layananBk'])
            ->when($this->search, function ($query) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('guruBk', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                })
                ->orWhere('tempat', 'like', '%' . $this->search . '%');
            })
            ->latest('tanggal_konseling')
            ->paginate($this->perPage);

        // Hitung statistik
        $terjadwal = Konseling::where('status', 'terjadwal')->count();
        $selesai = Konseling::where('status', 'selesai')->count();
        $berlangsung = Konseling::where('status', 'terjadwal')
            ->whereDate('tanggal_konseling', today())
            ->count();
        $dibatalkan = Konseling::where('status', 'dibatalkan')->count();

        $siswa = Siswa::where('status', 'aktif')->get();
        $guruBk = Guru::all(); // Ambil semua guru
        $layananBk = LayananBk::where('status', true)->get();

        return view('livewire.admin.jadwal-konseling', [
            'konseling' => $konseling,
            'terjadwal' => $terjadwal,
            'selesai' => $selesai,
            'berlangsung' => $berlangsung,
            'dibatalkan' => $dibatalkan,
            'siswa' => $siswa,
            'guruBk' => $guruBk,
            'layananBk' => $layananBk
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->modalTitle = 'Jadwalkan Konseling';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $konseling = Konseling::findOrFail($id);
        $this->konselingId = $id;
        $this->siswa_id = $konseling->siswa_id;
        $this->guru_id = $konseling->guru_id;
        $this->layanan_bk_id = $konseling->layanan_bk_id;
        $this->tanggal_konseling = $konseling->tanggal_konseling->format('Y-m-d');
        $this->waktu_mulai = $konseling->waktu_mulai;
        $this->waktu_selesai = $konseling->waktu_selesai;
        $this->tempat = $konseling->tempat;
        $this->permasalahan = $konseling->permasalahan;
        $this->tindakan = $konseling->tindakan;
        $this->hasil = $konseling->hasil;
        $this->status = $konseling->status;
        $this->catatan = $konseling->catatan;
        $this->modalTitle = 'Edit Jadwal Konseling';
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Debug: Lihat data sebelum disimpan
        // dd([
        //     'waktu_mulai' => $this->waktu_mulai,
        //     'waktu_selesai' => $this->waktu_selesai,
        //     'type_waktu_mulai' => gettype($this->waktu_mulai),
        //     'type_waktu_selesai' => gettype($this->waktu_selesai)
        // ]);

        // Cek konflik jadwal
        $konflik = Konseling::where('guru_id', $this->guru_id)
            ->where('tanggal_konseling', $this->tanggal_konseling)
            ->where('id', '!=', $this->konselingId)
            ->where(function ($query) {
                $query->whereBetween('waktu_mulai', [$this->waktu_mulai, $this->waktu_selesai])
                      ->orWhereBetween('waktu_selesai', [$this->waktu_mulai, $this->waktu_selesai])
                      ->orWhere(function ($q) {
                          $q->where('waktu_mulai', '<=', $this->waktu_mulai)
                            ->where('waktu_selesai', '>=', $this->waktu_selesai);
                      });
            })
            ->exists();

        if ($konflik) {
            session()->flash('error', 'Guru BK sudah memiliki jadwal pada waktu tersebut.');
            return;
        }

        $data = [
            'siswa_id' => $this->siswa_id,
            'guru_id' => $this->guru_id,
            'layanan_bk_id' => $this->layanan_bk_id,
            'tanggal_konseling' => $this->tanggal_konseling,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_selesai' => $this->waktu_selesai,
            'tempat' => $this->tempat,
            'permasalahan' => $this->permasalahan,
            'tindakan' => $this->tindakan,
            'hasil' => $this->hasil,
            'status' => $this->status,
            'catatan' => $this->catatan
        ];

        if ($this->konselingId) {
            $konseling = Konseling::findOrFail($this->konselingId);
            $konseling->update($data);
            session()->flash('success', 'Jadwal konseling berhasil diupdate.');
        } else {
            Konseling::create($data);
            session()->flash('success', 'Jadwal konseling berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function updateStatus($id, $status)
    {
        $konseling = Konseling::findOrFail($id);
        $konseling->update(['status' => $status]);
        
        session()->flash('success', 'Status konseling berhasil diupdate.');
    }

    public function delete($id)
    {
        $konseling = Konseling::findOrFail($id);
        $konseling->delete();
        
        session()->flash('success', 'Jadwal konseling berhasil dihapus.');
    }

    private function resetForm()
    {
        $this->reset([
            'konselingId',
            'siswa_id',
            'guru_id',
            'layanan_bk_id',
            'tanggal_konseling',
            'waktu_mulai',
            'waktu_selesai',
            'tempat',
            'permasalahan',
            'tindakan',
            'hasil',
            'status',
            'catatan'
        ]);
        $this->resetErrorBag();
        $this->tanggal_konseling = now()->format('Y-m-d');
        $this->waktu_mulai = '08:00';
        $this->waktu_selesai = '09:00';
    }

    // Method untuk handle perubahan waktu
    public function updatedWaktuMulai($value)
    {
        if ($value && $this->waktu_selesai) {
            // Jika waktu mulai diubah, set waktu selesai 1 jam setelahnya
            $waktuMulai = \Carbon\Carbon::createFromFormat('H:i', $value);
            $this->waktu_selesai = $waktuMulai->addHour()->format('H:i');
        }
    }
}