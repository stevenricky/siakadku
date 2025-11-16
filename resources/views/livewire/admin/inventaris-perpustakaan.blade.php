{{-- resources/views/livewire/admin/inventaris-perpustakaan.blade.php --}}
<div class="p-4 sm:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Inventaris Perpustakaan</h1>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">
                Kelola koleksi buku dan statistik perpustakaan
            </p>
        </div>
        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
            <button 
                wire:click="openTambahModal"
                class="bg-blue-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center flex-1 sm:flex-none"
            >
                <i class="fas fa-plus mr-2 text-xs sm:text-sm"></i>
                <span class="text-xs sm:text-sm">Tambah Buku</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <!-- ... stats cards tetap sama ... -->
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                <input 
                    type="text" 
                    wire:model.live="search"
                    placeholder="Cari judul/penulis/penerbit..." 
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white sm:col-span-2"
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
                <button 
                    wire:click="resetFilters"
                    class="bg-gray-500 text-white px-3 py-2 text-sm rounded-lg hover:bg-gray-600 transition"
                >
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Buku</h3>
                    <select 
                        wire:model.live="perPage" 
                        class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                    >
                        <option value="10">10 per halaman</option>
                        <option value="25">25 per halaman</option>
                        <option value="50">50 per halaman</option>
                    </select>
                </div>
                
                <!-- Card Layout for Books -->
                <div class="p-4">
                    @forelse($buku as $item)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4 hover:shadow-md transition-shadow">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                                <div class="flex-1">
                                    <div class="flex items-start justify-between">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                            {{ $item->judul }}
                                        </h4>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status_badge }}">
                                            {{ $item->status_text }}
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <i class="fas fa-user mr-2"></i>
                                            <span>Penulis: {{ $item->penulis }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <i class="fas fa-building mr-2"></i>
                                            <span>Penerbit: {{ $item->penerbit }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <i class="fas fa-calendar mr-2"></i>
                                            <span>Tahun: {{ $item->tahun_terbit }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <i class="fas fa-layer-group mr-2"></i>
                                            <span>Kategori: {{ $item->kategori->nama_kategori ?? '-' }}</span>
                                        </div>
                                        @if($item->isbn)
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                <i class="fas fa-barcode mr-2"></i>
                                                <span>ISBN: {{ $item->isbn }}</span>
                                            </div>
                                        @endif
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <i class="fas fa-map-marker-alt mr-2"></i>
                                            <span>Rak: {{ $item->rak_buku }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                Stok:
                                            </span>
                                            <span class="text-sm font-mono text-gray-900 dark:text-white">
                                                {{ $item->stok_tersedia }}/{{ $item->stok }}
                                            </span>
                                            @if($item->stok_tersedia <= 0)
                                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Habis</span>
                                            @elseif($item->stok_tersedia < 5)
                                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Sedikit</span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex space-x-2 mt-2 sm:mt-0">
                                            <button 
                                                wire:click="showDetail({{ $item->id }})"
                                                class="text-blue-600 hover:text-blue-900 text-xs px-2 py-1 bg-blue-50 rounded"
                                                title="Detail"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button 
                                                wire:click="openEditModal({{ $item->id }})"
                                                class="text-green-600 hover:text-green-900 text-xs px-2 py-1 bg-green-50 rounded"
                                                title="Edit"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <!-- Tombol hapus dinonaktifkan -->
                                            <button 
                                                disabled
                                                class="text-gray-400 text-xs px-2 py-1 bg-gray-100 rounded cursor-not-allowed"
                                                title="Hapus dinonaktifkan"
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

                <!-- Pagination -->
                @if($buku->hasPages())
                <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $buku->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Buku Populer -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Buku Populer</h3>
                <div class="space-y-3">
                    @forelse($bukuPopuler as $populer)
                        @if($populer->buku)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded flex items-center justify-center">
                                    <i class="fas fa-book text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ \Illuminate\Support\Str::limit($populer->buku->judul, 25) }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $populer->total_peminjaman }} peminjaman
                                    </p>
                                </div>
                            </div>
                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                {{ $populer->buku->kategori->nama_kategori ?? '-' }}
                            </span>
                        </div>
                        @endif
                    @empty
                        <div class="text-center text-gray-500 dark:text-gray-400 py-4">
                            <i class="fas fa-chart-bar text-2xl mb-2"></i>
                            <p class="text-sm">Belum ada data peminjaman</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Statistik Kategori -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Statistik Kategori</h3>
                <div class="space-y-3">
                    @forelse($statistikKategori as $kategori)
                        <div class="flex justify-between items-center p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded">
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $kategori->nama_kategori }}
                            </span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-600 px-2 py-1 rounded">
                                {{ $kategori->buku_count }} buku
                            </span>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 dark:text-gray-400 py-4">
                            <i class="fas fa-folder text-2xl mb-2"></i>
                            <p class="text-sm">Belum ada kategori</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Aksi Cepat</h3>
                <div class="space-y-2">
                    <button 
                        wire:click="openTambahModal"
                        class="w-full text-left px-4 py-2 text-sm text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900 rounded-lg transition"
                    >
                        <i class="fas fa-plus mr-2"></i>Tambah Buku Baru
                    </button>
                    <button 
                        class="w-full text-left px-4 py-2 text-sm text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900 rounded-lg transition"
                    >
                        <i class="fas fa-file-export mr-2"></i>Ekspor Data Buku
                    </button>
                    <button 
                        wire:click="refreshData"
                        class="w-full text-left px-4 py-2 text-sm text-purple-600 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900 rounded-lg transition"
                    >
                        <i class="fas fa-sync-alt mr-2"></i>Refresh Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Modal Tambah Buku -->
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
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ISBN</label>
                                <input 
                                    type="text" 
                                    wire:model="isbn" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                    placeholder="ISBN buku (opsional)"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul *</label>
                                <input 
                                    type="text" 
                                    wire:model="judul" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                    placeholder="Judul buku"
                                >
                                @error('judul') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Penulis *</label>
                                <input 
                                    type="text" 
                                    wire:model="penulis" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                    placeholder="Nama penulis"
                                >
                                @error('penulis') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Penerbit *</label>
                                <input 
                                    type="text" 
                                    wire:model="penerbit" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                    placeholder="Nama penerbit"
                                >
                                @error('penerbit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Terbit *</label>
                                <input 
                                    type="number" 
                                    wire:model="tahun_terbit" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                    placeholder="Tahun terbit"
                                    min="1900"
                                    max="{{ date('Y') }}"
                                >
                                @error('tahun_terbit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori *</label>
                                <select 
                                    wire:model="kategori_id" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                >
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoriList as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stok *</label>
                                <input 
                                    type="number" 
                                    wire:model="stok" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                    placeholder="Jumlah stok"
                                    min="0"
                                >
                                @error('stok') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rak Buku *</label>
                                <input 
                                    type="text" 
                                    wire:model="rak_buku" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                    placeholder="Lokasi rak"
                                >
                                @error('rak_buku') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select 
                                wire:model="status" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                            >
                                <option value="tersedia">Tersedia</option>
                                <option value="dipinjam">Dipinjam</option>
                                <option value="rusak">Rusak</option>
                                <option value="hilang">Hilang</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                            <textarea 
                                wire:model="deskripsi" 
                                rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                placeholder="Deskripsi buku..."
                            ></textarea>
                        </div>
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
                            Simpan Buku
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Edit Buku -->
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
                    <div class="space-y-4">
                        <!-- Form fields sama seperti modal tambah -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ISBN</label>
                                <input 
                                    type="text" 
                                    wire:model="isbn" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                    placeholder="ISBN buku (opsional)"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul *</label>
                                <input 
                                    type="text" 
                                    wire:model="judul" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                    placeholder="Judul buku"
                                >
                                @error('judul') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- ... fields lainnya sama ... -->

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
                            Update Buku
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Detail Buku -->
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
                
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Judul</p>
                            <p class="text-gray-900 dark:text-white">{{ $selectedBuku->judul }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Penulis</p>
                            <p class="text-gray-900 dark:text-white">{{ $selectedBuku->penulis }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Penerbit</p>
                            <p class="text-gray-900 dark:text-white">{{ $selectedBuku->penerbit }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tahun Terbit</p>
                            <p class="text-gray-900 dark:text-white">{{ $selectedBuku->tahun_terbit }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</p>
                            <p class="text-gray-900 dark:text-white">{{ $selectedBuku->kategori->nama_kategori ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $selectedBuku->status_badge }}">
                                {{ $selectedBuku->status_text }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok</p>
                            <p class="text-gray-900 dark:text-white">{{ $selectedBuku->stok_tersedia }}/{{ $selectedBuku->stok }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rak Buku</p>
                            <p class="text-gray-900 dark:text-white">{{ $selectedBuku->rak_buku }}</p>
                        </div>
                    </div>

                    @if($selectedBuku->isbn)
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">ISBN</p>
                        <p class="text-gray-900 dark:text-white font-mono">{{ $selectedBuku->isbn }}</p>
                    </div>
                    @endif

                    @if($selectedBuku->deskripsi)
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</p>
                        <p class="text-gray-900 dark:text-white">{{ $selectedBuku->deskripsi }}</p>
                    </div>
                    @endif
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button 
                        type="button" 
                        wire:click="closeDetail" 
                        class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
                    >
                        Tutup
                    </button>
                    <button 
                        wire:click="openEditModal({{ $selectedBuku->id }})"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                    >
                        <i class="fas fa-edit mr-2"></i>
                        Edit Buku
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Success/Error Messages -->
    @if (session()->has('success'))
    <div class="fixed top-4 right-4 z-50 animate-fade-in">
        <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="fixed top-4 right-4 z-50 animate-fade-in">
        <div class="bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('error') }}
        </div>
    </div>
    @endif

    @if (session()->has('info'))
    <div class="fixed top-4 right-4 z-50 animate-fade-in">
        <div class="bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
            <i class="fas fa-info-circle mr-2"></i>
            {{ session('info') }}
        </div>
    </div>
    @endif

    <!-- Loading Indicator -->
    <div wire:loading class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 flex items-center">
            <i class="fas fa-spinner fa-spin text-blue-600 mr-3"></i>
            <span class="text-gray-700 dark:text-gray-300">Memproses...</span>
        </div>
    </div>
</div>