<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use App\Models\Mapel;
use App\Models\Jadwal;
use Livewire\Attributes\Layout;



#[Layout('layouts.app-new')]

class RencanaBelajar extends Component
{
    public $rencanaBelajar = [];
    public $mapelId;
    public $topik;
    public $targetWaktu;
    public $keterangan;

    public function mount()
    {
        $this->loadRencanaBelajar();
    }

    public function render()
    {
        $siswa = auth()->user()->siswa;
        
        // PERBAIKAN: Tentukan tabel spesifik untuk kolom id
        $mapels = Mapel::whereHas('jadwal', function($query) use ($siswa) {
            $query->where('jadwals.kelas_id', $siswa->kelas_id); // Tentukan tabel jadwals
        })->get();

        $jadwalKosong = Jadwal::where('kelas_id', $siswa->kelas_id)
            ->get()
            ->groupBy('hari')
            ->map(function($jadwalHari) {
                $waktuSibuk = $jadwalHari->pluck('jam_mulai')->toArray();
                // Logic untuk menemukan waktu kosong
                return ['08:00-10:00', '13:00-15:00']; // Contoh waktu kosong
            });

        return view('livewire.siswa.rencana-belajar', [
            'mapels' => $mapels,
            'jadwalKosong' => $jadwalKosong,
            'rencanaBelajar' => $this->rencanaBelajar
        ]);
    }

    public function tambahRencana()
    {
        $this->validate([
            'mapelId' => 'required|exists:mapels,id',
            'topik' => 'required|string|max:255',
            'targetWaktu' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $this->rencanaBelajar[] = [
            'id' => uniqid(),
            'mapel_id' => $this->mapelId,
            'topik' => $this->topik,
            'target_waktu' => $this->targetWaktu,
            'keterangan' => $this->keterangan,
            'status' => 'belum',
            'created_at' => now(),
        ];

        $this->simpanRencanaBelajar();
        $this->reset(['mapelId', 'topik', 'targetWaktu', 'keterangan']);
        session()->flash('success', 'Rencana belajar berhasil ditambahkan.');
    }

    public function updateStatus($index, $status)
    {
        if (isset($this->rencanaBelajar[$index])) {
            $this->rencanaBelajar[$index]['status'] = $status;
            $this->simpanRencanaBelajar();
            session()->flash('success', 'Status rencana berhasil diupdate.');
        }
    }

    public function hapusRencana($index)
    {
        if (isset($this->rencanaBelajar[$index])) {
            unset($this->rencanaBelajar[$index]);
            $this->rencanaBelajar = array_values($this->rencanaBelajar);
            $this->simpanRencanaBelajar();
            session()->flash('success', 'Rencana belajar berhasil dihapus.');
        }
    }

    private function loadRencanaBelajar()
    {
        // Load dari database atau session
        $siswa = auth()->user()->siswa;
        
        // Contoh data dengan mapel yang relevan untuk siswa
        $this->rencanaBelajar = [
            [
                'id' => '1',
                'mapel_id' => 1,
                'topik' => 'Trigonometri Dasar',
                'target_waktu' => '2024-01-20 19:00:00',
                'keterangan' => 'Belajar rumus sinus, cosinus, tangen',
                'status' => 'belum',
                'created_at' => now(),
            ],
            [
                'id' => '2',
                'mapel_id' => 2,
                'topik' => 'Struktur Atom',
                'target_waktu' => '2024-01-19 20:00:00',
                'keterangan' => 'Mempelajari konfigurasi elektron',
                'status' => 'selesai',
                'created_at' => now()->subDays(1),
            ],
        ];
    }

    private function simpanRencanaBelajar()
    {
        // Simpan ke database atau session
        session()->put('rencana_belajar_' . auth()->id(), $this->rencanaBelajar);
    }
}