<div class="p-4 sm:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Manajemen Buku</h1>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">Kelola koleksi buku perpustakaan</p>
        </div>
        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
            <button 
                wire:click="openTambahModal"
                class="bg-blue-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center flex-1 sm:flex-none"
            >
                <i class="fas fa-plus mr-2 text-xs sm:text-sm"></i>
                <span class="text-xs sm:text-sm">Tambah Buku</span>
            </button>
            <button 
                wire:click="exportCSV"
                class="bg-green-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-green-700 transition flex items-center justify-center flex-1 sm:flex-none"
            >
                <i class="fas fa-file-csv mr-2 text-xs sm:text-sm"></i>
                <span class="text-xs sm:text-sm">Export CSV</span>
            </button>
            <button 
                wire:click="printPDF"
                class="bg-red-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-red-700 transition flex items-center justify-center flex-1 sm:flex-none"
            >
                <i class="fas fa-file-pdf mr-2 text-xs sm:text-sm"></i>
                <span class="text-xs sm:text-sm">Print PDF</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-book text-blue-600 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Total Buku</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalBuku }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Tersedia</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $tersedia }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <i class="fas fa-book-reader text-orange-600 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Dipinjam</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $dipinjam }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-red-600 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Rusak/Hilang</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $rusakHilang }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                <input 
                    type="text" 
                    wire:model.live="search"
                    placeholder="Cari judul/penulis/penerbit..." 
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
                <select 
                    wire:model.live="kategoriFilter"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
                <select 
                    wire:model.live="statusFilter"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Status</option>
                    <option value="tersedia">Tersedia</option>
                    <option value="dipinjam">Dipinjam</option>
                    <option value="rusak">Rusak</option>
                    <option value="hilang">Hilang</option>
                </select>
                <select 
                    wire:model.live="perPage"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
                <button 
                    wire:click="resetFilters"
                    class="bg-gray-500 text-white px-3 py-2 text-sm rounded-lg hover:bg-gray-600 transition"
                >
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <!-- Mobile Card View -->
        <div class="sm:hidden p-4 space-y-4">
            @forelse($buku as $item)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Cover -->
                        <div class="flex-shrink-0">
                            @if($item->cover)
                                <img src="{{ asset('storage/' . $item->cover) }}" alt="{{ $item->judul }}" class="w-24 h-32 object-cover rounded">
                            @else
                                <div class="w-24 h-32 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center">
                                    <i class="fas fa-book text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Book Info -->
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                                {{ $item->judul }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                {{ $item->tahun_terbit }}
                            </p>
                            
                            <div class="grid grid-cols-2 gap-2 text-sm mb-2">
                                <div>
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Penulis:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $item->penulis }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Kategori:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $item->kategori->nama_kategori ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Stok:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $item->stok }} buku</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Dipinjam:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $item->dipinjam }} buku</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status_badge }}">
                                    {{ $item->status_text }}
                                </span>
                                
                                <div class="flex gap-1">
                                    <button 
                                        wire:click="showDetail({{ $item->id }})"
                                        class="text-blue-600 hover:text-blue-900 text-xs px-2 py-1 bg-blue-50 rounded"
                                        title="Detail"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button 
                                        wire:click="openEditModal({{ $item->id }})"
                                        class="text-yellow-600 hover:text-yellow-900 text-xs px-2 py-1 bg-yellow-50 rounded"
                                        title="Edit"
                                    >
                                        <i class="fas fa-edit"></i>
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
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="text-gray-500 dark:text-gray-400">
                        <i class="fas fa-book text-4xl mb-4"></i>
                        <p class="text-lg font-medium">Tidak ada data buku</p>
                        <p class="text-sm mt-2">Mulai dengan menambahkan buku baru</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cover</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Judul</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Penulis</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stok</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($buku as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                @if($item->cover)
                                    <img src="{{ asset('storage/' . $item->cover) }}" alt="{{ $item->judul }}" class="w-12 h-16 object-cover rounded">
                                @else
                                    <div class="w-12 h-16 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center">
                                        <i class="fas fa-book text-gray-400 text-lg"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->judul }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item->tahun_terbit }}
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $item->penulis }}</div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $item->kategori->nama_kategori ?? '-' }}
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $item->stok }} buku
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item->dipinjam }} dipinjam
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
                                        wire:click="showDetail({{ $item->id }})"
                                        class="text-blue-600 hover:text-blue-900 text-xs px-2 py-1 bg-blue-50 rounded"
                                        title="Detail"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button 
                                        wire:click="openEditModal({{ $item->id }})"
                                        class="text-yellow-600 hover:text-yellow-900 text-xs px-2 py-1 bg-yellow-50 rounded"
                                        title="Edit"
                                    >
                                        <i class="fas fa-edit"></i>
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
                            <td colspan="7" class="px-6 py-8 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-book text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">Tidak ada data buku</p>
                                    <p class="text-sm mt-2">Mulai dengan menambahkan buku baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-3 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $buku->links() }}
        </div>
    </div>

    <!-- Modal Tambah -->
    @if($showTambahModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Buku Baru</h3>
                    <button wire:click="closeTambahModal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form wire:submit="simpanBuku">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Kolom 1 -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ISBN</label>
                                <input 
                                    type="text" 
                                    wire:model="isbn" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                    placeholder="Masukkan ISBN"
                                >
                                @error('isbn') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul *</label>
                                <input 
                                    type="text" 
                                    wire:model="judul" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                    placeholder="Masukkan judul buku"
                                >
                                @error('judul') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Penulis *</label>
                                <input 
                                    type="text" 
                                    wire:model="penulis" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                    placeholder="Masukkan nama penulis"
                                >
                                @error('penulis') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Penerbit *</label>
                                <input 
                                    type="text" 
                                    wire:model="penerbit" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                    placeholder="Masukkan nama penerbit"
                                >
                                @error('penerbit') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Terbit *</label>
                                <input 
                                    type="number" 
                                    wire:model="tahunTerbit" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                    placeholder="Masukkan tahun terbit"
                                    min="1900"
                                    max="{{ date('Y') + 1 }}"
                                >
                                @error('tahunTerbit') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>

                        <!-- Kolom 2 -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori *</label>
                                <select 
                                    wire:model="kategoriId" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                >
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoriList as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('kategoriId') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stok *</label>
                                <input 
                                    type="number" 
                                    wire:model="stok" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                    placeholder="Masukkan jumlah stok"
                                    min="1"
                                >
                                @error('stok') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rak Buku *</label>
                                <input 
                                    type="text" 
                                    wire:model="rakBuku" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                    placeholder="Contoh: A1, B2, C3"
                                >
                                @error('rakBuku') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                                <select 
                                    wire:model="status" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                >
                                    <option value="tersedia">Tersedia</option>
                                    <option value="dipinjam">Dipinjam</option>
                                    <option value="rusak">Rusak</option>
                                    <option value="hilang">Hilang</option>
                                </select>
                                @error('status') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cover Buku</label>
                                <input 
                                    type="file" 
                                    wire:model="cover" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                    accept="image/*"
                                >
                                @error('cover') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi (full width) -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                        <textarea 
                            wire:model="deskripsi" 
                            rows="3" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                            placeholder="Deskripsi buku..."
                        ></textarea>
                        @error('deskripsi') 
                            <span class="text-red-500 text-xs">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button 
                            type="button" 
                            wire:click="closeTambahModal" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        >
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Edit -->
    @if($showEditModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Buku</h3>
                    <button wire:click="closeEditModal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form wire:submit="updateBuku">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Kolom 1 -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ISBN</label>
                                <input 
                                    type="text" 
                                    wire:model="isbn" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                    placeholder="Masukkan ISBN"
                                >
                                @error('isbn') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul *</label>
                                <input 
                                    type="text" 
                                    wire:model="judul" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                    placeholder="Masukkan judul buku"
                                >
                                @error('judul') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Penulis *</label>
                                <input 
                                    type="text" 
                                    wire:model="penulis" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                    placeholder="Masukkan nama penulis"
                                >
                                @error('penulis') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Penerbit *</label>
                                <input 
                                    type="text" 
                                    wire:model="penerbit" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                    placeholder="Masukkan nama penerbit"
                                >
                                @error('penerbit') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Terbit *</label>
                                <input 
                                    type="number" 
                                    wire:model="tahunTerbit" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                    placeholder="Masukkan tahun terbit"
                                    min="1900"
                                    max="{{ date('Y') + 1 }}"
                                >
                                @error('tahunTerbit') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>

                        <!-- Kolom 2 -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori *</label>
                                <select 
                                    wire:model="kategoriId" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                >
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoriList as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('kategoriId') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stok *</label>
                                <input 
                                    type="number" 
                                    wire:model="stok" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                    placeholder="Masukkan jumlah stok"
                                    min="0"
                                >
                                @error('stok') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rak Buku *</label>
                                <input 
                                    type="text" 
                                    wire:model="rakBuku" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                    placeholder="Contoh: A1, B2, C3"
                                >
                                @error('rakBuku') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                                <select 
                                    wire:model="status" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                >
                                    <option value="tersedia">Tersedia</option>
                                    <option value="dipinjam">Dipinjam</option>
                                    <option value="rusak">Rusak</option>
                                    <option value="hilang">Hilang</option>
                                </select>
                                @error('status') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cover Buku</label>
                                <input 
                                    type="file" 
                                    wire:model="cover" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                    accept="image/*"
                                >
                                @error('cover') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                                @if($bukuId)
                                    @php
                                        $currentBuku = \App\Models\Buku::find($bukuId);
                                    @endphp
                                    @if($currentBuku && $currentBuku->cover)
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Cover saat ini:</p>
                                            <img src="{{ asset('storage/' . $currentBuku->cover) }}" alt="Cover Preview" class="w-20 h-28 object-cover rounded mt-1">
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi (full width) -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                        <textarea 
                            wire:model="deskripsi" 
                            rows="3" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                            placeholder="Deskripsi buku..."
                        ></textarea>
                        @error('deskripsi') 
                            <span class="text-red-500 text-xs">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button 
                            type="button" 
                            wire:click="closeEditModal" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        >
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Detail -->
    @if($showDetailModal && $selectedBuku)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Buku</h3>
                    <button wire:click="closeDetail" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Cover -->
                    <div class="md:col-span-1">
                        @if($selectedBuku->cover)
                            <img src="{{ asset('storage/' . $selectedBuku->cover) }}" alt="{{ $selectedBuku->judul }}" class="w-full h-64 object-cover rounded-lg">
                        @else
                            <div class="w-full h-64 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Detail -->
                    <div class="md:col-span-2 space-y-3">
                        <div>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $selectedBuku->judul }}</h4>
                            <p class="text-gray-600 dark:text-gray-400">{{ $selectedBuku->tahun_terbit }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Penulis</p>
                                <p class="text-gray-900 dark:text-white">{{ $selectedBuku->penulis }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Penerbit</p>
                                <p class="text-gray-900 dark:text-white">{{ $selectedBuku->penerbit }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</p>
                                <p class="text-gray-900 dark:text-white">{{ $selectedBuku->kategori->nama_kategori ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">ISBN</p>
                                <p class="text-gray-900 dark:text-white">{{ $selectedBuku->isbn ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Total</p>
                                <p class="text-gray-900 dark:text-white">{{ $selectedBuku->stok }} buku</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Dipinjam</p>
                                <p class="text-gray-900 dark:text-white">{{ $selectedBuku->dipinjam }} buku</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Tersedia</p>
                                <p class="text-gray-900 dark:text-white">{{ $selectedBuku->stok_tersedia }} buku</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rak</p>
                                <p class="text-gray-900 dark:text-white">{{ $selectedBuku->rak_buku }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $selectedBuku->status_badge }}">
                                    {{ $selectedBuku->status_text }}
                                </span>
                            </div>
                        </div>

                        @if($selectedBuku->deskripsi)
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</p>
                            <p class="text-gray-900 dark:text-white mt-1">{{ $selectedBuku->deskripsi }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button 
                        type="button" 
                        wire:click="closeDetail" 
                        class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
                    >
                        Tutup
                    </button>
                </div>
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