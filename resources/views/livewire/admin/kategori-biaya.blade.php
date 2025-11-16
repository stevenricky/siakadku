<div class="p-4 sm:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Manajemen Kategori Biaya</h1>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">Kelola kategori biaya untuk sistem pembayaran</p>
        </div>
        <button 
            wire:click="openCreateForm"
            class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg flex items-center space-x-2 transition-colors duration-300 w-full sm:w-auto justify-center"
        >
            <i class="fas fa-plus text-xs sm:text-sm"></i>
            <span class="text-xs sm:text-sm">Tambah Kategori</span>
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4 transition-colors duration-300">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                    <i class="fas fa-list text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Total Kategori</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalKategori }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4 transition-colors duration-300">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                    <i class="fas fa-check text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Aktif</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalAktif }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4 transition-colors duration-300">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400">
                    <i class="fas fa-times text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Non Aktif</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalNonAktif }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4 transition-colors duration-300">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400">
                    <i class="fas fa-money-bill-wave text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Kategori SPP</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalSpp }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 transition-colors duration-300">
        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-white">Filter Data</h2>
        </div>
        <div class="p-3 sm:p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pencarian</label>
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Cari nama kategori..."
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300"
                    >
                </div>

                <!-- Jenis Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Biaya</label>
                    <select 
                        wire:model.live="jenisFilter"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300"
                    >
                        <option value="">Semua Jenis</option>
                        @foreach($jenisList as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Periode Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Periode</label>
                    <select 
                        wire:model.live="periodeFilter"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300"
                    >
                        <option value="">Semua Periode</option>
                        @foreach($periodeList as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select 
                        wire:model.live="statusFilter"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300"
                    >
                        <option value="">Semua Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Non Aktif</option>
                    </select>
                </div>

                <!-- Per Page -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data per Halaman</label>
                    <select 
                        wire:model.live="perPage"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300"
                    >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <!-- Reset Filters -->
            <div class="mt-4 flex justify-end">
                <button 
                    wire:click="resetFilters"
                    class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-300"
                >
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden transition-colors duration-300">
        <!-- Mobile Card View -->
        <div class="sm:hidden">
            @forelse($kategoriList as $kategori)
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $kategori->nama_kategori }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ Str::limit($kategori->deskripsi, 50) }}
                            </div>
                        </div>
                        <span @class([
                            'px-2 py-1 text-xs font-semibold rounded-full',
                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $kategori->status,
                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' => !$kategori->status
                        ]) 
                        wire:click="toggleStatus({{ $kategori->id }})"
                        title="Klik untuk {{ $kategori->status ? 'nonaktifkan' : 'aktifkan' }}"
                        >
                            {{ $kategori->status ? 'Aktif' : 'Non Aktif' }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 mb-3 text-xs">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Jenis:</span>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $jenisList[$kategori->jenis] }}
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Periode:</span>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $periodeList[$kategori->periode] }}
                            </div>
                        </div>
                        <div class="col-span-2">
                            <span class="text-gray-500 dark:text-gray-400">Jumlah Biaya:</span>
                            <div class="font-medium text-gray-900 dark:text-white">
                                Rp {{ number_format($kategori->jumlah_biaya, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-2">
                        <button 
                            wire:click="openEditForm({{ $kategori->id }})"
                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-300 p-1"
                            title="Edit"
                        >
                            <i class="fas fa-edit"></i>
                        </button>
                        <button 
                            wire:click="deleteKategori({{ $kategori->id }})"
                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-300 p-1"
                            onclick="return confirm('Hapus kategori biaya?')"
                            title="Hapus"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center">
                    <div class="text-gray-500 dark:text-gray-400">
                        <i class="fas fa-inbox text-4xl mb-4"></i>
                        <p class="text-lg font-medium">Tidak ada data kategori biaya</p>
                        <p class="text-sm mt-2">Mulai dengan menambahkan kategori biaya</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah Biaya</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Periode</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($kategoriList as $kategori)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-300">
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $kategori->nama_kategori }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($kategori->deskripsi, 50) }}</div>
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <span @class([
                                    'px-2 py-1 text-xs font-semibold rounded-full',
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' => $kategori->jenis === 'spp',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $kategori->jenis === 'dana_siswa',
                                    'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' => $kategori->jenis === 'lainnya'
                                ])>
                                    {{ $jenisList[$kategori->jenis] }}
                                </span>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                Rp {{ number_format($kategori->jumlah_biaya, 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $periodeList[$kategori->periode] }}
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <span @class([
                                    'px-2 py-1 text-xs font-semibold rounded-full cursor-pointer',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $kategori->status,
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' => !$kategori->status
                                ]) 
                                wire:click="toggleStatus({{ $kategori->id }})"
                                title="Klik untuk {{ $kategori->status ? 'nonaktifkan' : 'aktifkan' }}"
                                >
                                    {{ $kategori->status ? 'Aktif' : 'Non Aktif' }}
                                </span>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button 
                                    wire:click="openEditForm({{ $kategori->id }})"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-300"
                                    title="Edit"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button 
                                    wire:click="deleteKategori({{ $kategori->id }})"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-300"
                                    onclick="return confirm('Hapus kategori biaya?')"
                                    title="Hapus"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-inbox mr-2"></i>Tidak ada data kategori biaya
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-3 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $kategoriList->links() }}
        </div>
    </div>

    <!-- Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-75 overflow-y-auto h-full w-full z-50 transition-opacity duration-300">
            <div class="relative top-4 sm:top-20 mx-auto p-4 border w-full max-w-2xl shadow-lg rounded-lg bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 transition-colors duration-300 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $formType === 'create' ? 'Tambah Kategori Biaya' : 'Edit Kategori Biaya' }}
                    </h3>
                    <button wire:click="closeForm" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form wire:submit="saveKategori">
                    <div class="space-y-4 mt-4">
                        <!-- Nama Kategori -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Kategori *</label>
                            <input 
                                type="text" 
                                wire:model="nama_kategori"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                                placeholder="Masukkan nama kategori"
                                required
                            >
                            @error('nama_kategori') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                            <textarea 
                                wire:model="deskripsi"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                                placeholder="Deskripsi kategori biaya..."
                            ></textarea>
                            @error('deskripsi') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Jenis -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Biaya *</label>
                                <select 
                                    wire:model="jenis"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                                    required
                                >
                                    @foreach($jenisList as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('jenis') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                            </div>

                            <!-- Periode -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Periode *</label>
                                <select 
                                    wire:model="periode"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                                    required
                                >
                                    @foreach($periodeList as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('periode') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Jumlah Biaya -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah Biaya *</label>
                            <input 
                                type="number" 
                                wire:model="jumlah_biaya"
                                min="0"
                                step="0.01"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                                placeholder="0"
                                required
                            >
                            @error('jumlah_biaya') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    wire:model="status"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                >
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Aktif</span>
                            </label>
                            @error('status') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button 
                            type="button"
                            wire:click="closeForm"
                            class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-300 w-full sm:w-auto"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 rounded-lg transition-colors duration-300 w-full sm:w-auto"
                        >
                            {{ $formType === 'create' ? 'Simpan' : 'Update' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 z-50 max-w-sm w-full">
            <div class="bg-green-500 dark:bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg transition-colors duration-300">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-check-circle"></i>
                    <span class="text-sm">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-50 max-w-sm w-full">
            <div class="bg-red-500 dark:bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg transition-colors duration-300">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-exclamation-circle"></i>
                    <span class="text-sm">{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif
</div>