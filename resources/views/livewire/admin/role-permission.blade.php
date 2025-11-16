<div>
    <!-- Simple Header -->
    <div class="bg-white dark:bg-gray-800 shadow mb-6">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Role Management</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola role dan permission sistem</p>
                </div>
                <button wire:click="showCreateModal" 
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Role
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session()->has('success'))
    <div class="max-w-7xl mx-auto px-4 mb-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session()->has('error'))
    <div class="max-w-7xl mx-auto px-4 mb-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    </div>
    @endif

    <!-- Search and Content -->
    <div class="max-w-7xl mx-auto px-4">
        <!-- Search -->
        <div class="mb-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" 
                       wire:model.live.debounce.300ms="search"
                       placeholder="Cari role..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="sm:hidden space-y-4">
            @forelse($roles as $role)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 dark:text-white text-lg">{{ $role->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Dibuat: {{ $role->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 ml-2">
                        {{ $role->users_count ?? $role->users()->count() }} user
                    </span>
                </div>
                
                <!-- Permissions Section -->
                <div class="mb-3">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Permissions:</span>
                        @if($role->permissions->count() > 3)
                        <button wire:click="$toggle('expandedRoles.{{ $role->id }}')" 
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-sm flex items-center">
                            <span>{{ $this->isRoleExpanded($role->id) ? 'Sembunyikan' : 'Lihat Semua' }}</span>
                            <i class="fas fa-chevron-down ml-1 text-xs transition-transform duration-200 {{ $this->isRoleExpanded($role->id) ? 'rotate-180' : '' }}"></i>
                        </button>
                        @endif
                    </div>
                    
                    <div class="flex flex-wrap gap-1">
                        @php
                            $isExpanded = $this->isRoleExpanded($role->id);
                            $displayPermissions = $isExpanded ? $role->permissions : $role->permissions->take(3);
                        @endphp
                        
                        @foreach($displayPermissions as $permission)
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                            {{ $permission->name }}
                        </span>
                        @endforeach
                        
                        @if(!$isExpanded && $role->permissions->count() > 3)
                        <button wire:click="$set('expandedRoles.{{ $role->id }}', true)"
                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-500">
                            +{{ $role->permissions->count() - 3 }} lagi
                        </button>
                        @endif
                        
                        @if($role->permissions->count() === 0)
                        <span class="text-xs text-gray-400">Tidak ada permissions</span>
                        @endif
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex justify-end pt-3 border-t border-gray-200 dark:border-gray-600">
                    @if(!in_array($role->name, ['admin', 'guru', 'siswa']))
                    <button wire:click="deleteRole({{ $role->id }})" 
                            wire:confirm="Hapus role {{ $role->name }}?"
                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 px-3 py-1 rounded border border-red-200 dark:border-red-800 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-sm flex items-center">
                        <i class="fas fa-trash mr-1 text-xs"></i>
                        Hapus
                    </button>
                    @else
                    <span class="text-gray-400 text-sm flex items-center">
                        <i class="fas fa-lock mr-1 text-xs"></i>
                        System role
                    </span>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-8 bg-white dark:bg-gray-800 rounded-lg shadow">
                <i class="fas fa-inbox text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-500 dark:text-gray-400">Tidak ada data role</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table -->
        <div class="hidden sm:block bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Users</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Permissions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($roles as $role)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-user-shield text-blue-600 dark:text-blue-300"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $role->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            Created: {{ $role->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    {{ $role->users_count ?? $role->users()->count() }} users
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-2">
                                    <div class="flex flex-wrap gap-1 max-w-md">
                                        @php
                                            $isExpanded = $this->isRoleExpanded($role->id);
                                            $displayPermissions = $isExpanded ? $role->permissions : $role->permissions->take(4);
                                        @endphp
                                        
                                        @foreach($displayPermissions as $permission)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 truncate max-w-xs">
                                            {{ $permission->name }}
                                        </span>
                                        @endforeach
                                        
                                        @if(!$isExpanded && $role->permissions->count() > 4)
                                        <button wire:click="$set('expandedRoles.{{ $role->id }}', true)"
                                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-500 transition-colors">
                                            +{{ $role->permissions->count() - 4 }} lagi
                                        </button>
                                        @endif
                                        
                                        @if($role->permissions->count() === 0)
                                        <span class="text-xs text-gray-400">Tidak ada permissions</span>
                                        @endif
                                    </div>
                                    
                                    @if($role->permissions->count() > 4)
                                    <button wire:click="$toggle('expandedRoles.{{ $role->id }}')" 
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-xs flex items-center self-start">
                                        <span>{{ $this->isRoleExpanded($role->id) ? 'Sembunyikan' : 'Lihat Semua' }}</span>
                                        <i class="fas fa-chevron-down ml-1 text-xs transition-transform duration-200 {{ $this->isRoleExpanded($role->id) ? 'rotate-180' : '' }}"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if(!in_array($role->name, ['admin', 'guru', 'siswa']))
                                <button wire:click="deleteRole({{ $role->id }})" 
                                        wire:confirm="Hapus role {{ $role->name }}?"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 px-3 py-1 rounded border border-red-200 dark:border-red-800 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-sm flex items-center">
                                    <i class="fas fa-trash mr-1 text-xs"></i>
                                    Hapus
                                </button>
                                @else
                                <span class="text-gray-400 text-sm flex items-center">
                                    <i class="fas fa-lock mr-1 text-xs"></i>
                                    System role
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-inbox text-3xl mb-2 opacity-50"></i>
                                    <p class="text-sm">Tidak ada data role</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($roles->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $roles->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" x-data x-show="$wire.showModal" @keydown.escape.window="$wire.hideModal()">
        <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-md max-h-[90vh] overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tambah Role Baru</h3>
                <button wire:click="hideModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="px-6 py-4 overflow-y-auto">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nama Role *
                    </label>
                    <input type="text" 
                           wire:model="roleName"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Masukkan nama role"
                           autofocus>
                    @error('roleName')
                    <p class="text-red-500 text-xs mt-1 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Permissions
                    </label>
                    <div class="max-h-40 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md p-3 bg-gray-50 dark:bg-gray-700">
                        @foreach($allPermissions as $permission)
                        <label class="flex items-center mb-2 p-2 rounded hover:bg-white dark:hover:bg-gray-600 transition-colors cursor-pointer">
                            <input type="checkbox" 
                                   wire:model="permissions"
                                   value="{{ $permission->name }}"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ $permission->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        Dipilih: <span class="font-medium">{{ count($permissions) }}</span> permissions
                    </p>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                <button wire:click="hideModal" 
                        class="w-full sm:w-auto px-4 py-2 text-gray-600 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Batal
                </button>
                <button wire:click="createRole"
                        wire:loading.attr="disabled"
                        class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="createRole">Simpan Role</span>
                    <span wire:loading wire:target="createRole">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>