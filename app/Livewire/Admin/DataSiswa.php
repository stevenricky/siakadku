<?php

namespace App\Livewire\Admin;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class DataSiswa extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'nama_lengkap';
    public $sortDirection = 'asc';
    
    // Form properties
    public $showForm = false;
    public $formType = 'create';
    public $siswaId = null;
    
    public $nama_lengkap;
    public $nis;
    public $nisn;
    public $email;
    public $jenis_kelamin = 'L';
    public $tempat_lahir;
    public $tanggal_lahir;
    public $alamat;
    public $no_telp;
    public $kelas_id;
    public $status = 'aktif';

    public $kelasList;

    // ⭐⭐ PERBAIKAN: Tambahkan property untuk protected IDs ⭐⭐
    protected $protectedIds = [1, 2, 3, 4, 5]; // ID siswa testing yang tidak boleh dihapus

    protected $queryString = ['search', 'perPage', 'sortField', 'sortDirection'];

    protected $rules = [
        'nama_lengkap' => 'required|string|max:255',
        'nis' => 'required|string|unique:siswas,nis',
        'nisn' => 'required|string|unique:siswas,nisn',
        'email' => 'required|email|unique:users,email',
        'jenis_kelamin' => 'required|in:L,P',
        'tempat_lahir' => 'required|string',
        'tanggal_lahir' => 'required|date',
        'alamat' => 'required|string',
        'no_telp' => 'required|string',
        'kelas_id' => 'required|exists:kelas,id',
        'status' => 'required|in:aktif,lulus,pindah',
    ];

    public function mount()
    {
        $this->kelasList = Kelas::with('waliKelas')->get();
    }

    // ⭐⭐ TAMBAHAN: Method untuk pagination dan sorting ⭐⭐
    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
    }

    // ⭐⭐ TAMBAHAN: Method untuk form handling ⭐⭐
    public function openCreateForm()
    {
        $this->resetForm();
        $this->formType = 'create';
        $this->showForm = true;
        $this->jenis_kelamin = 'L';
    }

    public function openEditForm($id)
    {
        $siswa = Siswa::with('user')->findOrFail($id);
        
        $this->siswaId = $siswa->id;
        $this->nama_lengkap = $siswa->nama_lengkap;
        $this->nis = $siswa->nis;
        $this->nisn = $siswa->nisn;
        $this->email = $siswa->user->email;
        $this->jenis_kelamin = $siswa->jenis_kelamin ?? 'L';
        $this->tempat_lahir = $siswa->tempat_lahir;
        $this->tanggal_lahir = $siswa->tanggal_lahir;
        $this->alamat = $siswa->alamat;
        $this->no_telp = $siswa->no_telp;
        $this->kelas_id = $siswa->kelas_id;
        $this->status = $siswa->status;
        
        $this->formType = 'edit';
        $this->showForm = true;
    }

    public function resetForm()
    {
        $this->reset([
            'siswaId', 'nama_lengkap', 'nis', 'nisn', 'email', 'jenis_kelamin',
            'tempat_lahir', 'tanggal_lahir', 'alamat', 'no_telp', 'kelas_id', 'status'
        ]);
        $this->jenis_kelamin = 'L';
        $this->resetErrorBag();
        $this->showForm = false;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetErrorBag();
    }

    public function saveSiswa()
    {
        if ($this->formType === 'edit') {
            $this->rules['nis'] = 'required|string|unique:siswas,nis,' . $this->siswaId;
            $this->rules['nisn'] = 'required|string|unique:siswas,nisn,' . $this->siswaId;
            $this->rules['email'] = 'required|email|unique:users,email,' . Siswa::find($this->siswaId)->user_id;
        }

        $validatedData = $this->validate();

        try {
            if ($this->formType === 'create') {
                // Create user first
                $user = User::create([
                    'name' => $validatedData['nama_lengkap'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make('password123'),
                    'role' => 'siswa',
                ]);

                // Create siswa
                $siswaData = [
                    'user_id' => $user->id,
                    'nis' => $validatedData['nis'],
                    'nisn' => $validatedData['nisn'],
                    'nama_lengkap' => $validatedData['nama_lengkap'],
                    'jenis_kelamin' => $validatedData['jenis_kelamin'],
                    'tempat_lahir' => $validatedData['tempat_lahir'],
                    'tanggal_lahir' => $validatedData['tanggal_lahir'],
                    'alamat' => $validatedData['alamat'],
                    'no_telp' => $validatedData['no_telp'],
                    'kelas_id' => $validatedData['kelas_id'],
                    'status' => $validatedData['status'],
                ];

                $siswa = Siswa::create($siswaData);

                session()->flash('success', 'Data siswa berhasil ditambahkan.');
            } else {
                // Update siswa
                $siswa = Siswa::find($this->siswaId);
                $updateData = [
                    'nis' => $validatedData['nis'],
                    'nisn' => $validatedData['nisn'],
                    'nama_lengkap' => $validatedData['nama_lengkap'],
                    'jenis_kelamin' => $validatedData['jenis_kelamin'],
                    'tempat_lahir' => $validatedData['tempat_lahir'],
                    'tanggal_lahir' => $validatedData['tanggal_lahir'],
                    'alamat' => $validatedData['alamat'],
                    'no_telp' => $validatedData['no_telp'],
                    'kelas_id' => $validatedData['kelas_id'],
                    'status' => $validatedData['status'],
                ];

                $siswa->update($updateData);

                // Update user email if changed
                $siswa->user->update([
                    'email' => $validatedData['email'],
                    'name' => $validatedData['nama_lengkap'],
                ]);

                session()->flash('success', 'Data siswa berhasil diperbarui.');
            }

            $this->closeForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ⭐⭐ PERBAIKAN: Tambahkan method untuk cek data protected ⭐⭐
    public function isProtected($id)
    {
        return in_array($id, $this->protectedIds);
    }

    // ⭐⭐ PERBAIKAN: Update method delete untuk handle protected data ⭐⭐
    public function deleteSiswa($id)
    {
        // Cek apakah data termasuk protected
        if ($this->isProtected($id)) {
            session()->flash('error', '❌ Data siswa testing tidak dapat dihapus!');
            return;
        }

        try {
            $siswa = Siswa::findOrFail($id);
            $namaSiswa = $siswa->nama_lengkap;
            
            // Soft delete siswa
            $siswa->delete();
            
            // Soft delete user juga
            if ($siswa->user) {
                $siswa->user->delete();
            }

            session()->flash('success', "Data siswa {$namaSiswa} berhasil dihapus.");

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        if ($this->search) {
            $this->resetPage();
        }

        $siswas = Siswa::query()
            ->with(['user:id,email,name', 'kelas:id,nama_kelas'])
            ->when($this->search, function ($q) {
                $q->where('nama_lengkap', 'like', "%{$this->search}%")
                  ->orWhere('nis', 'like', "%{$this->search}%")
                  ->orWhere('nisn', 'like', "%{$this->search}%")
                  ->orWhereHas('user', fn($uq) =>
                      $uq->where('email', 'like', "%{$this->search}%")
                  );
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.data-siswa', [
            'siswas' => $siswas,
        ]);
    }
}