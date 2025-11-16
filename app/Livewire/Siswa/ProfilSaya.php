<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use App\Models\Siswa;
use Illuminate\Support\Facades\Storage;

class ProfilSaya extends Component
{
    public $siswa;
    public $nama_lengkap;
    public $jenis_kelamin;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $alamat;
    public $no_telepon;
    public $nama_orangtua;
    public $no_telepon_orangtua;
    public $foto;

    public function mount()
    {
        $this->siswa = Siswa::where('user_id', auth()->id())->firstOrFail();
        $this->loadData();
    }

    public function loadData()
    {
        $this->nama_lengkap = $this->siswa->nama_lengkap;
        $this->jenis_kelamin = $this->siswa->jenis_kelamin;
        $this->tempat_lahir = $this->siswa->tempat_lahir;
        $this->tanggal_lahir = $this->siswa->tanggal_lahir?->format('Y-m-d');
        $this->alamat = $this->siswa->alamat;
        $this->no_telepon = $this->siswa->no_telepon;
        $this->nama_orangtua = $this->siswa->nama_orangtua;
        $this->no_telepon_orangtua = $this->siswa->no_telepon_orangtua;
    }

    public function render()
    {
        return view('livewire.siswa.profil-saya', [
            'siswa' => $this->siswa
        ]);
    }

    public function updateProfil()
    {
        $this->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:15',
            'nama_orangtua' => 'required|string|max:255',
            'no_telepon_orangtua' => 'required|string|max:15',
        ]);

        $this->siswa->update([
            'nama_lengkap' => $this->nama_lengkap,
            'jenis_kelamin' => $this->jenis_kelamin,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'alamat' => $this->alamat,
            'no_telepon' => $this->no_telepon,
            'nama_orangtua' => $this->nama_orangtua,
            'no_telepon_orangtua' => $this->no_telepon_orangtua,
        ]);

        session()->flash('success', 'Profil berhasil diperbarui.');
    }

    public function updateFoto()
    {
        $this->validate([
            'foto' => 'required|image|max:2048',
        ]);

        if ($this->siswa->foto) {
            Storage::delete($this->siswa->foto);
        }

        $path = $this->foto->store('siswa/foto', 'public');
        $this->siswa->update(['foto' => $path]);

        session()->flash('success', 'Foto profil berhasil diperbarui.');
        $this->reset('foto');
    }
}