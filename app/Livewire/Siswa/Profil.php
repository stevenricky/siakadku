<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class Profil extends Component
{
    public $siswa;


    public function mount()
    {
        // PERBAIKAN: Tambahkan null checking untuk siswa
        $siswa = auth()->user()->siswa;
        if ($siswa) {
            $this->siswa = $siswa->load('kelas');
        } else {
            $this->siswa = null;
        }
    }

    public function render()
    {
        // HAPUS layout() dari sini
        return view('livewire.siswa.profil');
    }
}