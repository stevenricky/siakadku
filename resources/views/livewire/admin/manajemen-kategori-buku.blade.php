<div class="p-4 sm:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Manajemen Kategori Buku</h1>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">Kelola kategori koleksi buku perpustakaan</p>
        </div>
        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
            <button 
                wire:click="openCreateForm"
                class="bg-blue-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center flex-1 sm:flex-none"
            >
                <i class="fas fa-plus mr-2 text-xs sm:text-sm"></i>
                <span class="text-xs sm:text-sm">Tambah Kategori</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-layer-group text-blue-600 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Total Kategori</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalKategori }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Aktif</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalAktif }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-times-circle text-red-600 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Nonaktif</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalNonaktif }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <input 
                    type="text" 
                    wire:model.live="search"
                    placeholder="Cari nama/kode kategori..." 
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
                <select 
                    wire:model.live="perPage"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
                <button 
                    wire:click="$set('search', '')"
                    class="bg-gray-500 text-white px-3 py-2 text-sm rounded-lg hover:bg-gray-600 transition"
                >
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="w-full min-w-full sm:min-w-0">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kode</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah Buku</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($kategori as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <div class="text-xs sm:text-sm font-mono font-medium text-gray-900 dark:text-white">
                                    {{ $item->kode_kategori }}
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4">
                                <div class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->nama_kategori }}
                                </div>
                                @if($item->deskripsi)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ Str::limit($item->deskripsi, 50) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <div class="text-xs sm:text-sm text-gray-900 dark:text-white">
                                    {{ $item->buku_count }} buku
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status_badge }}">
                                    {{ $item->status_text }}
                                </span>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    <button 
                                        wire:click="openEditForm({{ $item->id }})"
                                        class="text-yellow-600 hover:text-yellow-900 text-xs px-2 py-1 bg-yellow-50 rounded"
                                        title="Edit"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button 
                                        wire:click="toggleStatus({{ $item->id }})"
                                        class="{{ $item->status ? 'text-red-600 hover:text-red-900 bg-red-50' : 'text-green-600 hover:text-green-900 bg-green-50' }} text-xs px-2 py-1 rounded"
                                        title="{{ $item->status ? 'Nonaktifkan' : 'Aktifkan' }}"
                                    >
                                        <i class="fas fa-{{ $item->status ? 'times' : 'check' }}"></i>
                                    </button>
                                    <!-- ⭐⭐ PERBAIKAN: Tombol delete dinonaktifkan ⭐⭐ -->
                                    <button 
                                        disabled
                                        class="text-gray-400 cursor-not-allowed opacity-50 text-xs px-2 py-1 bg-gray-100 rounded"
                                        title="Fungsi hapus dinonaktifkan"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-layer-group text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">Tidak ada data kategori</p>
                                    <p class="text-sm mt-2">Mulai dengan menambahkan kategori baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-3 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $kategori->links() }}
        </div>
    </div>

    <!-- Form Modal -->
    @if($showForm)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $formType === 'create' ? 'Tambah Kategori Baru' : 'Edit Kategori' }}
                    </h3>
                    <button wire:click="closeForm" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form wire:submit="saveKategori">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Kategori *</label>
                            <input 
                                type="text" 
                                wire:model="nama_kategori" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                placeholder="Masukkan nama kategori"
                            >
                            @error('nama_kategori') 
                                <span class="text-red-500 text-xs">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode Kategori *</label>
                            <input 
                                type="text" 
                                wire:model="kode_kategori" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                placeholder="Contoh: FIK, PEN, SNS" 
                                style="text-transform: uppercase"
                            >
                            @error('kode_kategori') 
                                <span class="text-red-500 text-xs">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                            <textarea 
                                wire:model="deskripsi" 
                                rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                placeholder="Deskripsi kategori..."
                            ></textarea>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    wire:model="status" 
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                >
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Kategori Aktif</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button 
                            type="button" 
                            wire:click="closeForm" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        >
                            {{ $formType === 'create' ? 'Simpan' : 'Update' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Flash Message -->
    @if(session()->has('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 max-w-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 max-w-sm">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span class="text-sm">{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>