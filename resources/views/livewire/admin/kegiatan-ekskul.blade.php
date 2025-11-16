<div>
    <div class="p-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kegiatan Ekstrakurikuler</h1>
            <button 
                wire:click="openCreateForm"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center w-full sm:w-auto justify-center"
            >
                <i class="fas fa-plus mr-2"></i>Tambah Kegiatan
            </button>
        </div>

        <!-- Flash Messages -->
        @if(session()->has('success'))
            <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 rounded-lg flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.style.display='none'" class="text-lg">&times;</button>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 rounded-lg flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.style.display='none'" class="text-lg">&times;</button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Terlaksana</h3>
                        <p class="text-xl sm:text-2xl font-bold text-green-600 dark:text-green-400">
                            {{ \App\Models\KegiatanEkskul::where('status', 'terlaksana')->count() }}
                        </p>
                    </div>
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl sm:text-2xl"></i>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Dibatalkan</h3>
                        <p class="text-xl sm:text-2xl font-bold text-red-600 dark:text-red-400">
                            {{ \App\Models\KegiatanEkskul::where('status', 'dibatalkan')->count() }}
                        </p>
                    </div>
                    <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-xl sm:text-2xl"></i>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Ditunda</h3>
                        <p class="text-xl sm:text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                            {{ \App\Models\KegiatanEkskul::where('status', 'ditunda')->count() }}
                        </p>
                    </div>
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-xl sm:text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
            <div class="p-3 sm:p-4">
                <div class="flex overflow-x-auto space-x-2 sm:space-x-4 pb-2 scrollbar-hide">
                    <button 
                        wire:click="$set('ekskulFilter', '')"
                        class="px-3 py-2 text-sm sm:text-base {{ !$ekskulFilter ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} rounded-lg whitespace-nowrap transition flex-shrink-0"
                    >
                        Semua
                    </button>
                    @foreach($ekskulList as $ekskul)
                        <button 
                            wire:click="$set('ekskulFilter', '{{ $ekskul->id }}')"
                            class="px-3 py-2 text-sm sm:text-base {{ $ekskulFilter == $ekskul->id ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} rounded-lg whitespace-nowrap transition flex-shrink-0"
                        >
                            {{ $ekskul->nama_ekskul }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Activities Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @forelse($kegiatans as $kegiatan)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <!-- Gambar Kegiatan -->
                    <div class="h-32 sm:h-40 relative">
                        @if($kegiatan->dokumentasi)
                            <img 
                                src="{{ asset('storage/' . $kegiatan->dokumentasi) }}" 
                                alt="{{ $kegiatan->nama_kegiatan }}"
                                class="w-full h-full object-cover"
                                onerror="console.log('Image failed to load:', this.src); this.src='https://via.placeholder.com/300x128/3B82F6/FFFFFF?text={{ urlencode($kegiatan->ekstrakurikuler->nama_ekskul) }}'"
                            >
                        @else
                            <div class="w-full h-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <i class="fas fa-calendar-alt text-3xl opacity-80 mb-2"></i>
                                    <p class="text-sm font-medium">{{ $kegiatan->ekstrakurikuler->nama_ekskul }}</p>
                                </div>
                            </div>
                        @endif
                        <div class="absolute top-2 right-2">
                            @if($kegiatan->status === 'terlaksana')
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full">Terlaksana</span>
                            @elseif($kegiatan->status === 'dibatalkan')
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded-full">Dibatalkan</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded-full">Ditunda</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-3 sm:p-4">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-1">
                            {{ $kegiatan->nama_kegiatan }}
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                            {{ $kegiatan->deskripsi }}
                        </p>
                        
                        <div class="space-y-1 sm:space-y-2 mb-3">
                            <div class="flex items-center text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-calendar-alt mr-2 w-3 sm:w-4"></i>
                                <span>{{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->translatedFormat('d M Y') }}</span>
                            </div>
                            <div class="flex items-center text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-clock mr-2 w-3 sm:w-4"></i>
                                <span>{{ $kegiatan->waktu_mulai }} - {{ $kegiatan->waktu_selesai }}</span>
                            </div>
                            <div class="flex items-center text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-map-marker-alt mr-2 w-3 sm:w-4"></i>
                                <span class="line-clamp-1">{{ $kegiatan->tempat }}</span>
                            </div>
                            <div class="flex items-center text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                <i class="fas fa-user mr-2 w-3 sm:w-4"></i>
                                <span>{{ $kegiatan->pembina }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-3 border-t border-gray-200 dark:border-gray-600">
                            <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-medium bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                {{ $kegiatan->ekstrakurikuler->nama_ekskul }}
                            </span>
                            <div class="flex space-x-2">
                                <button 
                                    wire:click="openEditForm({{ $kegiatan->id }})"
                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-xs sm:text-sm font-medium"
                                >
                                    Edit
                                </button>
                                <!-- Tombol delete dinonaktifkan -->
                                <button 
                                    disabled
                                    class="text-gray-400 cursor-not-allowed text-xs sm:text-sm font-medium"
                                    title="Hapus dinonaktifkan"
                                >
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8 sm:py-12">
                    <i class="fas fa-calendar-times text-3xl sm:text-4xl text-gray-400 dark:text-gray-500 mb-3 sm:mb-4"></i>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada kegiatan</h3>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-4">Belum ada kegiatan ekstrakurikuler yang ditambahkan.</p>
                    <button 
                        wire:click="openCreateForm"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm sm:text-base"
                    >
                        <i class="fas fa-plus mr-2"></i>Tambah Kegiatan Pertama
                    </button>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($kegiatans->hasPages())
            <div class="mt-4 sm:mt-6">
                {{ $kegiatans->links() }}
            </div>
        @endif

        <!-- Create/Edit Modal -->
        @if($showForm)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-2 sm:p-4 z-50">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[95vh] overflow-y-auto">
                    <div class="p-4 sm:p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">
                                {{ $formType === 'create' ? 'Tambah Kegiatan' : 'Edit Kegiatan' }}
                            </h2>
                            <button 
                                wire:click="closeForm"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                            >
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>

                        <form wire:submit="saveKegiatan">
                            <div class="space-y-4">
                                <!-- Upload Gambar -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Gambar Dokumentasi
                                    </label>
                                    <div class="flex items-center justify-center w-full">
                                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="fas fa-cloud-upload-alt text-gray-400 dark:text-gray-500 text-2xl mb-2"></i>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                                                    <span class="font-semibold">Klik untuk upload</span> atau drag & drop
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, JPEG (Max. 2MB)</p>
                                            </div>
                                            <input 
                                                type="file" 
                                                wire:model="dokumentasi"
                                                class="hidden" 
                                                accept="image/*"
                                            >
                                        </label>
                                    </div>
                                    
                                    @if($dokumentasi)
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Preview:</p>
                                            <div class="mt-1 relative">
                                                <img 
                                                    src="{{ $dokumentasi->temporaryUrl() }}" 
                                                    alt="Preview" 
                                                    class="h-32 w-full object-cover rounded-lg"
                                                >
                                                <button 
                                                    type="button"
                                                    wire:click="$set('dokumentasi', null)"
                                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition"
                                                >
                                                    <i class="fas fa-times text-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @elseif($formType === 'edit' && $existing_dokumentasi)
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Gambar saat ini:</p>
                                            <img 
                                                src="{{ asset('storage/' . $existing_dokumentasi) }}" 
                                                alt="Current image" 
                                                class="h-32 w-full object-cover rounded-lg mt-1"
                                            >
                                        </div>
                                    @endif
                                    
                                    @error('dokumentasi')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Form fields lainnya tetap sama -->
                                <!-- Ekstrakurikuler -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Ekstrakurikuler *
                                    </label>
                                    <select 
                                        wire:model="ekstrakurikuler_id"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base"
                                    >
                                        <option value="">Pilih Ekstrakurikuler</option>
                                        @foreach($ekskulList as $ekskul)
                                            <option value="{{ $ekskul->id }}">{{ $ekskul->nama_ekskul }}</option>
                                        @endforeach
                                    </select>
                                    @error('ekstrakurikuler_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nama Kegiatan -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Nama Kegiatan *
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="nama_kegiatan"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base"
                                        placeholder="Masukkan nama kegiatan"
                                    >
                                    @error('nama_kegiatan')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Deskripsi -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Deskripsi *
                                    </label>
                                    <textarea 
                                        wire:model="deskripsi"
                                        rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base"
                                        placeholder="Deskripsi kegiatan"
                                    ></textarea>
                                    @error('deskripsi')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Tanggal Kegiatan -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Tanggal Kegiatan *
                                        </label>
                                        <input 
                                            type="date" 
                                            wire:model="tanggal_kegiatan"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base"
                                        >
                                        @error('tanggal_kegiatan')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Status -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Status *
                                        </label>
                                        <select 
                                            wire:model="status"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base"
                                        >
                                            @foreach($statusList as $statusOption)
                                                <option value="{{ $statusOption }}">{{ ucfirst($statusOption) }}</option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Waktu Mulai -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Waktu Mulai *
                                        </label>
                                        <input 
                                            type="time" 
                                            wire:model="waktu_mulai"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base"
                                        >
                                        @error('waktu_mulai')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Waktu Selesai -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Waktu Selesai *
                                        </label>
                                        <input 
                                            type="time" 
                                            wire:model="waktu_selesai"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base"
                                        >
                                        @error('waktu_selesai')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Tempat -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Tempat *
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="tempat"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base"
                                        placeholder="Tempat pelaksanaan kegiatan"
                                    >
                                    @error('tempat')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Pembina -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Pembina *
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="pembina"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base"
                                        placeholder="Nama pembina kegiatan"
                                    >
                                    @error('pembina')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Hasil Kegiatan -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Hasil Kegiatan
                                    </label>
                                    <textarea 
                                        wire:model="hasil_kegiatan"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base"
                                        placeholder="Hasil yang dicapai (opsional)"
                                    ></textarea>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                                <button 
                                    type="button"
                                    wire:click="closeForm"
                                    class="px-4 py-2 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm sm:text-base order-2 sm:order-1"
                                >
                                    Batal
                                </button>
                                <button 
                                    type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm sm:text-base order-1 sm:order-2"
                                >
                                    {{ $formType === 'create' ? 'Simpan' : 'Update' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>