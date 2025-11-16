<div class="p-4 sm:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Peminjaman Buku</h1>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">Kelola peminjaman dan pengembalian buku perpustakaan</p>
        </div>
        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
            <button 
                wire:click="openTambahModal"
                class="bg-blue-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center flex-1 sm:flex-none"
            >
                <i class="fas fa-plus mr-2 text-xs sm:text-sm"></i>
                <span class="text-xs sm:text-sm">Peminjaman Baru</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-exchange-alt text-blue-600 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Sedang Dipinjam</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalDipinjam }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-red-600 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Terlambat</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTerlambat }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Dikembalikan</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalDikembalikan }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <input 
                    type="text" 
                    wire:model.live="search"
                    placeholder="Cari kode/nama peminjam/buku..." 
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
                <select 
                    wire:model.live="statusFilter"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Status</option>
                    <option value="dipinjam">Dipinjam</option>
                    <option value="dikembalikan">Dikembalikan</option>
                    <option value="terlambat">Terlambat</option>
                    <option value="hilang">Hilang</option>
                </select>
                <input 
                    type="date" 
                    wire:model.live="tanggalFilter"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
                <button 
                    wire:click="resetFilters"
                    class="bg-gray-500 text-white px-3 py-2 text-sm rounded-lg hover:bg-gray-600 transition"
                >
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="sm:hidden space-y-4">
        @forelse($peminjaman as $item)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <div class="text-xs sm:text-sm font-mono font-medium text-gray-900 dark:text-white">
                            {{ $item->kode_peminjaman }}
                        </div>
                        <div class="flex items-center mt-2">
                            <div class="h-8 w-8 flex-shrink-0 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-xs"></i>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->peminjam->name ?? 'Tidak diketahui' }}
                                    @if($item->peminjam && $item->peminjam->siswa)
                                        ({{ $item->peminjam->siswa->nama_lengkap ?? 'Siswa' }})
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    @if($item->peminjam && $item->peminjam->siswa && $item->peminjam->siswa->kelas)
                                        {{ $item->peminjam->siswa->kelas->nama_kelas ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status_badge }}">
                        {{ $item->status_text }}
                    </span>
                </div>
                
                <div class="space-y-2 mb-3">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Buku</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->barang->nama_barang ?? 'Tidak diketahui' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Pinjam</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Kembali</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($item->tanggal_kembali)->translatedFormat('d M Y') }}</span>
                    </div>
                    @if($item->denda > 0)
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Denda</span>
                        <span class="text-sm font-medium text-red-600 dark:text-red-400">Rp {{ number_format($item->denda, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
                
                <div class="flex justify-between items-center pt-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex space-x-2">
                        <button 
                            wire:click="showDetail({{ $item->id }})"
                            class="text-blue-600 hover:text-blue-900 text-xs px-2 py-1 bg-blue-50 rounded"
                            title="Detail"
                        >
                            <i class="fas fa-eye"></i>
                        </button>
                        @if(in_array($item->status, ['dipinjam', 'terlambat']))
                            <button 
                                wire:click="openKembalikanModal({{ $item->id }})"
                                class="text-green-600 hover:text-green-900 text-xs px-2 py-1 bg-green-50 rounded"
                                title="Kembalikan"
                            >
                                <i class="fas fa-undo"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                <div class="text-gray-500 dark:text-gray-400">
                    <i class="fas fa-exchange-alt text-4xl mb-4"></i>
                    <p class="text-lg font-medium">Tidak ada data peminjaman</p>
                    <p class="text-sm mt-2">Mulai dengan membuat peminjaman baru</p>
                </div>
            </div>
        @endforelse
        
        <!-- Pagination for Mobile -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            {{ $peminjaman->links() }}
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kode</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Peminjam</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Buku</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Pinjam</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($peminjaman as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <div class="text-xs sm:text-sm font-mono font-medium text-gray-900 dark:text-white">
                                    {{ $item->kode_peminjaman }}
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 flex-shrink-0 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600 text-xs"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item->peminjam->name ?? 'Tidak diketahui' }}
                                            @if($item->peminjam && $item->peminjam->siswa)
                                                ({{ $item->peminjam->siswa->nama_lengkap ?? 'Siswa' }})
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            @if($item->peminjam && $item->peminjam->siswa && $item->peminjam->siswa->kelas)
                                                {{ $item->peminjam->siswa->kelas->nama_kelas ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->barang->nama_barang ?? 'Tidak diketahui' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item->barang->kategori->nama ?? '-' }}
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Kembali: {{ \Carbon\Carbon::parse($item->tanggal_kembali)->translatedFormat('d M Y') }}
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status_badge }}">
                                    {{ $item->status_text }}
                                </span>
                                @if($item->denda > 0)
                                    <div class="text-xs text-red-600 dark:text-red-400 mt-1">
                                        Denda: Rp {{ number_format($item->denda, 0, ',', '.') }}
                                    </div>
                                @endif
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
                                    @if(in_array($item->status, ['dipinjam', 'terlambat']))
                                        <button 
                                            wire:click="openKembalikanModal({{ $item->id }})"
                                            class="text-green-600 hover:text-green-900 text-xs px-2 py-1 bg-green-50 rounded"
                                            title="Kembalikan"
                                        >
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-exchange-alt text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">Tidak ada data peminjaman</p>
                                    <p class="text-sm mt-2">Mulai dengan membuat peminjaman baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-3 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $peminjaman->links() }}
        </div>
    </div>

    <!-- Modal Tambah Peminjaman -->
    @if($showTambahModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Peminjaman Buku Baru</h3>
                    <button wire:click="closeTambahModal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form wire:submit="simpanPeminjaman">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Peminjam *</label>
                            <select 
                                wire:model="peminjamId" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">Pilih Peminjam</option>
                                @foreach($siswaList as $siswa)
                                    @if($siswa->user)
                                        <option value="{{ $siswa->user->id }}">
                                            {{ $siswa->nama_lengkap }} - {{ $siswa->kelas->nama_kelas ?? '-' }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('peminjamId') 
                                <span class="text-red-500 text-xs">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Barang *</label>
                            <select 
                                wire:model="barangId" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">Pilih Barang</option>
                                @foreach($barangList as $barang)
                                    <option value="{{ $barang->id }}">
                                        {{ $barang->nama_barang }} (Stok: {{ $barang->jumlah }})
                                    </option>
                                @endforeach
                            </select>
                            @error('barangId') 
                                <span class="text-red-500 text-xs">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Pinjam *</label>
                                <input 
                                    type="date" 
                                    wire:model="tanggalPinjam" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                >
                                @error('tanggalPinjam') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Kembali *</label>
                                <input 
                                    type="date" 
                                    wire:model="tanggalKembali" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                >
                                @error('tanggalKembali') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan</label>
                            <textarea 
                                wire:model="keterangan" 
                                rows="2" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                placeholder="Keterangan peminjaman..."
                            ></textarea>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-end gap-3 mt-6">
                        <button 
                            type="button" 
                            wire:click="closeTambahModal" 
                            class="w-full sm:w-auto px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        >
                            Simpan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Detail -->
    @if($showDetailModal && $selectedPeminjaman)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Peminjaman</h3>
                    <button wire:click="closeDetail" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode Peminjaman</p>
                            <p class="text-gray-900 dark:text-white font-mono">{{ $selectedPeminjaman->kode_peminjaman }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $selectedPeminjaman->status_badge }}">
                                {{ $selectedPeminjaman->status_text }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Peminjam</p>
                        <p class="text-gray-900 dark:text-white">{{ $selectedPeminjaman->peminjam->name ?? 'Tidak diketahui' }}</p>
                        @if($selectedPeminjaman->peminjam && $selectedPeminjaman->peminjam->siswa)
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedPeminjaman->peminjam->siswa->nama_lengkap }}</p>
                            @if($selectedPeminjaman->peminjam->siswa->kelas)
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedPeminjaman->peminjam->siswa->kelas->nama_kelas }}</p>
                            @endif
                        @endif
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Barang yang Dipinjam</p>
                        <p class="text-gray-900 dark:text-white">{{ $selectedPeminjaman->barang->nama_barang ?? 'Tidak diketahui' }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedPeminjaman->barang->kategori->nama ?? '-' }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Pinjam</p>
                            <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($selectedPeminjaman->tanggal_pinjam)->translatedFormat('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Kembali</p>
                            <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($selectedPeminjaman->tanggal_kembali)->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>

                    @if($selectedPeminjaman->tanggal_dikembalikan)
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Dikembalikan</p>
                        <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($selectedPeminjaman->tanggal_dikembalikan)->translatedFormat('d F Y') }}</p>
                    </div>
                    @endif

                    @if($selectedPeminjaman->denda > 0)
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Denda</p>
                        <p class="text-red-600 dark:text-red-400 font-semibold">Rp {{ number_format($selectedPeminjaman->denda, 0, ',', '.') }}</p>
                    </div>
                    @endif

                    @if($selectedPeminjaman->keterangan)
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Keterangan</p>
                        <p class="text-gray-900 dark:text-white">{{ $selectedPeminjaman->keterangan }}</p>
                    </div>
                    @endif

                    <!-- Info Durasi -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                        <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Durasi Peminjaman</p>
                        <p class="text-blue-700 dark:text-blue-400">{{ $selectedPeminjaman->durasi_formatted }}</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:justify-end gap-3 mt-6">
                    <button 
                        type="button" 
                        wire:click="closeDetail" 
                        class="w-full sm:w-auto px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
                    >
                        Tutup
                    </button>
                    @if(in_array($selectedPeminjaman->status, ['dipinjam', 'terlambat']))
                        <button 
                            wire:click="openKembalikanModal({{ $selectedPeminjaman->id }})"
                            type="button"
                            class="w-full sm:w-auto px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                        >
                            <i class="fas fa-undo mr-2"></i>
                            Kembalikan Buku
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Pengembalian -->
    @if($showKembalikanModal && $selectedPeminjaman)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pengembalian Buku</h3>
                    <button wire:click="closeKembalikanModal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="kembalikanBuku">
                    <div class="space-y-4">
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                            <div class="text-sm text-blue-800 dark:text-blue-300">
                                <p class="font-medium">Detail Peminjaman</p>
                                <p class="mt-1">{{ $selectedPeminjaman->peminjam->name ?? 'Tidak diketahui' }}</p>
                                @if($selectedPeminjaman->peminjam && $selectedPeminjaman->peminjam->siswa)
                                    <p class="text-xs mt-1">{{ $selectedPeminjaman->peminjam->siswa->nama_lengkap }}</p>
                                @endif
                                <p class="text-xs mt-1">{{ $selectedPeminjaman->barang->nama_barang ?? 'Tidak diketahui' }}</p>
                                <p class="text-xs mt-1">Kode: {{ $selectedPeminjaman->kode_peminjaman }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Dikembalikan *</label>
                            <input 
                                type="date" 
                                wire:model="tanggalDikembalikan" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                            >
                            @error('tanggalDikembalikan') 
                                <span class="text-red-500 text-xs">{{ $message }}</span> 
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kondisi Barang *</label>
                            <select 
                                wire:model="kondisiBuku" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                            >
                                <option value="baik">Baik</option>
                                <option value="rusak">Rusak</option>
                                <option value="hilang">Hilang</option>
                            </select>
                            @error('kondisiBuku') 
                                <span class="text-red-500 text-xs">{{ $message }}</span> 
                            @endif
                        </div>

                        @if($denda > 0)
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                                <span class="text-sm font-medium text-yellow-800 dark:text-yellow-300">
                                    Terlambat: Rp {{ number_format($denda, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan Pengembalian</label>
                            <textarea 
                                wire:model="keteranganPengembalian" 
                                rows="2" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                placeholder="Keterangan kondisi barang..."
                            ></textarea>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-end gap-3 mt-6">
                        <button 
                            type="button" 
                            wire:click="closeKembalikanModal" 
                            class="w-full sm:w-auto px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="w-full sm:w-auto px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                        >
                            <i class="fas fa-check mr-2"></i>
                            Proses Pengembalian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Success/Error Messages -->
    @if (session()->has('success'))
    <div class="fixed top-4 right-4 z-50 animate-fade-in">
        <div class="bg-green-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg flex items-center max-w-xs sm:max-w-md">
            <i class="fas fa-check-circle mr-2"></i>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="fixed top-4 right-4 z-50 animate-fade-in">
        <div class="bg-red-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg flex items-center max-w-xs sm:max-w-md">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <span class="text-sm">{{ session('error') }}</span>
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