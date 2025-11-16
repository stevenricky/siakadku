<div class="p-4 sm:p-6">
    <!-- Header dan Tombol -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Arsip Dokumen</h1>
        <button wire:click="create" class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm sm:text-base flex items-center justify-center">
            <i class="fas fa-plus mr-2"></i>Unggah Dokumen
        </button>
    </div>

    <!-- Statistik Utama -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Total Dokumen</p>
                    <p class="text-lg sm:text-xl font-bold text-blue-600 dark:text-blue-400">{{ $totalDokumen }}</p>
                </div>
                <div class="flex-shrink-0 ml-2">
                    <i class="fas fa-file text-blue-600 dark:text-blue-400 text-sm sm:text-base"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Kategori</p>
                    <p class="text-lg sm:text-xl font-bold text-green-600 dark:text-green-400">{{ $totalKategori }}</p>
                </div>
                <div class="flex-shrink-0 ml-2">
                    <i class="fas fa-folder text-green-600 dark:text-green-400 text-sm sm:text-base"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Publik</p>
                    <p class="text-lg sm:text-xl font-bold text-purple-600 dark:text-purple-400">{{ $dokumenPublik }}</p>
                </div>
                <div class="flex-shrink-0 ml-2">
                    <i class="fas fa-lock-open text-purple-600 dark:text-purple-400 text-sm sm:text-base"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Terbatas</p>
                    <p class="text-lg sm:text-xl font-bold text-red-600 dark:text-red-400">{{ $dokumenTerbatas }}</p>
                </div>
                <div class="flex-shrink-0 ml-2">
                    <i class="fas fa-lock text-red-600 dark:text-red-400 text-sm sm:text-base"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Kategori Dokumen -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        @foreach($kategoriOptions as $key => $label)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i class="fas fa-{{ $key === 'akademik' ? 'graduation-cap' : ($key === 'kesiswaan' ? 'users' : ($key === 'administrasi' ? 'cog' : ($key === 'laporan' ? 'chart-bar' : 'folder'))) }} text-blue-600 dark:text-blue-400 text-sm"></i>
                </div>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $kategoriCounts[$key] ?? 0 }} file</span>
            </div>
            <h3 class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white mb-1 truncate">{{ $label }}</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1">
                @if($key === 'akademik') Kurikulum, silabus, RPP
                @elseif($key === 'kesiswaan') Data siswa, prestasi
                @elseif($key === 'administrasi') Surat, administrasi
                @elseif($key === 'laporan') Bulanan, semester
                @else Dokumen {{ $label }}
                @endif
            </p>
        </div>
        @endforeach
    </div>

    <!-- Search dan Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Cari nama dokumen, deskripsi, nomor..." 
                        class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
                <div class="w-full sm:w-32">
                    <select 
                        wire:model.live="perPage"
                        class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="5">5 data</option>
                        <option value="10">10 data</option>
                        <option value="25">25 data</option>
                        <option value="50">50 data</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Arsip - Desktop -->
    <div class="hidden lg:block bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dokumen</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Akses</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($arsip as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-4 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-{{ $this->getFileIcon($item->file_dokumen) }} text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div class="ml-4 min-w-0 flex-1">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $item->nama_dokumen }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        @if($item->file_exists)
                                            {{ $item->file_size }} • {{ strtoupper($item->file_extension) }}
                                        @else
                                            <span class="text-red-500">File tidak tersedia</span>
                                        @endif
                                    </div>
                                    @if($item->deskripsi)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate mt-1">{{ $item->deskripsi }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-300">
                                {{ $item->kategori_formatted }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                            <div>{{ $item->tanggal_dokumen->translatedFormat('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Tahun: {{ $item->tahun_arsip }}</div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($item->akses === 'publik') bg-green-100 text-green-800
                                @elseif($item->akses === 'terbatas') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $item->akses_formatted }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex space-x-1">
                                @if($item->file_exists)
                                <button wire:click="download({{ $item->id }})" class="p-1 text-blue-600 hover:text-blue-900 transition-colors" title="Unduh">
                                    <i class="fas fa-download text-xs"></i>
                                </button>
                                @else
                                <button class="p-1 text-gray-400 cursor-not-allowed" title="File tidak tersedia" disabled>
                                    <i class="fas fa-download text-xs"></i>
                                </button>
                                @endif
                                <button wire:click="showDetail({{ $item->id }})" class="p-1 text-purple-600 hover:text-purple-900 transition-colors" title="Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                                <button wire:click="edit({{ $item->id }})" class="p-1 text-green-600 hover:text-green-900 transition-colors" title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <button wire:click="delete({{ $item->id }})" onclick="return confirm('Hapus dokumen ini?')" class="p-1 text-red-600 hover:text-red-900 transition-colors" title="Hapus">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-folder-open text-2xl sm:text-3xl mb-2"></i>
                                <p class="text-sm sm:text-base">Tidak ada data arsip</p>
                                <p class="text-xs sm:text-sm mt-1">Klik tombol "Unggah Dokumen" untuk menambahkan dokumen</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Desktop -->
        @if($arsip->hasPages())
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
            {{ $arsip->links() }}
        </div>
        @endif
    </div>

    <!-- Card Arsip - Mobile -->
    <div class="lg:hidden space-y-3">
        @forelse($arsip as $item)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="space-y-3">
                <!-- Header -->
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3 flex-1 min-w-0">
                        <div class="flex-shrink-0 h-12 w-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-{{ $this->getFileIcon($item->file_dokumen) }} text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-2">{{ $item->nama_dokumen }}</h3>
                            <div class="flex items-center space-x-2 mt-1">
                                @if($item->file_exists)
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $item->file_size }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">•</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ strtoupper($item->file_extension) }}</span>
                                @else
                                    <span class="text-xs text-red-500">File tidak tersedia</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info -->
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Kategori</div>
                        <div class="text-gray-900 dark:text-white font-medium">{{ $item->kategori_formatted }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Tanggal</div>
                        <div class="text-gray-900 dark:text-white font-medium">{{ $item->tanggal_dokumen->translatedFormat('d/m/Y') }}</div>
                    </div>
                </div>

                <!-- Deskripsi -->
                @if($item->deskripsi)
                <div class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2">
                    {{ $item->deskripsi }}
                </div>
                @endif

                <!-- Status dan Aksi -->
                <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                        @if($item->akses === 'publik') bg-green-100 text-green-800
                        @elseif($item->akses === 'terbatas') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ $item->akses_formatted }}
                    </span>
                    
                    <div class="flex space-x-2">
                        @if($item->file_exists)
                        <button wire:click="download({{ $item->id }})" class="p-1 text-blue-600 hover:text-blue-900" title="Unduh">
                            <i class="fas fa-download text-xs"></i>
                        </button>
                        @else
                        <button class="p-1 text-gray-400 cursor-not-allowed" title="File tidak tersedia" disabled>
                            <i class="fas fa-download text-xs"></i>
                        </button>
                        @endif
                        <button wire:click="showDetail({{ $item->id }})" class="p-1 text-purple-600 hover:text-purple-900" title="Detail">
                            <i class="fas fa-eye text-xs"></i>
                        </button>
                        <button wire:click="edit({{ $item->id }})" class="p-1 text-green-600 hover:text-green-900" title="Edit">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                        <button wire:click="delete({{ $item->id }})" onclick="return confirm('Hapus dokumen ini?')" class="p-1 text-red-600 hover:text-red-900" title="Hapus">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
            <div class="text-gray-500 dark:text-gray-400">
                <i class="fas fa-folder-open text-3xl mb-3"></i>
                <p class="text-sm">Tidak ada data arsip</p>
                <p class="text-xs mt-1">Klik tombol "Unggah Dokumen" untuk menambah</p>
            </div>
        </div>
        @endforelse

        <!-- Pagination Mobile -->
        @if($arsip->hasPages())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            {{ $arsip->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Form -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-2 sm:p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl sm:max-w-4xl max-h-[95vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $modalTitle }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 p-1">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                
                <form wire:submit="save">
                    <div class="grid grid-cols-1 gap-3 sm:gap-4">
                        <!-- Nama Dokumen -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Dokumen *</label>
                            <input type="text" wire:model="nama_dokumen" placeholder="Nama dokumen..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('nama_dokumen') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kategori dan Nomor Dokumen -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori *</label>
                                <select wire:model="kategori_arsip" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoriOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_arsip') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Dokumen</label>
                                <input type="text" wire:model="nomor_dokumen" placeholder="Nomor dokumen..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                            <textarea wire:model="deskripsi" rows="2" placeholder="Deskripsi dokumen..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>

                        <!-- Tanggal dan Tahun -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Dokumen *</label>
                                <input type="date" wire:model="tanggal_dokumen" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('tanggal_dokumen') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Arsip *</label>
                                <select wire:model="tahun_arsip" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @foreach($tahunOptions as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                                @error('tahun_arsip') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Akses dan Lokasi Fisik -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Akses *</label>
                                <select wire:model="akses" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @foreach($aksesOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('akses') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lokasi Fisik</label>
                                <input type="text" wire:model="lokasi_fisik" placeholder="Lokasi penyimpanan fisik..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <!-- File Dokumen -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                File Dokumen *
                                @if(!$arsipId)
                                <span class="text-xs text-gray-500">(PDF, DOC, XLS, PPT, JPG, PNG - max 10MB)</span>
                                @else
                                <span class="text-xs text-gray-500">(Kosongkan jika tidak ingin mengubah file)</span>
                                @endif
                            </label>
                            <input type="file" wire:model="file_dokumen" 
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('file_dokumen') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            @if($file_dokumen)
                                <p class="text-xs text-green-600 mt-1">File: {{ $file_dokumen->getClientOriginalName() }}</p>
                            @endif
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan</label>
                            <textarea wire:model="keterangan" rows="2" placeholder="Keterangan tambahan..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <button type="button" wire:click="$set('showModal', false)" class="flex-1 sm:flex-none px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">Batal</button>
                        <button type="submit" class="flex-1 sm:flex-none px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Detail -->
    @if($showDetailModal && $selectedArsip)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-2 sm:p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-sm sm:max-w-2xl max-h-[95vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Dokumen</h3>
                    <button wire:click="$set('showDetailModal', false)" class="text-gray-400 hover:text-gray-600 p-1">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <!-- Header -->
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 h-16 w-16 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-{{ $this->getFileIcon($selectedArsip->file_dokumen) }} text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2 class="text-base sm:text-xl font-bold text-gray-900 dark:text-white line-clamp-2">{{ $selectedArsip->nama_dokumen }}</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                @if($selectedArsip->file_exists)
                                    {{ $selectedArsip->file_size }} • {{ strtoupper($selectedArsip->file_extension) }}
                                @else
                                    <span class="text-red-500">File tidak tersedia</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Info Utama -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Kategori</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedArsip->kategori_formatted }}</div>
                        </div>
                        <div>
                            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Akses</div>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($selectedArsip->akses === 'publik') bg-green-100 text-green-800
                                @elseif($selectedArsip->akses === 'terbatas') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $selectedArsip->akses_formatted }}
                            </span>
                        </div>
                    </div>

                    <!-- Informasi Dokumen -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Nomor Dokumen</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedArsip->nomor_dokumen ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Tanggal Dokumen</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedArsip->tanggal_dokumen->translatedFormat('d F Y') }}</div>
                        </div>
                        <div>
                            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Tahun Arsip</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedArsip->tahun_arsip }}</div>
                        </div>
                        <div>
                            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Lokasi Fisik</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedArsip->lokasi_fisik ?: '-' }}</div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    @if($selectedArsip->deskripsi)
                    <div>
                        <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-2">Deskripsi</div>
                        <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            {{ $selectedArsip->deskripsi }}
                        </div>
                    </div>
                    @endif

                    <!-- Keterangan -->
                    @if($selectedArsip->keterangan)
                    <div>
                        <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-2">Keterangan</div>
                        <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            {{ $selectedArsip->keterangan }}
                        </div>
                    </div>
                    @endif

                    <!-- Info Tambahan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div>
                            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Dibuat Oleh</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedArsip->createdBy->name }}</div>
                        </div>
                        <div>
                            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Dibuat Pada</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedArsip->created_at->translatedFormat('d F Y H:i') }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <button wire:click="$set('showDetailModal', false)" class="flex-1 sm:flex-none px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        Tutup
                    </button>
                    @if($selectedArsip->file_exists)
                    <button wire:click="download({{ $selectedArsip->id }})" class="flex-1 sm:flex-none px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-download mr-1"></i>Unduh
                    </button>
                    @endif
                    <button wire:click="edit({{ $selectedArsip->id }})" class="flex-1 sm:flex-none px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg z-50 max-w-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-4 right-4 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg z-50 max-w-sm">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span class="text-sm">{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>

