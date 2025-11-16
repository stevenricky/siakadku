<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

#[Layout('layouts.app-new')]
class RolePermission extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $roleName;
    public $permissions = [];
    public $showModal = false;
    public $modalTitle = 'Tambah Role';
    public $expandedRoles = [];

    public function mount()
    {
        $this->perPage = session()->get('perPage', 10);
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);
    }

    // Method untuk menampilkan modal
    public function showCreateModal()
    {
        $this->reset(['roleName', 'permissions']);
        $this->modalTitle = 'Tambah Role';
        $this->showModal = true;
    }

    // Method untuk menyembunyikan modal
    public function hideModal()
    {
        $this->showModal = false;
        $this->reset(['roleName', 'permissions']);
    }

    // Helper method untuk cek apakah role expanded
    public function isRoleExpanded($roleId)
    {
        return $this->expandedRoles[$roleId] ?? false;
    }

    public function render()
    {
        // Cek apakah tabel roles ada
        try {
            $roles = Role::with(['permissions', 'users'])
                ->when($this->search, function($query) {
                    $query->where('name', 'like', '%'.$this->search.'%');
                })
                ->latest()
                ->paginate($this->perPage);

            $allPermissions = Permission::all();
        } catch (\Exception $e) {
            // Jika tabel belum ada, return empty collections
            $roles = collect();
            $allPermissions = collect();
            
            session()->flash('error', 'Tabel roles belum tersedia. Silakan jalankan migration dan seeder.');
        }

        return view('livewire.admin.role-permission', [
            'roles' => $roles,
            'allPermissions' => $allPermissions
        ]);
    }

    public function createRole()
    {
        $this->validate([
            'roleName' => 'required|string|max:255|unique:roles,name',
        ]);

        try {
            $role = Role::create(['name' => $this->roleName]);
            $role->syncPermissions($this->permissions);

            $this->hideModal();
            session()->flash('success', 'Role "'.$this->roleName.'" berhasil dibuat.');
            
            // Reset form
            $this->reset(['roleName', 'permissions']);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat role: ' . $e->getMessage());
        }
    }

    public function deleteRole($roleId)
    {
        try {
            $role = Role::findOrFail($roleId);
            
            // Cek jika role masih digunakan
            if ($role->users()->count() > 0) {
                session()->flash('error', 'Tidak dapat menghapus role "'.$role->name.'" yang masih digunakan oleh pengguna.');
                return;
            }

            $roleName = $role->name;
            $role->delete();
            session()->flash('success', 'Role "'.$roleName.'" berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus role: ' . $e->getMessage());
        }
    }
}