<div class="p-4 sm:p-6">
    <!-- Header dengan tombol tambah -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Materi Pembelajaran</h1>
        <button 
            wire:click="create" 
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center sm:justify-start space-x-2 transition-colors w-full sm:w-auto"
            type="button"
        >
            <i class="fas fa-plus"></i>
            <span>Tambah Materi</span>
        </button>
    </div>

    <!-- Search dan Filter -->
    <div class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
        <div class="flex flex-col gap-4">
            <div class="flex-1">
                <input 
                    type="text" 
                    wire:model.live="search" 
                    placeholder="Cari materi..." 
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                >
            </div>
            <div class="flex space-x-2">
                <select 
                    wire:model.live="perPage" 
                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                >
                    <option value="5">5 per halaman</option>
                    <option value="10">10 per halaman</option>
                    <option value="25">25 per halaman</option>
                    <option value="50">50 per halaman</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="sm:hidden space-y-4">
        @if($materiList->count() > 0)
            @foreach($materiList as $materi)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="text-base font-medium text-gray-900 dark:text-white">
                                {{ $materi->judul }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ $materi->mapel->nama_mapel }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $materi->tanggal_dibuat }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-1">
                            @if($materi->file)
                                <i class="{{ $materi->file_icon }} {{ $materi->file_icon_color }}"></i>
                            @elseif($materi->link)
                                <i class="fas fa-link text-blue-500"></i>
                            @else
                                <i class="fas fa-sticky-note text-gray-400"></i>
                            @endif
                        </div>
                    </div>
                    
                    @if($materi->deskripsi)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                            {{ Str::limit($materi->deskripsi, 100) }}
                        </p>
                    @endif
                    
                    <div class="flex justify-between items-center">
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            @if($materi->file)
                                {{ Str::limit($materi->file, 15) }}
                            @elseif($materi->link)
                                Link
                            @else
                                -
                            @endif
                        </div>
                        
                        <div class="flex space-x-2">
                            <button 
                                wire:click="view({{ $materi->id }})" 
                                class="p-2 text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/20 rounded"
                                type="button"
                                title="Lihat"
                            >
                                <i class="fas fa-eye"></i>
                            </button>
                            <button 
                                wire:click="edit({{ $materi->id }})" 
                                class="p-2 text-green-600 hover:bg-green-50 dark:text-green-400 dark:hover:bg-green-900/20 rounded"
                                type="button"
                                title="Edit"
                            >
                                <i class="fas fa-edit"></i>
                            </button>
                            @if($materi->file)
                                <button 
                                    wire:click="download({{ $materi->id }})" 
                                    class="p-2 text-purple-600 hover:bg-purple-50 dark:text-purple-400 dark:hover:bg-purple-900/20 rounded"
                                    type="button"
                                    title="Download"
                                >
                                    <i class="fas fa-download"></i>
                                </button>
                            @endif
                            <button 
                                wire:click="delete({{ $materi->id }})" 
                                wire:confirm="Apakah Anda yakin ingin menghapus materi ini?"
                                disabled
                                class="p-2 text-gray-400 cursor-not-allowed rounded"
                                type="button"
                                title="Hapus"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <!-- Pagination for Mobile -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                {{ $materiList->links() }}
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                <i class="fas fa-book-open text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <p class="text-gray-500 dark:text-gray-400 mb-4">Belum ada materi pembelajaran.</p>
                <button 
                    wire:click="create" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center justify-center space-x-2 transition-colors w-full sm:w-auto"
                    type="button"
                >
                    <i class="fas fa-plus"></i>
                    <span>Tambah Materi Pertama</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Desktop Table View -->
    <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        @if($materiList->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">File</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($materiList as $materi)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $materi->judul }}</div>
                                    @if($materi->deskripsi)
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ Str::limit($materi->deskripsi, 100) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $materi->mapel->nama_mapel }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($materi->file)
                                        <div class="flex items-center space-x-2">
                                            <i class="{{ $materi->file_icon }} {{ $materi->file_icon_color }}"></i>
                                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($materi->file, 20) }}</span>
                                        </div>
                                    @elseif($materi->link)
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-link text-blue-500"></i>
                                            <span class="text-sm text-blue-600 dark:text-blue-400">Link</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $materi->tanggal_dibuat }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button 
                                            wire:click="view({{ $materi->id }})" 
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                            type="button"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button 
                                            wire:click="edit({{ $materi->id }})" 
                                            class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                            type="button"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if($materi->file)
                                            <button 
                                                wire:click="download({{ $materi->id }})" 
                                                class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300"
                                                type="button"
                                            >
                                                <i class="fas fa-download"></i>
                                            </button>
                                        @endif
                                        <button 
                                            wire:click="delete({{ $materi->id }})" 
                                            wire:confirm="Apakah Anda yakin ingin menghapus materi ini?"
                                            disabled
                                            class="text-gray-400 cursor-not-allowed"
                                            type="button"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                {{ $materiList->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-book-open text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <p class="text-gray-500 dark:text-gray-400">Belum ada materi pembelajaran.</p>
                <button 
                    wire:click="create" 
                    class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center space-x-2 transition-colors"
                    type="button"
                >
                    <i class="fas fa-plus"></i>
                    <span>Tambah Materi Pertama</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Modal Tambah/Edit -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

                <!-- Modal panel -->
                <div class="relative inline-block w-full max-w-2xl px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-gray-800 rounded-lg shadow-xl sm:my-8 sm:align-middle sm:w-full sm:p-6">
                    <div class="absolute top-0 right-0 pt-4 pr-4">
                        <button wire:click="closeModal" type="button" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">
                        {{ $editMode ? 'Edit Materi' : 'Tambah Materi' }}
                    </h3>
                    
                    <form wire:submit="save">
                        <div class="space-y-4">
                            <!-- Mapel -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Mata Pelajaran *
                                </label>
                                <select 
                                    wire:model="mapelId" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                    required
                                >
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach($mapels as $mapel)
                                        <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                                    @endforeach
                                </select>
                                @error('mapelId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Judul -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Judul Materi *
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="judul" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                    placeholder="Masukkan judul materi..."
                                    required
                                >
                                @error('judul') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Deskripsi *
                                </label>
                                <textarea 
                                    wire:model="deskripsi" 
                                    rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                    placeholder="Masukkan deskripsi materi..."
                                    required
                                ></textarea>
                                @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- File -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    File Materi (Opsional)
                                </label>
                                <input 
                                    type="file" 
                                    wire:model="file" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                >
                                @error('file') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                @if($file)
                                    <p class="text-sm text-green-600 mt-1">File dipilih: {{ $file->getClientOriginalName() }}</p>
                                @endif
                            </div>

                            <!-- Link -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Link (Opsional)
                                </label>
                                <input 
                                    type="url" 
                                    wire:model="link" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                    placeholder="https://..."
                                >
                                @error('link') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                            <button 
                                type="button" 
                                wire:click="closeModal"
                                class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit" 
                                class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                {{ $editMode ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal View Materi -->
    @if($showViewModal && $selectedMateri)
    <div class="fixed inset-0 overflow-y-auto z-50">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6 w-full">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Detail Materi
                        </h3>
                        <button wire:click="$set('showViewModal', false)" 
                                class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Header Info -->
                        <div class="flex items-start space-x-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            @if($selectedMateri->file)
                            <i class="{{ $selectedMateri->file_icon }} {{ $selectedMateri->file_icon_color }} text-2xl mt-1"></i>
                            @elseif($selectedMateri->link)
                            <i class="fas fa-link text-blue-500 text-2xl mt-1"></i>
                            @else
                            <i class="fas fa-sticky-note text-gray-400 text-2xl mt-1"></i>
                            @endif
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $selectedMateri->judul }}
                                </h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $selectedMateri->mapel->nama_mapel }} • 
                                    {{ $selectedMateri->guru->nama_lengkap }} • 
                                    {{ $selectedMateri->tanggal_dibuat }}
                                </p>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Deskripsi Materi
                            </label>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">
                                    {{ $selectedMateri->deskripsi }}
                                </p>
                            </div>
                        </div>

                        <!-- File Info -->
                        @if($selectedMateri->file)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                File Materi
                            </label>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg gap-4">
                                <div class="flex items-center space-x-3">
                                    <i class="{{ $selectedMateri->file_icon }} {{ $selectedMateri->file_icon_color }} text-xl"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $selectedMateri->file }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ strtoupper(pathinfo($selectedMateri->file, PATHINFO_EXTENSION)) }} File
                                        </p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    @if($selectedMateri->can_preview)
                                    <a href="{{ $selectedMateri->file_url }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-purple-600 hover:bg-purple-700">
                                        <i class="fas fa-external-link-alt mr-1"></i> Preview
                                    </a>
                                    @endif
                                    <button wire:click="download({{ $selectedMateri->id }})" 
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                                        <i class="fas fa-download mr-1"></i> Download
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Link Info -->
                        @if($selectedMateri->link)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Link Materi
                            </label>
                            <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <a href="{{ $selectedMateri->link }}" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 break-all">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    {{ $selectedMateri->link }}
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="mt-5 sm:mt-6">
                    <button wire:click="$set('showViewModal', false)" 
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50">
            <div class="bg-green-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg flex items-center max-w-xs sm:max-w-md">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed bottom-4 right-4 z-50">
            <div class="bg-red-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg flex items-center max-w-xs sm:max-w-md">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span class="text-sm">{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>