<?php

namespace App\Livewire\Guru;

use App\Models\Absensi as AbsensiModel;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;



#[Layout('layouts.app-new')]
class AbsensiSiswa extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $kelasFilter;
    public $tanggalFilter;
    public $jadwalFilter;

    public $kelasList;
    public $jadwalList;

    // Form properties
    public $showForm = false;
    public $absensiId;
    public $siswaId;
    public $status = 'hadir';
    public $keterangan;

    protected $queryString = ['search', 'perPage', 'kelasFilter', 'tanggalFilter', 'jadwalFilter'];

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
        $guruId = auth()->user()->guru->id;
        
        // PERBAIKAN: Inisialisasi dengan collection kosong
        $this->kelasList = Kelas::whereHas('jadwal', function ($query) use ($guruId) {
            $query->where('guru_id', $guruId);
        })->get() ?? collect(); // Tambahkan fallback ke collection kosong

        $this->jadwalList = collect(); // Inisialisasi jadwalList dengan collection kosong
        $this->tanggalFilter = now()->format('Y-m-d');
    }

    public function updatedKelasFilter($value)
    {
        if ($value) {
            $this->jadwalList = Jadwal::where('kelas_id', $value)
                ->where('guru_id', auth()->user()->guru->id)
                ->get();
        } else {
            $this->jadwalList = collect();
        }
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function openAbsensiForm($siswaId)
    {
        $this->siswaId = $siswaId;
        $this->reset(['status', 'keterangan']);
        $this->showForm = true;
    }

    public function saveAbsensi()
    {
        $this->validate([
            'status' => 'required|in:hadir,sakit,izin,alpha',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            AbsensiModel::updateOrCreate(
                [
                    'siswa_id' => $this->siswaId,
                    'jadwal_id' => $this->jadwalFilter,
                    'tanggal' => $this->tanggalFilter,
                ],
                [
                    'status' => $this->status,
                    'keterangan' => $this->keterangan,
                ]
            );

            session()->flash('success', 'Absensi berhasil disimpan.');
            $this->showForm = false;
            $this->reset(['status', 'keterangan']);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $absensi = collect();
        $siswas = collect();

        if ($this->kelasFilter && $this->jadwalFilter && $this->tanggalFilter) {
            $siswas = Siswa::where('kelas_id', $this->kelasFilter)
                ->where('status', 'aktif')
                ->when($this->search, function ($query) {
                    $query->where('nama_lengkap', 'like', '%' . $this->search . '%')
                          ->orWhere('nis', 'like', '%' . $this->search . '%');
                })
                ->orderBy('nama_lengkap')
                ->get();

            // Load existing absensi for the selected date and jadwal
            $absensi = AbsensiModel::where('jadwal_id', $this->jadwalFilter)
                ->where('tanggal', $this->tanggalFilter)
                ->get()
                ->keyBy('siswa_id');
        }

        // PERBAIKAN: Pastikan $kelasList dan $jadwalList tidak null
        return view('livewire.guru.absensi-siswa', [
            'siswas' => $siswas,
            'absensi' => $absensi,
            'kelasList' => $this->kelasList ?? collect(), // Tambahkan fallback
            'jadwalList' => $this->jadwalList ?? collect(), // Tambahkan fallback
        ]);
    }
}