<div>
    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 gap-3">
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl sm:text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate">
                         RPP & Modul Pembelajaran
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Kelola Rencana Pelaksanaan Pembelajaran dan modul ajar
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <!-- Search Mobile -->
                    <div class="sm:hidden">
                        <input type="text" 
                               wire:model.debounce.300ms="search"
                               placeholder="Cari RPP..."
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm">
                    </div>
                    
                    <button wire:click="create" 
                            class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah RPP
                    </button>
                </div>
            </div>

            <!-- Search Desktop -->
            <div class="hidden sm:block mb-4 sm:mb-6 bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               wire:model.debounce.300ms="search"
                               placeholder="Cari berdasarkan judul RPP atau nama mapel..."
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            @if($rppList->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book text-blue-600 dark:text-blue-400 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total RPP</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $rppList->total() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chalkboard-teacher text-green-600 dark:text-green-400 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Mapel Diajar</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $mapels->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-graduate text-purple-600 dark:text-purple-400 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Guru</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $guru->nama_lengkap ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Mobile Card View -->
            <div class="sm:hidden space-y-4">
                @if($rppList->isNotEmpty())
                    @foreach($rppList as $rpp)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h3 class="text-base font-medium text-gray-900 dark:text-white">
                                    {{ Str::limit($rpp->judul, 50) }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $rpp->mapel->nama_mapel }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $rpp->created_at->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex justify-end gap-2 mt-3">
                            <button wire:click="edit({{ $rpp->id }})" 
                                    class="inline-flex items-center px-3 py-1.5 border border-blue-300 text-blue-600 rounded-md hover:bg-blue-50 dark:text-blue-400 dark:border-blue-700 dark:hover:bg-blue-900/20 text-sm">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </button>
                            <button wire:click="delete({{ $rpp->id }})" 
                                    wire:confirm="Apakah Anda yakin ingin menghapus RPP ini?"
                                    disabled
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-gray-400 rounded-md cursor-not-allowed text-sm">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </div>
                    </div>
                    @endforeach
                @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                    <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-book-open text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum ada RPP</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                        Mulai buat RPP pertama Anda untuk mengelola rencana pembelajaran dengan lebih terstruktur.
                    </p>
                    <button wire:click="create" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus mr-2"></i>
                        Buat RPP Pertama
                    </button>
                </div>
                @endif
            </div>

            <!-- Desktop Table View -->
            <div class="hidden sm:block bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                @if($rppList->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Judul RPP
                                </th>
                                <th class="hidden sm:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Mata Pelajaran
                                </th>
                                <th class="hidden md:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Dibuat
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($rppList as $rpp)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ Str::limit($rpp->judul, 50) }}
                                    </div>
                                    <div class="sm:hidden text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $rpp->mapel->nama_mapel }}
                                    </div>
                                    <div class="md:hidden text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $rpp->created_at->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="hidden sm:table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $rpp->mapel->nama_mapel }}
                                </td>
                                <td class="hidden md:table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $rpp->created_at->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                                        <button wire:click="edit({{ $rpp->id }})" 
                                                class="inline-flex items-center text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-xs sm:text-sm">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                        <button wire:click="delete({{ $rpp->id }})" 
                                                wire:confirm="Apakah Anda yakin ingin menghapus RPP ini?"
                                                disabled
                                                class="inline-flex items-center text-gray-400 cursor-not-allowed text-xs sm:text-sm">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
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
                            Menampilkan {{ $rppList->firstItem() }} - {{ $rppList->lastItem() }} dari {{ $rppList->total() }} RPP
                        </div>
                        <div class="flex-1 sm:flex-none">
                            {{ $rppList->links('vendor.livewire.tailwind') }}
                        </div>
                    </div>
                </div>
                @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-book-open text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum ada RPP</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4 max-w-md mx-auto">
                        Mulai buat RPP pertama Anda untuk mengelola rencana pembelajaran dengan lebih terstruktur.
                    </p>
                    <button wire:click="create" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus mr-2"></i>
                        Buat RPP Pertama
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 overflow-y-auto z-50">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full sm:p-6 w-full">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                        {{ $modalTitle }}
                    </h3>
                    
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 gap-4 max-h-[70vh] overflow-y-auto pr-2">
                            <!-- Scrollable content -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Mata Pelajaran *
                                    </label>
                                    <select wire:model="mapelId"
                                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                                        <option value="">Pilih Mata Pelajaran</option>
                                        @foreach($mapels as $mapel)
                                        <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }} - Kelas {{ $mapel->tingkat }}</option>
                                        @endforeach
                                    </select>
                                    @error('mapelId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Judul RPP *
                                    </label>
                                    <input type="text" 
                                           wire:model="judul"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                           placeholder="Contoh: RPP Matematika Kelas 10 - Aljabar">
                                    @error('judul') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Kompetensi Dasar *
                                </label>
                                <textarea wire:model="kompetensiDasar"
                                          rows="3"
                                          class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                          placeholder="Tuliskan kompetensi dasar sesuai kurikulum..."></textarea>
                                @error('kompetensiDasar') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tujuan Pembelajaran *
                                </label>
                                <textarea wire:model="tujuanPembelajaran"
                                          rows="3"
                                          class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                          placeholder="Tuliskan tujuan pembelajaran yang ingin dicapai..."></textarea>
                                @error('tujuanPembelajaran') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Materi Pembelajaran *
                                </label>
                                <textarea wire:model="materi"
                                          rows="4"
                                          class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                          placeholder="Tuliskan materi pembelajaran yang akan diajarkan..."></textarea>
                                @error('materi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Metode Pembelajaran
                                    </label>
                                    <input type="text" 
                                           wire:model="metode"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                           placeholder="Contoh: Ceramah, Diskusi, Praktik">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Media Pembelajaran
                                    </label>
                                    <input type="text" 
                                           wire:model="media"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                           placeholder="Contoh: PowerPoint, Video, Alat Peraga">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Langkah-langkah Kegiatan *
                                </label>
                                <textarea wire:model="langkahKegiatan"
                                          rows="4"
                                          class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                          placeholder="Tuliskan langkah-langkah kegiatan pembelajaran (Pendahuluan, Inti, Penutup)..."></textarea>
                                @error('langkahKegiatan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Penilaian
                                </label>
                                <textarea wire:model="penilaian"
                                          rows="3"
                                          class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                          placeholder="Tuliskan teknik dan rubrik penilaian..."></textarea>
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
                        Simpan RPP
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>