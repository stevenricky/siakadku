<div>
    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 gap-3">
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl sm:text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate">
                        📝 Tugas & Kuis
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Kelola tugas, kuis, dan ulangan untuk siswa
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <!-- Search Mobile -->
                    <div class="sm:hidden">
                        <input type="text" 
                               wire:model.debounce.300ms="search"
                               placeholder="Cari tugas..."
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm">
                    </div>
                    
                    <button wire:click="create" 
                            class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus mr-2"></i>
                        Buat Tugas
                    </button>
                </div>
            </div>

            <!-- Search Desktop -->
            <div class="hidden sm:block mb-4 sm:mb-6 bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               wire:model.debounce.300ms="search"
                               placeholder="Cari berdasarkan judul tugas atau nama mapel..."
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            @if($tugasList->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tasks text-blue-600 dark:text-blue-400 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Tugas</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $tugasList->total() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-play-circle text-green-600 dark:text-green-400 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Aktif</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $tugasList->where('status', 'active')->count() }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-orange-600 dark:text-orange-400 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Mendesak</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $tugasList->where('status', 'urgent')->count() }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kadaluarsa</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $tugasList->where('status', 'expired')->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Tugas List -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                @if($tugasList->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Tugas
                                </th>
                                <th class="hidden sm:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Mapel & Tipe
                                </th>
                                <th class="hidden md:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Deadline
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status & Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($tugasList as $tugas)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <td class="px-3 py-4">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            @if($tugas->file)
                                            <i class="fas fa-file text-blue-500 text-lg"></i>
                                            @else
                                            <i class="fas fa-sticky-note text-gray-400 text-lg"></i>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $tugas->judul }}
                                                </div>
                                                @if(!$tugas->is_published)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    <i class="fas fa-eye-slash mr-1"></i> Draft
                                                </span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2">
                                                {{ Str::limit($tugas->deskripsi, 100) }}
                                            </div>
                                            <div class="flex items-center mt-1 space-x-2">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $tugas->tipe_color }}-100 text-{{ $tugas->tipe_color }}-800 dark:bg-{{ $tugas->tipe_color }}-900 dark:text-{{ $tugas->tipe_color }}-200">
                                                    {{ ucfirst($tugas->tipe) }}
                                                </span>
                                                @if($tugas->file)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    <i class="fas fa-paperclip mr-1"></i> File
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sm:hidden text-xs text-gray-500 dark:text-gray-400 mt-2">
                                        {{ $tugas->mapel->nama_mapel }} • 
                                        <span class="capitalize">{{ $tugas->tipe }}</span>
                                    </div>
                                    <div class="md:hidden text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Deadline: {{ $tugas->deadline->format('d/m/Y H:i') }}
                                    </div>
                                </td>
                                <td class="hidden sm:table-cell px-3 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $tugas->mapel->nama_mapel }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 capitalize">{{ $tugas->tipe }}</div>
                                </td>
                                <td class="hidden md:table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    <div class="flex flex-col">
                                        <span>{{ $tugas->deadline->format('d F Y H:i') }}</span>
                                        <span class="text-xs text-{{ $tugas->status_color }}-600 dark:text-{{ $tugas->status_color }}-400">
                                            {{ $tugas->deadline_relative }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-1 items-start sm:items-center">
                                        <!-- Status Badge -->
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-{{ $tugas->status_color }}-100 text-{{ $tugas->status_color }}-800 dark:bg-{{ $tugas->status_color }}-900 dark:text-{{ $tugas->status_color }}-200 mb-2 sm:mb-0">
                                            @if($tugas->status == 'expired')
                                            <i class="fas fa-times mr-1"></i> Kadaluarsa
                                            @elseif($tugas->status == 'urgent')
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Mendesak
                                            @else
                                            <i class="fas fa-check mr-1"></i> Aktif
                                            @endif
                                        </span>

                                        <div class="flex flex-wrap gap-1">
                                            <!-- View Button -->
                                            <button wire:click="view({{ $tugas->id }})" 
                                                    class="inline-flex items-center text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-xs px-2 py-1 rounded hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                                <i class="fas fa-eye mr-1"></i>
                                            </button>

                                            <!-- Download Button -->
                                            @if($tugas->file)
                                            <button wire:click="download({{ $tugas->id }})" 
                                                    class="inline-flex items-center text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 text-xs px-2 py-1 rounded hover:bg-green-50 dark:hover:bg-green-900/20">
                                                <i class="fas fa-download mr-1"></i>
                                            </button>
                                            @endif

                                            <!-- Edit Button -->
                                            <button wire:click="edit({{ $tugas->id }})" 
                                                    class="inline-flex items-center text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300 text-xs px-2 py-1 rounded hover:bg-orange-50 dark:hover:bg-orange-900/20">
                                                <i class="fas fa-edit mr-1"></i>
                                            </button>

                                            <!-- Publish/Unpublish Button -->
                                            <button wire:click="togglePublish({{ $tugas->id }})" 
                                                    class="inline-flex items-center text-{{ $tugas->is_published ? 'yellow' : 'green' }}-600 hover:text-{{ $tugas->is_published ? 'yellow' : 'green' }}-900 dark:text-{{ $tugas->is_published ? 'yellow' : 'green' }}-400 dark:hover:text-{{ $tugas->is_published ? 'yellow' : 'green' }}-300 text-xs px-2 py-1 rounded hover:bg-{{ $tugas->is_published ? 'yellow' : 'green' }}-50 dark:hover:bg-{{ $tugas->is_published ? 'yellow' : 'green' }}-900/20">
                                                <i class="fas fa-{{ $tugas->is_published ? 'eye-slash' : 'eye' }} mr-1"></i>
                                            </button>

                                            <!-- Delete Button -->
                                            <button wire:click="delete({{ $tugas->id }})" 
                                                    wire:confirm="Apakah Anda yakin ingin menghapus tugas ini?"
                                                    class="inline-flex items-center text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-xs px-2 py-1 rounded hover:bg-red-50 dark:hover:bg-red-900/20">
                                                <i class="fas fa-trash mr-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white dark:bg-gray-800 px-3 sm:px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Menampilkan {{ $tugasList->firstItem() }} - {{ $tugasList->lastItem() }} dari {{ $tugasList->total() }} tugas
                        </div>
                        <div class="flex-1 sm:flex-none">
                            {{ $tugasList->links() }}
                        </div>
                    </div>
                </div>
                @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-tasks text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum ada tugas</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4 max-w-md mx-auto">
                        Mulai buat tugas pertama Anda untuk memberikan latihan dan evaluasi kepada siswa.
                    </p>
                    <button wire:click="create" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus mr-2"></i>
                        Buat Tugas Pertama
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Tugas -->
    @if($showModal)
    <div class="fixed inset-0 overflow-y-auto z-50">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6 w-full">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                        {{ $editMode ? 'Edit Tugas' : 'Buat Tugas Baru' }}
                    </h3>
                    
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 gap-4 max-h-[70vh] overflow-y-auto pr-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Mata Pelajaran *
                                    </label>
                                    <select wire:model="mapelId"
                                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                                        <option value="">Pilih Mata Pelajaran</option>
                                        @foreach($mapels as $mapel)
                                        <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                                        @endforeach
                                    </select>
                                    @error('mapelId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Tipe Tugas *
                                    </label>
                                    <select wire:model="tipe"
                                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                                        <option value="tugas">Tugas</option>
                                        <option value="kuis">Kuis</option>
                                        <option value="ulangan">Ulangan</option>
                                    </select>
                                    @error('tipe') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Judul Tugas *
                                </label>
                                <input type="text" 
                                       wire:model="judul"
                                       class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                       placeholder="Masukkan judul tugas">
                                @error('judul') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Deskripsi *
                                </label>
                                <textarea wire:model="deskripsi"
                                          rows="3"
                                          class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                          placeholder="Deskripsi tugas..."></textarea>
                                @error('deskripsi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Instruksi (Opsional)
                                </label>
                                <textarea wire:model="instruksi"
                                          rows="2"
                                          class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                          placeholder="Instruksi pengerjaan..."></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Deadline *
                                    </label>
                                    <input type="datetime-local" 
                                           wire:model="deadline"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                                    @error('deadline') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Nilai Maksimal *
                                    </label>
                                    <input type="number" 
                                           wire:model="max_score"
                                           min="1"
                                           max="1000"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                           placeholder="100">
                                    @error('max_score') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    File Tugas (Opsional)
                                </label>
                                <input type="file" 
                                       wire:model="file"
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-600 dark:file:text-gray-200">
                                @error('file') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" 
                                       wire:model="is_published"
                                       id="is_published"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_published" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    Publikasikan tugas ini
                                </label>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="mt-5 sm:mt-6 flex flex-col-reverse sm:flex-row sm:gap-3 sm:justify-end">
                    <button wire:click="$set('showModal', false)" 
                            type="button"
                            class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                        Batal
                    </button>
                    <button wire:click="save"
                            type="button"
                            class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                        {{ $editMode ? 'Update' : 'Simpan' }} Tugas
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal View Tugas -->
    @if($showViewModal && $selectedTugas)
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
                            Detail Tugas
                        </h3>
                        <button wire:click="$set('showViewModal', false)" 
                                class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Header Info -->
                        <div class="flex items-start space-x-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            @if($selectedTugas->file)
                            <i class="fas fa-file text-blue-500 text-2xl mt-1"></i>
                            @else
                            <i class="fas fa-sticky-note text-gray-400 text-2xl mt-1"></i>
                            @endif
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $selectedTugas->judul }}
                                </h4>
                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $selectedTugas->tipe_color }}-100 text-{{ $selectedTugas->tipe_color }}-800 dark:bg-{{ $selectedTugas->tipe_color }}-900 dark:text-{{ $selectedTugas->tipe_color }}-200">
                                        {{ ucfirst($selectedTugas->tipe) }}
                                    </span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $selectedTugas->mapel->nama_mapel }}
                                    </span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $selectedTugas->guru->nama_lengkap }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Status Info -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-{{ $selectedTugas->status_color }}-50 dark:bg-{{ $selectedTugas->status_color }}-900/20 rounded-lg">
                                <p class="text-sm font-medium text-{{ $selectedTugas->status_color }}-800 dark:text-{{ $selectedTugas->status_color }}-200">Status</p>
                                <p class="text-lg font-semibold text-{{ $selectedTugas->status_color }}-600 dark:text-{{ $selectedTugas->status_color }}-400 capitalize">
                                    {{ $selectedTugas->status }}
                                </p>
                            </div>
                            <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Nilai Maks</p>
                                <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                                    {{ $selectedTugas->max_score }}
                                </p>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Deskripsi Tugas
                            </label>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">
                                    {{ $selectedTugas->deskripsi }}
                                </p>
                            </div>
                        </div>

                        <!-- Instruksi -->
                        @if($selectedTugas->instruksi)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Instruksi Pengerjaan
                            </label>
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
                                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">
                                    {{ $selectedTugas->instruksi }}
                                </p>
                            </div>
                        </div>
                        @endif

                        <!-- Deadline -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Deadline
                            </label>
                            <div class="flex items-center justify-between p-4 bg-{{ $selectedTugas->status_color }}-50 dark:bg-{{ $selectedTugas->status_color }}-900/20 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $selectedTugas->deadline->format('d F Y H:i') }}
                                    </p>
                                    <p class="text-xs text-{{ $selectedTugas->status_color }}-600 dark:text-{{ $selectedTugas->status_color }}-400">
                                        {{ $selectedTugas->deadline_relative }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- File -->
                        @if($selectedTugas->file)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                File Tugas
                            </label>
                            <div class="flex items-center justify-between p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-file text-blue-500 text-xl"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $selectedTugas->file }}
                                        </p>
                                    </div>
                                </div>
                                <button wire:click="download({{ $selectedTugas->id }})" 
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                                    <i class="fas fa-download mr-1"></i> Download
                                </button>
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
            <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed bottom-4 right-4 z-50">
            <div class="bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif
</div>