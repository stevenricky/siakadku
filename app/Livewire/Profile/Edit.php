<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;

#[Layout('layouts.app-new')]
class Edit extends Component
{
    use WithFileUploads;

    public $activeTab = 'personal';
    public $name;
    public $email;
    public $avatar;
    public $foto_profil;

    // Password fields
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    // Data siswa
    public $nis;
    public $nisn;
    public $nama_lengkap;
    public $jenis_kelamin;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $alamat;
    public $no_telp;
    public $kelas_id;
    public $tahun_ajaran_id;
    public $status;

    // Data guru
    public $nip;
    public $guru_nama_lengkap;
    public $guru_jenis_kelamin;
    public $guru_tempat_lahir;
    public $guru_tanggal_lahir;
    public $guru_alamat;
    public $guru_no_telp;
    public $guru_status;

    // Data untuk dropdown
    public $kelas_list = [];
    public $tahun_ajaran_list = [];

    public function mount()
    {
        $this->refreshUserData();
        $this->loadDropdownData();
    }

    public function refreshUserData()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->foto_profil = $user->foto_profil;

        // Load data berdasarkan role
        if ($user->isSiswa() && $user->siswa) {
            $this->loadSiswaData($user->siswa);
        } elseif ($user->isGuru() && $user->guru) {
            $this->loadGuruData($user->guru);
        }

        $this->dispatch('profileDataRefreshed');
    }

    protected function loadSiswaData(Siswa $siswa)
    {
        $this->nis = $siswa->nis;
        $this->nisn = $siswa->nisn;
        $this->nama_lengkap = $siswa->nama_lengkap;
        $this->jenis_kelamin = $siswa->jenis_kelamin;
        $this->tempat_lahir = $siswa->tempat_lahir;
        $this->tanggal_lahir = $siswa->tanggal_lahir?->format('Y-m-d');
        $this->alamat = $siswa->alamat;
        $this->no_telp = $siswa->no_telp;
        $this->kelas_id = $siswa->kelas_id;
        $this->tahun_ajaran_id = $siswa->tahun_ajaran_id;
        $this->status = $siswa->status;
    }

    protected function loadGuruData(Guru $guru)
    {
        $this->nip = $guru->nip;
        $this->guru_nama_lengkap = $guru->nama_lengkap;
        $this->guru_jenis_kelamin = $guru->jenis_kelamin;
        $this->guru_tempat_lahir = $guru->tempat_lahir;
        $this->guru_tanggal_lahir = $guru->tanggal_lahir?->format('Y-m-d');
        $this->guru_alamat = $guru->alamat;
        $this->guru_no_telp = $guru->no_telp;
        $this->guru_status = $guru->status;
    }

    protected function loadDropdownData()
    {
        // Load kelas data dengan urutan
        $this->kelas_list = Kelas::orderBy('nama_kelas')->pluck('nama_kelas', 'id')->toArray();
        
        // Load tahun ajaran data dengan format yang lebih baik
        $this->tahun_ajaran_list = TahunAjaran::query()
            ->select('id', 'tahun_awal', 'tahun_akhir', 'semester')
            ->orderBy('tahun_awal', 'desc')
            ->orderBy('semester', 'desc')
            ->get()
            ->mapWithKeys(function ($item) {
                $nama_tahun = $item->tahun_awal . '/' . $item->tahun_akhir . ' - ' . ucfirst($item->semester);
                return [$item->id => $nama_tahun];
            })
            ->toArray();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->dispatch('tabChanged', tab: $tab);
    }

    public function updatedAvatar()
    {
        $this->validate([
            'avatar' => ['image', 'max:2048'],
        ]);

        // Preview avatar secara real-time
        $this->dispatch('avatarUpdated');
    }

    public function updateProfile()
    {
        $user = Auth::user();
        
        // Validasi dasar
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        try {
            // Handle upload foto profil
            if ($this->avatar) {
                // Hapus foto lama jika ada
                if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                    Storage::disk('public')->delete($user->foto_profil);
                }

                // Simpan foto baru
                $path = $this->avatar->store('profile-photos', 'public');
                $user->foto_profil = $path;
            }

            // Update data user
            $user->name = $this->name;
            $user->email = $this->email;
            $user->save();

            // Update data role-specific
            if ($user->isSiswa() && $user->siswa) {
                $this->updateSiswaData($user->siswa);
            } elseif ($user->isGuru() && $user->guru) {
                $this->updateGuruData($user->guru);
            }

            // Refresh data
            $this->refreshUserData();
            $this->avatar = null;

            $this->dispatch('profileUpdated');
            $this->dispatch('show-toast', 
                type: 'success', 
                message: 'Profile berhasil diperbarui!'
            );

        } catch (\Exception $e) {
            $this->dispatch('show-toast', 
                type: 'error', 
                message: 'Terjadi kesalahan: ' . $e->getMessage()
            );
        }
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($this->new_password);
        $user->save();

        // Reset password fields
        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';

        $this->dispatch('passwordUpdated');
        $this->dispatch('show-toast', 
            type: 'success', 
            message: 'Password berhasil diperbarui!'
        );
    }

    protected function updateSiswaData(Siswa $siswa)
    {
        // Validasi data siswa
        $this->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat' => ['required', 'string'],
            'no_telp' => ['required', 'string', 'max:15'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
        ]);

        $siswa->update([
            'nama_lengkap' => $this->nama_lengkap,
            'jenis_kelamin' => $this->jenis_kelamin,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'alamat' => $this->alamat,
            'no_telp' => $this->no_telp,
            'kelas_id' => $this->kelas_id,
            'tahun_ajaran_id' => $this->tahun_ajaran_id,
        ]);
    }

    protected function updateGuruData(Guru $guru)
    {
        // Validasi data guru
        $this->validate([
            'guru_nama_lengkap' => ['required', 'string', 'max:255'],
            'guru_jenis_kelamin' => ['required', 'in:L,P'],
            'guru_tempat_lahir' => ['required', 'string', 'max:255'],
            'guru_tanggal_lahir' => ['required', 'date'],
            'guru_alamat' => ['required', 'string'],
            'guru_no_telp' => ['required', 'string', 'max:15'],
        ]);

        $guru->update([
            'nama_lengkap' => $this->guru_nama_lengkap,
            'jenis_kelamin' => $this->guru_jenis_kelamin,
            'tempat_lahir' => $this->guru_tempat_lahir,
            'tanggal_lahir' => $this->guru_tanggal_lahir,
            'alamat' => $this->guru_alamat,
            'no_telp' => $this->guru_no_telp,
        ]);
    }

    public function removePhoto()
    {
        $user = Auth::user();

        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $user->foto_profil = null;
        $user->save();

        $this->refreshUserData();
        $this->avatar = null;

        $this->dispatch('profileRemoved');
        $this->dispatch('show-toast', 
            type: 'success', 
            message: 'Foto profil berhasil dihapus!'
        );
    }

    // Real-time validation
    public function updated($property)
    {
        if (in_array($property, ['name', 'email', 'nama_lengkap', 'guru_nama_lengkap'])) {
            $this->validateOnly($property, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', Rule::unique('users')->ignore(Auth::id())],
                'nama_lengkap' => ['sometimes', 'required', 'string', 'max:255'],
                'guru_nama_lengkap' => ['sometimes', 'required', 'string', 'max:255'],
            ]);
        }
    }

    // Computed Properties
    public function getUserProperty()
    {
        return Auth::user();
    }

    public function getFotoUrlProperty()
    {
        if (!$this->foto_profil) {
            return null;
        }
        return Storage::disk('public')->url($this->foto_profil) . '?v=' . time();
    }

    #[On('refreshProfile')]
    public function refreshFromEvent()
    {
        $this->refreshUserData();
    }

    public function render()
    {
        return view('livewire.profile.edit', [
            'user' => $this->user,
            'fotoUrl' => $this->fotoUrl,
        ]);
    }
}