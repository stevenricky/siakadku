<?php

namespace App\Livewire\Admin;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class DataGuru extends Component
{
    use WithPagination;

    // Properties for search and filter
    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;
    public $sortField = 'nama_lengkap';
    public $sortDirection = 'asc';

    // Properties for form
    public $showForm = false;
    public $showDetailModal = false;
    public $showDeleteModal = false;
    public $formType = 'create'; // 'create' or 'edit'
    
    // Form fields
    public $guruId;
    public $nama_lengkap;
    public $nip;
    public $email;
    public $jenis_kelamin;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $no_telp;
    public $alamat;
    public $status = 'aktif';

    // For detail and delete
    public $selectedGuru;
    public $guruToDelete;

    protected $rules = [
        'nama_lengkap' => 'required|string|max:255',
        'nip' => 'required|string|max:20|unique:gurus,nip',
        'email' => 'required|email|unique:users,email',
        'jenis_kelamin' => 'required|in:L,P',
        'tempat_lahir' => 'required|string|max:255',
        'tanggal_lahir' => 'required|date',
        'no_telp' => 'required|string|max:15',
        'alamat' => 'required|string',
        'status' => 'required|in:aktif,non-aktif',
    ];

    public function mount()
    {
        // Initialize properties if needed
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

    public function openCreateForm()
    {
        $this->resetForm();
        $this->formType = 'create';
        $this->showForm = true;
    }

    public function openEditForm($id)
    {
        $guru = Guru::findOrFail($id);
        
        $this->guruId = $guru->id;
        $this->nama_lengkap = $guru->nama_lengkap;
        $this->nip = $guru->nip;
        $this->email = $guru->user->email;
        $this->jenis_kelamin = $guru->jenis_kelamin;
        $this->tempat_lahir = $guru->tempat_lahir;
        $this->tanggal_lahir = $guru->tanggal_lahir;
        $this->no_telp = $guru->no_telp;
        $this->alamat = $guru->alamat;
        $this->status = $guru->status;
        
        $this->formType = 'edit';
        $this->showForm = true;
    }

    public function showDetail($id)
    {
        $this->selectedGuru = Guru::with('user')->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function confirmDelete($id)
    {
        $this->guruToDelete = Guru::findOrFail($id);
        $this->showDeleteModal = true;
    }

   public function deleteGuru()
{
    if ($this->guruToDelete) {
        try {
            \DB::transaction(function () {
                $guruId = $this->guruToDelete->id;
                $userId = $this->guruToDelete->user_id;

                // 1. Hapus data jadwal yang terkait dengan guru ini
                if (\Schema::hasTable('jadwals')) {
                    \App\Models\Jadwal::where('guru_id', $guruId)->delete();
                }

                // 2. Hapus data nilai yang terkait dengan guru ini
                if (\Schema::hasTable('nilais')) {
                    \App\Models\Nilai::where('guru_id', $guruId)->delete();
                }

                // 3. Update kelas yang menggunakan guru ini sebagai wali kelas
                if (\Schema::hasTable('kelas')) {
                    \App\Models\Kelas::where('wali_kelas_id', $guruId)
                        ->update(['wali_kelas_id' => null]);
                }

                // 4. Hapus data guru
                $this->guruToDelete->delete();

                // 5. Hapus user account (jika ada)
                if (\Schema::hasTable('users') && $userId) {
                    \App\Models\User::where('id', $userId)->delete();
                }
            });
            
            session()->flash('success', 'Data guru berhasil dihapus.');
            $this->showDeleteModal = false;
            $this->guruToDelete = null;
            
            // Refresh data
            $this->render();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            \Log::error('Error deleting guru: ' . $e->getMessage());
        }
    }
}

    public function saveGuru()
    {
        if ($this->formType === 'edit') {
            $this->rules['nip'] = 'required|string|max:20|unique:gurus,nip,' . $this->guruId;
            $this->rules['email'] = 'required|email|unique:users,email,' . Guru::find($this->guruId)->user_id;
        }

        $this->validate();

        try {
            if ($this->formType === 'create') {
                // Create user account first
                $user = User::create([
                    'name' => $this->nama_lengkap,
                    'email' => $this->email,
                    'password' => Hash::make('password123'), // Default password
                    'role' => 'guru',
                ]);

                // Create guru record
                Guru::create([
                    'user_id' => $user->id,
                    'nama_lengkap' => $this->nama_lengkap,
                    'nip' => $this->nip,
                    'jenis_kelamin' => $this->jenis_kelamin,
                    'tempat_lahir' => $this->tempat_lahir,
                    'tanggal_lahir' => $this->tanggal_lahir,
                    'no_telp' => $this->no_telp,
                    'alamat' => $this->alamat,
                    'status' => $this->status,
                ]);

                session()->flash('success', 'Data guru berhasil ditambahkan.');
            } else {
                // Update guru record
                $guru = Guru::findOrFail($this->guruId);
                $guru->update([
                    'nama_lengkap' => $this->nama_lengkap,
                    'nip' => $this->nip,
                    'jenis_kelamin' => $this->jenis_kelamin,
                    'tempat_lahir' => $this->tempat_lahir,
                    'tanggal_lahir' => $this->tanggal_lahir,
                    'no_telp' => $this->no_telp,
                    'alamat' => $this->alamat,
                    'status' => $this->status,
                ]);

                // Update user email if changed
                $guru->user->update([
                    'email' => $this->email,
                    'name' => $this->nama_lengkap,
                ]);

                session()->flash('success', 'Data guru berhasil diperbarui.');
            }

            $this->resetForm();
            $this->showForm = false;
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset([
            'guruId',
            'nama_lengkap',
            'nip',
            'email',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'no_telp',
            'alamat',
            'status',
            'showForm',
            'showDetailModal',
            'showDeleteModal',
            'selectedGuru',
            'guruToDelete',
        ]);
    }

    public function exportData()
    {
        // Implement export functionality here
        session()->flash('success', 'Fitur ekspor data akan segera tersedia.');
    }

    public function render()
    {
        $query = Guru::with('user')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                      ->orWhere('nip', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($userQuery) {
                          $userQuery->where('email', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            });

        $gurus = $query->orderBy($this->sortField, $this->sortDirection)
                      ->paginate($this->perPage);

        return view('livewire.admin.data-guru', compact('gurus'));
    }
}