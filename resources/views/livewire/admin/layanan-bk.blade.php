<div class="p-4 sm:p-6">
    <!-- Header dan Tombol Tambah -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Layanan Bimbingan Konseling</h1>
        <button wire:click="create" class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm sm:text-base">
            <i class="fas fa-plus mr-2"></i>Tambah Layanan
        </button>
    </div>

    <!-- Search dan Filter -->
    <div class="mb-6 flex flex-col gap-4">
        <div class="flex-1">
            <input 
                type="text" 
                wire:model.live="search"
                placeholder="Cari layanan..." 
                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
        </div>
        <div class="w-full sm:w-48">
            <select 
                wire:model.live="perPage"
                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
                <option value="5">5 per halaman</option>
                <option value="10">10 per halaman</option>
                <option value="25">25 per halaman</option>
                <option value="50">50 per halaman</option>
            </select>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i class="fas fa-hands-helping text-blue-600 dark:text-blue-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Total Layanan</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalLayanan }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <i class="fas fa-user-friends text-green-600 dark:text-green-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Individu</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $layananIndividu }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <i class="fas fa-users text-purple-600 dark:text-purple-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Kelompok</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $layananKelompok }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                    <i class="fas fa-graduation-cap text-orange-600 dark:text-orange-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Kelas</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $layananKelas }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data - Desktop -->
    <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Layanan BK</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Layanan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sasaran</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($layananBk as $layanan)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $layanan->nama_layanan }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($layanan->deskripsi, 50) }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $jenisLayananOptions[$layanan->jenis_layanan] ?? $layanan->jenis_layanan }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ ucfirst($layanan->sasaran) }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $layanan->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $layanan->status ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-3">
                                <button wire:click="edit({{ $layanan->id }})" class="text-blue-600 hover:text-blue-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="delete({{ $layanan->id }})" onclick="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')" class="text-red-600 hover:text-red-900" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada data layanan BK
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($layananBk->hasPages())
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $layananBk->links() }}
        </div>
        @endif
    </div>

    <!-- Tabel Data - Mobile -->
    <div class="sm:hidden space-y-4">
        @forelse($layananBk as $layanan)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="space-y-3">
                <!-- Header -->
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $layanan->nama_layanan }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ Str::limit($layanan->deskripsi, 60) }}
                        </div>
                    </div>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $layanan->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $layanan->status ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                <!-- Detail Info -->
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <div class="text-gray-500 dark:text-gray-400 text-xs">Jenis</div>
                        <div class="text-gray-900 dark:text-white">
                            {{ $jenisLayananOptions[$layanan->jenis_layanan] ?? $layanan->jenis_layanan }}
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-500 dark:text-gray-400 text-xs">Sasaran</div>
                        <div class="text-gray-900 dark:text-white capitalize">
                            {{ $layanan->sasaran }}
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-4 pt-2 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="edit({{ $layanan->id }})" class="text-blue-600 hover:text-blue-900 text-sm" title="Edit">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                    <button wire:click="delete({{ $layanan->id }})" onclick="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')" class="text-red-600 hover:text-red-900 text-sm" title="Hapus">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
            <div class="text-gray-500 dark:text-gray-400">
                <i class="fas fa-hands-helping text-3xl mb-3"></i>
                <p>Tidak ada data layanan BK</p>
            </div>
        </div>
        @endforelse

        <!-- Pagination Mobile -->
        @if($layananBk->hasPages())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            {{ $layananBk->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Form -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ $modalTitle }}</h3>
                
                <form wire:submit="save">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Layanan</label>
                            <input type="text" wire:model="nama_layanan" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('nama_layanan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                            <textarea wire:model="deskripsi" rows="3" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Layanan</label>
                            <select wire:model="jenis_layanan" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih Jenis Layanan</option>
                                @foreach($jenisLayananOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('jenis_layanan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sasaran</label>
                            <select wire:model="sasaran" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih Sasaran</option>
                                @foreach($sasaranOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('sasaran') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="status" id="status" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="status" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Aktif</label>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                        <button type="button" wire:click="$set('showModal', false)" class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">Batal</button>
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
    <div class="fixed top-4 right-4 z-50 max-w-sm w-full sm:max-w-md">
        <div class="bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg text-sm">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="fixed top-4 right-4 z-50 max-w-sm w-full sm:max-w-md">
        <div class="bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg text-sm">
            {{ session('error') }}
        </div>
    </div>
    @endif
</div>