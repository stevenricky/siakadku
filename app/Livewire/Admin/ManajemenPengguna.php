<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app-new')]
class ManajemenPengguna extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function render()
    {
        $users = User::with('guru', 'siswa')
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('email', 'like', '%'.$this->search.'%')
                      ->orWhere('role', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.manajemen-pengguna', [
            'users' => $users
        ]);
    }

    public function updateRole($userId, $role)
    {
        $user = User::findOrFail($userId);
        $user->update(['role' => $role]);
        session()->flash('success', 'Role pengguna berhasil diupdate.');
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        
        // Cek apakah user memiliki relasi sebelum dihapus
        if ($user->guru()->exists() || $user->siswa()->exists()) {
            session()->flash('error', 'Tidak dapat menghapus pengguna yang masih memiliki data terkait.');
            return;
        }

        $user->delete();
        session()->flash('success', 'Pengguna berhasil dihapus.');
    }
}