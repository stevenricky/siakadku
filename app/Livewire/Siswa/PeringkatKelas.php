<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use App\Models\Nilai;
use App\Models\Siswa;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class PeringkatKelas extends Component
{
    public $semester;
    public $tahunAjaranId;

    public function mount()
    {
        // Set default values
        $this->semester = 'ganjil';
        $this->tahunAjaranId = \App\Models\TahunAjaran::where('status', 'aktif')->first()?->id;
    }

    public function render()
    {
        $siswaAuth = auth()->user()->siswa;
        
        // Ambil semua siswa di kelas yang sama
        $siswaKelas = Siswa::with(['user', 'kelas'])
            ->where('kelas_id', $siswaAuth->kelas_id)
            ->get();

        // Hitung peringkat berdasarkan nilai rata-rata
        $peringkat = [];
        foreach ($siswaKelas as $siswa) {
            $nilaiSiswa = Nilai::where('siswa_id', $siswa->id)
                ->when($this->semester, function($query) {
                    $query->where('semester', $this->semester);
                })
                ->when($this->tahunAjaranId, function($query) {
                    $query->where('tahun_ajaran_id', $this->tahunAjaranId);
                })
                ->get();

            if ($nilaiSiswa->count() > 0) {
                $rataRata = $nilaiSiswa->avg('nilai_akhir');
                $peringkat[] = [
                    'siswa' => $siswa,
                    'rata_rata' => round($rataRata, 2),
                    'jumlah_mapel' => $nilaiSiswa->count(),
                    'peringkat' => 0,
                ];
            }
        }

        // Urutkan berdasarkan rata-rata tertinggi
        usort($peringkat, function($a, $b) {
            return $b['rata_rata'] <=> $a['rata_rata'];
        });

        // Beri peringkat
        foreach ($peringkat as $index => &$data) {
            $data['peringkat'] = $index + 1;
        }

        $tahunAjarans = \App\Models\TahunAjaran::all();

        // Cari peringkat siswa yang login
        $peringkatSaya = null;
        foreach ($peringkat as $data) {
            if ($data['siswa']->id === $siswaAuth->id) {
                $peringkatSaya = $data;
                break;
            }
        }

        return view('livewire.siswa.peringkat-kelas', [
            'peringkat' => $peringkat,
            'tahunAjarans' => $tahunAjarans,
            'siswaAuth' => $siswaAuth,
            'peringkatSaya' => $peringkatSaya,
            'totalSiswa' => $siswaKelas->count()
        ]);
    }
}